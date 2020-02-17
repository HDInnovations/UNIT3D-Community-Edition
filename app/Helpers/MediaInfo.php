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

namespace App\Helpers;

class MediaInfo
{
    private $regex_section = "/^(?:(?:general|video|audio|text|menu)(?:\s\#\d+?)*)$/i";

    public function parse($string)
    {
        $string = trim($string);
        $lines = preg_split("/\r\n|\n|\r/", $string);

        $output = [];
        foreach ($lines as $line) {
            $line = trim($line); // removed strtolower, unnecessary with the i-switch in the regexp (caseless) and adds problems with values; added it in the required places instead.
            if (preg_match($this->regex_section, $line)) {
                $section = $line;
                $output[$section] = [];
            }
            if (isset($section)) {
                $output[$section][] = $line;
            }
        }

        if (count($output)) {
            $output = $this->parseSections($output);
        }

        return $this->formatOutput($output);
    }

    private function parseSections(array $sections)
    {
        $output = [];
        foreach ($sections as $key => $section) {
            $key_section = strtolower(explode(' ', $key)[0]);
            if (!empty($section)) {
                if ($key_section === 'general') {
                    $output[$key_section] = $this->parseProperty($section, $key_section);
                } else {
                    $output[$key_section][] = $this->parseProperty($section, $key_section);
                }
            }
        }

        return $output;
    }

    private function parseProperty($sections, $section)
    {
        $output = [];
        foreach ($sections as $info) {
            $property = null;
            $value = null;
            $info = explode(':', $info, 2);
            if (count($info) >= 2) {
                $property = trim(strtolower($info[0]));
                $value = trim($info[1]);
            }
            if ($property && $value) {
                switch (strtolower($section)) {
                    case 'general':
                        switch ($property) {
                            case 'complete name':
                            case 'completename':
                                $output['file_name'] = self::stripPath($value);

                                break;
                            case 'format':
                                $output['format'] = $value;

                                break;
                            case 'duration':
                                $output['duration'] = $value;

                                break;
                            case 'file size':
                            case 'filesize':
                                $output['file_size'] = $this->parseFileSize($value);

                                break;
                            case 'overall bit rate':
                            case 'overallbitrate':
                                $output['bit_rate'] = $this->parseBitRate($value);

                                break;
                        }

                        break;

                    case 'video':
                        switch ($property) {
                            case 'format':
                                $output['format'] = $value;

                                break;
                            case 'format version':
                            case 'format_version':
                                $output['format_version'] = $value;

                                break;
                            case 'codec id':
                            case 'codecid':
                                $output['codec'] = $value;

                                break;
                            case 'width':
                                $output['width'] = $this->parseWidthHeight($value);

                                break;
                            case 'height':
                                $output['height'] = $this->parseWidthHeight($value);

                                break;
                            case 'stream size':
                            case 'stream_size':
                                $output['stream_size'] = $this->parseFileSize($value);

                                break;
                            case 'writing library':
                            case 'encoded_library':
                                $output['writing_library'] = $value;

                                break;
                            case 'frame rate mode':
                            case 'framerate_mode':
                                $output['framerate_mode'] = $value;

                                break;
                            case 'frame rate':
                            case 'framerate':
                                // if variable this becomes Original frame rate
                                $output['frame_rate'] = $value;

                                break;
                            case 'display aspect ratio':
                            case 'displayaspectratio':
                                $output['aspect_ratio'] = str_replace('/', ':', $value); // mediainfo sometimes uses / instead of :
                                break;
                            case 'bit rate':
                            case 'bitrate':
                                $output['bit_rate'] = $this->parseBitRate($value);

                                break;
                            case 'bit rate mode':
                            case 'bitrate_mode':
                                $output['bit_rate_mode'] = $value;

                                break;
                            case 'nominal bit rate':
                            case 'bitrate_nominal':
                                $output['bit_rate_nominal'] = $this->parseBitRate($value);

                                break;
                            case 'bits/(pixel*frame)':
                            case 'bits-(pixel*frame)':
                                $output['bit_pixel_frame'] = $value;

                                break;
                            case 'bit depth':
                            case 'bitdepth':
                                $output['bit_depth'] = $value;

                                break;
                            case 'encoding settings':
                                $output['encoding_settings'] = $value;

                                break;
                            case 'language':
                                $output['language'] = $value;

                                break;
                            case 'format profile':
                                $output['format_profile'] = $value;

                                break;
                            case 'title':
                                $output['title'] = $value;

                                break;
                            case 'color primaries':
                                $output['title'] = $value;

                                break;
                            case 'scan type':
                                $output['scan_type'] = $value;

                                break;
                        }

                        break;

                    case 'audio':
                        switch ($property) {
                            case 'codec id':
                            case 'codecid':
                                $output['codec'] = $value;

                                break;
                            case 'format':
                                $output['format'] = $value;

                                break;
                            case 'bit rate':
                            case 'bitrate':
                                $output['bit_rate'] = $this->parseBitRate($value);

                                break;
                            case 'channel(s)':
                                $output['channels'] = $this->parseAudioChannels($value);

                                break;
                            case 'title':
                                $output['title'] = $value;

                                break;
                            case 'language':
                                $output['language'] = $value;

                                break;
                            case 'format profile':
                            case 'format_profile':
                                $output['format_profile'] = $value;

                                break;
                            case 'stream size':
                            case 'stream_size':
                                $output['stream_size'] = $this->parseFileSize($value);

                                break;
                        }

                        break;

                    case 'text':
                        switch ($property) {
                            case 'codec id':
                            case 'codecid':
                                $output['codec'] = $value;

                                break;
                            case 'format':
                                $output['format'] = $value;

                                break;
                            case 'title':
                                $output['title'] = $value;

                                break;
                            case 'language':
                                $output['language'] = $value;

                                break;
                            case 'default':
                                $output['default'] = $value;

                                break;
                            case 'forced':
                                $output['forced'] = $value;

                                break;
                        }

                        break;
                }
            }
        }

        return $output;
    }

    public static function stripPath($string)
    {
        $string = str_replace('\\', '/', $string);
        $path_parts = pathinfo($string);

        return $path_parts['basename'];
    }

    private function parseFileSize($string)
    {
        $number = (float) $string;
        preg_match('/[KMGTPEZ]/i', $string, $size);
        if (!empty($size[0])) {
            $number = $this->computerSize($number, $size[0].'b');
        }

        return $number;
    }

    private function parseBitRate($string)
    {
        $string = str_replace(' ', '', strtolower($string));

        return str_replace('kbps', ' kbps', $string);
    }

    private function parseWidthHeight($string)
    {
        return str_replace(['pixels', ' '], null, strtolower($string));
    }

    private function parseAudioChannels($string)
    {
        $replace = [
            ' '        => '',
            'channels' => 'ch',
            'channel'  => 'ch',
            '1ch'      => '1.0ch',
            '7ch'      => '6.1ch',
            '6ch'      => '5.1ch',
            '2ch'      => '2.0ch',
        ];

        return str_ireplace(array_keys($replace), $replace, $string);
    }

    private function formatOutput($data)
    {
        $output = [];
        $output['general'] = !empty($data['general']) ? $data['general'] : null;
        $output['video'] = !empty($data['video']) ? $data['video'] : null;
        $output['audio'] = !empty($data['audio']) ? $data['audio'] : null;
        $output['text'] = !empty($data['text']) ? $data['text'] : null;

        return $output;
    }

    public function prepareViewCrumbs($data)
    {
        $output = ['general'=>[], 'video'=>[], 'audio'=>[]];

        $general_crumbs = ['format'=>'ucfirst', 'duration'=>null];

        if ($data['general'] === null) {
            $output['general'] = null;
        } else {
            if (isset($data['general']['format'])) {
                $output['general'][] = ucfirst($data['general']['format']);
            }
            if (isset($data['general']['duration'])) {
                $output['general'][] = $data['general']['duration'];
            }
        }

        if ($data['video'] === null) {
            $output['video'] = null;
        } else {
            $temp_output = [];
            foreach ($data['video'] as $video_element) {
                $temp_video_output = [];
                if (isset($video_element['format'])) {
                    $temp_video_output[] = strtoupper($video_element['format']);
                }
                if (isset($video_element['width']) && isset($video_element['height'])) {
                    $temp_video_output[] = $video_element['width'].' x '.$video_element['height'];
                }
                foreach (['aspect_ratio', 'frame_rate', 'bit_depth', 'bit_rate', 'format_profile', 'scan_type', 'title', 'color primaries'] as $property) {
                    if (isset($video_element[$property])) {
                        $temp_video_output[] = $video_element[$property];
                    }
                }

                if (!empty($temp_video_output)) {
                    $temp_output[] = $temp_video_output;
                }
            }

            $output['video'] = !empty($temp_output) ? $temp_output : null;
        }

        if ($data['audio'] === null) {
            $output['audio'] = null;
        } else {
            $temp_output = [];
            foreach ($data['audio'] as $audio_element) {
                $temp_audio_output = [];
                foreach (['language', 'format', 'channels', 'bit_rate', 'title'] as $property) {
                    if (isset($audio_element[$property])) {
                        $temp_audio_output[] = $audio_element[$property];
                    }
                }

                if (!empty($temp_audio_output)) {
                    $temp_output[] = $temp_audio_output;
                }
            }

            $output['audio'] = !empty($temp_output) ? $temp_output : null;
        }

        if ($data['text'] === null) {
            $output['text'] = null;
        } else {
            $temp_output = [];
            foreach ($data['text'] as $text_element) {
                $temp_text_output = [];
                foreach (['language', 'format', 'title'] as $property) {
                    if (isset($text_element[$property])) {
                        $temp_text_output[] = $text_element[$property];
                    }
                }
                if (isset($text_element['forced']) && strtolower($text_element['forced']) === 'yes') {
                    $temp_text_output[] = 'Forced';
                }

                if (!empty($temp_text_output)) {
                    $temp_output[] = $temp_text_output;
                }
            }

            $output['text'] = !empty($temp_output) ? $temp_output : null;
        }

        return $output;
    }

    private function parseAudioFormat($string)
    {
    }

    private function computerSize($number, $size)
    {
        $bytes = (float) $number;
        $size = strtolower($size);

        $factors = ['b' => 0, 'kb' => 1, 'mb' => 2, 'gb' => 3, 'tb' => 4, 'pb' => 5, 'eb' => 6, 'zb' => 7, 'yb' => 8];

        if (isset($factors[$size])) {
            return (float) number_format($bytes * pow(1024, $factors[$size]), 2, '.', '');
        }

        return $bytes;
    }
}
