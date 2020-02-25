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
        $this->alert('Demo Seeder v2.0 (Author: Poppabear)');
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
                        'featured'    => false,
                        'mediainfo'   => '
Complete name                            : Double.Impact.1991.1080p.BluRay.DD+5.1.x264-LoRD.mkv
Format                                   : Matroska
Format version                           : Version 4
File size                                : 14.1 GiB
Duration                                 : 1 h 49 min
Overall bit rate                         : 18.5 Mb/s
Encoded date                             : UTC 2020-02-24 10:07:15
Writing application                      : mkvmerge v43.0.0 (\'The Quartermaster\') 64-bit
Writing library                          : libebml v1.3.10 + libmatroska v1.5.2

Video
ID                                       : 1
Format                                   : AVC
Format/Info                              : Advanced Video Codec
Format profile                           : High@L4.1
Format settings                          : CABAC / 4 Ref Frames
Format settings, CABAC                   : Yes
Format settings, Reference frames        : 4 frames
Codec ID                                 : V_MPEG4/ISO/AVC
Duration                                 : 1 h 49 min
Bit rate                                 : 17.5 Mb/s
Width                                    : 1 920 pixels
Height                                   : 1 040 pixels
Display aspect ratio                     : 1.85:1
Frame rate mode                          : Constant
Frame rate                               : 23.976 (24000/1001) FPS
Color space                              : YUV
Chroma subsampling                       : 4:2:0
Bit depth                                : 8 bits
Scan type                                : Progressive
Bits/(Pixel*Frame)                       : 0.366
Stream size                              : 13.4 GiB (94%)
Title                                    : LoRD
Writing library                          : x264 core 159 r2991+49 ~ LoRD
Encoding settings                        : cabac=1 / ref=4 / deblock=1:-3:-3 / analyse=0x3:0x133 / me=umh / subme=10 / psy=1 / fade_compensate=0.00 / psy_rd=1.10:0.00 / mixed_ref=1 / me_range=32 / chroma_me=1 / trellis=2 / 8x8dct=1 / cqm=0 / deadzone=21,11 / fast_pskip=0 / chroma_qp_offset=-2 / threads=12 / lookahead_threads=2 / sliced_threads=0 / nr=0 / decimate=0 / interlaced=0 / bluray_compat=0 / constrained_intra=0 / fgo=0 / bframes=9 / b_pyramid=2 / b_adapt=2 / b_bias=0 / direct=3 / weightb=1 / open_gop=0 / weightp=2 / keyint=250 / keyint_min=23 / scenecut=40 / intra_refresh=0 / rc_lookahead=60 / rc=2pass / mbtree=0 / bitrate=17500 / ratetol=1.0 / qcomp=0.70 / qpmin=0:0:0 / qpmax=69:69:69 / qpstep=4 / cplxblur=20.0 / qblur=0.5 / vbv_maxrate=62500 / vbv_bufsize=78125 / nal_hrd=none / filler=0 / ip_ratio=1.25 / pb_ratio=1.20 / aq=3:0.55 / aq-sensitivity=10.00 / aq-factor=1.00:1.00:1.00 / aq2=0 / aq3=0
Language                                 : English
Default                                  : Yes
Forced                                   : No

Audio
ID                                       : 2
Format                                   : E-AC-3
Format/Info                              : Enhanced AC-3
Commercial name                          : Dolby Digital Plus
Codec ID                                 : A_EAC3
Duration                                 : 1 h 49 min
Bit rate mode                            : Constant
Bit rate                                 : 1 023 kb/s
Channel(s)                               : 6 channels
Channel layout                           : L R C LFE Ls Rs
Sampling rate                            : 48.0 kHz
Frame rate                               : 93.750 FPS (512 SPF)
Compression mode                         : Lossy
Stream size                              : 803 MiB (6%)
Title                                    : DD+5.1
Language                                 : English
Service kind                             : Complete Main
Default                                  : Yes
Forced                                   : No

Text #1
ID                                       : 3
Format                                   : UTF-8
Codec ID                                 : S_TEXT/UTF8
Codec ID/Info                            : UTF-8 Plain Text
Duration                                 : 1 h 41 min
Bit rate                                 : 26 b/s
Count of elements                        : 669
Stream size                              : 19.9 KiB (0%)
Language                                 : English
Default                                  : No
Forced                                   : No

Text #2
ID                                       : 4
Format                                   : UTF-8
Codec ID                                 : S_TEXT/UTF8
Codec ID/Info                            : UTF-8 Plain Text
Duration                                 : 1 h 48 min
Bit rate                                 : 30 b/s
Count of elements                        : 872
Stream size                              : 24.1 KiB (0%)
Title                                    : SDH
Language                                 : English
Default                                  : No
Forced                                   : No

Text #3
ID                                       : 5
Format                                   : UTF-8
Codec ID                                 : S_TEXT/UTF8
Codec ID/Info                            : UTF-8 Plain Text
Duration                                 : 1 h 41 min
Bit rate                                 : 26 b/s
Count of elements                        : 659
Stream size                              : 19.9 KiB (0%)
Language                                 : French
Default                                  : No
Forced                                   : No

Text #4
ID                                       : 6
Format                                   : UTF-8
Codec ID                                 : S_TEXT/UTF8
Codec ID/Info                            : UTF-8 Plain Text
Duration                                 : 1 h 41 min
Bit rate                                 : 24 b/s
Count of elements                        : 668
Stream size                              : 18.3 KiB (0%)
Title                                    : european
Language                                 : Portuguese
Default                                  : No
Forced                                   : No

Text #5
ID                                       : 7
Format                                   : UTF-8
Codec ID                                 : S_TEXT/UTF8
Codec ID/Info                            : UTF-8 Plain Text
Duration                                 : 1 h 42 min
Bit rate                                 : 26 b/s
Count of elements                        : 765
Stream size                              : 19.5 KiB (0%)
Title                                    : latin
Language                                 : Spanish
Default                                  : No
Forced                                   : No

Menu
00:00:00.000                             : en:Logo/Title/Intro
00:03:24.037                             : en:"My Babies!"
00:09:45.918                             : en:Life In L.A.
00:13:08.621                             : en:"He\'s Your Brother!"
00:21:01.510                             : en:Fencing Benzes
00:29:05.118                             : en:Mistaken Identity
00:37:34.627                             : en:Fishing Expedition
00:42:22.039                             : en:The Drop
00:50:05.711                             : en:Bad Cognac
00:58:56.032                             : en:On the Run
01:07:33.549                             : en:Sibling Rivalry
01:17:59.174                             : en:Hotel Invasion
01:23:51.443                             : en:A Captive Audience
01:28:49.532                             : en:One-On-One Combat
01:34:15.858                             : en:Who Gets Away?
01:44:15.249                             : en:End Credits
                        ',
                        'created_at' => now(),
                        'updated_at' => now(),
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
