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
                'icon' => 'fa fa-dot-circle-o'
            ],
            'Away' => [
                'color' => '#FFDC00',
                'icon' => 'fa fa-paper-plane-o'
            ],
            'Busy' => [
                'color' => '#FF4136',
                'icon' => 'fa fa-bell-slash-o'
            ],
            'Offline' => [
                'color' => '#AAAAAA',
                'icon' => 'fa fa-power-off'
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
