<?php
/**
 * NOTICE OF LICENSE
 *
 * UNIT3D is open-sourced software licensed under the GNU General Public License v3.0
 * The details is bundled with this project in the file LICENSE.txt.
 *
 * @project    UNIT3D
 * @license    https://www.gnu.org/licenses/agpl-3.0.en.html/ GNU Affero General Public License v3.0
 * @author     Poppabear
 */

namespace App\Console\Commands;

use App\Services\Clients\OmdbClient;
use App\Services\MovieScrapper;
use App\Torrent;
use App\User;
use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class demoSeed extends Command
{

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'demo:seed';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Seeds fake data for demonstration or testing purposes';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->alert('Demo Seeder v1.0 Beta (Author: Poppabear)');
        $this->warn('*** This process could take a few minutes ***');
        $this->warn('Press CTRL + C to abort');

        sleep(5);

        foreach ($this->ids() as $key => $value) {
            // Users
            $this->info('Creating User Account');

            $uid = factory(User::class)->create()->id;

            foreach ($value as $id) {

                // random boolean
                if ([false,true][rand(0,1)]) {

                    $r = $this->search('tt' . $id);

                    // Torrents
                    $this->info('Creating Movie Torrents for Account ID #'.$uid);

                    factory(Torrent::class)->create([
                        'user_id' => $uid,
                        'imdb' => $id,
                        'name' => $r['Title'],
                        'slug' => str_slug($r['Title']),
                        'description' => $r['Plot'],
                        'category_id' => 1,
                    ]);
                }
            }
        }

        $this->alert('Demo data has been successfully seeded!');
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [

        ];
    }

    private function search($id)
    {
        // we delay between api calls to reduce throttling
        usleep(500000);

        $key = env('OMDB_API_KEY', config('api-keys.omdb'));

        $url = 'http://www.omdbapi.com/?apikey=' . $key . '&i=' . $id . '&r=json&plot=full';

        $omdb = new OmdbClient($key);

        return $omdb->toArray($omdb->request($url));
    }

    private function ids()
    {
        return [
            ["2948356", "2094766", "2592614", "0139809", "0088323", "1959409", "0076729", "0089175", "3553976"],
            ["0051622", "0078788", "6315800", "0472033", "2488496", "3748528", "0230011", "0158983", "4972582"],
            ["3783958", "4276820", "0311113", "4276820", "4972582", "2072233", "4385888", "0106611", "3640424"],
            ["2713180", "2282016", "0402022", "0467406", "0448134", "0079116", "0386064", "0120657", "0111161"],
            ["0113568", "0108399", "0265086", "0317248", "1675434", "1436045", "0416044", "0103639", "0399201"],
            ["2960470", "0101414", "0325710", "2488496", "3631112", "0120591", "0395169", "1862079", "0133093"],
            ["0289043", "3289956", "3521164", "0325980", "0377062", "1598778", "0401792", "4418398", "4303340"],
            ["4276820", "0118929", "1540133", "0499549", "3393786", "1620935", "1878870", "0387808", "0159365"],
            ["3470600", "3717252", "0266987", "0046438", "2094766", "1032755", "3521164", "3183660", "2119532"],
            ["4662420", "4276820", "2278388", "1895315", "0342258", "0266308", "0477348", "0120746", "0129167"],
        ];
    }

}
