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
use Exception;
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
                $r = $this->search($id);

                // Torrents
                $this->info('Creating Movie Torrents for Account ID #'.$uid);

                try {
                    factory(Torrent::class)->create([
                        'user_id'     => $uid,
                        'tmdb'        => $id,
                        'name'        => $r->title.' ('.$r->releaseYear.')',
                        'slug'        => Str::slug($r->title),
                        'description' => $r->plot,
                        'category_id' => 1,
                    ]);
                } catch (Exception $e) {
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

        $client = new \App\Services\MovieScrapper(config('api-keys.tmdb'), config('api-keys.tvdb'), config('api-keys.omdb'));

        return $client->scrape('movie', null, $id);
    }

    private function ids()
    {
        return [
            '25385',
            '46623',
            '4935',
            '16448',
            '458737',
            '42581',
            '66247',
            '11778',
            '139038',
            '1955',
            '468224',
            '27983',
            '503346',
            '506528',
            '7979',
            '25598',
            '7450',
            '14612',
            '505948',
            '504562',
            '136311',
            '1672',
            '45706',
            '98193',
            '9458',
            '126432',
            '22194',
            '35102',
            '58878',
            '2742',
            '92283',
            '1268',
            '28656',
            '13821',
            '4788',
            '121642',
            '177494',
            '8073',
            '27554',
            '335',
            '533444',
            '511322',
            '501170',
            '501170',
            '537328',
            '549053',
            '537791',
            '501170',
            '554241',
            '74714',
            '458737',
            '419704',
            '169800',
            '174865',
            '40805',
            '644492',
            '537791',
            '510',
            '496243',
            '657559',
            '102780',
            '42580',
            '7451',
            '46724',
            '111066',
            '399035',
            '279859',
            '40577',
            '504562',
            '2038',
            '17487',
            '38615',
            '504562',
            '506528',
            '506528',
            '9360',
            '506528',
            '182502',
            '10513',
            '67307',
            '10363',
            '13989',
            '27022',
            '42871',
            '30014',
            '30014',
            '504562',
            '851',
            '51618',
            '858',
            '182117',
            '43376',
            '38575',
            '98372',
            '345916',
            '345887',
            '156022',
            '665035',
            '71395',
            '8961',
            '290859',
            '70160',
            '174675',
            '174675',
            '174865',
            '290859',
            '47758',
            '9972',
            '290859',
            '36495',
            '290859',
            '331450',
            '38082',
            '420809',
            '25518',
            '10149',
            '194880',
            '1416',
            '112200',
            '627',
            '99377',
            '27118',
            '673',
            '767',
            '675',
            '674',
            '22733',
            '672',
            '567629',
            '300792',
            '318781',
            '227159',
            '227159',
            '105670',
            '39043',
            '260513',
            '32077',
            '290859',
            '59434',
            '43691',
            '30637',
            '35',
            '48197',
            '4351',
            '254904',
            '395925',
            '15',
            '336050',
            '484133',
            '21147',
            '20639',
            '290859',
            '4348',
            '1640',
            '2667',
            '27595',
            '181330',
            '583718',
            '16061',
            '570701',
            '42200',
            '22293',
            '290859',
            '15919',
            '30998',
            '11967',
            '469722',
            '569486',
            '20526',
            '619263',
            '13019',
            '332675',
            '335797',
            '672',
            '489064',
            '254320',
            '1620',
            '39434',
            '373571',
            '12445',
            '398181',
            '569486',
            '664474',
            '671',
            '70868',
            '76489',
            '76489',
            '487848',
            '152792',
            '152792',
            '15357',
            '12444',
            '70364',
            '10543',
            '19184',
            '85414',
            '85414',
            '39578',
            '27437',
            '27449',
            '40478',
            '506528',
            '99377',
            '28572',
            '17481',
            '47370',
            '9772',
            '275631',
            '599975',
            '587638',
            '28656',
            '290859',
            '504562',
            '517814',
            '914',
            '487291',
            '40350',
            '20943',
            '20943',
            '9325',
            '13752',
            '476070',
            '11824',
            '509874',
            '40350',
            '550134',
            '631143',
            '39881',
            '191104',
            '503752',
            '278927',
            '290859',
            '32067',
            '588199',
            '48130',
            '15592',
            '64278',
            '140071',
            '599975',
            '43492',
            '302401',
            '505',
            '599975',
            '240628',
            '278927',
            '338967',
            '519010',
            '22561',
            '59414',
            '962',
            '258410',
            '339403',
            '611914',
            '11045',
            '504562',
            '290859',
            '843',
            '109466',
            '14782',
            '87487',
            '31067',
            '12646',
            '37833',
            '14903',
            '28218',
            '15895',
            '42894',
            '9806',
            '660858',
            '109445',
            '617773',
            '599975',
            '38643',
            '79817',
            '34038',
            '475888',
            '475888',
            '56078',
            '22257',
            '81223',
            '37317',
            '42894',
            '611914',
            '79817',
            '64115',
            '660521',
            '324857',
            '8953',
            '19124',
            '492059',
            '544003',
            '5951',
            '611914',
            '52920',
            '2148',
            '79645',
            '41280',
            '578189',
            '110369',
            '68812',
        ];
    }
}
