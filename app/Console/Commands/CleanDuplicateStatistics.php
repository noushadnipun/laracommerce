<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Product\ProductStatistics;
use Illuminate\Support\Facades\DB;

class CleanDuplicateStatistics extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'statistics:clean-duplicates';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clean up duplicate product statistics entries';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('Cleaning up duplicate product statistics entries...');

        // Find products with duplicate statistics
        $duplicates = ProductStatistics::select('product_id')
            ->groupBy('product_id')
            ->havingRaw('COUNT(*) > 1')
            ->get();

        if ($duplicates->isEmpty()) {
            $this->info('No duplicate statistics found.');
            return 0;
        }

        $this->info("Found {$duplicates->count()} products with duplicate statistics.");

        $cleaned = 0;

        foreach ($duplicates as $duplicate) {
            $productId = $duplicate->product_id;
            
            // Get all statistics for this product
            $stats = ProductStatistics::where('product_id', $productId)
                ->orderBy('created_at', 'asc')
                ->get();

            if ($stats->count() > 1) {
                // Keep the first one and merge data from others
                $keep = $stats->first();
                $toDelete = $stats->skip(1);

                // Merge all data into the first record
                foreach ($toDelete as $stat) {
                    $keep->views += $stat->views;
                    $keep->clicks += $stat->clicks;
                    $keep->cart_adds += $stat->cart_adds;
                    $keep->wishlist_adds += $stat->wishlist_adds;
                    $keep->compare_adds += $stat->compare_adds;
                    $keep->shares += $stat->shares;
                    $keep->total_sales += $stat->total_sales;
                    $keep->total_revenue += $stat->total_revenue;
                    
                    // Use the latest average rating
                    if ($stat->average_rating > $keep->average_rating) {
                        $keep->average_rating = $stat->average_rating;
                    }
                    
                    // Use the latest last_viewed_at
                    if ($stat->last_viewed_at && (!$keep->last_viewed_at || $stat->last_viewed_at > $keep->last_viewed_at)) {
                        $keep->last_viewed_at = $stat->last_viewed_at;
                    }
                }

                $keep->save();

                // Delete the duplicate records
                $toDelete->each(function ($stat) {
                    $stat->delete();
                });

                $cleaned++;
                $this->line("Cleaned duplicates for product ID: {$productId}");
            }
        }

        $this->info("Successfully cleaned {$cleaned} duplicate statistics entries.");
        return 0;
    }
}