<?php

use App\ChatStatus;
use Illuminate\Database\Seeder;

class ChatStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $statuses = [
            'Online' => [
                'color' => '#2ECC40',
                'icon' => '{{ config("other.font-awesome") }} fa-dot-circle'
            ],
            'Away' => [
                'color' => '#FFDC00',
                'icon' => '{{ config("other.font-awesome") }} fa-paper-plane'
            ],
            'Busy' => [
                'color' => '#FF4136',
                'icon' => '{{ config("other.font-awesome") }} fa-bell-slash'
            ],
            'Offline' => [
                'color' => '#AAAAAA',
                'icon' => '{{ config("other.font-awesome") }} fa-power-off'
            ]
        ];

        foreach ($statuses as $status => $columns) {
            ChatStatus::create([
                'name' => $status,
                'color' => $columns['color'],
                'icon' => $columns['icon']
            ]);
        }
    }
}
