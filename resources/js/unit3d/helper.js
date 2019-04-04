/*
 * NOTICE OF LICENSE.
 *
 * UNIT3D is open-sourced software licensed under the GNU General Public License v3.0
 * The details is bundled with this project in the file LICENSE.txt.
 *
 * @project    UNIT3D
 *
 * @license    https://www.gnu.org/licenses/agpl-3.0.en.html/ GNU Affero General Public License v3.0
 * @author     HDVinnie, singularity43
 *
 * File Contents:
 *
 * uploadExtensionBuilder - To parse torrent files titles / Used: Upload
 * userFilterBuilder - To add filters for user search / Used: All User Histories
 * torrentBookmarkBuilder - To show bookmark buttons for users / Used: Home, Torrents
 * facetedSearchBuilder - To add filters for search / Used: Torrents
 * forumTipBuilder - To add tip buttons for forum / Used: Topics
 * userExtensionBuilder - To add toggle capabilities to BON / Used: BON
 * configExtensionBuilder - To add Swal to config manager / Used: Admin Config Manager
 *
 * After classes, event attachments then globals.
*/

class uploadExtensionBuilder {
    constructor() {
        // Empty for now
    }
    hook() {
        let name = document.querySelector('#title');
        let torrent = document.querySelector('#torrent');
        if (!name.value) {
            let fileEndings = ['.mkv.torrent', '.mp4.torrent', '.torrent'];
            let allowed = ['1.0', '2.0', '5.1', '6.1', '7.1', 'H.264'];
            var newValue = '';
            var preValue = torrent.value;
            fileEndings.forEach(function (e) {
                preValue = preValue.replace(e, '');
            });
            var recursion = preValue.split('\\').pop().split('/').pop();
            for(var i=0; i<recursion.length; i++) {
                var prev = false;
                var next = false;
                if(recursion[i] == '.') {
                    var joined = false;
                    for(var j=0; j<allowed.length; j++) {
                        var tmp = allowed[j].split('.');
                        if(tmp[0] == 'H') {
                            if (recursion[i - 1] != undefined && recursion[i - 1] == tmp[0] && recursion[i + 1] != undefined && recursion[i + 1] == '2' && recursion[i + 2] != undefined && recursion[i + 2] == '6' && recursion[i + 3] != undefined && recursion[i + 3] =='4') {
                                joined = true;
                            }
                        } else {
                            if (recursion[i - 1] != undefined && recursion[i - 1] == tmp[0] && recursion[i + 1] != undefined && recursion[i + 1] == tmp[1]) {
                                joined = true;
                            }
                        }
                    }
                    if(joined == false) { newValue=newValue+' '; }
                    else {
                        newValue = newValue+'.';
                    }
                }
                else {
                    newValue = newValue + recursion[i];
                }
            }
            name.value = newValue;
        }
    }
}
class userFilterBuilder {
    constructor() {
        this.csrf = document.querySelector("meta[name='csrf-token']").getAttribute("content");
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
        this.handle(this.start,true);
    }
    handle(page,nav) {

        var userId = $('#userFilter').attr('userId');
        var userName = $('#userFilter').attr('userName');

        var view = $('#userFilter').attr('view');

        var active = (function () {
            if ($("#active").is(":checked")) {
                return $("#active").val();
            }
        })();

        var seeding = (function () {
            if ($("#seeding").is(":checked")) {
                return $("#seeding").val();
            }
        })();

        var leeching = (function () {
            if ($("#leeching").is(":checked")) {
                return $("#leeching").val();
            }
        })();

        var prewarned = (function () {
            if ($("#prewarned").is(":checked")) {
                return $("#prewarned").val();
            }
        })();

        var hr = (function () {
            if ($("#hr").is(":checked")) {
                return $("#hr").val();
            }
        })();

        var immune = (function () {
            if ($("#immune").is(":checked")) {
                return $("#immune").val();
            }
        })();

        var completed = (function () {
            if ($("#completed").is(":checked")) {
                return $("#completed").val();
            }
        })();

        var pending = (function () {
            if ($("#pending").is(":checked")) {
                return $("#pending").val();
            }
        })();

        var approved = (function () {
            if ($("#approved").is(":checked")) {
                return $("#approved").val();
            }
        })();

        var rejected = (function () {
            if ($("#rejected").is(":checked")) {
                return $("#rejected").val();
            }
        })();

        var dead = (function () {
            if ($("#dead").is(":checked")) {
                return $("#dead").val();
            }
        })();

        var alive = (function () {
            if ($("#alive").is(":checked")) {
                return $("#alive").val();
            }
        })();

        var reseed = (function () {
            if ($("#reseed").is(":checked")) {
                return $("#reseed").val();
            }
        })();

        var error = (function () {
            if ($("#error").is(":checked")) {
                return $("#error").val();
            }
        })();

        var satisfied = (function () {
            if ($("#satisfied").is(":checked")) {
                return $("#satisfied").val();
            }
        })();

        var notsatisfied = (function () {
            if ($("#notsatisfied").is(":checked")) {
                return $("#notsatisfied").val();
            }
        })();

        var rewarded = (function () {
            if ($("#rewarded").is(":checked")) {
                return $("#rewarded").val();
            }
        })();

        var notrewarded = (function () {
            if ($("#notrewarded").is(":checked")) {
                return $("#notrewarded").val();
            }
        })();

        var dying = (function () {
            if ($("#dying").is(":checked")) {
                return $("#dying").val();
            }
        })();

        var legendary = (function () {
            if ($("#legendary").is(":checked")) {
                return $("#legendary").val();
            }
        })();

        var large = (function () {
            if ($("#large").is(":checked")) {
                return $("#large").val();
            }
        })();

        var huge = (function () {
            if ($("#huge").is(":checked")) {
                return $("#huge").val();
            }
        })();

        var everyday = (function () {
            if ($("#everyday").is(":checked")) {
                return $("#everyday").val();
            }
        })();

        var legendary_seeder = (function () {
            if ($("#legendary_seeder").is(":checked")) {
                return $("#legendary_seeder").val();
            }
        })();

        var mvp_seeder = (function () {
            if ($("#mvp_seeder").is(":checked")) {
                return $("#mvp_seeder").val();
            }
        })();

        var committed_seeder = (function () {
            if ($("#committed_seeder").is(":checked")) {
                return $("#committed_seeder").val();
            }
        })();

        var teamplayer_seeder = (function () {
            if ($("#teamplayer_seeder").is(":checked")) {
                return $("#teamplayer_seeder").val();
            }
        })();

        var participant_seeder = (function () {
            if ($("#participant_seeder").is(":checked")) {
                return $("#participant_seeder").val();
            }
        })();

        var old = (function () {
            if ($("#old").is(":checked")) {
                return $("#old").val();
            }
        })();

        var unfilled = (function () {
            if ($("#unfilled").is(":checked")) {
                return $("#unfilled").val();
            }
        })();

        var filled = (function () {
            if ($("#filled").is(":checked")) {
                return $("#filled").val();
            }
        })();

        var claimed = (function () {
            if ($("#claimed").is(":checked")) {
                return $("#claimed").val();
            }
        })();

        var search = $("#search").val();

        var sorting = $("#sorting").val();
        var direction = $("#direction").val();

        if(userFilterXHR != null) {
            userFilterXHR.abort();
        }
        userFilterXHR = $.ajax({
            url: '/'+userName+'.'+userId+'/userFilters',
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
                $("#userFilter").html('<i class="fal fa-spinner fa-spin fa-3x fa-fw"></i>')
            }
        }).done(function (e) {
            $data = $(e);
            $("#userFilter").html($data);
            if (page) {
                $("#filterHeader")[0].scrollIntoView();
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
        $('.userFilter').each(function() {
            if($(this).attr('trigger')) {
                var trigger = $(this).attr('trigger');
            } else {
                var trigger = 'click';
            }
            $(this).off(trigger);
            $(this).on(trigger, function(e) {
                 userFilter.handle();
            });
        });

        var page = 0;
        if (window.location.hash && window.location.hash.indexOf('page')) {
            page = parseInt(window.location.hash.split('/')[1]);
        }
        if (page && page > 0) {
            this.start = page;
            this.force();
        }
    }
}
class facetedSearchBuilder {
    constructor() {
        this.lazyloader = '';
        this.active = '';
        this.start = 0;
        this.memory = {};
        this.api = '';
        this.csrf = document.querySelector("meta[name='csrf-token']").getAttribute("content");
        if($('#facetedSearch') && $('#facetedSearch').attr('font-awesome')) {
            this.font = $('#facetedSearch').attr('font-awesome');
        } else {
            this.font = 'fal';
        }
    }
    put(id) {
        this.lazyloader = id;
    }
    get() {
        return this.lazyloader;
    }
    settings() {
        if ($('#facetedDefault').is(':visible')){
            var force = 2;
        } else {
            var force = 1;
        }
        var localXHR = new XMLHttpRequest();
        localXHR = $.ajax({
            url: '/filterSettings',
            data: {
                _token: this.csrf,
                force: force,
            },
            type: 'get'
        }).done(function (e) { });
    }
    show(page,nav) {
        if(facetedSearchXHR != null) {
            facetedSearchXHR.abort();
        }
        facetedSearchXHR = new XMLHttpRequest();
        if ($('#facetedDefault').is(':visible')){
            var search = $("#query").val();
        } else {
            var search = $("#search").val();
        }
        var description = $("#description").val();
        var uploader = $("#uploader").val();
        var imdb = $("#imdb").val();
        var tvdb = $("#tvdb").val();
        var tmdb = $("#tmdb").val();
        var mal = $("#mal").val();
        var categories = [];
        var types = [];
        var genres = [];
        var qty = $("#qty").val();
        var notdownloaded = (function () {
            if ($("#notdownloaded").is(":checked")) {
                return $("#notdownloaded").val();
            }
        })();
        var downloaded = (function () {
            if ($("#downloaded").is(":checked")) {
                return $("#downloaded").val();
            }
        })();
        var idling = (function () {
            if ($("#idling").is(":checked")) {
                return $("#idling").val();
            }
        })();
        var leeching = (function () {
            if ($("#leeching").is(":checked")) {
                return $("#leeching").val();
            }
        })();
        var freeleech = (function () {
            if ($("#freeleech").is(":checked")) {
                return $("#freeleech").val();
            }
        })();
        var doubleupload = (function () {
            if ($("#doubleupload").is(":checked")) {
                return $("#doubleupload").val();
            }
        })();
        var featured = (function () {
            if ($("#featured").is(":checked")) {
                return $("#featured").val();
            }
        })();
        var seeding = (function () {
            if ($("#seeding").is(":checked")) {
                return $("#seeding").val();
            }
        })();
        var stream = (function () {
            if ($("#stream").is(":checked")) {
                return $("#stream").val();
            }
        })();
        var highspeed = (function () {
            if ($("#highspeed").is(":checked")) {
                return $("#highspeed").val();
            }
        })();
        var sd = (function () {
            if ($("#sd").is(":checked")) {
                return $("#sd").val();
            }
        })();
        var internal = (function () {
            if ($("#internal").is(":checked")) {
                return $("#internal").val();
            }
        })();
        var alive = (function () {
            if ($("#alive").is(":checked")) {
                return $("#alive").val();
            }
        })();
        var dying = (function () {
            if ($("#dying").is(":checked")) {
                return $("#dying").val();
            }
        })();
        var dead = (function () {
            if ($("#dead").is(":checked")) {
                return $("#dead").val();
            }
        })();
        $(".category:checked").each(function () {
            categories.push($(this).val());
        });
        $(".type:checked").each(function () {
            types.push($(this).val());
        });
        $(".genre:checked").each(function () {
            genres.push($(this).val());
        });


        if (this.view == 'card') {
            var sorting = $("#sorting").val();
            var direction = $("#direction").val();
            qty = 33;
        } else if(this.view == 'group') {
            var sorting = $("#sorting").val();
            var direction = $("#direction").val();
        } else {
            if ($('#created_at').attr('state') && $('#created_at').attr('state') > 0) {
                var sorting = 'created_at';
                if ($('#created_at').attr('state') == 1) {
                    var direction = 'asc';
                } else if ($('#created_at').attr('state') == 2) {
                    var direction = 'desc';
                }
            }
            if ($('#name').attr('state') && $('#name').attr('state') > 0) {
                var sorting = 'name';
                if ($('#name').attr('state') == 1) {
                    var direction = 'asc';
                } else if ($('#name').attr('state') == 2) {
                    var direction = 'desc';
                }
            }
            if ($('#seeders').attr('state') && $('#seeders').attr('state') > 0) {
                var sorting = 'seeders';
                if ($('#seeders').attr('state') == 1) {
                    var direction = 'asc';
                } else if ($('#seeders').attr('state') == 2) {
                    var direction = 'desc';
                }
            }
            if ($('#leechers').attr('state') && $('#leechers').attr('state') > 0) {
                var sorting = 'leechers';
                if ($('#leechers').attr('state') == 1) {
                    var direction = 'asc';
                } else if ($('#leechers').attr('state') == 2) {
                    var direction = 'desc';
                }
            }
            if ($('#times_completed').attr('state') && $('#times_completed').attr('state') > 0) {
                var sorting = 'times_completed';
                if ($('#times_completed').attr('state') == 1) {
                    var direction = 'asc';
                } else if ($('#times_completed').attr('state') == 2) {
                    var direction = 'desc';
                }
            }
            if ($('#size').attr('state') && $('#size').attr('state') > 0) {
                var sorting = 'size';
                if ($('#size').attr('state') == 1) {
                    var direction = 'asc';
                } else if ($('#size').attr('state') == 2) {
                    var direction = 'desc';
                }
            }
        }
        facetedSearchXHR = $.ajax({
            url: this.api,
            data: {
                _token: this.csrf,
                search: search,
                description: description,
                uploader: uploader,
                imdb: imdb,
                tvdb: tvdb,
                notdownloaded: notdownloaded,
                downloaded: downloaded,
                idling: idling,
                leeching: leeching,
                seeding: seeding,
                view: this.view,
                tmdb: tmdb,
                mal: mal,
                categories: categories,
                types: types,
                genres: genres,
                freeleech: freeleech,
                doubleupload: doubleupload,
                featured: featured,
                stream: stream,
                highspeed: highspeed,
                sd: sd,
                internal: internal,
                alive: alive,
                dying: dying,
                dead: dead,
                sorting: sorting,
                direction: direction,
                page: page,
                qty: qty
            },
            type: 'get',
            beforeSend: function () {
                $("#facetedSearch").html('<i class="fal fa-spinner fa-spin fa-3x fa-fw"></i>')
            }
        }).done(function (e) {
            $data = $(e);
            $("#facetedSearch").html($data);
            if (page) {
                $("#facetedHeader")[0].scrollIntoView();
            }
            if (!nav) {
                if (window.history && window.history.replaceState) {
                    window.history.replaceState(null, null, ' ');
                }
            }
            torrentBookmark.update();
            facetedSearch.refresh();
            facetedSearchXHR = null;
            facetedSearch.posters();
        });
    }
    posters() {
        $('.show-poster').each(function() {
            $(this).off('click');
            $(this).on('click', function(e) {
                e.preventDefault();
                var name = $(this).attr('data-name');
                var image = $(this).attr('data-image');
                Swal.fire({
                    showConfirmButton: false,
                    showCloseButton: true,
                    background: 'rgb(35,35,35)',
                    width: 970,
                    html: image,
                    title: name,
                    text: '',
                });
            });
        });
    }
    inform() {
        return this.active;
    }
    handle(id) {
        var trigger = $('#'+id).attr('trigger');
        this.active = id;
        if(trigger && trigger == 'keyup') {
            var length = $('#'+id).val().length;
            if(length == this.memory[id]) return;
            this.memory[id] = length;
        } else if(trigger && trigger == 'sort') {
            $('.facetedSort').each(function() {
                if($(this).attr('id') && $(this).attr('id') == facetedSearch.inform()) {

                    if($('#'+id).length == 0) { return; }

                    if($('#'+id).attr('state') && ($('#'+id).attr('state') == 0 || $('#'+id).attr('state') == 2)) {
                        $('#'+id).attr('state',1);
                    } else if($('#'+id).attr('state') && ($('#'+id).attr('state') == 1)) {
                        $('#'+id).attr('state',2);
                    } else {
                        $('#'+id).attr('state',1);
                    }
                } else {
                    $(this).attr('state', 0);
                }
            });
        }
        this.show(0);
    }
    refresh(callback) {
        $('.facetedSearch').each(function() {
            var trigger = $(this).attr("trigger") ? $(this).attr("trigger") : 'click';
            var cloner = trigger;
            if(cloner == 'sort') { cloner='click'; }
            $(this).off(cloner);
            $(this).on(cloner, function(e) {
                if($(this).attr('trigger') == 'sort') {
                    e.preventDefault();
                }
                facetedSearch.handle($(this).attr('id'));
            });
        });
        $('.facetedLoader').each(function() {
            $(this).off('click');
            $(this).on('click', function(e) {
                e.preventDefault();
                if($(this).attr('torrent')) {
                    facetedSearch.put($(this).attr('torrent'));
                    $('.facetedLoading').each(function () {
                        var check = facetedSearch.get();
                        if ($(this).attr('torrent') && $(this).attr('torrent') == check) {
                            $(this).show();
                        }
                    });
                    $('#facetedCell'+$(this).attr('torrent')).hide();
                }
            });
        });
        $('#facetedFiltersToggle').off('click');
        $('#facetedFiltersToggle').on('click', function(e) {
            facetedSearch.settings();
            e.preventDefault();
            if ($('#facetedDefault').is(':visible')){
                $('#facetedFilters').show();
                $('#facetedDefault').hide();
                return;
            }
            $('#facetedFilters').hide();
            $('#facetedDefault').show();
        });
        if(callback) {
            callback();
        }
    }
    force() {
        this.show(this.start,true);
    }
    init(type) {
        this.type = type;
        if (this.type == 'card') {
            this.api = '/filterTorrents';
            this.view = 'card';
        }
        else if (this.type == 'request') {
            this.api = '/filterRequests';
            this.view = 'request';
        }
        else if (this.type == 'group') {
            this.api = '/filterTorrents';
            this.view = 'group';
        }
        else {
            this.api = '/filterTorrents';
            this.view = 'list';
        }

        var page = 0;
        if (window.location.hash && window.location.hash.indexOf('page')) {
            page = parseInt(window.location.hash.split('/')[1]);
        }
        if (page && page > 0) {
            this.start = page;
            this.refresh(function() { facetedSearch.force(); });
        }
        else {
            this.refresh(function() { });
        }
        this.posters();
    }
}
class userExtensionBuilder {
    constructor() {
        this.csrf = document.querySelector("meta[name='csrf-token']").getAttribute("content");
    }
    handle(flag) {
        if(flag) {
            $('.'+this.extension).each(function() {
                $(this).show();
            });
        }
        else {
            $('.'+this.extension).each(function() {
                $(this).hide();
            });
        }
    }
    init() {
        this.extension = $('#userExtension').attr('extension');
        $('#extended').off('change');
        $('#extended').on('change', function() {
            userExtension.handle(($(this).is(':checked')));
        });
        this.handle();
    }
}
class forumTipBuilder {
    constructor() {
        this.csrf = document.querySelector("meta[name='csrf-token']").getAttribute("content");
        this.leaveTip = $('#forumTip').attr('leaveTip');
        this.quickTip = $('#forumTip').attr('quickTip');
        this.route = $('#forumTip').attr("route");
    }
    handle(user,id) {
        this.user = user;
        this.template = '<div class="some-padding">' +
            '<div class="box">' +
            '<form role="form" method="POST" action="'+this.route+'">' +
            '<input type="hidden" name="_token" value="' + this.csrf + '">' +
            '<input type="hidden" name="recipient" value="'+this.user+'">' +
            '<input type="hidden" name="post" value="'+id+'">'+
            '<input type="number" name="tip" value="0" placeholder="0" class="form-control">' +
            '<button type="submit" class="btn btn-primary">'+this.leaveTip+'</button>' +
            '<br>' +
            '<br>' +
            '<span class="text-green text-bold">'+this.quickTip+'</span>' +
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

        $('#forumTip'+id).html(this.template);
        $('#forumTip'+id).show();
    }
    init() {
        $('.forumTip').each(function() {
            $(this).on('click', function(e) {
                e.preventDefault();
                forumTip.handle($(this).attr("user"),$(this).attr("post"));
            });
        });
    }
}
class configExtensionBuilder {
    constructor() {
        this.csrf = document.querySelector("meta[name='csrf-token']").getAttribute("content");
    }
    handle(keyv,val) {
        this.keyv = keyv;
        this.val = val;
        this.input = val.replace(/['"]/g,'');
        Swal.fire({
            title: 'Change Value',
            width: '800px',
            inputAttributes: {
                autocapitalize: 'off'
            },
            html:
                '<div class="text-left">'+
                '<input type="text" size="30" id="val_'+configExtension.keyv+'" class="form-control" name="val" value="'+this.input+'" />' +
                '</div>',
            showCancelButton: true,
            confirmButtonText: 'Save',
            showLoaderOnConfirm: true,
            preConfirm: () => {
                this.val = $('#val_'+configExtension.keyv).val();
                $.ajax({
                    url: this.update,
                    data: {
                        'filePath' : configExtension.path,
                        'key' : configExtension.keyv,
                        'value' : ''+String(configExtension.val)+'',
                    },
                    beforeSend: function (request){
                        request.setRequestHeader("X-CSRF-TOKEN", configExtension.csrf);
                    },
                    type: 'PUT',
                    success: function(result) {
                        $('#key_val_'+configExtension.keyv).html(configExtension.val);
                        $('#button_'+configExtension.keyv).attr('val',configExtension.val);
                    }
                });
            },
            allowOutsideClick: false
        }).then(result => {
            if (result.value) {
                Swal.fire({
                    title: 'Value Has Been Changed',
                    timer: 1500,
                    onOpen: () => {
                        swal.showLoading();
                    }
                })
            }
        });
    }
    init() {
        $('#configBox').hide();
        this.view = $('#configExtension').attr('view');
        this.index = $('#configExtension').attr('index');
        this.update = $('#configExtension').attr('update');
        this.file = $('#configExtension').attr('file');
        this.path = $('#configExtension').attr('path');
        $('.file-select').off('change');
        $('.file-select').on('change', function(){
            var file = $(this).val();
            if (file) {
                window.location.href = configExtension.view+"/"+$(this).val();
            } else {
                window.location.href = configExtension.index;
            }
        });
        if(this.file == 'yes') {
            $('.edit').each(function () {
                $(this).off('click');
                $(this).on('click', function (e) {
                    e.preventDefault();
                    configExtension.handle($(this).attr('keyv'),$(this).attr('val'));
                });
            });
        }
    }
}
class torrentBookmarkBuilder {
    constructor() {
        this.csrf = document.querySelector("meta[name='csrf-token']").getAttribute("content");
        if($('#facetedBookmark') && $('#facetedBookmark').attr('font-awesome')) {
            this.font = $('#facetedBookmark').attr('font-awesome');
        } else {
            this.font = 'fal';
        }
    }
    update() {
        $('.torrentBookmark').each(function() {
            var active = $(this).attr("state") ? $(this).attr("state") : 0;
            var id = $(this).attr("id") ? $(this).attr("id") : 0;
            var custom = $(this).attr("custom") ? $(this).attr("custom") : '';
            $(this).off('click');
            if(active == 1) {
                $(this).attr("data-original-title","Unbookmark Torrent");
            } else {
                $(this).attr("data-original-title","Bookmark Torrent");
            }
            $(this).html('<button custom="'+custom+'" state="'+active+'" torrent="'+id+'" class="btn ' + (active > 0 ? 'btn-circle btn-danger' : 'btn-circle btn-primary') + '"><i class="'+this.font+' fal fa-bookmark"></i></button>');
            $(this).on('click', function() {
                torrentBookmark.handle($(this).attr("torrent"),$(this).attr("state"),$(this).attr("custom"));
            });
        });
    }
    handle(id,active,custom) {
        if(!active || active == 0) {
            this.create(id,custom);
            return;
        }
        this.destroy(id,custom);
    }
    create(id,custom) {

        if(torrentBookmarkXHR != null) {
            torrentBookmarkXHR.abort();
        }

        torrentBookmarkXHR = new XMLHttpRequest();

        torrentBookmarkXHR = $.ajax({
            url: '/torrents/bookmark/' + id,
            data: {
                _token: this.csrf,
            },
            type: 'get'
        }).done(function (e) {
            Swal.fire({
                position: 'center',
                type: 'success',
                title: 'Torrent Has Been Bookmarked Successfully!',
                showConfirmButton: false,
                timer: 4500,
            });
            if(custom && custom != '') {
                $('#'+custom).html('<button custom="'+custom+'" state="1" torrent="' + id + '" class="btn btn-circle btn-danger"><i class="' + this.font + ' fal fa-bookmark"></i></button>');
                $('#'+custom).attr("state", '1');
                $('#'+custom).attr("data-original-title", 'Unbookmark Torrent');
            } else {
                $('#torrentBookmark' + id).html('<button custom="'+custom+'" state="1" torrent="' + id + '" class="btn btn-circle btn-danger"><i class="' + this.font + ' fal fa-bookmark"></i></button>');
                $('#torrentBookmark' + id).attr("state", '1');
                $('#torrentBookmark' + id).attr("data-original-title", 'Unbookmark Torrent');
            }
            torrentBookmarkXHR = null;
        });
    }
    destroy(id,custom) {

        if(torrentBookmarkXHR != null) {
            return;
        }

        torrentBookmarkXHR = new XMLHttpRequest();

        torrentBookmarkXHR = $.ajax({
            url: '/torrents/unbookmark/' + id,
            data: {
                _token: this.csrf,
            },
            type: 'get'
        }).done(function (e) {
            Swal.fire({
                position: 'center',
                type: 'success',
                title: 'Torrent Has Been Unbookmarked Successfully!',
                showConfirmButton: false,
                timer: 4500,
            });
            if(custom && custom != '') {
                $('#' + custom).html('<button custom="'+custom+'" state="0" torrent="' + id + '" class="btn btn-circle btn-primary"><i class="' + this.font + ' fal fa-bookmark"></i></button>');
                $('#' + custom).attr("state", '0');
                $('#' + custom).attr("data-original-title", 'Bookmark Torrent');
            } else {
                $('#torrentBookmark' + id).html('<button custom="'+custom+'" state="0" torrent="' + id + '" class="btn btn-circle btn-primary"><i class="' + this.font + ' fal fa-bookmark"></i></button>');
                $('#torrentBookmark' + id).attr("state", '0');
                $('#torrentBookmark' + id).attr("data-original-title", 'Bookmark Torrent');
            }
            torrentBookmarkXHR = null;
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
        emoji.textcomplete()
    }
    if($('#comments').length > 0) {
        if (window.location.hash && window.location.hash.substring) {
            let hash = window.location.hash.substring(1).split('/')[0];
            if (hash == 'comments') {
                $("#comments")[0].scrollIntoView();
            }
        }
    }
    if($('#upload-form-description').length > 0) {
        $('#upload-form-description').wysibb({});
        emoji.textcomplete()
    }
    if(document.getElementById('facetedSearch')) {
        var facetedType = document.getElementById('facetedSearch').getAttribute('type');
        facetedSearch.init(facetedType);
    }
    if(document.getElementById('userFilter')) {
        userFilter.init();
    }
    if(document.getElementById('forumTip')) {
        forumTip.init();
    }
    if(document.getElementById('userExtension')) {
        userExtension.init();
    }
    if(document.getElementById('configExtension')) {
        configExtension.init();
    }
    torrentBookmark.update();
});
$(document).on('click', '.pagination a', function (e) {

    var url = $(this).attr('href');
    if($('#comments').length > 0) {
        e.preventDefault();
        window.location.href = url + '#comments';
        return;
    }

    if(!document.getElementById('facetedSearch') && !document.getElementById('userFilter')) { }
    else {

        e.preventDefault();

        var sub = null;
        if (window.location.hash && window.location.hash.substring) {
            sub = window.location.hash.substring(1).split('/')[0];
        }
        if (!sub) {
            sub = 'page';
        }
        var link_url = $(this).attr('href');
        var page = parseInt(link_url.split('page=')[1]);
        var url = (window.location.href.split("#")[0]) + '#' + sub + '/' + page;
        if (window.history && window.history.pushState) {
            window.history.pushState("", "", url);
        }
        if (document.getElementById('facetedSearch')) {
            facetedSearch.show(page, true);
        }
        if (document.getElementById('userFilter')) {
            userFilter.handle(page, true);
        }

    }
});
$(document).mousedown(function(){
    if(audioLoaded == 0) {
        window.sounds = new Object();
        var sound = new Audio('/sounds/alert.mp3');
        sound.load();
        window.sounds['alert.mp3'] = sound;
    }
    audioLoaded = 1;
});
if(document.getElementById('torrent')) {
    document.querySelector("#torrent").addEventListener("change", () => {
        uploadExtension.hook();
    });
}
// Globals

const facetedSearch = new facetedSearchBuilder();
const torrentBookmark = new torrentBookmarkBuilder();
const userFilter = new userFilterBuilder();
const forumTip = new forumTipBuilder();
const userExtension = new userExtensionBuilder();
const configExtension = new configExtensionBuilder();
const uploadExtension = new uploadExtensionBuilder();
var userFilterXHR = null;
var facetedSearchXHR = null;
var torrentBookmarkXHR = null;
var audioLoaded = 0;