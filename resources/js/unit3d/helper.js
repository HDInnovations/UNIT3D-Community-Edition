// NOTICE OF LICENSE
//
// UNIT3D is open-sourced software licensed under the GNU Affero General Public License v3.0
// The details is bundled with this project in the file LICENSE.txt.
//
// @project    UNIT3D
//
// @license    https://www.gnu.org/licenses/agpl-3.0.en.html/ GNU Affero General Public License v3.0
// @author     HDVinnie, singularity43, MiM
//
// File Contents:
//
// uploadExtensionBuilder - To parse torrent files titles / Used: Upload
// userFilterBuilder - To add filters for user search / Used: All User Histories
// forumTipBuilder - To add tip buttons for forum / Used: Topics
// userExtensionBuilder - To add toggle capabilities to BON / Used: BON
//
// After classes, event attachments then globals.
class uploadExtensionBuilder {
    removeDots(title) {
        // Remove extensions
        title = title.replace(/\.mkv$/i, '');
        title = title.replace(/\.mk3d$/i, '');
        title = title.replace(/\.mp4$/i, '');
        title = title.replace(/\.iso$/i, '');
        title = title.replace(/\.m2ts$/i, '');
        title = title.replace(/\.ts$/i, '');
        // Replace dots with spaces
        title = title.replaceAll('.', ' ');
        // Fix extra spaces, this will be done at the end again.
        title = title.replace(/ +/g, ' ');
        // Audio Codec channel fixes
        title = title.replace(/ E-?AC-?3 /i, ' DD+ ');
        title = title.replace(/ AC-?3 /i, ' DD ');
        title = title.replace(/ AC-? ?3-?EX /i, ' DD EX ');
        title = title.replace(/( DTS-?H?D? ?MA ?)(\d)( )(\d)/i, ' DTS-HD MA $2.$4');
        title = title.replace(/( DTS-?X ?)(\d)( )(\d)/i, ' DTS:X $2.$4');
        title = title.replace(/( DTS-?X)(-?[^ ]*)$/i, ' DTS:X 7.1$2');
        title = title.replace(/( DTS-?H?D? ?HRA? ?)(\d)( )(\d)/i, ' DTS-HD HRA $2.$4');
        title = title.replace(/( FLAC ?)(\d)( )(\d)/i, '$1 $2.$4');
        title = title.replace(/( L?PCM ?)(\d)( )(\d)/i, '$1 $2.$4');
        title = title.replace(/( DD[P+]? ?)(\d)( )(\d)/i, '$1 $2.$4');
        title = title.replace(/( DD-? ?EX ?)(\d)( )(\d)/i, ' DD EX $2.$4');
        title = title.replace(/( Opus ?)(\d)( )(\d)/i, ' Opus $2.$4');
        title = title.replace(/( AAC? ?L?C? ?S?B?R? ?)(\d)( )(\d)/i, ' AAC $2.$4');
        title = title.replace(/ DD-EX /i, ' DD EX ');
        title = title.replace(/ DDPA ?5.1 /i, ' DD+ 5.1 Atmos ');
        title = title.replace(/( \d\.\d)\+Atmos /i, '$1 Atmos ');
        title = title.replace(/ DDP /i, ' DD+ ');
        // Fix Atmos
        if (title.includes('Atmos') && !title.includes(' DD+ ')) {
            if (title.includes('TrueHD')) {
                // Case when Atmos has all codec and channel, but wrong order.
                title = title.replace(/TrueHD Atmos 7.1/i, 'TrueHD 7.1 Atmos');
            } else {
                if (title.includes(' 7.1 ')) {
                    // Case when Atmos has 7.1 after it
                    title = title.replace(/Atmos 7.1/i, 'TrueHD 7.1 Atmos');
                } else {
                    // Case when Atmos is alone
                    title = title.replace(/Atmos/i, 'TrueHD 7.1 Atmos');
                }
            }
        }
        title = title.replace(/ +DTSMA /i, ' DTS-HD MA ');
        // Fix for parenthesis around year.
        title = title.replace(/( \()(\d{4})(\) )/i, ' $2 ');
        // Fix 1080P and alike
        title = title.replace(/( )(\d{3,4})(I )/i, ' $2i ');
        title = title.replace(/( )(\d{3,4})(P )/i, ' $2p ');
        // Video codec fixes fix
        title = title.replace(/ +MPEG2 +/i, ' MPEG-2 ');
        title = title.replace(/ +VC1 +/i, ' VC-1 ');
        title = title.replace(/( +dxva)([ -])/i, ' $2');
        // Fixes for H.264/H.265
        title = title.replace(/( +H ?26)(\d)/i, ' H.26$2');
        title = title.replace(/( +X ?26)(\d)/i, ' x26$2');
        // Dolby Vision fix
        title = title.replace(/( +DoVi +)/i, ' DV ');
        title = title.replace(/ +(HDR10Plus|HDR10P|HDR\+|HDR10\+) +/i, ' HDR10+ ');
        title = title.replace(/( +HEVC +)(HDR|HDR10)( +)/i, ' HDR HEVC ');
        // WEB-DL fix
        title = title.replace(/ WEBDL /i, ' WEB-DL ');
        title = title.replace(/ WEB /i, ' WEB-DL ');
        // Fix two common services for WEB
        title = title.replace(/( +Netflix +WEB)/i, ' NF WEB');
        title = title.replace(/( +Amazon +WEB)/i, ' AMZN WEB');
        if (title.includes('WEB-DL')) {
            title = title.replace(/( +X26)(\d)/i, ' H.26$2');
            title = title.replace(/ +AVC/i, ' H.264');
            title = title.replace(/ +HEVC/i, ' H.265');
        }
        // Fix for ##x## instead of S##E##
        title = title.replace(/( +)(\d{2})(x)(\d{2} )/i, ' S$2E$4');
        // Remove episode (non S00/E00)
        if (!title.includes('E00') && !title.includes('S00')) {
            title = title.replace(/(S\d{2}E\d{2}[^ ]* )(.* )(\d{3,4}[ip])/i, '$1 $3');
        }
        //Blu-ray fixes
        title = title.replace(/ +Bluray +/i, ' BluRay ');
        title = title.replace(/ +BDRip +/i, ' BluRay ');
        title = title.replace(/ +Blu-Ray +/i, ' Blu-ray ');
        // Remux fix
        title = title.replace(/( +remux)( *)/i, ' REMUX$2');
        if (title.includes(' REMUX')) {
            title = title.replace(/ Blu-ray /i, ' BluRay ');
            lowerTitle = title.toLowerCase();
            if (
                !title.includes('BluRay') &&
                !(lowerTitle.includes(' dvd ') || lowerTitle.includes(' ntsc ') || lowerTitle.includes(' pal '))
            ) {
                title = title.replace(/ REMUX/i, ' ');
                title = title.replace(/( \d{3,4}[ip] )/i, ' $1 BluRay REMUX ');
            }
        }
        // A fix for Blu-ray Dolby Vision.  All DV on Blu-rays has HDR10 base layer
        if (
            (title.includes('Blu-ray') || title.includes('BluRay')) &&
            title.includes(' DV') &&
            !title.includes('HDR')
        ) {
            title = title.replace(/( DV )(HEVC|[hx]\.?265)/i, ' DV HDR $2 ');
        }
        // 3D location fix, only when it is after the year/season/ep, not if it is before.
        title = title.replace(
            /(S\d{2}E\d{2}|S\d{2}|\d{4} .*)( 3D )(.* \d{3,4}[ip] .*)( Blu-ray|BluRay)/i,
            '$1 $3 3D $4'
        );
        title = title.replace(/(S\d{2}E\d{2}|S\d{2}|\d{4}.*)( 3D)( \d{3,4}[ip] .*)( Blu-ray|BluRay)/i, '$1$3 3D $4');
        // Fix for date formats
        title = title.replace(/( \d{4})( +)(\d{2})( +)(\d{2} )/, '$1-$3-$5');
        title = title.replace(/( \d{4})( +)(\d{2} )/, '$1-$3');
        title = title.replace(/(UHD)?( BluRay)(.*)( \d{3,4}[ip] )/i, '$4 $1 $2 $3 ');
        // Fix some remux ordering
        title = title.replace(
            /( +\d{3,4}[ip] +)(.*)(UHD)?( +BluRay)(.*)(AVC|HEVC|MPEG-2|VC-1)(.*)(Hybrid)?( +REMUX)/i,
            '$8$1$2$3$4 REMUX $5$6$7'
        );
        title = title.replace(/(.*)(NTSC|PAL)?( +DVD)(.*)(Hybrid)?( +REMUX)/i, '$1$2 $5 $3 REMUX $4 ');
        // Move the video codec to correct location in a remux.
        if (title.includes('REMUX')) {
            // Move video codec where it should be
            title = title.replace(/(BluRay|DVD)( +REMUX +)(.*)(AVC|HEVC|MPEG-2|VC-1)/i, '$1$2 $4 $3');
        }
        // Fixing HDR ordering
        if (title.includes('HEVC') || title.includes('H.265') || title.includes('x265')) {
            if (title.includes(' DV ') && title.includes(' HDR10+ ')) {
                // Remove the two HDR formats and place in correct order.
                // Replace with space to not ruin spacing.
                title = title.replace(' DV ', ' ');
                title = title.replace(' HDR10+ ', ' ');
                // Replace them where HEVC/H.265/x265 is.
                title = title.replace(/( )(HEVC|H\.265|x265)/i, ' DV HDR10+ $2');
            } else if (title.includes(' DV ') && title.includes(' HDR ')) {
                // Remove the two HDR formats and place in correct order.
                // Replace with space to not ruin spacing.
                title = title.replace(' DV ', ' ');
                title = title.replace(' HDR ', ' ');
                // Replace them where HEVC/H.265/x265 is.
                title = title.replace(/( )(HEVC|H\.265|x265)/i, ' DV HDR $2');
            } else if (title.includes(' DV ')) {
                // Remove the DV and place in correct order.
                // Replace with space to not ruin spacing.
                title = title.replace(' DV ', ' ');
                // Replace them where HEVC/H.265/x265 is.
                title = title.replace(/( )(HEVC|H\.265|x265)/, ' DV $2');
            } else if (title.includes(' HDR10+ ')) {
                // Remove the HDR10+ place in correct order.
                // Replace with space to not ruin spacing.
                title = title.replace(' HDR10+ ', ' ');
                // Replace them where HEVC/H.265/x265 is.
                title = title.replace(/( )(HEVC|H\.265|x265)/i, ' HDR10+ $2');
            } else if (title.includes(' HDR ')) {
                // Remove the HDR and place in correct order.
                // Replace with space to not ruin spacing.
                title = title.replace(' HDR ', ' ');
                // Replace them where HEVC is.
                title = title.replace(/( )(HEVC|H\.265|x265)/i, ' HDR $2');
            }
        }
        // Fixing UHD being missing in 2160p Blu-rays
        if (!title.includes('UHD')) {
            title = title.replace(/( +2160p)(.*)( Blu-?ray )/i, ' 2160p $2 UHD$3');
        }
        // Fixing hybrid casing/location
        if (title.toLowerCase().includes('hybrid')) {
            title = title.replace(/(.*)( \d{3,4}[ip] )(.*)(Hybrid)(.+)/i, '$1 Hybrid$2$3 $5');
            title = title.replace(
                /(.*)(S\d{2}E\d{2}|S\d{2}|\d{4})( .*)(Hybrid)(.*)( \d{3,4}[ip] )/i,
                '$1$2$3 $5 Hybrid $6'
            );
        }
        // Fix extra spaces
        title = title.replace(/ +/g, ' ');
        // Fix space before group
        title = title.replace(/( ?- ?)([^ ]*)$/i, '-$2');
        return title.trim();
    }

    hook() {
        let name = document.querySelector('#title');
        let tmdb = document.querySelector('#autotmdb');
        let imdb = document.querySelector('#autoimdb');

        if (!name.value.trim() && !tmdb.value.trim()) {
            let torrent = document.querySelector('#torrent');
            let release;
            if (!name.value) {
                const fileEndings = ['.mkv.torrent', '.mp4.torrent', '.torrent'];
                let newValue = torrent.value;
                // strip path
                newValue = newValue.split('\\').pop().split('/').pop();
                // remove file endings
                fileEndings.forEach(function (e) {
                    newValue = newValue.replace(e, '');
                });
                // replace dots with spaces
                name.value = this.removeDots(newValue);
            }

            /* PARSING */
            release = title_parser.parse(name.value, {
                strict: true, // if no main tags found, will throw an exception
                flagged: true, // add flags to generated relese name (like STV, REMASTERED, READNFO)
                erase: [], // add expressions to erase before parsing
                defaults: {
                    language: 'ENGLISH',
                }, // defaults values for : language, resolution and year
            });

            let matcher = name.value.toLowerCase();

            // Torrent Category
            if (release.type === 'Movie') {
                $('#autocat').val(1);
            } else if (release.type === 'TV Show') {
                $('#autocat').val(2);
            }

            // Torrent Type
            if (
                matcher.indexOf('bd50') > 0 ||
                matcher.indexOf('bd25') > 0 ||
                matcher.indexOf('untouched') > 0 ||
                matcher.indexOf('dvd5') > 0 ||
                matcher.indexOf('dvd9') > 0 ||
                matcher.indexOf('mpeg-2') > 0 ||
                matcher.indexOf('avc') > 0 ||
                matcher.indexOf('vc-1') > 0
            ) {
                $('#autotype').val(1);
            }
            if (matcher.indexOf('remux') > 0) {
                $('#autotype').val(2);
            }
            if (matcher.indexOf('x264') > 0) {
                $('#autotype').val(3);
            }
            if (matcher.indexOf('x265') > 0) {
                $('#autotype').val(3);
            }
            if (matcher.indexOf('webdl') > 0 || matcher.indexOf('web-dl') > 0) {
                $('#autotype').val(4);
            }
            if (matcher.indexOf('web-rip') > 0 || matcher.indexOf('webrip') > 0) {
                $('#autotype').val(5);
            }
            if (matcher.indexOf('hdtv') > 0) {
                $('#autotype').val(6);
            }

            // Torrent Resolution
            if (release.resolution) {
                $('#autores').val(release.resolution);
            }

            // Torrent Season (TV Only)
            if (release.season) {
                $('#season_number').val(release.season);
            }

            // Torrent Episode (TV Only)
            if (release.episode) {
                $('#episode_number').val(release.episode);
            }

            // Torrent TMDB ID
            if (release.type === 'Movie') {
                theMovieDb.search.getMovie(
                    {
                        query: release.title,
                        year: release.year,
                    },
                    successCB,
                    errorCB
                );
            } else if (release.type === 'TV Show') {
                theMovieDb.search.getTv(
                    {
                        query: release.title,
                    },
                    successCB,
                    errorCB
                );
            }

            function successCB(data) {
                data = JSON.parse(data);
                if (release.type === 'Movie') {
                    if (data.results && data.results.length > 0) {
                        $('#autotmdb').val(data.results[0].id);
                        $('#apimatch').val(
                            'Found Match: ' + data.results[0].title + ' (' + data.results[0].release_date + ')'
                        );
                        theMovieDb.movies.getKeywords(
                            {
                                id: data.results[0].id,
                            },
                            success,
                            error
                        );
                        theMovieDb.movies.getExternalIds(
                            {
                                id: data.results[0].id,
                            },
                            s,
                            e
                        );
                    }
                } else if (release.type === 'TV Show') {
                    if (data.results && data.results.length > 0) {
                        $('#autotmdb').val(data.results[0].id);
                        $('#apimatch').val(
                            'Found Match: ' + data.results[0].name + ' (' + data.results[0].first_air_date + ')'
                        );
                        theMovieDb.tv.getKeywords(
                            {
                                id: data.results[0].id,
                            },
                            success,
                            error
                        );
                        theMovieDb.tv.getExternalIds(
                            {
                                id: data.results[0].id,
                            },
                            s,
                            e
                        );
                    }
                }
            }

            function errorCB(data) {
                console.log('Error callback: ' + data);
            }

            //Torrent Keywords
            function success(data) {
                data = JSON.parse(data);
                if (release.type === 'Movie') {
                    let tags = data.keywords.map(({ name }) => name).join(', ');
                    $('#autokeywords').val(tags);
                } else if (release.type === 'TV Show') {
                    let tags = data.results.map(({ name }) => name).join(', ');
                    $('#autokeywords').val(tags);
                }
            }

            function error(data) {
                console.log('Error callback: ' + data);
            }

            //Torrent External IDs
            function s(data) {
                data = JSON.parse(data);
                let imdb = data.imdb_id;
                imdb = imdb.substring(2);
                if (release.type === 'Movie') {
                    $('#autoimdb').val(imdb);
                } else if (release.type === 'TV Show') {
                    $('#autoimdb').val(imdb);
                    $('#autotvdb').val(data.tvdb_id);
                }
            }

            function e(data) {
                console.log('Error callback: ' + data);
            }

            // Torrent Stream Optimized?
            if (release.container === 'MP4' && release.audio === 'AAC') {
                document.getElementById('stream').checked = true;
            }
        }
    }
}

class userFilterBuilder {
    constructor() {
        this.csrf = document.querySelector("meta[name='csrf-token']").getAttribute('content');
        this.api = '';
        this.filter = '';
        this.start = 0;
        this.view = 'history';
    }

    set(filter) {
        this.filter = filter;
    }

    get() {
        return this.filter;
    }

    force() {
        this.handle(this.start, true);
    }

    handle(page, nav) {
        const userId = $('#userFilter').attr('userId');
        const userName = $('#userFilter').attr('userName');

        const view = $('#userFilter').attr('view');

        const active = (function () {
            if ($('#active').is(':checked')) {
                return $('#active').val();
            }
        })();

        const seeding = (function () {
            if ($('#seeding').is(':checked')) {
                return $('#seeding').val();
            }
        })();

        const leeching = (function () {
            if ($('#leeching').is(':checked')) {
                return $('#leeching').val();
            }
        })();

        const prewarned = (function () {
            if ($('#prewarned').is(':checked')) {
                return $('#prewarned').val();
            }
        })();

        const hr = (function () {
            if ($('#hr').is(':checked')) {
                return $('#hr').val();
            }
        })();

        const immune = (function () {
            if ($('#immune').is(':checked')) {
                return $('#immune').val();
            }
        })();

        const completed = (function () {
            if ($('#completed').is(':checked')) {
                return $('#completed').val();
            }
        })();

        const pending = (function () {
            if ($('#pending').is(':checked')) {
                return $('#pending').val();
            }
        })();

        const approved = (function () {
            if ($('#approved').is(':checked')) {
                return $('#approved').val();
            }
        })();

        const rejected = (function () {
            if ($('#rejected').is(':checked')) {
                return $('#rejected').val();
            }
        })();

        const dead = (function () {
            if ($('#dead').is(':checked')) {
                return $('#dead').val();
            }
        })();

        const alive = (function () {
            if ($('#alive').is(':checked')) {
                return $('#alive').val();
            }
        })();

        const reseed = (function () {
            if ($('#reseed').is(':checked')) {
                return $('#reseed').val();
            }
        })();

        const error = (function () {
            if ($('#error').is(':checked')) {
                return $('#error').val();
            }
        })();

        const satisfied = (function () {
            if ($('#satisfied').is(':checked')) {
                return $('#satisfied').val();
            }
        })();

        const notsatisfied = (function () {
            if ($('#notsatisfied').is(':checked')) {
                return $('#notsatisfied').val();
            }
        })();

        const rewarded = (function () {
            if ($('#rewarded').is(':checked')) {
                return $('#rewarded').val();
            }
        })();

        const notrewarded = (function () {
            if ($('#notrewarded').is(':checked')) {
                return $('#notrewarded').val();
            }
        })();

        const dying = (function () {
            if ($('#dying').is(':checked')) {
                return $('#dying').val();
            }
        })();

        const legendary = (function () {
            if ($('#legendary').is(':checked')) {
                return $('#legendary').val();
            }
        })();

        const large = (function () {
            if ($('#large').is(':checked')) {
                return $('#large').val();
            }
        })();

        const huge = (function () {
            if ($('#huge').is(':checked')) {
                return $('#huge').val();
            }
        })();

        const everyday = (function () {
            if ($('#everyday').is(':checked')) {
                return $('#everyday').val();
            }
        })();

        const legendary_seeder = (function () {
            if ($('#legendary_seeder').is(':checked')) {
                return $('#legendary_seeder').val();
            }
        })();

        const mvp_seeder = (function () {
            if ($('#mvp_seeder').is(':checked')) {
                return $('#mvp_seeder').val();
            }
        })();

        const committed_seeder = (function () {
            if ($('#committed_seeder').is(':checked')) {
                return $('#committed_seeder').val();
            }
        })();

        const teamplayer_seeder = (function () {
            if ($('#teamplayer_seeder').is(':checked')) {
                return $('#teamplayer_seeder').val();
            }
        })();

        const participant_seeder = (function () {
            if ($('#participant_seeder').is(':checked')) {
                return $('#participant_seeder').val();
            }
        })();

        const old = (function () {
            if ($('#old').is(':checked')) {
                return $('#old').val();
            }
        })();

        const unfilled = (function () {
            if ($('#unfilled').is(':checked')) {
                return $('#unfilled').val();
            }
        })();

        const filled = (function () {
            if ($('#filled').is(':checked')) {
                return $('#filled').val();
            }
        })();

        const claimed = (function () {
            if ($('#claimed').is(':checked')) {
                return $('#claimed').val();
            }
        })();

        const search = $('#search').val();

        const sorting = $('#sorting').val();
        const direction = $('#direction').val();

        if (userFilterXHR != null) {
            userFilterXHR.abort();
        }
        userFilterXHR = $.ajax({
            url: '/users/' + userName + '/userFilters',
            data: {
                _token: this.csrf,
                page: page,
                active: active,
                sorting: sorting,
                direction: direction,
                seeding: seeding,
                prewarned: prewarned,
                completed: completed,
                hr: hr,
                rewarded: rewarded,
                notrewarded: notrewarded,
                immune: immune,
                claimed: claimed,
                filled: filled,
                unfilled: unfilled,
                name: search,
                pending: pending,
                leeching: leeching,
                approved: approved,
                rejected: rejected,
                dead: dead,
                alive: alive,
                satisfied: satisfied,
                notsatisfied: notsatisfied,
                reseed: reseed,
                error: error,
                dying: dying,
                legendary: legendary,
                old: old,
                huge: huge,
                large: large,
                everyday: everyday,
                legendary_seeder: legendary_seeder,
                mvp_seeder: mvp_seeder,
                teamplayer_seeder: teamplayer_seeder,
                committed_seeder: committed_seeder,
                participant_seeder: participant_seeder,
                view: view,
            },
            type: 'post',
            beforeSend: function () {
                $('#userFilter').html('<i class="fal fa-spinner fa-spin fa-3x fa-fw"></i>');
            },
        }).done(function (e) {
            $data = $(e);
            $('#userFilter').html($data);
            if (page) {
                $('#filterHeader')[0].scrollIntoView();
            }
            if (!nav) {
                if (window.history && window.history.replaceState) {
                    window.history.replaceState(null, null, ' ');
                }
            }
            userFilterXHR = null;
        });
    }

    init() {
        $('.userFilter').each(function () {
            if ($(this).attr('trigger')) {
                var trigger = $(this).attr('trigger');
            } else {
                var trigger = 'click';
            }
            $(this).off(trigger);
            $(this).on(
                trigger,
                _.debounce(function (e) {
                    userFilter.handle();
                }, 400)
            );
        });

        let page = 0;
        if (window.location.hash && window.location.hash.indexOf('page')) {
            page = parseInt(window.location.hash.split('/')[1]);
        }
        if (page && page > 0) {
            this.start = page;
            this.force();
        }
    }
}

class userExtensionBuilder {
    constructor() {
        this.csrf = document.querySelector("meta[name='csrf-token']").getAttribute('content');
    }

    handle(flag) {
        if (flag) {
            $('.' + this.extension).each(function () {
                $(this).show();
            });
        } else {
            $('.' + this.extension).each(function () {
                $(this).hide();
            });
        }
    }

    init() {
        this.extension = $('#userExtension').attr('extension');
        $('#extended').off('change');
        $('#extended').on('change', function () {
            userExtension.handle($(this).is(':checked'));
        });
        this.handle();
    }
}

class forumTipBuilder {
    constructor() {
        this.csrf = document.querySelector("meta[name='csrf-token']").getAttribute('content');
        this.leaveTip = $('#forumTip').attr('leaveTip');
        this.quickTip = $('#forumTip').attr('quickTip');
        this.route = $('#forumTip').attr('route');
    }

    handle(user, id) {
        this.user = user;
        this.template =
            '<div class="some-padding">' +
            '<div class="box">' +
            '<form role="form" method="POST" action="' +
            this.route +
            '">' +
            '<input type="hidden" name="_token" value="' +
            this.csrf +
            '">' +
            '<input type="hidden" name="recipient" value="' +
            this.user +
            '">' +
            '<input type="hidden" name="post" value="' +
            id +
            '">' +
            '<input type="number" name="tip" value="0" placeholder="0" class="form-control">' +
            '<button type="submit" class="btn btn-primary">' +
            this.leaveTip +
            '</button>' +
            '<br>' +
            '<br>' +
            '<span class="text-green text-bold">' +
            this.quickTip +
            '</span>' +
            '<br>' +
            '<button type="submit" value="10" name="tip" class="label label-sm label-success space-me">10 BON</button>' +
            '<button type="submit" value="20" name="tip" class="label label-sm label-danger space-me">20 BON</button>' +
            '<button type="submit" value="50" name="tip" class="label label-sm label-info space-me">50 BON</button>' +
            '<button type="submit" value="100" name="tip" class="label label-sm label-warning space-me">100 BON</button>' +
            '<button type="submit" value="200" name="tip" class="label label-sm label-danger space-me">200 BON</button>' +
            '<button type="submit" value="500" name="tip" class="label label-sm label-primary space-me">500 BON</button>' +
            '<button type="submit" value="1000" name="tip" class="label label-sm label-success space-me">1000 BON</button>' +
            '</form>' +
            '</div>' +
            '</div>';

        $('#forumTip' + id).html(this.template);
        $('#forumTip' + id).show();
    }

    init() {
        $('.forumTip').each(function () {
            $(this).on('click', function (e) {
                e.preventDefault();
                forumTip.handle($(this).attr('user'), $(this).attr('post'));
            });
        });
    }
}

// Global attachments.
// Attach to events using jQuery.

$(document).ajaxComplete(function () {
    $('[data-toggle="tooltip"]').tooltip();
});
$(document).ready(function () {
    if (document.getElementById('request-form-description')) {
        $('#request-form-description').wysibb({});
    }
    if ($('#comments').length > 0) {
        if (window.location.hash && window.location.hash.substring) {
            let hash = window.location.hash.substring(1).split('/')[0];
            if (hash == 'comments') {
                $('#comments')[0].scrollIntoView();
            }
        }
    }
    if ($('#upload-form-description').length > 0) {
        $('#upload-form-description').wysibb({});
    }
    if (document.getElementById('userFilter')) {
        userFilter.init();
    }
    if (document.getElementById('forumTip')) {
        forumTip.init();
    }
    if (document.getElementById('userExtension')) {
        userExtension.init();
    }
});
$(document).on('click', '.pagination a', function (e) {
    var url = $(this).attr('href');
    if ($('#comments').length > 0) {
        e.preventDefault();
        window.location.href = url + '#comments';
        return;
    }

    if (!document.getElementById('facetedSearch') && !document.getElementById('userFilter')) {
    } else {
        e.preventDefault();

        let sub = null;
        if (window.location.hash && window.location.hash.substring) {
            sub = window.location.hash.substring(1).split('/')[0];
        }
        if (!sub) {
            sub = 'page';
        }
        const link_url = $(this).attr('href');
        const page = parseInt(link_url.split('page=')[1]);
        var url = window.location.href.split('#')[0] + '#' + sub + '/' + page;
        if (window.history && window.history.pushState) {
            window.history.pushState('', '', url);
        }
        if (document.getElementById('facetedSearch')) {
            facetedSearch.show(page, true);
        }
        if (document.getElementById('userFilter')) {
            userFilter.handle(page, true);
        }
    }
});
$(document).mousedown(function () {
    if (audioLoaded == 0) {
        window.sounds = {};
        const sound = new Audio('/sounds/alert.mp3');
        sound.load();
        window.sounds['alert.mp3'] = sound;
    }
    audioLoaded = 1;
});
if (document.getElementById('torrent')) {
    document.querySelector('#torrent').addEventListener('change', () => {
        uploadExtension.hook();
    });
}
// Globals
const userFilter = new userFilterBuilder();
const forumTip = new forumTipBuilder();
const userExtension = new userExtensionBuilder();
const uploadExtension = new uploadExtensionBuilder();
var userFilterXHR = null;
var audioLoaded = 0;
