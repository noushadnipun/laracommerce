<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Models\Term;

class TermsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $terms = [
            [
                'id' => 1,
                'name' => 'Page',
                'slug' => 'page',
                'created_at' => '2021-02-13 15:25:35',
                'updated_at' => '2021-02-13 15:25:35',
            ],
            [
                'id' => 2,
                'name' => 'Slider',
                'slug' => 'slider',
                'created_at' => '2021-02-13 15:25:35',
                'updated_at' => '2021-02-13 15:25:35',
            ],
        ];

        foreach ($terms as $term) {
            Term::updateOrCreate(
                ['id' => $term['id']],
                $term
            );
        }

        $this->command->info('✅ Terms seeded successfully!');
    }
}
