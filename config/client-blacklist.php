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

return [

    /*
    |--------------------------------------------------------------------------
    | Enabled
    |--------------------------------------------------------------------------
    |
    | Blacklist On/Off
    |
    */

    'enabled' => true,

    /*
    |--------------------------------------------------------------------------
    | Blacklist Clients
    |--------------------------------------------------------------------------
    | An array of clients to be blacklisted which will reject them from announcing
    | to the sites tracker.
    |
    | clients -> Please use Regex. this is to blacklist multiple clients in a fast way.
	| For example, to blacklist only the client "bittorrent" add this following: '/^bittorrent$/'
	| this means, exactly match bittorrent first of all, / at the beginning and /[modifyer, normaly i for case unsensitive]
	| then after this, in that example here, the ^ means that this is the first position of the string, so nothing before
	| the letter "b...". the $ at the end of the string means, that nothing shoud follow in this string. so that the string must end
	| on "...t". for example, we want to blacklist all instances of "uglyclient/123.413.221" we must add
	| we can do this by adding following rule: "/^(uglyclient)\/[0-9\.]+$/i"
	| the "\" before the "/" and "." is needed for escaping. so, that means "uglyclient/6564.342.2346.2234.222.52" will also be blocked.
	| Best way to learn regex is, to go on https://regex101.com/
	|
    */
	// Regex: "/^(µ?u?x?(web)?Torrent)(Mac)?( ?\/? ?[0-9a-zA-Z\(\) \/\:\.]+)?/i"
    'clients' => [
        "/^(u?x?(web)?Torrent)(Mac)?( ?\/? ?[0-9a-zA-Z\(\) \/\:\.]+)?/i",
    ],

    /*
    |--------------------------------------------------------------------------
    | Blacklist Clients
    |--------------------------------------------------------------------------
	|
	| Don't forget to add the readable on this array for display on the page.
	|
	*/
	'clientsreadable' => [
		'uTorrent* (Also Mac)',
		'webTorrent*'
	]
];
