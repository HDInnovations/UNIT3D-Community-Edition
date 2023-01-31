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

        \sleep(5);

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
                        $year = (int) \substr($movie['release_date'], 0, 4);
                    }

                    $freeleech = ['0', '25', '50', '75', '100'];
                    $selected = \random_int(0, \count($freeleech) - 1);

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
                        'mediainfo'      => '
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
                        'created_at' => \now(),
                        'bumped_at'  => \now(),
                        'updated_at' => \now(),
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
                        $year = (int) \substr($tv['first_air_date'], 0, 4);
                    }

                    $freeleech = ['0', '25', '50', '75', '100'];
                    $selected = \random_int(0, \count($freeleech) - 1);

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
                        'mediainfo'      => '
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
                        'created_at' => \now(),
                        'bumped_at'  => \now(),
                        'updated_at' => \now(),
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
        \sleep(2);
        $tmdbScraper = new TMDBScraper();
        $tmdbScraper->movie($id);

        return (new Movie($id))->getData();
    }

    private function fetchTv($id)
    {
        \sleep(2);
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
