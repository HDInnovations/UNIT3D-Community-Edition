<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\DonationItem;

class DonationItemsSeeder extends Seeder
{
    private $donationItems;

    public function __construct()
    {
        $this->donationItems = $this->getDonationItems();
    }

    /**
     * Auto generated seed file.
     */
    public function run(): void
    {
        foreach ($this->donationItems as $donationItem) {
            DonationItem::updateOrCreate($donationItem);
        }
    }

    private function getDonationItems(): array
    {
        return [
            [
                'id'           => 1,
                'type'         => 'vip',
                'name'         => '1 Month',
                'description'  => '',
                'bon_bonus'    => 1000,
                'ul_bonus'     => 0,
                'invite_bonus' => 0,
                'days_active'  => 30,
                'price_usd'    => '5.20',
            ],
            [
                'id'           => 2,
                'type'         => 'vip',
                'name'         => '3 Months',
                'description'  => '',
                'bon_bonus'    => 2500,
                'ul_bonus'     => 0,
                'invite_bonus' => 0,
                'days_active'  => 90,
                'price_usd'    => '10.40',
            ],
            [
                'id'           => 3,
                'type'         => 'vip',
                'name'         => '6 Months',
                'description'  => '',
                'bon_bonus'    => 6000,
                'ul_bonus'     => 0,
                'invite_bonus' => 0,
                'days_active'  => 180,
                'price_usd'    => '20.70',
            ],
            [
                'id'           => 4,
                'type'         => 'vip',
                'name'         => '12 Months',
                'description'  => '',
                'bon_bonus'    => 15000,
                'ul_bonus'     => 0,
                'invite_bonus' => 0,
                'days_active'  => 365,
                'price_usd'    => '41.40',
            ],
        ];
    }
}
