<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\ProductBrand;
use App\Models\ImportJob;
use App\Jobs\ProcessProductImport;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use ZipArchive;

class ProductImportController extends Controller
{
    /**
     * Show import page
     */
    public function index()
    {
        $categories = ProductCategory::orderBy('name')->get();
        $brands = ProductBrand::orderBy('name')->get();
        
        return view('admin.product.import', compact('categories', 'brands'));
    }
    
    /**
     * Download sample Excel template
     */
    public function downloadTemplate()
    {
        $templateData = $this->generateTemplateData();
        
        // Create CSV content
        $csvContent = $this->generateCSVContent($templateData);
        
        $filename = 'product_import_template.csv';
        
        return response($csvContent)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
    }
    
    /**
     * Process ZIP file upload and import
     */
    public function import(Request $request)
    {
        $request->validate([
            'import_file' => 'required|file|mimes:zip|max:10240' // 10MB max
        ]);
        
        try {
            $zipFile = $request->file('import_file');
            $filename = 'import_' . time() . '.zip';
            $zipPath = storage_path('app/imports/' . $filename);
            
            // Create imports directory
            if (!file_exists(dirname($zipPath))) {
                mkdir(dirname($zipPath), 0755, true);
            }
            
            // Move uploaded file
            $zipFile->move(dirname($zipPath), $filename);
            
            // Create import job
            $importJob = ImportJob::create([
                'user_id' => auth()->id(),
                'filename' => $filename,
                'original_filename' => $zipFile->getClientOriginalName(),
                'status' => 'pending'
            ]);
            
            // Dispatch job to queue
            ProcessProductImport::dispatch($importJob, $zipPath);
            
            return back()->with('success', 'Import job queued successfully! You can track progress below.');
            
        } catch (\Exception $e) {
            return back()->with('error', 'Import failed: ' . $e->getMessage());
        }
    }

    /**
     * Get import job status
     */
    public function getStatus($id)
    {
        $importJob = ImportJob::findOrFail($id);
        
        return response()->json([
            'status' => $importJob->status,
            'progress' => $importJob->progress_percentage,
            'processed' => $importJob->processed_products,
            'total' => $importJob->total_products,
            'successful' => $importJob->successful_imports,
            'failed' => $importJob->failed_imports,
            'log' => $importJob->import_log
        ]);
    }

    /**
     * Get user's import jobs
     */
    public function getJobs()
    {
        $jobs = ImportJob::where('user_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->paginate(10);
            
        return response()->json($jobs);
    }
    
    /**
     * Generate template data
     */
    private function generateTemplateData()
    {
        return [
            // Headers
            [
                'Title', 'Code', 'Description', 'Short_Description', 'Regular_Price', 'Sale_Price', 
                'Purchase_Price', 'Total_Stock', 'Category_ID', 'Brand_ID', 'Shipping_Type', 
                'Shipping_Cost', 'Visibility', 'Refundable', 'Featured_Image', 'Gallery_Images', 'Remote_Images'
            ],
            // Sample data
            [
                'iPhone 15 Pro', 'IPH15P', 'Latest iPhone Pro with advanced features', 'New iPhone Pro', 
                '1200', '1100', '800', '50', '1', '1', '0', '0', '1', '1', 
                'iphone15pro.jpg', 'iphone15_1.jpg,iphone15_2.jpg', 'https://example.com/iphone15.jpg'
            ],
            [
                'Samsung Galaxy S24', 'SAMS24', 'Samsung flagship smartphone', 'Galaxy S24', 
                '1000', '950', '600', '30', '1', '2', '1', '10', '1', '1', 
                'samsung_s24.jpg', 'samsung_1.jpg,samsung_2.jpg', 'https://example.com/samsung.jpg'
            ],
            [
                'MacBook Pro 15"', 'MBP15', 'Apple MacBook Pro laptop', 'MacBook Pro', 
                '2000', '1800', '1500', '15', '2', '1', '0', '0', '1', '1', 
                'macbook_pro.jpg', 'macbook_1.jpg,macbook_2.jpg', 'https://example.com/macbook.jpg'
            ]
        ];
    }
    
    /**
     * Generate CSV content
     */
    private function generateCSVContent($data)
    {
        $output = fopen('php://temp', 'r+');
        
        foreach ($data as $row) {
            fputcsv($output, $row);
        }
        
        rewind($output);
        $csv = stream_get_contents($output);
        fclose($output);
        
        return $csv;
    }
    
    /**
     * Find Excel/CSV file in extracted directory
     */
    private function findExcelFile($path)
    {
        $files = glob($path . '/*.{xlsx,xls,csv}', GLOB_BRACE);
        return !empty($files) ? $files[0] : null;
    }
    
    /**
     * Process Excel/CSV file
     */
    private function processExcelFile($filePath)
    {
        $products = [];
        $handle = fopen($filePath, 'r');
        
        // Skip header row
        $headers = fgetcsv($handle);
        
        while (($data = fgetcsv($handle)) !== FALSE) {
            if (count($data) >= 17) { // Ensure we have enough columns
                $products[] = [
                    'title' => $data[0] ?? '',
                    'code' => $data[1] ?? '',
                    'description' => $data[2] ?? '',
                    'short_description' => $data[3] ?? '',
                    'regular_price' => $data[4] ?? 0,
                    'sale_price' => $data[5] ?? null,
                    'purchase_price' => $data[6] ?? 0,
                    'total_stock' => $data[7] ?? 0,
                    'category_id' => $data[8] ?? 1,
                    'brand_id' => $data[9] ?? null,
                    'shipping_type' => $data[10] ?? 0,
                    'shipping_cost' => $data[11] ?? 0,
                    'visibility' => $data[12] ?? 1,
                    'refundable' => $data[13] ?? 1,
                    'featured_image' => $data[14] ?? '',
                    'gallery_images' => $data[15] ?? '',
                    'remote_images' => $data[16] ?? ''
                ];
            }
        }
        
        fclose($handle);
        return $products;
    }
    
    /**
     * Process images from images folder
     */
    private function processImages($imagesPath)
    {
        $files = glob($imagesPath . '/*.{jpg,jpeg,png,gif}', GLOB_BRACE);
        
        foreach ($files as $file) {
            $filename = basename($file);
            $destination = public_path('uploads/images/' . $filename);
            
            // Create directory if not exists
            if (!file_exists(dirname($destination))) {
                mkdir(dirname($destination), 0755, true);
            }
            
            // Copy file
            copy($file, $destination);
        }
    }
    
    /**
     * Import products to database
     */
    private function importProducts($products, $imagesPath)
    {
        $importedCount = 0;
        
        foreach ($products as $productData) {
            try {
                // Process images
                $featuredImage = $this->processImagePath($productData['featured_image'], $imagesPath);
                $galleryImages = $this->processGalleryImages($productData['gallery_images'], $imagesPath);
                $remoteImages = $this->processRemoteImages($productData['remote_images']);
                
                // Create product
                $product = Product::create([
                    'title' => $productData['title'],
                    'slug' => \Str::slug($productData['title']),
                    'code' => $productData['code'],
                    'description' => $productData['description'],
                    'short_description' => $productData['short_description'],
                    'regular_price' => $productData['regular_price'],
                    'sale_price' => $productData['sale_price'],
                    'purchase_price' => $productData['purchase_price'],
                    'total_stock' => $productData['total_stock'],
                    'current_stock' => $productData['total_stock'],
                    'category_id' => $productData['category_id'],
                    'brand_id' => $productData['brand_id'],
                    'shipping_type' => $productData['shipping_type'],
                    'shipping_cost' => $productData['shipping_cost'],
                    'visibility' => $productData['visibility'],
                    'refundable' => $productData['refundable'],
                    'featured_image' => $featuredImage,
                    'product_image' => $galleryImages,
                    'remote_images' => $remoteImages,
                    'user_id' => auth()->id()
                ]);
                
                $importedCount++;
                
            } catch (\Exception $e) {
                \Log::error('Product import error: ' . $e->getMessage());
                continue;
            }
        }
        
        return $importedCount;
    }
    
    /**
     * Process featured image path
     */
    private function processImagePath($imageName, $imagesPath)
    {
        if (empty($imageName)) return null;
        
        $imagePath = $imagesPath . '/' . $imageName;
        if (file_exists($imagePath)) {
            return basename($imageName);
        }
        
        return null;
    }
    
    /**
     * Process gallery images
     */
    private function processGalleryImages($galleryImages, $imagesPath)
    {
        if (empty($galleryImages)) return [];
        
        $images = explode(',', $galleryImages);
        $processedImages = [];
        
        foreach ($images as $image) {
            $image = trim($image);
            $imagePath = $imagesPath . '/' . $image;
            if (file_exists($imagePath)) {
                $processedImages[] = basename($image);
            }
        }
        
        return $processedImages;
    }
    
    /**
     * Process remote images
     */
    private function processRemoteImages($remoteImages)
    {
        if (empty($remoteImages)) return [];
        
        $images = explode(',', $remoteImages);
        return array_map('trim', $images);
    }
    
    /**
     * Cleanup temporary files
     */
    private function cleanup($path)
    {
        if (is_dir($path)) {
            $this->deleteDirectory($path);
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
