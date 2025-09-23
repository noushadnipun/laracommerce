<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\Product;

class TrimProductTitlesSlugsSeeder extends Seeder
{
    public function run(): void
    {
        $maxTitle = 70;   // characters
        $maxSlug  = 80;   // characters

        $count = 0;
        Product::chunk(500, function ($products) use (&$count, $maxTitle, $maxSlug) {
            foreach ($products as $p) {
                $originalTitle = (string)($p->title ?? '');
                $newTitle = Str::limit($originalTitle, $maxTitle, '');

                // Generate compact slug from (new) title
                $base = Str::slug($newTitle);
                $base = Str::limit($base, $maxSlug, '');
                if ($base === '') {
                    $base = 'item';
                }
                $slug = $base;
                $suffix = 1;
                while (Product::where('slug', $slug)->where('id', '!=', $p->id)->exists()) {
                    $slug = Str::limit($base.'-'.$suffix++, $maxSlug, '');
                }

                // Only update if changed
                if ($newTitle !== $p->title || $slug !== $p->slug) {
                    $p->title = $newTitle;
                    $p->slug = $slug;
                    $p->save();
                    $count++;
                }
            }
        });

        $this->command->info("Trimmed titles/slugs for {$count} products.");
    }
}



