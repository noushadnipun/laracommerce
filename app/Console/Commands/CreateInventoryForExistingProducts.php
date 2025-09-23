<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Product;
use App\Models\Inventory;
use App\Models\StockMovement;

class CreateInventoryForExistingProducts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'inventory:create-for-existing-products';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create inventory records for existing products that don\'t have inventory data';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('Starting to create inventory records for existing products...');
        
        // Get all products that don't have inventory records
        $products = Product::whereDoesntHave('inventory')->get();
        
        if ($products->isEmpty()) {
            $this->info('All products already have inventory records.');
            return 0;
        }
        
        $this->info("Found {$products->count()} products without inventory records.");
        
        $bar = $this->output->createProgressBar($products->count());
        $bar->start();
        
        $created = 0;
        $skipped = 0;
        
        foreach ($products as $product) {
            try {
                // Create inventory record
                $inventory = Inventory::create([
                    'product_id' => $product->id,
                    'current_stock' => $product->current_stock ?? 0,
                    'total_stock' => $product->total_stock ?? 0,
                    'reserved_stock' => 0,
                    'low_stock_threshold' => 10,
                    'unit_cost' => $product->purchase_price ?? 0,
                    'total_value' => ($product->purchase_price ?? 0) * ($product->current_stock ?? 0)
                ]);
                
                // Create initial stock movement if there's stock
                if (($product->current_stock ?? 0) > 0) {
                    StockMovement::create([
                        'product_id' => $product->id,
                        'type' => 'import',
                        'quantity' => $product->current_stock ?? 0,
                        'previous_stock' => 0,
                        'new_stock' => $product->current_stock ?? 0,
                        'reference_type' => 'initial_setup',
                        'notes' => 'Initial inventory setup from existing product data',
                        'user_id' => 1
                    ]);
                }
                
                $created++;
                
            } catch (\Exception $e) {
                $this->error("Failed to create inventory for product {$product->id}: " . $e->getMessage());
                $skipped++;
            }
            
            $bar->advance();
        }
        
        $bar->finish();
        $this->newLine();
        
        $this->info("✅ Successfully created inventory records for {$created} products.");
        
        if ($skipped > 0) {
            $this->warn("⚠️  Skipped {$skipped} products due to errors.");
        }
        
        $this->info('Inventory creation completed!');
        
        return 0;
    }
}