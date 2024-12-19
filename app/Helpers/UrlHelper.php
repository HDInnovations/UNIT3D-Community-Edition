<?php

namespace App\Helpers;

class UrlHelper
{
    /**
     * Array of trusted URLs or domains.
     *
     * @var array
     */
    private static $trustedUrls = [
        'imdb.com', 'imdb.org', 'imdb.co', 'imdb.idv', 'imdbshow.com', 'media-imdb.com',
        'archive.org',
        'coverartarchive.org',
        'dvdempire.com', 'adultdvdempire.com', 'adultempire.com',
        'cduniverse.com',

    ];

    /**
     * Array of trusted IP ranges.
     *
     * @var array
     */
    private static $trustedIpRanges = [
        '192.168.1.0/24',
        '10.0.0.0/8',
        '52.94.224.0/20', // imdb (AWS host most of the public web, not adding all possible ranges)
        '207.241.224.0/20','207.241.224.0/24','207.241.237.0/24','208.70.24.0/21', // archive.org
        '142.132.128.0/17', // coverartarchive
        '199.182.184.0/22', '204.14.177.0/24', // dvdempire
    ];

    /**
     * Check if a URL is trusted for external host
     *
     * @param string $url The URL to check
     * @return bool
     */
    public static function isTrustedExternalHost($url)
    {
        $host = parse_url($url, PHP_URL_HOST);

        // Check if the host matches any safe URL or domain
        if (in_array($host, self::$trustedUrls)) {
            return true;
        }

        // Convert the host to IP if possible, then check IP ranges
        if (filter_var($host, FILTER_VALIDATE_IP)) {
            $ip = $host;
        } else {
            $ip = gethostbyname($host);
        }

        if ($ip !== $host) { // if DNS lookup was successful
            foreach (self::$trustedIpRanges as $range) {
                if (self::ipInRange($ip, $range)) {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * Check if an IP is within a given range.
     *
     * @param string $ip IP address to check
     * @param string $range IP range in CIDR format
     * @return bool
     */
    private static function ipInRange($ip, $range)
    {
        [$subnet, $bits] = explode('/', $range);
        $ip = ip2long($ip);
        $subnet = ip2long($subnet);
        $mask = -1 << (32 - $bits);
        $subnet &= $mask; # nb: in case the supplied subnet wasn't correctly aligned
        return ($ip & $mask) == $subnet;
    }
}
