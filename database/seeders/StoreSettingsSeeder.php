<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\StoreSettings;

class StoreSettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settings = [
            [
                'id' => 1,
                'meta_name' => 'shipping_type',
                'meta_value' => 'flat_rate',
                'created_at' => null,
                'updated_at' => '2021-02-19 23:52:54',
            ],
            [
                'id' => 2,
                'meta_name' => 'shipping_flat_rate',
                'meta_value' => '120',
                'created_at' => null,
                'updated_at' => '2021-02-19 23:30:28',
            ],
            [
                'id' => 3,
                'meta_name' => 'ssl_store_id',
                'meta_value' => 'icon4602b36900b1e5',
                'created_at' => null,
                'updated_at' => '2021-03-05 13:00:04',
            ],
            [
                'id' => 4,
                'meta_name' => 'ssl_store_password',
                'meta_value' => 'icon4602b36900b1e5@ssl',
                'created_at' => null,
                'updated_at' => '2021-03-05 13:11:42',
            ],
            [
                'id' => 5,
                'meta_name' => 'ssl_sandbox_live',
                'meta_value' => 'sandbox',
                'created_at' => null,
                'updated_at' => '2021-03-05 13:00:04',
            ],
        ];

        foreach ($settings as $setting) {
            StoreSettings::updateOrCreate(
                ['id' => $setting['id']],
                $setting
            );
        }

        $this->command->info('✅ Store Settings seeded successfully!');
    }
}
