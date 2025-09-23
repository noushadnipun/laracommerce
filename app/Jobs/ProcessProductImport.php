<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\Product;
use App\Models\Inventory;
use App\Models\StockMovement;
use App\Models\ImportJob;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use ZipArchive;

class ProcessProductImport implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $importJob;
    protected $zipPath;
    protected $extractPath;

    /**
     * Create a new job instance.
     */
    public function __construct(ImportJob $importJob, $zipPath)
    {
        $this->importJob = $importJob;
        $this->zipPath = $zipPath;
    }

    /**
     * Execute the job.
     */
    public function handle()
    {
        try {
            $this->importJob->markAsStarted();
            $this->importJob->addToLog('Starting import process...', 'info');

            // Extract ZIP file
            $this->extractZipFile();
            
            // Process Excel file
            $products = $this->processExcelFile();
            
            // Process products
            $this->processProducts($products);
            
            // Cleanup
            $this->cleanup();
            
            $this->importJob->markAsCompleted();
            $this->importJob->addToLog('Import completed successfully!', 'success');
            
        } catch (\Exception $e) {
            $this->importJob->markAsFailed($e->getMessage());
            $this->importJob->addToLog('Import failed: ' . $e->getMessage(), 'error');
            Log::error('Product import failed: ' . $e->getMessage());
        }
    }

    /**
     * Extract ZIP file
     */
    private function extractZipFile()
    {
        $this->extractPath = storage_path('app/temp/import_' . $this->importJob->id);
        
        if (!file_exists($this->extractPath)) {
            mkdir($this->extractPath, 0755, true);
        }

        $zip = new ZipArchive();
        if ($zip->open($this->zipPath) === TRUE) {
            $zip->extractTo($this->extractPath);
            $zip->close();
            $this->importJob->addToLog('ZIP file extracted successfully', 'info');
        } else {
            throw new \Exception('Failed to extract ZIP file');
        }
    }

    /**
     * Process Excel file
     */
    private function processExcelFile()
    {
        $excelFile = $this->findExcelFile();
        if (!$excelFile) {
            throw new \Exception('No Excel/CSV file found in ZIP');
        }

        $products = [];
        $handle = fopen($excelFile, 'r');
        
        // Skip header row
        $headers = fgetcsv($handle);
        
        while (($data = fgetcsv($handle)) !== FALSE) {
            if (count($data) >= 11) {
                $products[] = [
                    'title' => $data[0] ?? '',
                    'code' => $data[1] ?? '',
                    'description' => $data[2] ?? '',
                    'regular_price' => $data[3] ?? 0,
                    'sale_price' => $data[4] ?? null,
                    'purchase_price' => $data[5] ?? 0,
                    'total_stock' => $data[6] ?? 0,
                    'category_id' => $data[7] ?? 1,
                    'brand_id' => $data[8] ?? null,
                    'low_stock_threshold' => $data[9] ?? 10,
                    'unit_cost' => $data[10] ?? 0,
                    'featured_image' => $data[11] ?? '',
                    'gallery_images' => $data[12] ?? '',
                    'remote_images' => $data[13] ?? ''
                ];
            }
        }
        
        fclose($handle);
        
        $this->importJob->update([
            'total_products' => count($products)
        ]);
        
        $this->importJob->addToLog('Found ' . count($products) . ' products to import', 'info');
        
        return $products;
    }

    /**
     * Process products
     */
    private function processProducts($products)
    {
        $processed = 0;
        $successful = 0;
        $failed = 0;

        foreach ($products as $productData) {
            try {
                $this->processProduct($productData);
                $successful++;
            } catch (\Exception $e) {
                $failed++;
                $this->importJob->addToLog('Failed to import product: ' . $productData['code'] . ' - ' . $e->getMessage(), 'error');
            }
            
            $processed++;
            $this->importJob->updateProgress($processed, $successful, $failed);
        }
    }

    /**
     * Process individual product
     */
    private function processProduct($productData)
    {
        $existingProduct = Product::where('code', $productData['code'])->first();
        
        if ($existingProduct) {
            $this->updateExistingProduct($existingProduct, $productData);
        } else {
            $this->createNewProduct($productData);
        }
    }

    /**
     * Create new product
     */
    private function createNewProduct($productData)
    {
        $product = Product::create([
            'title' => $productData['title'],
            'slug' => \Str::slug($productData['title']),
            'code' => $productData['code'],
            'description' => $productData['description'],
            'regular_price' => $productData['regular_price'],
            'sale_price' => $productData['sale_price'],
            'purchase_price' => $productData['purchase_price'],
            'total_stock' => $productData['total_stock'],
            'current_stock' => $productData['total_stock'],
            'category_id' => $productData['category_id'],
            'brand_id' => $productData['brand_id'],
            'user_id' => $this->importJob->user_id
        ]);

        // Create inventory
        $inventory = Inventory::create([
            'product_id' => $product->id,
            'current_stock' => $productData['total_stock'],
            'total_stock' => $productData['total_stock'],
            'reserved_stock' => 0,
            'low_stock_threshold' => $productData['low_stock_threshold'],
            'unit_cost' => $productData['unit_cost'],
            'total_value' => $productData['unit_cost'] * $productData['total_stock']
        ]);

        // Log stock movement
        StockMovement::create([
            'product_id' => $product->id,
            'type' => 'import',
            'quantity' => $productData['total_stock'],
            'previous_stock' => 0,
            'new_stock' => $productData['total_stock'],
            'reference_type' => 'import',
            'reference_id' => $this->importJob->id,
            'notes' => 'Imported from Excel',
            'user_id' => $this->importJob->user_id
        ]);

        // Process images
        $this->processImages($product, $productData);
    }

    /**
     * Update existing product
     */
    private function updateExistingProduct($product, $productData)
    {
        $inventory = $product->inventory;
        
        if ($inventory) {
            $previousStock = $inventory->current_stock;
            $newStock = $previousStock + $productData['total_stock'];
            
            $inventory->update([
                'current_stock' => $newStock,
                'total_stock' => $inventory->total_stock + $productData['total_stock'],
                'unit_cost' => $productData['unit_cost'],
                'total_value' => $productData['unit_cost'] * $newStock
            ]);
            
            // Log stock movement
            StockMovement::create([
                'product_id' => $product->id,
                'type' => 'import',
                'quantity' => $productData['total_stock'],
                'previous_stock' => $previousStock,
                'new_stock' => $newStock,
                'reference_type' => 'import',
                'reference_id' => $this->importJob->id,
                'notes' => 'Imported from Excel',
                'user_id' => $this->importJob->user_id
            ]);
        } else {
            // Create inventory if doesn't exist
            $inventory = Inventory::create([
                'product_id' => $product->id,
                'current_stock' => $productData['total_stock'],
                'total_stock' => $productData['total_stock'],
                'reserved_stock' => 0,
                'low_stock_threshold' => $productData['low_stock_threshold'],
                'unit_cost' => $productData['unit_cost'],
                'total_value' => $productData['unit_cost'] * $productData['total_stock']
            ]);
        }

        // Update product
        $product->update([
            'title' => $productData['title'],
            'description' => $productData['description'],
            'regular_price' => $productData['regular_price'],
            'sale_price' => $productData['sale_price'],
            'purchase_price' => $productData['purchase_price'],
            'total_stock' => $inventory->total_stock,
            'current_stock' => $inventory->current_stock
        ]);

        // Process images
        $this->processImages($product, $productData);
    }

    /**
     * Process images
     */
    private function processImages($product, $productData)
    {
        $imagesPath = $this->extractPath . '/images';
        
        if (is_dir($imagesPath)) {
            // Process featured image
            if (!empty($productData['featured_image'])) {
                $this->processImage($productData['featured_image'], $imagesPath);
            }
            
            // Process gallery images
            if (!empty($productData['gallery_images'])) {
                $galleryImages = explode(',', $productData['gallery_images']);
                foreach ($galleryImages as $image) {
                    $this->processImage(trim($image), $imagesPath);
                }
            }
        }
    }

    /**
     * Process individual image
     */
    private function processImage($imageName, $imagesPath)
    {
        $imagePath = $imagesPath . '/' . $imageName;
        
        if (file_exists($imagePath)) {
            $destination = public_path('uploads/images/' . $imageName);
            
            if (!file_exists(dirname($destination))) {
                mkdir(dirname($destination), 0755, true);
            }
            
            copy($imagePath, $destination);
        }
    }

    /**
     * Find Excel file in extracted directory
     */
    private function findExcelFile()
    {
        $files = glob($this->extractPath . '/*.{xlsx,xls,csv}', GLOB_BRACE);
        return !empty($files) ? $files[0] : null;
    }

    /**
     * Cleanup temporary files
     */
    private function cleanup()
    {
        if (is_dir($this->extractPath)) {
            $this->deleteDirectory($this->extractPath);
        }
        
        if (file_exists($this->zipPath)) {
            unlink($this->zipPath);
        }
    }

    /**
     * Delete directory recursively
     */
    private function deleteDirectory($dir)
    {
        if (is_dir($dir)) {
            $objects = scandir($dir);
            foreach ($objects as $object) {
                if ($object != "." && $object != "..") {
                    if (is_dir($dir . "/" . $object)) {
                        $this->deleteDirectory($dir . "/" . $object);
                    } else {
                        unlink($dir . "/" . $object);
                    }
                }
            }
            rmdir($dir);
        }
    }
}