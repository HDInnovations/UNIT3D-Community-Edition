<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\DonationItem;

class DonationItemsSeeder extends Seeder
{
    public function run(): void
    {
        DonationItem::upsert([
            [
                'id'          => 0,
                'type'        => 'Freeleech',
                'name'        => '0 Month',
                'description' => 'Custom amount of days',
                'seedbonus'   => 0,
                'uploaded'    => 0,
                'invites'     => 0,
                'days_active' => 0,
                'price_usd'   => '0',
            ],
            [
                'id'          => 11,
                'type'        => 'Freeleech',
                'name'        => '1 Month',
                'description' => '',
                'seedbonus'   => 1000,
                'uploaded'    => 0,
                'invites'     => 0,
                'days_active' => 30,
                'price_usd'   => '5.20',
            ],
            [
                'id'          => 12,
                'type'        => 'Freeleech',
                'name'        => '3 Months',
                'description' => '',
                'seedbonus'   => 2500,
                'uploaded'    => 0,
                'invites'     => 0,
                'days_active' => 90,
                'price_usd'   => '10.40',
            ],
            [
                'id'          => 13,
                'type'        => 'Freeleech',
                'name'        => '6 Months',
                'description' => '',
                'seedbonus'   => 6000,
                'uploaded'    => 0,
                'invites'     => 0,
                'days_active' => 180,
                'price_usd'   => '20.70',
            ],
            [
                'id'          => 14,
                'type'        => 'Freeleech',
                'name'        => '12 Months',
                'description' => '',
                'seedbonus'   => 15000,
                'uploaded'    => 0,
                'invites'     => 0,
                'days_active' => 365,
                'price_usd'   => '41.40',
            ],
        ], ['id']);
    }
}
