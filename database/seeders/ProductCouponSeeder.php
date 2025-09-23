<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ProductCoupon;

class ProductCouponSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $coupons = [
            [
                'code' => 'WELCOME10',
                'type' => 'percentage',
                'value' => '10',
                'amount' => '10',
                'expired_date' => now()->addMonths(3)->format('Y-m-d'),
                'status' => '1',
            ],
            [
                'code' => 'SAVE20',
                'type' => 'percentage',
                'value' => '20',
                'amount' => '20',
                'expired_date' => now()->addMonths(1)->format('Y-m-d'),
                'status' => '1',
            ],
            [
                'code' => 'FREESHIP',
                'type' => 'fixed',
                'value' => '9.99',
                'amount' => '9.99',
                'expired_date' => now()->addMonths(2)->format('Y-m-d'),
                'status' => '1',
            ],
            [
                'code' => 'FLASH50',
                'type' => 'percentage',
                'value' => '50',
                'amount' => '50',
                'expired_date' => now()->addDays(7)->format('Y-m-d'),
                'status' => '1',
            ],
            [
                'code' => 'STUDENT15',
                'type' => 'percentage',
                'value' => '15',
                'amount' => '15',
                'expired_date' => now()->addYear()->format('Y-m-d'),
                'status' => '1',
            ],
        ];

        foreach ($coupons as $coupon) {
            ProductCoupon::updateOrCreate(
                ['code' => $coupon['code']],
                $coupon
            );
        }
    }
}
