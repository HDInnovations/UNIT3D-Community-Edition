<?php

use Faker\Generator as Faker;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TorrentSeeder extends Seeder
{
    /**
     * @var Faker
     */
    private $faker;

    /**
     * TorrentSeeder constructor.
     *
     * @param Faker $faker
     */
    public function __construct(Faker $faker)
    {
        $this->faker = $faker;
    }

    /**
     * Run the database seeds.
     *
     * @return void
     * @throws Exception
     */
    public function run()
    {
        $torrents = (int) $this->command->ask('How many torrents to seed?', 100);

        $inserts = [];

        for ($i = 0; $i < $torrents; $i++) {
            $inserts[] = [
                'name'         => $this->faker->sentence,
                'slug'         => $this->faker->uuid,
                'description'  => $this->faker->paragraph,
                'imdb'         => '7366338',
                'category_id'  => 2,
                'user_id'      => 1,
                'info_hash'    => $this->faker->md5,
                'file_name'    => 'my_really_nice_file.'.$this->faker->fileExtension,
                'num_file'     => $this->faker->numberBetween(1, 50),
                'size'         => $this->faker->numberBetween(1, 99999999999),
                'announce'     => $this->faker->url,
                'type'         => 1,
                'status'       => 1,
                'moderated_by' => 1,
                'moderated_at' => now(),
                'created_at'   => now(),
                'updated_at'   => now(),
            ];
        }

        DB::table('torrents')->insert($inserts);
    }
}
