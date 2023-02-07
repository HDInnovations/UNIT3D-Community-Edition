<?php
/**
 * NOTICE OF LICENSE.
 *
 * UNIT3D Community Edition is open-sourced software licensed under the GNU Affero General Public License v3.0
 * The details is bundled with this project in the file LICENSE.txt.
 *
 * @project    UNIT3D Community Edition
 *
 * @author     HDVinnie <hdinnovations@protonmail.com>
 * @license    https://www.gnu.org/licenses/agpl-3.0.en.html/ GNU Affero General Public License v3.0
 */

namespace App\Console\Commands;

use App\Models\Torrent;
use App\Models\User;
use App\Services\Tmdb\Client\Movie;
use App\Services\Tmdb\Client\TV;
use App\Services\Tmdb\TMDBScraper;
use Exception;
use Illuminate\Console\Command;

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
     * Execute the console command.
     */
    public function handle(): void
    {
        $this->alert('Demo Seeder v2.0 (Author: Poppabear)');
        $this->warn('*** This process could take a few minutes ***');
        $this->warn('Press CTRL + C to abort');

        sleep(5);

        $abort = false;

        foreach ($this->movie_ids() as $key => $id) {
            // Users
            $this->info('Creating User Account');

            $uid = User::factory()->create([
                'chatroom_id'    => 1,
                'group_id'       => random_int(1, 20),
                'chat_status_id' => 1,
                'image'          => null,
                'custom_css'     => null,
                'locale'         => 'en',
            ])->id;

            // random boolean
            if ([false, true][random_int(0, 1)]) {
                $movie = $this->fetchMovie($id);

                // Torrents
                $this->info('Creating Movie Torrents for Account ID #'.$uid);

                try {
                    $year = 2021;

                    if (\array_key_exists('release_date', $movie)) {
                        $year = (int) substr($movie['release_date'], 0, 4);
                    }

                    $freeleech = ['0', '25', '50', '75', '100'];
                    $selected = random_int(0, \count($freeleech) - 1);

                    Torrent::factory()->create([
                        'user_id'        => $uid,
                        'tmdb'           => $id,
                        'name'           => $movie['title'].' ('.$year.')',
                        'description'    => $movie['overview'],
                        'category_id'    => 1,
                        'type_id'        => random_int(1, 6),
                        'resolution_id'  => random_int(1, 10),
                        'region_id'      => random_int(1, 242),
                        'distributor_id' => random_int(1, 965),
                        'free'           => $freeleech[$selected],
                        'featured'       => false,
                        'sticky'         => 0,
                        'release_year'   => $year,
                        'created_at'     => now(),
                        'bumped_at'      => now(),
                        'updated_at'     => now(),
                    ]);
                } catch (Exception $exception) {
                    $abort = true;

                    $this->warn($exception);

                    break;
                }
            }

            if ($abort) {
                break;
            }
        }

        foreach ($this->tv_ids() as $key => $id) {
            // Users
            $this->info('Creating User Account');

            $uid = User::factory()->create([
                'chatroom_id'    => 1,
                'group_id'       => random_int(1, 20),
                'chat_status_id' => 1,
                'image'          => null,
                'custom_css'     => null,
                'locale'         => 'en',
            ])->id;

            // random boolean
            if ([false, true][random_int(0, 1)]) {
                $tv = $this->fetchTv($id);

                // Torrents
                $this->info('Creating TV Torrents for Account ID #'.$uid);

                try {
                    $year = 2021;

                    if (\array_key_exists('first_air_date', $tv)) {
                        $year = (int) substr($tv['first_air_date'], 0, 4);
                    }

                    $freeleech = ['0', '25', '50', '75', '100'];
                    $selected = random_int(0, \count($freeleech) - 1);

                    Torrent::factory()->create([
                        'user_id'        => $uid,
                        'tmdb'           => $id,
                        'name'           => $tv['name'].' ('.$year.')',
                        'description'    => $tv['overview'],
                        'category_id'    => 2,
                        'type_id'        => random_int(1, 6),
                        'resolution_id'  => random_int(1, 10),
                        'region_id'      => random_int(1, 242),
                        'distributor_id' => random_int(1, 965),
                        'free'           => $freeleech[$selected],
                        'featured'       => false,
                        'sticky'         => 0,
                        'release_year'   => $year,
                        'created_at'     => now(),
                        'bumped_at'      => now(),
                        'updated_at'     => now(),
                    ]);
                } catch (Exception $exception) {
                    $abort = true;

                    $this->warn($exception);

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
            $this->alert('Ensure TMDB api key is set and run "php artisan config:clear"');
        } else {
            $this->alert('Demo data has been successfully seeded!');
        }
    }

    private function fetchMovie($id)
    {
        sleep(2);
        $tmdbScraper = new TMDBScraper();
        $tmdbScraper->movie($id);

        return (new Movie($id))->getData();
    }

    private function fetchTv($id)
    {
        sleep(2);
        $tmdbScraper = new TMDBScraper();
        $tmdbScraper->tv($id);

        return (new TV($id))->getData();
    }

    private function movie_ids(): array
    {
        return [
            '15283',
            '211182',
            '11020',
            '108688',
            '11134',
            '3114',
            '58693',
            '52113',
            '179150',
            '17057',
            '59961',
            '64700',
            '353486',
            '1091',
            '38643',
            '335983',
            '42745',
            '260513',
            '299536',
            '27622',
            '242049',
            '44560',
            '127570',
            '292834',
            '586101',
            '45884',
            '69152',
            '212778',
            '417407',
            '89899',
            '104086',
            '17339',
            '27612',
            '81976',
            '11510',
            '493201',
            '797',
            '366924',
            '42359',
            '84287',
            '47792',
            '549859',
            '19095',
            '10596',
            '24276',
            '303858',
            '14817',
            '95963',
            '23479',
            '293670',
            '54093',
            '425003',
            '436387',
            '146',
            '228',
            '341354',
            '2976',
            '13783',
            '16231',
            '520900',
            '10784',
            '95',
            '28322',
            '14029',
            '2675',
            '140260',
            '618214',
            '40108',
            '61143',
            '16281',
            '1556',
            '170296',
            '27030',
            '9349',
            '47715',
            '355993',
            '8224',
            '13982',
            '9959',
            '11652',
            '42690',
            '605',
            '417812',
            '276843',
            '451480',
            '83788',
            '447200',
            '133786',
            '420426',
            '11938',
            '63113',
            '130300',
            '67294',
            '174340',
            '477654',
            '258509',
            '550738',
            '197',
            '76489',
            '5910',
        ];
    }

    private function tv_ids(): array
    {
        return [
            '119815',
            '1408',
            '120965',
            '112668',
            '101400',
            '32726',
            '71391',
            '96005',
            '84915',
            '1813',
            '190',
            '65417',
            '74316',
            '127235',
            '35790',
            '62811',
            '16118',
            '44856',
            '34735',
            '32726',
            '66941',
            '88052',
            '82204',
            '62649',
            '84534',
            '73320',
            '74091',
            '28877',
            '826',
            '82744',
            '79084',
            '63181',
            '64196',
            '82747',
            '30984',
            '95432',
            '2706',
            '1920',
            '135517',
            '116858',
            '47480',
            '138233',
            '66840',
            '3319',
            '33841',
            '7248',
            '64464',
            '46279',
            '44006',
            '80230',
            '46459',
            '484',
            '46261',
            '1894',
            '826',
            '18347',
            '111133',
            '106158',
            '64406',
            '87148',
            '46261',
            '918',
            '56296',
            '44006',
            '64254',
            '82653',
            '129489',
            '1104',
            '1424',
            '83685',
            '63079',
            '46952',
            '62623',
            '52',
            '30984',
            '35046',
            '59117',
            '2710',
            '1705',
            '62688',
            '190',
            '113704',
            '501',
            '19757',
            '68595',
            '314',
            '112',
            '2207',
            '90282',
            '7166',
            '37863',
            '105843',
            '71663',
            '2046',
            '68864',
            '1402',
            '107530',
            '93519',
            '60694',
            '83097',
        ];
    }
}
