<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Product\ProductWishlist;

class CleanupOrphanedWishlist extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'wishlist:cleanup-orphaned';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clean up orphaned wishlist entries where products no longer exist';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('Cleaning up orphaned wishlist entries...');

        $deleted = ProductWishlist::cleanupOrphanedEntries();

        $this->info("Deleted {$deleted} orphaned wishlist entries.");
        
        return 0;
    }
}