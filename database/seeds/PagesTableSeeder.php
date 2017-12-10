<?php
/**
 * NOTICE OF LICENSE
 *
 * UNIT3D is open-sourced software licensed under the GNU General Public License v3.0
 * The details is bundled with this project in the file LICENSE.txt.
 *
 * @project    UNIT3D
 * @license    https://choosealicense.com/licenses/gpl-3.0/  GNU General Public License v3.0
 * @author     BluCrew
 */
 
use Illuminate\Database\Seeder;

class PagesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {


        \DB::table('pages')->delete();

        \DB::table('pages')->insert(array (
            0 =>
            array (
                'id' => 1,
                'name' => 'Rules',
                'slug' => 'rules',
                'content' => '[code]Last Updated December 1, 2017 -Amended Rules to allow Opus.
[/code]

[b][u][color=#9900ff][size=20]General Rules[/size][/color][/u][/b][list=1][*]If you are found to have more than one account, you could be banned.[/*][*]Disruptive behavior anywhere on the site will result in a warning and, in serious cases, an IMMEDIATE BAN[/*][*]No cheating! Cheaters will be banned without notice.[/*][*]Staff (moderators/administrators) are the final arbiters of these rules. Staff are there to use their best judgment to ensure the smooth functioning of the site. Cooperate with them and you\'ll have a pleasant time here.[list][*]Do NOT defy the moderators\' or administrators\' direct instructions. Doing so may result in a warning and, in serious cases, a ban from the site.[/*][*]Staff members decisions are final and not subject to appeal.[/*][/list][/*][*]Site Bashing, insulting or attacking staff will result in a warning being issued or an outright ban from the site.[/*][/list][b][u][color=#9900ff][size=20]Uploading [url=https://blutopia.xyz/p/upload-guide.5](GUIDE HERE)[/url][/size][/color][/u][/b][list=1][*]Do not upload torrents if you are not going to seed them![/*][*]No SD content allowed unless its part of a pack. [i]For example a BD Boxset that contains DVD\'s with BONUS content not found on BD\'s![/i][/*][*]Untouched Blurays/Custom Blurays and BD25 ReEncodes can be upped in BDMV Folder Format and/or an ISO Image.[/*][*]There must always be an English audio track present. In the event that you are uploading a foreign film that has no English audio available, English subtitles must be included.[/*][*]Do not use the NFO artwork in your description. Limit those artistic expressions to the NFO only.[/*][*][b][i]All uploads require screenshots unless it is an Untouched DVD, Bluray or Remux! PNG or TIFF only! Thumbnails are a must, make sure they are linked to the full resolution images. Don\'t post huge images in description! (MIN 3 SCREENS REQUIRED)[/i][/b][/*][*]Do not upload more than you or your internet connection can handle and seed at a decent rate! Do not upload new material if you have not finished any previous uploads.[/*][*]Make sure your uploads are well-seeded for at least 7 days +.[/*][*]When uploading a torrent you must be connectable. Make sure your ports are correctly forwarded.[/*][*]The only Allowed content to be uploaded is Movie and TV Shows. Everything else like for example software is not allowed![/*][*]Content containing adult content like nudity sex, extreme violence are allowed when the torrent is flagged as adult content.[/*][*]Porn is not allowed![/*][*]Files with Child Exploitation (AKA Child Pornography in any form) are absolutely forbidden! Anyone doing so will be banned.[/*][*]Archived files (.zip, .rar, ect) are not allowed![/*][*]Staff reserves the right to remove any file(s) they deem inappropriate and they have final say and is NOT up for discussion or complaint.[/*][*]Torrent title naming convention: 
- Do not alter any file names. You may only change the torrent title! If you change the original file names your torrent may be deleted. 
- Movie title/year must be as on [url=https://www.imdb.com/]https://www.imdb.com[/url]. TV/mini-series names must be as on [url=https://www.thetvdb.com/]https://www.thetvdb.com[/url]. 
Make sure to remove all unnecessary dots, if present. (except if the dots are part of the name of the movie i.e. S.W.A.T. or they are used for separation in the audio channel information, i.e. DD 5.1) Only the Latin alphabet should be used for the title. The characters" / : < > |" must not be used in the file/folder names and the title.[/*][*]For Blurays please use a proper BDInfo scan (Quick Summery Preferred For Less Clutter): [url=https://www.videohelp.com/software/BDInfo]https://www.videohelp.com/software/BDInfo[/url]﻿[/*][*]For everything else (Remux, Encodes, Web-DL, Ect.) Please use a proper MediaInfo Scan: [url=https://mediaarea.net/en/MediaInfo]https://mediaarea.net/en/MediaInfo[/url]﻿[/*][*]TRY NOT to dump everything that\'s in the MediaInfo output - be selective and stick with the most important bits. The following information must be present as a minimum (must be in English). For x264 and x265 based encodes, encoding settings are mandatory and need to be included in the description. [/*][*]Do not put links to other trackers in description![/*][/list][b]Description Template:
[/b][spoiler]GENERAL INFO
Name : 
Format : 
File size : 
Duration : 
Overall bit rate : (might be optional)
Chapters : Yes/No

VIDEO
Format : 
Format profile : 
Video bitrate : 
Width : 
Height : (or resolution in one row)
Display aspect ratio : 
Frame rate : 
Title : (optional - would help if original remuxer includes BDInfo info)

ENCODE SETTINGS: mandatory for all x264 and x265 based encodes! (code tags are advised for formatting).

AUDIO
Format : Lossless / Core
Bit rate : Lossless/ Lossy
Channels : 
Language : 
Title : (optional - would help if original remuxer includes BDInfo info)

SUBTITLES
Format : 
Language : 
[/spoiler]
[b]Allowed Content[/b][b]
[/b][list=1][*]Movie Content[/*][*]TV Content[/*][/list][list][*]Untouched Blurays[/*][*]Custom Blurays[/*][*]BD25 ReEncodes[/*][*]Remuxes[/*][*]1080p/720p Encodes[/*][*]1080p/720p WEB-DL’s[/*][*]FanRes Projects[/*][/list] Video[list][*]Resolutions: HD and UHD only
[/*][*]Codecs: H.264, H.265, AVC, MVC, VC-1, Apple ProRes, GoPro Cineform, MPEG-2, JPEG 2000[/*][*]Containers: MKV, MK3D, M2TS, TS, MP4, M4V, MOV
[/*][/list] Audio[list][*]Codecs: Dolby ATMOS, Dolby TrueHD, DTS:X, DTS Headphone X, DTS-HD MA, LPCM, PCM, DTS-HD HR, Dolby Digital Plus, DTS, AC-3, AAC, FLAC, ALAC, OPUS[/*][/list]
[b]SD Content[/b]
SAID TORRENT UPLOADED IN SD [u][b]MUST NOT[/b][/u] BE AVAILABLE IN HD!!!
[list]Formats allowed[*]Full DVD 5/9 Disc[/*][*]DVD Remux[/*][*]Other SD Formats are non-negotiable, Don\'t ask.[/*][/list]ANYONE constantly found uploading SD Content that doesn\'t fit these rules will have their Uploading rights revoked.

[b] Prohibited Content[/b][list][*]Any upscaled or upconverted content, e.g. DVD-to-HD, 2D-to-3D.[/*][*]Any re-encodes of other encodes a.k.a. BRRips; WEB-DL re-encodes.[/*][*]Any freely available web content.[/*][*]Any watermarked encode.[/*][*]Any cams.[/*][*]So called R5 Blu-rays (Digital TeleCine recordings) and any encodes thereof.[/*][*]Any encodes where the size and/or the bitrate imply a very bad quality of the encode. You can use the following rule of thumb for encodes: for 1080p the video bitrate must be at least 4.500kbps and for 720p at least 2.000kbps. In other words, no micro encodes such as YIFY.[/*][/list][quote][i]ABSOLUTELY NO MUSIC, APPS, EBOOKS , SPORTS, COMICS, GAMES, PORN!
[/i][/quote][list][/list][b][u][color=#9900ff][size=20]Downloading[/size][/color][/u][/b][list=1][*]This is a sharing community. As you download, you are required to share back your part.[/*][*]Never share the .torrent file you download from this site, it is a personal .torrent file![/*][*]You must maintain a general overall ratio of 0.4 or better to download.[/*][*]Check for other seeders before stopping to seed. Running from low-seeded torrents will not be treated lightly.[/*][/list]

[b][u][color=#9900ff][size=20]Hit and Run[/size]
[/color][/u][color=#ff0000][i]A FREELEECH TORRENT DOESNT MEAN ITS HIT AND RUN IMMUNE!!!!!!!!!!!!![/i][/color][/b]
[list][*]All torrents are required 7 days seedtime. ( Seedtime doesn\'t start until you are a seeder and have 100% of said torrent.)[b][i](Partial Download Are NOT Recommended For This Reason)[/i][/b][/*][*]There is a 3% buffer. This means that the said torrent will not trigger the Hit and Run system until you have downloaded at least 3% of the torrent\'s filesize in question. Once you have downloaded past the 3% mark then the Hit and Run system will be triggered and you will be required to have 7 days of seedtime for that torrent.
[/*][*]There is a grace period of 3 sequential days to be disconnected. Once that
grace period has ended, and if the torrent has not yet been seeded for 7 complete days or 168 hours in total,
it will result in 1 active warning for the user. (if the user is aware ahead of time that they will be unable to fix any VALID issue within the 3 day "grace period", they can contact a staff member which will be dealt with at Staff\'s discretion on a case by case basis.)[/*][*]Once a user has gotten an active warning, it will remain active for a period of 14 days before it goes away, regardless if you decide to suddenly reseed it or not.[/*][*]Once a user has 2 active warnings, their download privileges, and request privileges will be revoked.
[/*][*]Once a user has 3 active warnings, his or her account will be permanently banned, no questions asked.
[/*][*]You can only get one hit and run warning per torrent as per system rules.[/*][*]Once you start a download and the download amount is over 3% of the total filesize of that torrent it is then considered a download and is viable to hit and run rules.[/*][*]If your download and request privileges have been revoked due to having too many Hit and Runs, they can only be restored once your active warning count falls down below 2.[/*][*]Any other help that you need regarding active Hit and Runs is at the Staff\'s discretion.[/*][/list]
[b][u][color=#9900ff][size=20]Torrent Tags / Discounts[/size][/color][/u][/b][list][*]Global FreeLeech (Site is in Global Freeleech, all torrents are 100% free in where download stats are not counted on your account)[/*][*]Double Upload (Site is in Global Double Upload, all torrents you are actively uploading data on your upload is multiplied by two!)[/*][*]100% Free (Specified torrent has been granted 100% FreeLeech)[/*][*]Hot! (Specified torrent has 5 or more leechers)[/*][*]High Speeds! (Specified is being seeded from a registered high speed connection. Info here: [url=https://blutopia.xyz/articles/are-you-a-highspeed-seeder.7]https://blutopia.xyz/articles/are-you-a-highspeed-seeder.7[/url][/*][*]New (Specified torrent has been uploaded after your last login timestamp)[/*][*]Stream Optimized (Specified torrent is direct stream ready.) Info here: [url=https://blutopia.xyz/community/topic/1080p-stream-optimized-encoding.203]https://blutopia.xyz/community/topic/1080p-stream-optimized-encoding.203[/url][/*][*]Sticky! (Specified torrent has been pinned to top of torrent list by staff)[/*][*]Personal FL! (You have purchased 24Hr Personal FL from BON Store)[/*][*]Special FL! (Your group has special fl perk)[/*][/list][b][u][color=#9900ff][size=20]User Ranks[/size][/color][/u][/b]
[color=#ff0000]Banned:[/color] Account that has been banned/disabled.
[color=#666666]Validating:[/color] Newly registered account that has not yet been validated.
[color=#000000]Member:[/color] Newly registered users start out with 50GB Upload Credit. Have full rights of membership (upload/download) and full access to forums.
[color=#6aa84f]Uploader:[/color] Members that bring alot of content to the site. (rank is given at Staff\'s discretion)
[color=#9900ff]Trustee:[/color] Members that support the site\'s codebase developers. Staff members from other respected trackers. (rank is given at Staff\'s discretion)
[color=#93c47d]Moderator:[/color] Moderates the site.
[color=#ff00ff]Admin:[/color] Administers the site.
[color=#6d9eeb]Coder:[/color] Coder of the platform.
[color=#a61c00]Leech:[/color] Ratio Dropped Below Sites Minimum Requirements. Download Rights Disabled, Invite Rights Disabled and Request Rights Disabled!
[color=#9fc5e8]Member:[/color] Upload \'>=\' 0 but \'<\' 1TB and ratio above sites minimum
[color=#3c78d8]BluMember:[/color] Upload \'>=\' 1TB but \'<\' 5TB and account 1 month old
[color=#0000ff]BluMaster:[/color] Upload \'>=\' 5TB but \'<\' 20TB and account 1 month old
[color=#0B5394]BluExtremist:[/color] Upload \'>=\' 20TB but \'<\' 50TB and account 3 month old * Current Perks Included - exempt from the Mod queue when uploading.
[color=#0B5394]BluLegend:[/color] Upload \'>=\' 50TB but \'<\' 100TB and account 6 month old * Current Perks Included - exempt from the Mod queue when uploading
[color=#0B5394]Blutopian:[/color] Upload \'>=\' 100TB and account 1 year old﻿  * Current Perks Included - Immunity From H&R\'s & Special Freeleech, exempt from the Mod queue when uploading
[color=#0B5394]BluSeeder:[/color] Seeding Count \'>=\' 150  and account 1 month old and seedtime average 30 days or better * Current Perks Included - Immunity From H&R\'s, exempt from the Mod queue when uploading
[color=#0B5394]BluArchivist:[/color] Seeding Count \'>=\' 300 and account 3 month old and seedtime average 60 days or better *Current Perks Included - Immunity From H&R\'s, Special Freeleech and exempt from the Mod queue when uploading

[b][u][color=#9900ff][size=20]Chat[/size][/color][/u][/b][list=1][*]No foul language please![/*][*]Do not beg for requests in chat![/*][*]Do not beg for invites in chat![/*][*]Do not ask for invites to other trackers in chat![/*][*]Do not direct link other trackers in chat![/*][*]Be respectful, breaking these rules can get you banned![/*][/list][b][u][color=#9900ff][size=20]Requests[/size][/color][/u][/b][list=1][*]One title per request![/*][*]One season per request![/*][*]BON transactions are final![/*][*]If your request is filled then you must approve or deny it! Otherwise your request ability will be revoked![/*][*]Dont abuse the system![/*][*]Do not hotlink to other sites. [/*][*]Do not request someone to get a specific release from another tracker.[/*][/list][b][u][color=#9900ff][size=20]Comments[/size][/color][/u][/b][list=1][*]Courtesy to the uploader is always appreciated. Leave a comment, or a simple quick thanks will do![/*][*]Please limit your torrent comments to the specific upload in question. [/*][*]No asking/begging for bonus points or invites.[/*][*]Do not abuse your ability to place torrent comments.[/*][*]Flaming, arguments & comments like "this is garbage" will not be tolerated. Any critique shall be done tactfully and tastefully. If you have no intentions of downloading or don\'t have anything nice to say, don\'t comment! Remember, what could be one person\'s garbage is another person\'s treasure.[/*][*]If the torrent is not to your taste, stay away from it!! Comments that express bias (e.g., racism, sexism, homophobia, etc.) will not be tolerated.[/*][*]No language other than English is to be posted in torrent comments (unless English translation also provided).[/*][*]Do not post our torrent files and/or links to other torrent sites.[/*][*]Do not post links to materials not on the site. This includes links to subtitles, Megaupload/Rapidshare links, etc. Links within torrent descriptions to reference materials are allowed.[/*][*]No spamming or posting lame referral schemes/ads/links/pages anywhere on site. Offenders will be BANNED.[/*][*]Do not leave any personal information in the comments sections such as email addresses, PID, etc. This is for your own safety.[/*][*]No referral links allowed. Any link in attempt to obtain funds from user are strictly prohibited. i.e paypal. moneybooker, alertpay or any other related sites.[/*][/list][color=#9900ff][size=20][b][u]Respect[/u][/b][/size][/color][list=1][*]We expect you to honor the wishes of our uploaders if they put "do not re-upload" or "do not upload to other sites before x days" requests in their torrents. (Any other types of constraints put on re-upload of our materials elsewhere will be removed by the moderators as unreasonable.)[/*][*]Likewise, when uploading materials here that you have obtained from another site, we expect you to honor your member agreement with THAT site in respect to embargoes or bans on uploading that material elsewhere. If we learn that you have violated your agreement with the site from where you obtained the materials you reuploaded here, your upload will be deleted and you will be disciplined by the moderators here for having damaged our reputation. 
(Any punishment from the other site whose agreement you violated will remain up to them.)[/*][/list][b][u][color=#9900ff][size=20]Bug Reports[/size][/color][/u][/b][list=1][*]Use the proper tools to report bugs! Bugs Section in forums or Bug Report in side nav.[/*][*]Do not report bugs in chatbox. They will be missed, fogotten about or ignored![/*][*]When reporting a bug be descriptive![/*][/list][quote] [i]The site reserves the right to change and amend rules as it sees fit and at any time.[/i] [/quote]',
                'created_at' => '2016-12-20 22:09:45',
                'updated_at' => '2017-12-01 13:39:43',
            ),
            1 =>
            array (
                'id' => 3,
                'name' => 'FAQ',
                'slug' => 'faq',
                'content' => 'Due to unforeseen circumstances, this page is currently being redone. 
Thank you for your patience.',
                'created_at' => '2017-10-28 21:26:15',
                'updated_at' => '2017-10-28 21:26:15',
            ),
            2 =>
            array (
                'id' => 4,
                'name' => 'Suggested Clients',
                'slug' => 'suggested-clients',
                'content' => 'We suggest the following BitTorrent clients.  

[b][color=#ff0000]Windows[/color][/b][list][*]BitTornado - [url=https://anon.to/?http%3A%2F%2Fwww.bittornado.com%2F]http://www.bittornado.com[/url][/*][*]BitTorrent - [url=https://anon.to/?http%3A%2F%2Fwww.bittorrent.com%2F]http://www.bittorrent.com[/url][/*][*]Deluge - [url=https://anon.to/?http%3A%2F%2Fdeluge-torrent.org%2F]http://deluge-torrent.org[/url][/*][*]qBittorrent - [url=https://anon.to/?http%3A%2F%2Fwww.qbittorrent.org%2F]http://www.qbittorrent.org[/url][/*][*]Tixati - [url=https://anon.to/?https%3A%2F%2Fwww.tixati.com%2F]https://www.tixati.com[/url][/*][*]uTorrent - [url=https://anon.to/?http%3A%2F%2Fwww.utorrent.com%2F]http://www.utorrent.com[/url][/*][*]Vuze - [url=https://anon.to/?http%3A%2F%2Fwww.vuze.com%2F]http://www.vuze.com[/url][/*][/list][b][color=#9900ff]  MacOs[/color][/b][list][*]BitTorrent - [url=https://anon.to/?http%3A%2F%2Fwww.bittorrent.com%2F]http://www.bittorrent.com[/url][/*][*]Deluge - [url=https://anon.to/?http%3A%2F%2Fdeluge-torrent.org%2F]http://deluge-torrent.org[/url][/*][*]qBittorrent - [url=https://anon.to/?http%3A%2F%2Fwww.qbittorrent.org%2F]http://www.qbittorrent.org[/url][/*][*]Transmission - [url=https://anon.to/?https%3A%2F%2Fwww.transmissionbt.com%2F]https://www.transmissionbt.com[/url][/*][*]uTorrent - [url=https://anon.to/?http%3A%2F%2Fwww.utorrent.com%2F]http://www.utorrent.com[/url][/*][/list][b][color=#1e84cc]  Linux/Seedbox[/color][/b][list][*]Deluge - [url=https://anon.to/?http%3A%2F%2Fdeluge-torrent.org%2F]http://deluge-torrent.org[/url] [gui][/*][*]qBittorrent - [url=https://anon.to/?http%3A%2F%2Fwww.qbittorrent.org%2F]http://www.qbittorrent.org[/url] [gui][/*][*]rTorrent - [url=https://anon.to/?https%3A%2F%2Frakshasa.github.io%2Frtorrent%2F]https://rakshasa.github.io/rtorrent[/url] [server][/*][*]libtorrent based torrent clients - [url=https://anon.to/?http%3A%2F%2Fwww.libtorrent.org%2F]http://www.libtorrent.org[/url][/*][*]KTorrent - [url=https://anon.to/?https%3A%2F%2Fwww.kde.org%2Fapplications%2Finternet%2Fktorrent%2F]https://www.kde.org/applications/internet/ktorrent/[/url][/*][/list][code]Clients with Anonymous Mode, Stealth Mode or Invisible Mode which hides the Agent Type are allowed![/code]',
                'created_at' => '2017-01-27 15:52:01',
                'updated_at' => '2017-08-07 17:30:56',
            ),
            3 =>
            array (
                'id' => 5,
                'name' => 'Upload Guide',
                'slug' => 'upload-guide',
                'content' => '[i][u][color=#000000]﻿Contents:[/color][/u][/i][list][*]Read the uploading rules
[/*][*]Check for duplicates
[/*][*]Create the torrent file
[/*][*]Upload the torrent
[/*][*]Writing the torrent description
[/*][*]Load the torrent in your torrent client
[/*][/list][b][u][color=#9900ff]1. Read the uploading rules[/color]
[/u][/b]If you didn\'t read the uploading rules﻿ yet read them before continuing to the next step.
[b][u]
[color=#9900ff]2. Check for duplicates[/color]
[/u][/b]First you should check if the content you want to upload isn\'t already on the site.
Use the search function on the Torrents﻿ page or navbar.
If it is not on the site already you can continue to the next step.
[b][u]
[color=#9900ff]3. Create the torrent file (uTorrent)[/color]
[/u][/b]1. In uTorrent select File->Create new torrent
﻿2. Select the files; If it\'s a single file select Add File otherwise select Add Directory
3. Enter the announce URL in the Trackers field, make sure it is the only tracker URL. You can find your announce url at top of upload page. Will look like this.[i][u]﻿https://blutopia.xyz/announce/YOURPID
[/u][/i]4. Set Piece Size to (auto detect)
5. Check Private Torrent
6. Press Create And Save As, uTorrent will start hashing the files, once it\'s finished save the .torrent somewhere.
[b][u]
[color=#9900ff]4. Upload the torrent[/color]
[/u][/b]1. Goto the Upload﻿ page.
2. Upload the torrent file you made in the previus step.
3. Upload your NFO file. (OPTIONAL)
4. Enter the tilte of your upload. Make it clean and proper please
.5. If uploading a movie or tv you must enter the IMDB # (NUMBERS ONLY!)
6. If uploading tv you must enter the TVDB # (NUMBERS ONLY!)
7. Select the proper category for your upload.
8. Select the proper type for your upload.
9. Fill in the description of your upload. MediaInfo/BDInfo , Screenshots, ect. 
10. Anonymous Upload? Tick YES or NOAfter filling out all the fields upload the torrent.
[b][u]
[color=#9900ff]5. Load the torrent in your torrent client[/color]
[/u][/b]
* If your upload is stopped for Moderation because you are "Member" rank you can simply so one of two things.If you created the new torrent yourself and set the announce like above using https://blutopia.xyz/announce/YOURPID then you can simply load that file you created into your client and start to seed since its already linked to our announce with your PID. It may show in red  until your upload is approved so please be patient.If you used a premade torrent file from another site then you must download the new torrent file from our site so you have the proper file with our announce and your PID linked to it. If your upload is already approve you can download the new file from the torrent list page or torrent details page. If your torrent is still pending moderation then you can simply goto your profile and look under "Uploaded Torrents". You will see all your uploads there and the ones that are "Approved", "Denied" or "Pending" under the Moderation colum. Simply click the blue downlaod button for your pending uploads and add it to your client.I know this seem like a pain but this is our way of providing qaulity control to the site.  ',
                'created_at' => '2017-04-24 14:45:03',
                'updated_at' => '2017-08-07 17:34:42',
            ),
            4 =>
            array (
                'id' => 6,
                'name' => 'Tracker Codes',
                'slug' => 'tracker-codes',
                'content' => '[b][u][color=#9900ff][size="25"]Our Tracker Codes/Responses 

[/size][/color][/u][/b][font=Roboto, Helvetica Neue, Helvetica, Arial, sans-serif][color=#cccccc][b]
[/b][/color][/font][list][*][b][color=#ff0000]"﻿Please Call Passkey"[/color]  ---->  [i]No passkey provided in announce URL[/i][/b][/*][*][b][color=#ff0000]"﻿Passkey Is Invalid"[/color]  ---->  [i]No user found with supplied  passkey﻿[/i][/b][/*][*][b][color=#ff0000]"﻿I Think Your No Longer Welcome Here"[/color]  ---->  [i]You have been banned[/i][/b][/*][*][b][color=#ff0000]"﻿Bad Data From Client"[/color]  ---->  [i]Client Has Sent Bad Data To Tracker﻿[/i][/b][/*][*][b][color=#ff0000]"﻿Data From Client Is Negative"[/color]  ---->  [i]The up/down data sent to tracker is a negative value[/i][/b][/*][*][b][color=#ff0000]"﻿﻿Your Client Doesn\'t Support Compact"[/color]  ---->  [i]Update your client![/i][i][/i][/b][/*][*][b][color=#ff0000]"﻿﻿Torrent Not Found" [/color] ---->  [i]No torrent found on site with hash sent by client[/i][/b][/*][*][b][color=#ff0000]"﻿﻿﻿You Have Reached The Rate Limit"[/color]  ---->  [i]﻿[/i][i]You cannot download or seed the same torrents from more than 3 locations[/i][/b][/*][*][b][color=#ff0000]"﻿Torrent Is Complete But No Record Found"[/color]  ---->  [i]Force recheck said torrent in your client[/i][/b][/*][/list]',
                'created_at' => '2017-09-08 14:23:16',
                'updated_at' => '2017-09-08 14:27:12',
            ),
            5 =>
            array (
                'id' => 7,
                'name' => 'Terms Of Use',
                'slug' => 'terms-of-use',
            'content' => '*All references to "we", "us" or "our" refer to the site owner(s).

Welcome to our website located at https://blutopia.xyz (this "Site")! This Site allows you to:
(a) participate in interactive features that we may make available from time to time through the Site; or
(b) simply view this Site (collectively, the "Services"). We prepared this Terms of Use Agreement (this "Agreement") to help explain the terms that apply to your use of this Site and Services. Provisions in these terms that apply to the Site shall also apply to the Services, and provisions in these terms that apply to the Services shall also apply to this Site.In order to use the interactive features on this Site, you must first register with us through our on-line registration process on this Site. Regardless of how you decide to use this Site, your conduct on this Site and use of the Services is governed by this Agreement.YOU ACCEPT THIS AGREEMENT BY USING THIS SITE AND/OR THE SERVICES IN ANY MANNER. IF YOU DO NOT AGREE TO ALL THESE TERMS THEN DO NOT USE THIS WEBSITE.
[b]
1. Membership
[/b]When you use this Site, you represent that:
(a) the information you submit is truthful and accurate;
(b) you will update your contact information if it changes so that we can contact you;
(c) your use of this Site and your use of services available on this Site do not violate any applicable law or regulation;
(d) you are 13 years of age or older; and
(e) you will comply with the rules for on-line conduct and making Contributions (as defined in Section 2 below) to this Site, as discussed in Section 2 below. You further represent and warrant that you will comply with all local rules regarding on-line conduct and acceptable Contributions

[b]2. User conduct
[/b]This Site may provide fora and other features for communication. Please read our Privacy Policy, available at https://blutopia.xyz/p/privacy.8 to understand your privacy protections. You are entirely responsible for the content of, and any harm resulting from, any of your postings or submissions to this Site (collectively, "Contributions"). You understand that we may also make the Contributions you submit available to other websites and businesses (such other websites and businesses, the “Network”) where they may be used. Any licenses or other rights grants, and promises, representations and warranties you make about the Contributions with respect to this Site or the Services, you are also hereby making with respect to the use of such Contributions through and by the Network (i.e., wherever you are granting a license or other rights grant, or making a promise, representation or warranty, with respect to this Site or the Services, that grant, promise, representation or warranty shall be deemed and construed to also apply to the Network). When you create or make available a Contribution, you represent and warrant that you:own or have sufficient rights to post your Contributions on or through this Site;will not post Contributions that violate our or any other person’s privacy rights, publicity rights, intellectual property rights (including without limitation copyrights), confidentiality or contract rights;have fully complied with any third-party licenses relating to Contributions, agree to pay all royalties, fees and any other monies owning any person by reason of Contributions that you posted to or through this Site;will not post or submit Contributions that:
(i) are defamatory, damaging, disruptive, unlawful, inappropriate, offensive, inaccurate, pornographic, vulgar, indecent, profane, hateful, racially or ethnically offensive, obscene, lewd, lascivious, filthy, threatening, excessively violent, harassing, or otherwise objectionable;
(ii) incite, encourage or threaten immediate physical harm against another, including but not limited to, Contributions that promote racism, bigotry, sexism, religious intolerance or harm against any group or individual; or
(iii) contain material that solicits personal information from anyone under 13 or exploits anyone in a sexual or violent manner;will not post or submit Contributions that contain advertisements or solicit any person to buy or sell products or services (other than our products and services);will not use this Site for any unauthorized purpose including collecting usernames and/or email addresses of other users by electronic or other means for the purpose of sending unsolicited email or other electronic communications, or engaging in unauthorized framing of, or linking to, this Site without our express written consent;will not post or submit Contributions that constitute, contain, install or attempt to install or promote spyware, malware or other computer code, whether on our or others’ computers or equipment, designated to enable you or others to gather information about or monitor the on-line or other activities of another party;will not transmit chain letters, bulk or junk email or interfere with, disrupt, or create an undue burden on this Site or the networks or services connected to this Site, including without limitation, hacking into this Site, or using the system to send unsolicited or commercial emails, bulletins, comments or other communications; orwill not impersonate any other person or entity, sell or let others use your profile or password, provide false or misleading identification or address information, or invade the privacy, or violate the personal or proprietary right, of any person or entity.
[b]
3. Grant of License to Us for Contributions
[/b]We do not claim any ownership right in the Contributions that you post on or through this Site. After posting your Contributions on this Site, you continue to retain any rights you may have in your Contributions, including any intellectual property rights or other proprietary rights associated with your Contributions, subject to the license you grant to us below.By making a Contribution to this Site, you grant us a perpetual, non-exclusive (meaning you are free to license your Contribution to anyone else in addition to us), fully-paid, royalty-free (meaning that neither we nor anyone who directly or indirectly receives the Contribution from us are required to pay you to use your Contribution), sublicensable (so that we can distribute the Contributions to third parties, regardless of whether through this Site, through our other products, or through other sites or products offered by our affiliates)) and worldwide (because the Internet and this Site are global in reach) license to use, modify, create derivative works of, publicly perform, publicly display, reproduce and distribute the Contribution in connection with this Site and other websites and businesses, or the promotion thereof in any media formats and through any media channels now known or hereafter devised.If you provides us with any feedback (e.g. suggested improvements, corrections etc.) about the Site or Services (“Feedback”), you assign all right, title and interest in and to such Feedback to us and acknowledge that we will be entitled to use, including without limitation, implement and exploit, any such Feedback in any manner without any restriction or obligation. You further acknowledge and agree that we are not obligated to act on such Feedback.

[b]4. Grant of License to You to use Contributions for Personal, Non-Commercial Purposes

[/b][b]4.1 License.[/b]We grant you a non-exclusive license to use and copy other users’ Contributions solely for personal, non-commercial purposes subject to the restrictions set forth herein.

[b]4.2 License Restrictions.[/b]   
4.2.1 Retention of IP Notices. If you download, copy or print a copy of the Materials (as defined in Section 6 below) for your own personal use, you must retain all trademark, copyright and other proprietary notices contained in and on the materials.   
4.2.2 No Circumvention of IP Protection Mechanisms. You shall not either directly or through the use of any device, software, internet site, web-based service or other means remove, alter, bypass, avoid, interfere with, or circumvent any copyright, trademark, or other proprietary notices marked on Contributions or any digital rights management mechanism, device, or other content protection or access control measure associated with Contributions or the Site.   
4.2.3 No Unauthorized Copying, Broadcasting or Screen Scraping. You shall not either directly or through the use of any device, software, internet site, web-based service or other means copy, download, reproduce, duplicate, archive, distribute, upload, publish, modify, translate, broadcast, perform, display, sell, transfer, rent, sub-license, transmit or retransmit Contributions except as permitted in Section 4.1.   
4.2.4 No Indexing. Furthermore, you may not create, recreate, distribute or advertise an index of any Contributions unless authorized by us in writing.   
4.2.5 No Commercial Use of Contributions. You may not build a business utilizing the Contributions, whether or not for profit. Contributions covered by these restrictions include without limitation any text, graphics, layout, interface, logos, photographs, audio and video materials, and stills.   
4.2.6 No Derivative Works. In addition, you are strictly prohibited from creating derivative works or materials that otherwise are derived from or based on Contributions in any way, including montages, wallpaper, desktop themes, greeting cards, and merchandise, unless it is expressly permitted by us in writing. This prohibition applies even if you intend to give away the derivative materials free of charge.
[b]
5. Use and Protection of Account Number and Password
[/b]We may ask you to create a username and password. You are responsible for maintaining the confidentiality of your account number and password, if applicable. You are responsible for all uses of your account, whether or not actually or expressly authorized by you. When you use the Site or Services we ask you to use the Site and/or Services in a reasonable way that does not negatively affect other users ability to use the Site or Services.

[b]6. Our Intellectual Property Rights[/b]Content on this Site ("Materials"), the trademarks, service marks, and logos contained on this Site ("Marks"), is owned by or licensed to us and is subject to copyright and other intellectual property rights under United States and foreign laws and international conventions. We reserve all rights not expressly granted in and to this Site and the Materials. You agree that you will not circumvent, disable or otherwise interfere with security related features of this Site or features that:
(a) prevent or restrict use or copying of any Materials or
(b) enforce limitations on use of this Site or the Materials on this Site. You further agree not to access this Site by any means other than through the interface that we provide, unless otherwise specifically authorized by us in a separate written agreement.
[b]
7. Our Management of this Site/User Misconduct[/b]   
7.1 Our Site Management. We may, but are not required to:
(a) monitor or review this Site for violations of this Agreement and for compliance with our policies;
(b) report to law enforcement authorities and/or take legal action against anyone who violates this Agreement;
(c) refuse, restrict access to or the availability of, or remove or disable any Contribution or any portion thereof without prior notice to you; and/or
(d) manage this Site in a manner designed to protect our and third parties’ rights and property or to facilitate the proper functioning of this Site.   
7.2 Our Right to Terminate Users. Without limiting any other provision of this Agreement, we reserve the right to, in our sole discretion and without notice or liability deny access to and use of this Site to any person for any reason or for no reason at all, including without limitation for breach of any representation, warranty or covenant contained in this Agreement, or of any applicable law or regulation.   
7.3 Risk of Harm. Please note that there are risks, including but not limited to the risk of physical harm, of dealing with strangers, including persons who may be acting under false pretenses. Please choose carefully the information you post on this Site and that you give to other Site users. You are discouraged from publicly posting your full name, telephone numbers, street addresses or other information that identifies you or allows strangers to find you or steal your identity. Despite this prohibition, other people’s information may be offensive, harmful or inaccurate, and in some cases will be mislabeled or deceptively labeled. You assume all risks associated with dealing with other users with whom you come in contact through this Site. We expect that you will use caution and common sense when using this Site.
[b]
8. Copyright Policy
[/b]You are solely responsible for the content, including but not limited to photos, profiles information, messages, search results edits, and other content that you upload, publish or display (hereinafter, "submit") on or through the Service, or transmit to or share with other users. You may not submit content to the Service that you did not create or that you not have permission to submit. For submissions to search results pages, you may not submit content that is not compatible with the license used by the particular project of the Service. You understand and agree that the others may, but are not obligated to, edit, delete or remove (without notice) any content from the Service, for any reason or no reason. You are solely responsible at your sole cost and expense for creating backup copies and replacing any content you post or store on the Service or provide to the Company.
[b]Claims of Copyright Infringement[/b]
[i] For claims of copyright infringement, please contact us at blutopia@stealth.tg This site is in compliance with DCMA takedown notices.[/i]
[b]
9. Modifications
[/b]The Internet and technology are rapidly changing. Accordingly, we may modify this Agreement from time to time without notice and it is your responsibility to read it carefully and review any changes that may have been made. Since changes will be posted on this page, we encourage you to check this page regularly. Your continued use of this Site or the Services constitutes your agreement with such modifications.
[b]
10. Non-commercial Use by Users
[/b]The Site is made available to you only for your personal use, and you may not use the Site or any Contributions or Materials in connection with any commercial endeavors except those that are specifically approved by us in writing.
[b]
11. Third Party Sites
[/b]This Site may contain links to other websites ("Third Party Sites"). We do not own or operate the Third Party Sites, and we have not reviewed, and cannot review, all of the material, including goods or services, made available through Third-Party Sites. The availability of these links on this Site does not represent, warrant or imply that we endorse any Third Party Sites or any materials, opinions, goods or services available on them. Third party materials accessed through or used by means of the Third Party Sites may also be protected by copyright and other intellectual property laws. THIS AGREEMENT DOES NOT APPLY TO THIRD PARTY SITES. BEFORE VISITING A THIRD PARTY SITE BY MEANS OF THIS SITE OR A LINK LOCATED ON THIS SITE, USERS SHOULD REVIEW THE THIRD PARTY SITE’S TERMS AND CONDITIONS, PRIVACY POLICY AND ALL OTHER SITE DOCUMENTS, AND INFORM THEMSELVES OF THE REGULATIONS, POLICIES AND PRACTICES OF THESE THIRD PARTY SITES.
[b]
12. Disputes Between Users
[/b]You are solely responsible for your conduct. You agree that we cannot be liable for any dispute that arises between you and any other user.We may run advertisements and promotions from third parties on the Site. Your correspondence or business dealings with, or participation in promotions of, advertisers other than us found on or through the Site, including payment and delivery of related goods or services, and any other terms, conditions, warranties or representations associated with such dealings, are solely between You and such advertiser. We are not responsible or liable for any loss or damage of any sort incurred as the result of any such dealings or as the result of the presence of such advertisers on the Site.
[b]
13. Disclaimers
[/b]ALL CONTRIBUTIONS OR ANY OTHER MATERIALS OR ITEMS PROVIDED THROUGH THIS SITE BY US ARE PROVIDED "AS IS" AND "AS AVAILABLE," WITHOUT WARRANTY OR CONDITIONS OF ANY KIND. By operating this Site, WE DO not represent or imply that WE ENDORSE any Contributions or any other Materials or items available on or linked to by this Site, including without limitation, content hosted on third party Sites, or that WE BELIEVE Contributions or any other Materials or items to be accurate, useful or non-harmful. WE cannot guarantee and do not promise any specific results from use of this Site. No advice or information, whether oral or written, obtained by you from US or this Site shall create any warranty not expressly stated In THIS AGREEMENT.

YOU AGREE THAT YOUR USE OF THIS SITE AND SERVICES WILL BE AT YOUR SOLE RISK. WE DO NOT WARRANT THAT THE SITE OR SERVICES WILL BE AVAILABLE FOR USE, AND WE DO NOT MAKE ANY WARRANTIES AS TO THE QUALITY OF THE SITE, SERVICES OR ITS CONTENT. TO THE FULLEST EXTENT PERMITTED BY LAW, WE AND EACH OF OUR ADVERTISERS, LICENSORS, SUPPLIERS, OFFICERS, DIRECTORS, INVESTORS, EMPLOYEES, AGENTS, SERVICE PROVIDERS AND OTHER CONTRACTORS DISCLAIM ALL WARRANTIES, EXPRESS OR IMPLIED IN CONNECTION WITH THIS SITE AND YOUR USE THEREOF.WE MAKE NO WARRANTIES OR REPRESENTATIONS ABOUT THE ACCURACY, RELIABILITY, TIMELINESS OR COMPLETENESS OF THIS SITE\'S CONTENT, THE CONTENT OF ANY SITE LINKED TO THIS SITE, CONTRIBUTIONS, INFORMATION OR ANY OTHER ITEMS OR MATERIALS ON THIS SITE OR LINKED TO BY THIS SITE. WE ASSUME NO LIABILITY OR RESPONSIBILITY FOR ANY:

(A) ERRORS, MISTAKES OR INACCURACIES OF CONTENT, CONTRIBUTIONS AND MATERIALS,
(B) PERSONAL INJURY OR PROPERTY DAMAGE, OF ANY NATURE WHATSOEVER, RESULTING FROM YOUR ACCESS TO AND USE OF OUR SITE OR SERVICES,
(C) ANY ILLEGAL OR UNAUTHORIZED ACCESS TO OR USE OF OUR SECURE SERVERS AND ALL PERSONAL INFORMATION STORED THEREIN,
(D) ANY INTERRUPTION OR CESSATION OF TRANSMISSION TO OR FROM THIS SITE,
(E) ANY BUGS, VIRUSES, TROJAN HORSES, OR THE LIKE, WHICH MAY BE TRANSMITTED TO OR THROUGH THIS SITE BY ANY THIRD PARTY, AND/OR
(F) ANY ERRORS OR OMISSIONS IN ANY CONTRIBUTIONS, CONTENT AND MATERIALS OR FOR ANY LOSS OR DAMAGE OF ANY KIND INCURRED AS A RESULT OF THE USE OF ANY CONTENT, CONTRIBUTIONS, OR MATERIALS POSTED, TRANSMITTED, OR OTHERWISE MADE AVAILABLE VIA THIS SITE.WE WILL NOT BE LIABLE TO YOU FOR ANY LOSS OF ANY DATA (INCLUDING CONTENT) OR FOR LOSS OF USE OF THIS SITE.SOME STATES OR JURISDICTIONS DO NOT ALLOW THE LIMITATION OR EXCLUSION OF CERTAIN WARRANTIES, OR THE EXCLUSION OR LIMITATION OF CERTAIN DAMAGES. IF YOU RESIDE IN ONE OF THESE STATES OR JURISDICTIONS, THE ABOVE LIMITATIONS OR EXCLUSIONS MAY NOT APPLY TO YOU.',
                'created_at' => '2017-10-03 14:50:15',
                'updated_at' => '2017-10-03 18:31:25',
            ),
        ));


    }
}
