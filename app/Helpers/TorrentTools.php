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

class TorrentTools
{
    /**
     * Name of the file to be saved.
     */
    public static string $fileName = '';

    /**
     * Representative table of the decoded torrent.
     */
    public static array $decodedTorrent = [];

    /**
     * Moves and decodes the torrent.
     */
    public static function normalizeTorrent($torrentFile)
    {
        $result = Bencode::bdecode_file($torrentFile);

        // Whitelisted keys
        $result = \array_intersect_key($result, [
            'announce'   => '',
            'comment'    => '',
            'created by' => '',
            'encoding'   => '',
            'info'       => '',

        ]);
        $result['info'] = \array_intersect_key($result['info'], [
            'files'        => '',
            'length'       => '',
            'name'         => '',
            'piece length' => '',
            'pieces'       => '',
            'private'      => '',
            'source'       => '',
        ]);

        // The PID will be set if an user downloads the torrent, but for
        // security purposes it's better to overwrite the user-provided
        // announce URL.
        $result['announce'] = \config('app.url').'/announce/PID';
        $result['info']['source'] = \config('torrent.source');
        $result['info']['private'] = 1;

        if (\config('torrent.created_by_append') && \array_key_exists('created by', $result)) {
            $result['created by'] = \trim($result['created by'], '. ').'. '.\config('torrent.created_by', '');
        } else {
            $result['created by'] = \config('torrent.created_by', '');
        }

        $comment = \config('torrent.comment', null);
        if ($comment !== null) {
            $result['comment'] = $comment;
        }

        return $result;
    }

    /**
     * Calculate the number of files in the torrent.
     */
    public static function getFileCount($decodedTorrent): int
    {
        // Multiple file torrent ?
        if (\array_key_exists('files', $decodedTorrent['info']) && (\is_countable($decodedTorrent['info']['files']) ? \count($decodedTorrent['info']['files']) : 0)) {
            return \is_countable($decodedTorrent['info']['files']) ? \count($decodedTorrent['info']['files']) : 0;
        }

        return 1;
    }

    /**
     * Returns the size of the torrent files.
     */
    public static function getTorrentSize($decodedTorrent): mixed
    {
        $size = 0;
        if (\array_key_exists('files', $decodedTorrent['info']) && (\is_countable($decodedTorrent['info']['files']) ? \count($decodedTorrent['info']['files']) : 0)) {
            foreach ($decodedTorrent['info']['files'] as $k => $file) {
                $dir = '';
                $size += $file['length'];
                $count = \is_countable($file['path']) ? \count($file['path']) : 0;
            }
        } else {
            $size = $decodedTorrent['info']['length'];
            //$files[0] = $decodedTorrent['info']['name.utf-8'];
        }

        return $size;
    }

    /**
     * Returns the torrent file list.
     */
    public static function getTorrentFiles($decodedTorrent): array
    {
        $files = [];
        if (\array_key_exists('files', $decodedTorrent['info']) && (\is_countable($decodedTorrent['info']['files']) ? \count($decodedTorrent['info']['files']) : 0)) {
            foreach ($decodedTorrent['info']['files'] as $k => $file) {
                $dir = '';
                $count = \is_countable($file['path']) ? \count($file['path']) : 0;
                for ($i = 0; $i < $count; $i++) {
                    if ($i + 1 === $count) {
                        $fname = $dir.$file['path'][$i];
                        $files[$k]['name'] = $fname;
                    } else {
                        $dir .= $file['path'][$i].'/';
                        $files[$k]['name'] = $dir;
                    }

                    $files[$k]['size'] = $file['length'];
                }
            }
        } else {
            $files[0]['name'] = $decodedTorrent['info']['name'];
            $files[0]['size'] = $decodedTorrent['info']['length'];
        }

        return $files;
    }

    /**
     * Returns file and folder names from the torrent.
     */
    public static function getFilenameArray($decodedTorrent): array
    {
        $filenames = [];

        if (\array_key_exists('files', $decodedTorrent['info']) && (\is_countable($decodedTorrent['info']['files']) ? \count($decodedTorrent['info']['files']) : 0)) {
            foreach ($decodedTorrent['info']['files'] as $k => $file) {
                $count = \is_countable($file['path']) ? \count($file['path']) : 0;
                for ($i = 0; $i < $count; $i++) {
                    if (! \in_array($file['path'][$i], $filenames)) {
                        $filenames[] = $file['path'][$i];
                    }
                }
            }
        } else {
            $filenames[] = $decodedTorrent['info']['name'];
        }

        return $filenames;
    }

    /**
     * Returns the sha1 (hash) of the torrent.
     */
    public static function getTorrentHash($decodedTorrent): string
    {
        return \sha1(Bencode::bencode($decodedTorrent['info']));
    }

    /**
     * Returns the number of the torrent file.
     */
    public static function getTorrentFileCount($decodedTorrent): int
    {
        if (\array_key_exists('files', $decodedTorrent['info'])) {
            return \is_countable($decodedTorrent['info']['files']) ? \count($decodedTorrent['info']['files']) : 0;
        }

        return 1;
    }

    /**
     * Returns the NFO.
     */
    public static function getNfo($inputFile): bool|string|null
    {
        $fileName = \uniqid('', true).'.nfo';
        $inputFile->move(\getcwd().'/files/tmp/', $fileName);
        if (\file_exists(\getcwd().'/files/tmp/'.$fileName)) {
            $fileContent = \file_get_contents(\getcwd().'/files/tmp/'.$fileName);
            \unlink(\getcwd().'/files/tmp/'.$fileName);
        } else {
            $fileContent = null;
        }

        return $fileContent;
    }

    /**
     * Check if the filename is valid or not.
     */
    public static function isValidFilename($filename): bool
    {
        $result = true;
        if (\strlen((string) $filename) > 255 ||
            \preg_match('#[/?<>\\:*|"\x00-\x1f]#', (string) $filename) ||
            \preg_match('#(^\.+|[\. ]+)$#', (string) $filename) ||
            \preg_match('#^(con|prn|aux|nul|com\d|lpt\d)(\..*)?$#i', (string) $filename)) {
            $result = false;
        }

        return $result;
    }

    /**
     * Anonymize A Torrent Media Info.
     */
    public static function anonymizeMediainfo($mediainfo): array|string|null
    {
        if ($mediainfo === null) {
            return null;
        }

        $completeNameI = \strpos($mediainfo, 'Complete name');
        if ($completeNameI !== false) {
            $pathI = \strpos($mediainfo, ': ', $completeNameI);
            if ($pathI !== false) {
                $pathI += 2;
                $endI = \strpos($mediainfo, "\n", $pathI);
                $path = \substr($mediainfo, $pathI, $endI - $pathI);
                $newPath = MediaInfo::stripPath($path);

                return \substr_replace($mediainfo, $newPath, $pathI, \strlen($path));
            }
        }

        return $mediainfo;
    }

    /**
     * Parse Torrent Keywords.
     */
    public static function parseKeywords($text): array
    {
        return \array_filter(\array_unique(\array_map('trim', explode(',', $text))));
    }
}
