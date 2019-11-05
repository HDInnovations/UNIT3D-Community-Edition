<?php
/**
 * NOTICE OF LICENSE.
 *
 * UNIT3D is open-sourced software licensed under the GNU Affero General Public License v3.0
 * The details is bundled with this project in the file LICENSE.txt.
 *
 * @project    UNIT3D
 *
 * @license    https://www.gnu.org/licenses/agpl-3.0.en.html/ GNU Affero General Public License v3.0
 * @author     Poppabear
 */

namespace App\Console\Commands;

use App\Models\Torrent;
use App\Models\User;
use App\Services\Clients\OmdbClient;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class DemoSeed extends Command
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
    protected $description = 'Seeds Fake Data For Demonstration Or Testing Purposes';

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
        $this->alert('Demo Seeder v1.0 (Author: Poppabear)');
        $this->warn('*** This process could take a few minutes ***');
        $this->warn('Press CTRL + C to abort');

        sleep(5);

        $abort = false;

        foreach ($this->ids() as $key => $id) {
            // Users
            $this->info('Creating User Account');

            $uid = factory(User::class)->create()->id;

            // random boolean
            if ([false, true][rand(0, 1)]) {
                $r = $this->search('tt'.$id);

                // Torrents
                $this->info('Creating Movie Torrents for Account ID #'.$uid);

                try {
                    factory(Torrent::class)->create([
                            'user_id' => $uid,
                            'imdb' => $id,
                            'name' => $r['Title'],
                            'slug' => Str::slug($r['Title']),
                            'description' => $r['Plot'],
                            'category_id' => 1,
                        ]);
                } catch (\Exception $e) {
                    $abort = true;

                    break;
                }
            }

            if ($abort) {
                break;
            }
        }

        if ($abort) {
            $this->error('Aborted ...');
            $this->alert('Demo data was only PARTIALLY seeded! This is likely due to an API Request timeout.');
        } else {
            $this->alert('Demo data has been successfully seeded!');
        }
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

        $key = config('api-keys.omdb');

        $url = 'http://www.omdbapi.com/?apikey='.$key.'&i='.$id.'&r=json&plot=full';

        $omdb = new OmdbClient($key);

        return $omdb->toArray($omdb->request($url));
    }

    private function ids()
    {
        return [
            '2948356',
            '2094766',
            '2592614',
            '0139809',
            '0088323',
            '1959409',
            '0076729',
            '0089175',
            '3553976',
            '0051622',
            '0078788',
            '6315800',
            '0472033',
            '2488496',
            '3748528',
            '0230011',
            '0158983',
            '4972582',
            '3783958',
            '4276820',
            '0311113',
            '4276820',
            '4972582',
            '2072233',
            '4385888',
            '0106611',
            '3640424',
            '2282016',
            '0402022',
            '0467406',
            '0448134',
            '0079116',
            '0386064',
            '0120657',
            '0111161',
            '0113568',
            '0108399',
            '0265086',
            '0317248',
            '1675434',
            '1436045',
            '0416044',
            '0103639',
            '0399201',
            '2960470',
            '0101414',
            '0325710',
            '2488496',
            '3631112',
            '0120591',
            '0395169',
            '1862079',
            '0133093',
            '0289043',
            '3289956',
            '3521164',
            '0325980',
            '0377062',
            '1598778',
            '0401792',
            '4418398',
            '4303340',
            '4276820',
            '0118929',
            '1540133',
            '0499549',
            '3393786',
            '1620935',
            '1878870',
            '0387808',
            '0159365',
            '3470600',
            '3717252',
            '0266987',
            '0046438',
            '2094766',
            '1032755',
            '3521164',
            '3183660',
            '2119532',
            '4276820',
            '2278388',
            '1895315',
            '0342258',
            '0266308',
            '0477348',
            '0120746',
            '0129167',
            '0272152',
            '0101272',
            '0120815',
            '0910970',
            '2096673',
            '4698684',
            '0338564',
            '0343660',
            '0758758',
            '0124298',
            '0208092',
            '3783958',
            '3783958',
            '1034303',
            '0498380',
            '3666024',
            '0093990',
            '0098662',
            '0097165',
            '3748528',
            '1211837',
            '0109506',
            '4572514',
            '0814314',
            '0112384',
            '0375912',
            '0408306',
            '1568921',
            '0094625',
            '2630336',
            '1718199',
            '0455824',
            '1979319',
            '3095734',
            '0119229',
            '0087985',
            '0114436',
            '1712261',
            '1250777',
            '1790885',
            '1800302',
            '5068650',
            '1753383',
            '0162661',
            '0356910',
            '0319262',
            '0892255',
            '4172430',
            '0374569',
            '0246578',
            '2277860',
            '3659388',
            '1628841',
            '0097814',
            '3756788',
            '0108188',
            '2381991',
            '0298228',
            '4094724',
            '1091191',
            '0116277',
            '0129167',
            '0129167',
            '0055630',
            '0210945',
            '3385516',
            '0080745',
            '0816692',
            '2802144',
            '0938283',
            '0119250',
            '2719848',
            '0074812',
            '1454468',
            '0084787',
            '3717252',
            '0111255',
            '5700672',
            '1054606',
            '0360201',
            '0093870',
            '0108052',
            '0372784',
            '0068646',
            '4846340',
            '3183660',
            '0113540',
            '1935859',
            '4902904',
            '0105323',
            '0078788',
            '0112573',
            '0120631',
            '0078748',
            '0100802',
            '5788136',
        ];
    }
}
