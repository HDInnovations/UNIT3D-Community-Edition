<?php

use App\Models\ChatStatus;
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
                'icon'  => config('other.font-awesome').' fa-comment-smile',
            ],
            'Away' => [
                'color' => '#FFDC00',
                'icon'  => config('other.font-awesome').' fa-comment-minus',
            ],
            'Busy' => [
                'color' => '#FF4136',
                'icon'  => config('other.font-awesome').' fa-comment-exclamation',
            ],
            'Offline' => [
                'color' => '#AAAAAA',
                'icon'  => config('other.font-awesome').' fa-comment-slash',
            ],
        ];

        foreach ($statuses as $status => $columns) {
            ChatStatus::create([
                'name'  => $status,
                'color' => $columns['color'],
                'icon'  => $columns['icon'],
            ]);
        }
    }
}
