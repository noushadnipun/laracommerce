<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Models\TermTaxonomy;

class TermTaxonomySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $termTaxonomy = [
            [
                'id' => 1,
                'name' => 'Categories',
                'slug' => 'slider',
                'type' => null,
                'term_type' => 'slider',
                'created_at' => '2021-02-13 15:25:35',
                'updated_at' => '2021-02-13 15:25:35',
            ],
        ];
        
        foreach ($termTaxonomy as $term) {
            TermTaxonomy::updateOrCreate(
                ['id' => $term['id']],
                $term
            );
        }

        $this->command->info('Term Taxonomies seeded successfully!');
    }
}
