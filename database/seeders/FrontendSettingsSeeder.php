<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Models\FrontendSettings;

class FrontendSettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $settings = [
            [
                'id' => 1,
                'meta_name' => 'site_logoimg_id',
                'meta_value' => '3',
                'created_at' => '2021-02-13 15:25:35',
                'updated_at' => '2021-02-14 08:20:02',
            ],
            [
                'id' => 2,
                'meta_name' => 'home_slider',
                'meta_value' => '1',
                'created_at' => '2021-02-13 15:25:35',
                'updated_at' => '2021-02-19 05:35:09',
            ],
            [
                'id' => 3,
                'meta_name' => 'home_product_category',
                'meta_value' => '["1"]',
                'created_at' => '2021-02-13 15:25:35',
                'updated_at' => '2021-03-06 09:26:00',
            ],
            [
                'id' => 4,
                'meta_name' => 'company_phone',
                'meta_value' => '01923',
                'created_at' => null,
                'updated_at' => '2021-03-06 09:17:48',
            ],
            [
                'id' => 5,
                'meta_name' => 'home_slider_right_side_banner',
                'meta_value' => '2',
                'created_at' => null,
                'updated_at' => '2021-03-06 07:47:02',
            ],
            [
                'id' => 6,
                'meta_name' => 'footer_content',
                'meta_value' => 'trtrc',
                'created_at' => null,
                'updated_at' => '2021-03-06 09:17:44',
            ],
            [
                'id' => 7,
                'meta_name' => 'fb_url',
                'meta_value' => 'facebook.com',
                'created_at' => null,
                'updated_at' => '2021-03-06 09:22:34',
            ],
            [
                'id' => 8,
                'meta_name' => 'twitter_url',
                'meta_value' => 'twitter.com',
                'created_at' => null,
                'updated_at' => '2021-03-06 09:26:00',
            ],
            [
                'id' => 9,
                'meta_name' => 'instagram_url',
                'meta_value' => 'instagram.com',
                'created_at' => null,
                'updated_at' => '2021-03-06 09:26:00',
            ],
            // New homepage config keys (safe defaults)
            [
                'meta_name' => 'frontend_homepage_container',
                'meta_value' => 'container-fluid',
            ],
            [
                'meta_name' => 'homepage_sections_order',
                'meta_value' => json_encode(["hero","promo_strips","featured_categories","new_arrivals","best_sellers","on_sale","brand_carousel","recently_viewed","seo_block"]),
            ],
            [
                'meta_name' => 'promo_strips',
                'meta_value' => json_encode([
                    ["icon"=>"fa-truck","title"=>"Free Shipping","text"=>"On orders over 1000"],
                    ["icon"=>"fa-headphones","title"=>"Support 24/7","text"=>"We’re here to help"],
                    ["icon"=>"fa-shield","title"=>"Secure Payment","text"=>"100% secure"],
                ]),
            ],
            [
                'meta_name' => 'new_arrivals',
                'meta_value' => json_encode(["title"=>"New Arrivals","source"=>"latest","limit"=>12]),
            ],
            [
                'meta_name' => 'best_sellers',
                'meta_value' => json_encode(["title"=>"Best Sellers","source"=>"sales","limit"=>12]),
            ],
            [
                'meta_name' => 'on_sale',
                'meta_value' => json_encode(["title"=>"Hot Deals","limit"=>12]),
            ],
            [
                'meta_name' => 'brand_carousel',
                'meta_value' => json_encode(["title"=>"Top Brands","brand_ids"=>[]]),
            ],
            [
                'meta_name' => 'recently_viewed',
                'meta_value' => json_encode(["title"=>"Recently Viewed","limit"=>12]),
            ],
            [
                'meta_name' => 'homepage_seo_block',
                'meta_value' => json_encode(["title"=>"About Our Store","html"=>""]),
            ],
        ];

        foreach ($settings as $setting) {
            if (isset($setting['id'])) {
                FrontendSettings::updateOrCreate(['id' => $setting['id']], $setting);
            } else {
                FrontendSettings::updateOrCreate(['meta_name' => $setting['meta_name']], [
                    'meta_value' => $setting['meta_value']
                ]);
            }
        }

        $this->command->info('✅ Frontend Settings seeded successfully!');
    }
}
