<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use App\Models\Product;
use App\Models\ProductBrand;
use App\Models\ProductCategory;
use App\Models\Inventory;

class AmazonCsvSeeder extends Seeder
{
    public function run(): void
    {
        // Target categories (10) with keywords to match CSV categories
        $targetCategories = [
            'Electronics' => ['electronics', 'electronic', 'camera', 'audio', 'tv'],
            'Clothing' => ['clothing', 'apparel', 'men', 'women', 'girls', 'boys', 'fashion', 'shoes', 'jewelry'],
            'Computers' => ['computer', 'laptop', 'desktop', 'pc', 'monitor'],
            'Cell Phones' => ['cell', 'phone', 'mobile', 'smartphone'],
            'Home & Kitchen' => ['home', 'kitchen', 'household', 'cook'],
            'Sports & Outdoors' => ['sport', 'outdoor', 'fitness', 'cycling', 'camp'],
            'Beauty & Personal Care' => ['beauty', 'personal care', 'skincare', 'makeup', 'hair'],
            'Toys & Games' => ['toy', 'game', 'kids'],
            'Tools & Home Improvement' => ['tool', 'improvement', 'hardware'],
            'Grocery & Gourmet Food' => ['grocery', 'food', 'gourmet']
        ];
        $localPath = database_path('data/amazon-products.csv');
        $csv = '';
        if (file_exists($localPath)) {
            $csv = file_get_contents($localPath) ?: '';
        }
        if ($csv === '') {
            $url = 'https://raw.githubusercontent.com/luminati-io/eCommerce-dataset-samples/refs/heads/main/amazon-products.csv';
            try {
                $resp = Http::timeout(120)->retry(3, 2000)->get($url);
                if ($resp->ok()) {
                    $csv = $resp->body();
                    @mkdir(dirname($localPath), 0777, true);
                    @file_put_contents($localPath, $csv);
                }
            } catch (\Throwable $e) {
                $this->command->warn('Failed to download CSV: '.$e->getMessage());
            }
        }
        if ($csv === '') {
            $this->command->warn('CSV not available locally or from remote. Aborting.');
            return;
        }

        $lines = preg_split('/\r\n|\r|\n/', $csv);
        if (!$lines || count($lines) < 2) {
            $this->command->warn('CSV appears empty');
            return;
        }

        // Parse header
        $header = str_getcsv(array_shift($lines));

        // Map indices
        $idx = function(string $name) use ($header) {
            $pos = array_search($name, $header, true);
            return $pos === false ? null : $pos;
        };

        $iTitle = $idx('title');
        $iBrand = $idx('brand');
        $iFinal = $idx('final_price');
        $iInitial = $idx('initial_price');
        $iCurrency = $idx('currency');
        $iAvail = $idx('availability');
        $iCats = $idx('categories');
        $iImage = $idx('image_url');
        $iImages = $idx('images');
        $iUrl = $idx('url');
        $iDesc = $idx('description');
        // Bucket CSV rows into our target categories using keywords on the categories column
        $buckets = [];
        foreach (array_keys($targetCategories) as $k) { $buckets[$k] = []; }
        foreach ($lines as $line) {
            if (trim($line) === '') continue;
            $row = str_getcsv($line);
            if (!$row || count($row) < 3) continue;
            $rawCats = strtolower((string)($row[$iCats] ?? ''));
            $matchedCat = null;
            foreach ($targetCategories as $catName => $keywords) {
                foreach ($keywords as $kw) {
                    if ($kw !== '' && str_contains($rawCats, $kw)) { $matchedCat = $catName; break; }
                }
                if ($matchedCat) break;
            }
            if ($matchedCat) { $buckets[$matchedCat][] = $row; }
        }

        $totalImported = 0;
        foreach ($buckets as $catName => $rows) {
            if ($totalImported >= 300) break;
            // Prefer available items first
            usort($rows, function($a,$b) use($iAvail){
                $as = strtolower((string)($a[$iAvail] ?? ''));
                $bs = strtolower((string)($b[$iAvail] ?? ''));
                $ain = str_contains($as, 'in stock');
                $bin = str_contains($bs, 'in stock');
                if ($ain === $bin) return 0; return $ain ? -1 : 1;
            });

            $category = ProductCategory::firstOrCreate(
                ['slug' => Str::slug($catName)],
                ['name' => $catName, 'visibility' => 1]
            );

            $importedForCat = 0; $cursor = 0;
            while ($importedForCat < 30 && $cursor < count($rows) && $totalImported < 300) {
                $row = $rows[$cursor++];

                $title = trim((string)($row[$iTitle] ?? ''));
                if ($title === '') continue;
                $title = Str::limit($title, 70, '');

                // Build images array: prefer 'images' JSON array, fallback to 'image_url'
                $images = [];
                if ($iImages !== null) {
                    $imagesRaw = (string)($row[$iImages] ?? '');
                    if ($imagesRaw !== '') {
                        $decoded = json_decode($imagesRaw, true);
                        if (is_array($decoded)) {
                            $images = array_values(array_filter($decoded));
                        }
                    }
                }
                if (empty($images)) {
                    $imageUrl = trim((string)($row[$iImage] ?? ''));
                    if ($imageUrl !== '') { $images = [$imageUrl]; }
                }
                // Filter to likely valid image URLs
                $images = array_values(array_filter($images, function($u){ return preg_match('/\.(jpe?g|png|webp)(\?.*)?$/i', $u); }));
                if (empty($images)) continue;

                $brandName = trim((string)($row[$iBrand] ?? 'Generic')) ?: 'Generic';
                $brand = ProductBrand::firstOrCreate(
                    ['slug' => Str::slug($brandName)],
                    ['name' => $brandName, 'visibility' => 1]
                );

                // Sanitize price strings and convert to cents
                $finalStr = (string)($row[$iFinal] ?? '');
                $initialStr = (string)($row[$iInitial] ?? '');
                $sanitize = function ($s) {
                    $s = trim($s);
                    // remove non-numeric except dot and minus
                    $s = preg_replace('/[^0-9\.\-]/', '', $s ?? '');
                    if ($s === '' || strtolower($s) === 'null') return null;
                    return (float) $s;
                };
                $finalVal = $sanitize($finalStr);
                $initialVal = $sanitize($initialStr);
                $finalCents = $finalVal !== null ? (int) round($finalVal * 100) : null;
                $initialCents = $initialVal !== null ? (int) round($initialVal * 100) : null;
                $regular = $initialCents ?: ($finalCents ?: 0);
                $sale = ($initialCents !== null && $finalCents !== null && $finalCents < $initialCents) ? $finalCents : null;
                if ($regular === 0 && $finalCents !== null && $finalCents > 0) {
                    $regular = $finalCents;
                    $sale = null;
                }
                $desc = (string)($row[$iDesc] ?? '');
                $avail = strtolower((string)($row[$iAvail] ?? ''));
                $stock = str_contains($avail, 'in stock') ? rand(15, 60) : rand(0, 5);

                // Unique slug
                $base = Str::limit(Str::slug($title), 80, '');
                if ($base === '') $base = 'item';
                $slug = $base; $suffix = 1;
                while (Product::where('slug', $slug)->exists()) { $slug = Str::limit($base.'-'.$suffix++, 80, ''); }

                $product = Product::updateOrCreate([
                    'slug' => $slug,
                ],[
                    'user_id' => 1,
                    'category_id' => (string) $category->id,
                    'brand_id' => $brand->id,
                    'title' => $title,
                    'description' => $desc,
                    'short_description' => Str::limit(strip_tags($desc), 200),
                    'code' => strtoupper(Str::random(3)).'-'.random_int(10000,99999),
                    'regular_price' => $regular,
                    'sale_price' => $sale,
                    'purchase_price' => (int) round((($regular ?: ($finalCents ?? 0))) * 0.6),
                    'remote_images' => $images,
                    'visibility' => '1',
                ]);

                $inventory = Inventory::firstOrCreate(['product_id' => $product->id], [
                    'current_stock' => 0,
                    'total_stock' => 0,
                    'reserved_stock' => 0,
                    'low_stock_threshold' => 2,
                    'unit_cost' => max(0.01, $product->purchase_price / 100),
                    'total_value' => 0,
                    'is_active' => true,
                ]);
                $inventory->updateStock('adjustment', $stock, 'Seeder: Amazon CSV grouped import');

                $importedForCat++;
                $totalImported++;
            }
        }

        $this->command->info("Imported {$totalImported} products across 10 categories (30 each) from Amazon CSV.");
    }
}


