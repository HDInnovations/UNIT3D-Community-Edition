<template>
    <div
        :class="
            this.fullscreen == 1
                ? `col-md-10 col-sm-10 col-md-offset-1 chatbox panel-fullscreen`
                : `col-md-10 col-sm-10 col-md-offset-1 chatbox`
        "
        id="chatbody"
        audio="false"
    >
        <div
            :class="this.fullscreen == 1 ? `clearfix visible-sm-block panel-fullscreen` : `clearfix visible-sm-block`"
        ></div>
        <div :class="this.fullscreen == 1 ? `panel panel-chat panel-fullscreen` : `panel panel-chat`">
            <div class="panel-heading" id="frameHeader">
                <div class="button-holder no-space">
                    <div class="button-left">
                        <h4><i class="fas fa-comment-dots"></i> Chatbox v3.0</h4>
                    </div>
                    <div class="button-right">
                        <a href="" view="bot" @click.prevent="startBot()" class="btn btn-xs btn-warning">
                            <i class="fa fa-robot"></i> {{ helpName }}
                        </a>
                        <a
                            href=""
                            view="list"
                            v-if="target < 1 && bot < 1 && tab != 'userlist'"
                            @click.prevent="changeTab('list', 'userlist')"
                            class="btn btn-xs btn-primary"
                        >
                            <i class="fa fa-users"></i> Users In {{ tab }}: {{ users.length }}
                        </a>
                        <a
                            href="#"
                            id="panel-fullscreen"
                            role="button"
                            :class="`btn btn-xs btn-success`"
                            title="Toggle Fullscreen"
                            @click.prevent="changeFullscreen()"
                            ><i
                                :class="
                                    this.fullscreen == 1
                                        ? `glyphicon glyphicon-resize-small`
                                        : `glyphicon glyphicon-resize-full`
                                "
                            ></i>
                        </a>
                    </div>
                </div>
            </div>
            <div class="panel-body" id="frameBody">
                <div id="frame" @mouseover="freezeChat()" @mouseout="unfreezeChat()">
                    <div class="content no-space">
                        <div class="button-holder nav nav-tabs mb-5" id="frameTabs">
                            <div>
                                <ul role="tablist" class="nav nav-tabs no-border mb-0" v-if="boot == 1">
                                    <li
                                        v-for="echo in echoes"
                                        v-if="echo.room && echo.room.name.length > 0"
                                        :class="tab != '' && tab === echo.room.name ? 'active' : null"
                                    >
                                        <a
                                            href=""
                                            role="tab"
                                            view="room"
                                            @click.prevent="changeTab('room', echo.room.id)"
                                        >
                                            <i
                                                :class="
                                                    checkPings('room', echo.room.id)
                                                        ? 'fa fa-comment fa-beat text-success'
                                                        : 'fa fa-comment text-danger'
                                                "
                                            ></i>
                                            {{ echo.room.name }}
                                        </a>
                                    </li>
                                    <li
                                        v-for="echo in echoes"
                                        v-if="echo.target && echo.target.id >= 3 && echo.target.username.length > 0"
                                        :class="target >= 3 && target === echo.target.id ? 'active' : null"
                                    >
                                        <a
                                            href=""
                                            role="tab"
                                            view="target"
                                            @click.prevent="changeTab('target', echo.target.id)"
                                        >
                                            <i
                                                :class="
                                                    checkPings('target', echo.target.id)
                                                        ? 'fa fa-comment fa-beat text-success'
                                                        : 'fa fa-comment text-danger'
                                                "
                                            ></i>
                                            @{{ echo.target.username }}
                                        </a>
                                    </li>
                                    <li
                                        v-for="echo in echoes"
                                        v-if="echo.bot && echo.bot.id >= 1 && echo.bot.name.length > 0"
                                        :class="bot > 0 && bot === echo.bot.id ? 'active' : null"
                                    >
                                        <a href="" role="tab" view="bot" @click.prevent="changeTab('bot', echo.bot.id)">
                                            <i
                                                :class="
                                                    checkPings('bot', echo.bot.id)
                                                        ? 'fa fa-comment fa-beat text-success'
                                                        : 'fa fa-comment text-danger'
                                                "
                                            ></i>
                                            @{{ echo.bot.name }}
                                        </a>
                                    </li>
                                </ul>
                            </div>
                            <div class="button-right-small">
                                <div class="nav nav-tabs no-border mb-0 mt-5">
                                    <div class="mr-10">
                                        <a
                                            href=""
                                            v-if="bot > 0"
                                            view="exit"
                                            @click.prevent="leaveBot(bot)"
                                            class="btn btn-sm btn-danger"
                                        >
                                            <i class="fa fa-times"></i>
                                        </a>

                                        <a
                                            href=""
                                            v-if="bot < 1 && target > 0"
                                            view="exit"
                                            @click.prevent="leaveTarget(target)"
                                            class="btn btn-sm btn-danger"
                                        >
                                            <i class="fa fa-times"></i>
                                        </a>

                                        <a
                                            href=""
                                            v-if="bot < 1 && target < 1 && tab != '' && tab != 'userlist' && room != 1"
                                            view="exit"
                                            @click.prevent="leaveRoom(room)"
                                            class="btn btn-sm btn-danger"
                                        >
                                            <i class="fa fa-times"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <chat-messages
                            v-if="!state.connecting && tab != '' && tab != 'userlist'"
                            @pm-sent="(o) => createMessage(o.message, o.save, o.user_id, o.receiver_id, o.bot_id)"
                            :messages="msgs"
                        >
                        </chat-messages>
                        <chat-user-list
                            v-if="!state.connecting && tab === 'userlist'"
                            @pm-sent="(o) => createMessage(o.message, o.save, o.user_id, o.receiver_id, o.bot_id)"
                            :users="users"
                        >
                        </chat-user-list>
                    </div>
                </div>
            </div>
            <div class="panel-footer" id="frameFooter">
                <chat-form
                    @changedStatus="changeStatus"
                    @message-sent="(o) => createMessage(o.message, o.save, o.user_id, o.receiver_id, o.bot_id)"
                    @typing="isTyping"
                >
                </chat-form>
            </div>
        </div>
    </div>
</template>
<style lang="scss" scoped>
.panel-fullscreen {
    z-index: 9999;
    position: fixed;
    width: 100%;
    border: 0;
    height: 100%;
    top: 0;
    bottom: 0;
    left: 0;
    right: 0;
}
.panel-footer {
    padding: 5px;
    margin: 0;
}
.mr-10 {
    margin-right: 10px;
}
.no-border {
    border-bottom: none;
    border-top: none;
}
.chatbox {
    .nav-tabs {
        overflow-y: hidden;
    }

    .typing {
        height: 20px;

        .badge-extra {
            margin: 0;
        }
    }

    .statuses {
        i {
            &:hover {
                cursor: pointer;
            }
        }
    }

    .panel-body {
        padding: 0;
    }

    .decoda-image {
        min-height: 150px;
        max-height: 300px;
        max-width: 500px;
    }
}
</style>
<script>
import ChatroomsDropdown from './ChatroomsDropdown';
import ChatMessages from './ChatMessages';
import ChatForm from './ChatForm';
import ChatPms from './ChatPms';
import ChatUserList from './ChatUserList';

export default {
    props: {
        user: {
            type: Object,
            required: true,
        },
    },
    components: {
        ChatroomsDropdown,
        ChatMessages,
        ChatForm,
        ChatUserList,
        ChatPms,
    },
    data() {
        return {
            tab: '',
            state: {
                connecting: true,
            },
            auth: {},
            statuses: [],
            status: 0,
            echoes: [],
            bots: [],
            chatrooms: [],
            messages: [],
            users: [],
            pings: [],
            audibles: [],
            boot: 0,
            audioLoaded: 0,
            room: 0,
            fullscreen: 0,
            startup: 0,
            check: 0,
            target: 0,
            forced: false,
            bot: 0,
            activeTab: '',
            activeBot: '',
            activeRoom: '',
            activeTarget: '',
            activePeer: false,
            peerRoom: 0,
            botName: '',
            botId: 0,
            listening: 1,
            botCommand: '',
            helpName: '',
            helpCommand: '',
            frozen: false,
            push: false,
            helpId: 0,
            scroll: true,
            channel: null,
            chatter: null,
            config: {},
            receiver_id: null,
            bot_id: null,
        };
    },
    watch: {
        chatrooms() {
            this.changeRoom(this.auth.chatroom.id);
        },
        statuses() {
            this.changeStatus(this.auth.chat_status.id);
        },
        room(newVal, oldVal) {
            window.Echo.leave(`chatroom.${oldVal}`);
            this.channel = window.Echo.join(`chatroom.${newVal}`);
            this.listenForEvents();
        },
        messages() {
            this.$nextTick(function () {
                this.scrollToBottom();
            });
        },
    },
    computed: {
        msgs() {
            return _.filter(this.messages, (o) => {
                return o;
            });
        },
        last_id() {
            if (this.messages > 0) {
                return this.messages[m.length - 1].id;
            }

            return 0;
        },
        statusColor() {
            if (this.statuses.length > 0) {
                let i = _.findIndex(this.statuses, (o) => {
                    return o.id === this.status;
                });

                return this.statuses[i].color;
            }

            return '';
        },
    },
    methods: {
        isTyping(e) {
            if (this.tab != 'userlist') {
                if (this.target < 1 && this.channel && this.tab != '') {
                    this.channel.whisper('typing', {
                        username: e.username,
                    });
                }
            }
        },
        changeAudible(typeVal, targetVal, newVal) {
            if (typeVal == 'room') {
                let currentRoom = _.find(this.audibles, (o) => {
                    if (o.room && o.room.id && o.room.id == targetVal) {
                        return o.room.id;
                    }
                });
                if (currentRoom) {
                    let i = currentRoom.room.id;
                    this.toggleAudible('room', i, newVal);
                }
            } else if (typeVal == 'target') {
                let currentTarget = _.find(this.audibles, (o) => {
                    if (o.target && o.target.id && o.target.id == targetVal) {
                        return o.target.id;
                    }
                });
                if (currentTarget) {
                    let i = currentTarget.target.id;
                    this.toggleAudible('target', i, newVal);
                }
            } else if (typeVal == 'bot') {
                let currentBot = _.find(this.audibles, (o) => {
                    if (o.bot && o.bot.id && o.bot.id == targetVal) {
                        return o.bot.id;
                    }
                });
                if (currentBot) {
                    let i = currentBot.bot.id;
                    this.toggleAudible('bot', i, newVal);
                }
            }
        },
        changeTab(typeVal, newVal) {
            if (typeVal == 'room') {
                this.bot = 0;
                this.target = 0;
                this.bot_id = 0;
                this.receiver_id = 0;
                this.tab = newVal;
                this.activeTab = 'room' + newVal;
                this.activeRoom = newVal;
                this.deletePing('room', newVal);
                let currentRoom = _.find(this.echoes, (o) => {
                    if (o.room && o.room.id && o.room.id == newVal) {
                        return o.room.id;
                    }
                });
                if (currentRoom) {
                    let i = currentRoom.room.id;
                    this.changeRoom(i);
                    this.receiver_id = null;
                    this.bot_id = null;
                }

                let currentAudio = _.find(this.audibles, (o) => {
                    if (o.room && o.room.id && o.room.id == newVal) {
                        return o.id;
                    }
                });
                if (currentAudio) {
                    if (currentAudio.status == 1) {
                        this.listening = 1;
                    } else {
                        this.listening = 0;
                    }
                } else {
                }
                this.scrollToBottom(true);
            } else if (typeVal == 'target') {
                this.bot = 0;
                this.tab = newVal;
                this.activeTab = 'target' + newVal;
                this.activeTarget = newVal;
                this.deletePing('target', newVal);
                let currentTarget = _.find(this.echoes, (o) => {
                    if (o.target && o.target.username && o.target.id == newVal) {
                        return o.target.id;
                    }
                });
                if (currentTarget) {
                    let i = currentTarget.target.id;
                    this.changeTarget(i);
                    this.receiver_id = i;
                    this.bot_id = null;
                }

                let currentAudio = _.find(this.audibles, (o) => {
                    if (o.target && o.target.username && o.target.id == newVal) {
                        return o.id;
                    }
                });
                if (currentAudio) {
                    if (currentAudio.status == 1) {
                        this.listening = 1;
                    } else {
                        this.listening = 0;
                    }
                } else {
                }
                this.scrollToBottom(true);
            } else if (typeVal == 'bot') {
                this.target = 0;
                this.tab = newVal;
                this.activeTab = 'bot' + newVal;
                this.activeBot = newVal;
                this.deletePing('bot', newVal);
                let currentBot = _.find(this.echoes, (o) => {
                    if (o.bot && o.bot.name && o.bot.id == newVal) {
                        return o.bot.id;
                    }
                });
                if (currentBot) {
                    let i = currentBot.bot.id;
                    this.botName = currentBot.bot.name;
                    this.botCommand = currentBot.bot.command;
                    this.botId = currentBot.bot.id;
                    this.changeBot(i);
                    this.receiver_id = 1;
                    this.bot_id = i;
                }

                let currentAudio = _.find(this.audibles, (o) => {
                    if (o.bot && o.bot.name && o.bot.id == newVal) {
                        return o.id;
                    }
                });
                if (currentAudio) {
                    if (currentAudio.status == 1) {
                        this.listening = 1;
                    } else {
                        this.listening = 0;
                    }
                } else {
                }
                this.scrollToBottom(true);
            } else if (typeVal == 'list') {
                this.tab = newVal;
                this.scrollToBottom(true);
            }
        },
        fetchAudibles() {
            axios.get('/api/chat/audibles').then((response) => {
                this.audibles = response.data.data;
                let currentAudio = _.find(this.audibles, (o) => {
                    if (o.room && o.room.id && o.room.id == 1) {
                        return o.id;
                    }
                });
                if (currentAudio) {
                    if (currentAudio.status == 1) {
                        this.listening = 1;
                    } else {
                        this.listening = 0;
                    }
                } else {
                }
                this.fetchConfiguration();
            });
        },
        fetchEchoes() {
            axios.get('/api/chat/echoes').then((response) => {
                this.echoes = response.data.data;
                this.echoes = this.sortEchoes(this.echoes);
                this.boot = 1;
            });
        },
        fetchBots() {
            axios.get('/api/chat/bots').then((response) => {
                this.bots = response.data.data;
                this.helpId = this.bots[0].id;
                this.helpName = this.bots[0].name;
                this.helpCommand = this.bots[0].command;
            });
        },
        fetchRooms() {
            axios.get('/api/chat/rooms').then((response) => {
                this.chatrooms = response.data.data;
                this.room = this.chatrooms[0].id;
                this.tab = this.chatrooms[0].name;
                this.activeTab = 'room' + this.room;
                this.fetchConfiguration();
            });
        },
        fetchConfiguration() {
            axios.get(`/api/chat/config`).then((response) => {
                this.config = response.data;
            });
        },
        fetchBotMessages(id) {
            axios.get(`/api/chat/bot/${id}`).then((response) => {
                this.messages = _.reverse(response.data.data);
                this.scrollToBottom();
                this.state.connecting = false;
            });
        },
        fetchPrivateMessages() {
            axios.get(`/api/chat/private/messages/${this.target}`).then((response) => {
                this.messages = _.reverse(response.data.data);
                this.state.connecting = false;
                this.scrollToBottom(true);
            });
        },
        fetchMessages() {
            axios.get(`/api/chat/messages/${this.room}`).then((response) => {
                this.messages = _.reverse(response.data.data);
                this.state.connecting = false;
                this.scrollToBottom(true);
            });
        },
        fetchStatuses() {
            axios.get('/api/chat/statuses').then((response) => {
                this.statuses = response.data;
            });
        },
        forceMessage(name) {
            $('#chat-message').bbcode('/msg ' + name + ' ');
            $('#chat-message').htmlcode('/msg ' + name + ' ');
        },
        forceGift(name) {
            $('#chat-message').bbcode('/gift ' + name + ' ');
            $('#chat-message').htmlcode('/gift ' + name + ' ');
        },
        freezeChat() {
            this.frozen = true;
        },
        unfreezeChat() {
            let container = $('.messages .list-group');
            let xy = parseInt(container.prop('offsetHeight') + container.scrollTop());
            if (xy != undefined && this.frozen == true) {
                if (
                    Math.ceil(container.prop('scrollHeight') - container.scrollTop()) === container.prop('clientHeight')
                ) {
                    this.frozen = false;
                }
            }
        },
        leaveBot(id) {
            if (id > 0) {
                this.bot = 0;
                this.botName = '';
                this.botId = '';
                /* Update the users bot in the database */
                axios
                    .post(`/api/chat/echoes/${this.auth.id}/delete/bot`, {
                        bot_id: id,
                    })
                    .then((response) => {
                        // reassign the auth variable to the response data
                        this.auth = response.data;
                        $('#currentChatroom').val('1');
                        this.fetchRooms();
                    });
            }
        },
        toggleAudible(type, id, nv) {
            if (id != 0) {
                if (type == 'room') {
                    axios
                        .post(`/api/chat/audibles/${this.auth.id}/toggle/chatroom`, {
                            room_id: id,
                            nv: nv,
                        })
                        .then((response) => {
                            // reassign the auth variable to the response data
                            this.auth = response.data;
                            this.listening = nv;
                        });
                } else if (type == 'target') {
                    axios
                        .post(`/api/chat/audibles/${this.auth.id}/toggle/target`, {
                            target_id: id,
                            nv: nv,
                        })
                        .then((response) => {
                            // reassign the auth variable to the response data
                            this.auth = response.data;
                            this.listening = nv;
                        });
                } else if (type == 'bot') {
                    axios
                        .post(`/api/chat/audibles/${this.auth.id}/toggle/bot`, {
                            bot_id: id,
                            nv: nv,
                        })
                        .then((response) => {
                            // reassign the auth variable to the response data
                            this.auth = response.data;
                            this.listening = nv;
                        });
                }
            }
        },
        leaveRoom(id) {
            if (id != 1) {
                /* Update the users chatroom in the database */
                axios
                    .post(`/api/chat/echoes/${this.auth.id}/delete/chatroom`, {
                        room_id: id,
                    })
                    .then((response) => {
                        // reassign the auth variable to the response data
                        this.auth = response.data;
                        $('#currentChatroom').val('1');
                        this.fetchRooms();
                    });
            }
        },
        leaveTarget(id) {
            if (id != 1) {
                this.target = 0;
                /* Update the users chatroom in the database */
                axios
                    .post(`/api/chat/echoes/${this.auth.id}/delete/target`, {
                        target_id: id,
                    })
                    .then((response) => {
                        // reassign the auth variable to the response data
                        this.auth = response.data;
                        $('#currentChatroom').val('1');
                        this.fetchRooms();
                    });
            }
        },
        changeFullscreen() {
            if (this.fullscreen == 1) {
                this.fullscreen = 0;
                $('#frameBody').css({ height: '92vh', 'min-height': '300px', 'max-height': '590px' });
                $('#frameList').css({ height: 'initial', 'min-height': '300px', 'max-height': '535px' });
                $('#frameHeader').css({ height: 'initial', 'min-height': 'initial', 'max-height': 'initial' });
                $('#frameFooter').css({
                    'padding-top': '10px',
                    height: 'initial',
                    'min-height': 'initial',
                    'max-height': 'initial',
                });
                $('#frameWrap').css({ width: '100%', 'padding-top': '0px' });
            } else {
                this.fullscreen = 1;
                $('#frameBody').css({ height: '70vh', 'min-height': '0px', 'max-height': '70vh' });
                $('#frameList').css({ height: $('#frameBody').height() - $('#frameTabs').height() - 20 + 'px' });
                $('#frameHeader').css({ height: '6vh', 'min-height': '0px', 'max-height': '6vh' });
                $('#frameFooter').css({
                    'padding-top': '0px',
                    height: '24vh',
                    'min-height': '0px',
                    'max-height': '24vh',
                });
                $('#frameWrap').css({ width: '100%', 'padding-top': '5px' });
            }
        },
        changeStatus(status_id) {
            this.status = status_id;
            this.showStatuses = false;
            if (this.auth.chat_status.id !== status_id) {
                /* Update the users chat status in the database */
                axios
                    .post(`/api/chat/user/${this.auth.id}/status`, {
                        status_id: status_id,
                    })
                    .then((response) => {
                        // reassign the auth variable to the response data
                        this.auth = response.data;
                    });
            }
        },
        changeRoom(id) {
            this.forced = false;
            this.frozen = false;
            this.bot = 0;
            this.target = 0;
            this.room = id;
            this.bot_id = null;
            this.receiver_id = null;
            if (this.auth.chatroom.id === id) {
                this.tab = this.auth.chatroom.name;
                this.activeRoom = this.auth.chatroom.name;
                this.fetchMessages();
            } else {
                this.room = id;
                /* Update the users chatroom in the database */
                axios
                    .post(`/api/chat/user/${this.auth.id}/chatroom`, {
                        room_id: id,
                    })
                    .then((response) => {
                        // reassign the auth variable to the response data
                        this.auth = response.data;
                        this.tab = this.auth.chatroom.name;
                        this.activeRoom = this.auth.chatroom.name;
                        this.fetchMessages();
                    });
            }
        },
        changeTarget(id) {
            this.forced = false;
            this.frozen = false;
            if (this.target !== id && id != 0) {
                this.target = id;
                this.fetchPrivateMessages();
            } else {
                this.scrollToBottom(true);
            }
        },
        changeBot(id) {
            this.forced = false;
            this.frozen = false;
            if (this.bot !== id && id != 0) {
                this.bot = id;
                this.bot_id = id;
                this.receiver_id = 1;
                this.fetchBotMessages(this.bot);
            } else {
                this.scrollToBottom(true);
            }
        },
        sortEchoes(obj) {
            let output = obj.sort(function (a, b) {
                const nv1 = '';
                if (a.type == 'room') {
                    nv1 = a.name;
                }
                if (a.type == 'target') {
                    nv1 = a.username;
                }
                if (a.type == 'bot') {
                    nv1 = a.name;
                }
                const nv2 = '';
                if (b.type == 'room') {
                    nv1 = b.name;
                }
                if (b.type == 'target') {
                    nv1 = b.username;
                }
                if (b.type == 'bot') {
                    nv1 = b.name;
                }
                return nv1 - nv2;
            });
            return output;
        },
        startBot() {
            this.forced = false;
            if (this.bot == 9999) {
                this.scrollToBottom(true);
            } else {
                this.tab = '@' + this.helpName;
                this.bot = this.helpId;
                this.bot_id = this.helpId;
                this.receiver_id = 1;

                this.botId = this.helpId;
                this.botName = this.helpName;
                this.botCommand = this.helpCommand;

                this.fetchBotMessages(this.bot);
            }
        },
        playSound() {
            if (window.sounds && window.sounds.hasOwnProperty('alert.mp3')) {
                window.sounds['alert.mp3'].pause;
                window.sounds['alert.mp3'].position = 0;
                window.sounds['alert.mp3'].play();
            }
        },
        handleSound(type, id) {
            let audioState = $('#chatbody').attr('audio');
            if (type == 'room') {
                for (var i = 0; i < this.audibles.length; i++) {
                    if (
                        this.audibles[i].room != null &&
                        parseInt(this.audibles[i].status) == 1 &&
                        parseInt(this.audibles[i].room.id) == parseInt(id)
                    ) {
                        if (this.activeTab == 'room' + id && audioState == 'true') {
                            this.playSound();
                        } else if (this.activeTab != 'room' + id) {
                            this.playSound();
                        }
                    }
                }
            }
            if (type == 'target') {
                for (var i = 0; i < this.audibles.length; i++) {
                    if (
                        this.audibles[i].target != null &&
                        parseInt(this.audibles[i].status) == 1 &&
                        parseInt(this.audibles[i].target.id) == parseInt(id)
                    ) {
                        if (this.activeTab == 'target' + id && audioState == 'true') {
                            this.playSound();
                        } else if (this.activeTab != 'target' + id) {
                            this.playSound();
                        }
                    }
                }
            }
            if (type == 'bot') {
                for (var i = 0; i < this.audibles.length; i++) {
                    if (
                        this.audibles[i].bot != null &&
                        parseInt(this.audibles[i].status) == 1 &&
                        parseInt(this.audibles[i].bot.id) == parseInt(id)
                    ) {
                        if (this.activeTab == 'bot' + id && audioState == 'true') {
                            this.playSound();
                        } else if (this.activeTab != 'bot' + id) {
                            this.playSound();
                        }
                    }
                }
            }
        },
        handleMessage(type, id, message) {
            let audioState = $('#chatbody').attr('audio');
            if (type == 'room') {
                for (var i = 0; i < this.audibles.length; i++) {
                    if (
                        this.audibles[i].room != null &&
                        parseInt(this.audibles[i].status) == 1 &&
                        parseInt(this.audibles[i].room.id) == parseInt(id)
                    ) {
                        if (audioState == 'true') {
                            this.playSound();
                        } else if (this.activeTab != 'room' + id) {
                            this.playSound();
                        }
                    }
                }
            }
            if (type == 'target') {
                for (var i = 0; i < this.audibles.length; i++) {
                    if (
                        this.audibles[i].target != null &&
                        parseInt(this.audibles[i].status) == 1 &&
                        parseInt(this.audibles[i].target.id) == parseInt(id)
                    ) {
                        if (audioState == 'true') {
                            this.playSound();
                        } else if (this.activeTab != 'target' + id) {
                            this.playSound();
                        }
                    }
                }
            }
            if (type == 'bot') {
                for (var i = 0; i < this.audibles.length; i++) {
                    if (
                        this.audibles[i].bot != null &&
                        parseInt(this.audibles[i].status) == 1 &&
                        parseInt(this.audibles[i].bot.id) == parseInt(id)
                    ) {
                        if (audioState == 'true') {
                            this.playSound();
                        } else if (this.activeTab != 'bot' + id) {
                            this.playSound();
                        }
                    }
                }
            }
        },
        handlePing(type, id) {
            if (type == 'room') {
                var match = false;
                for (var i = 0; i < this.pings.length; i++) {
                    if (
                        this.pings[i].hasOwnProperty('type') &&
                        this.pings[i].type == 'room' &&
                        this.pings[i].id == id
                    ) {
                        match = true;
                    }
                }
            }
            if (type == 'target') {
                var match = false;
                for (var i = 0; i < this.pings.length; i++) {
                    if (
                        this.pings[i].hasOwnProperty('type') &&
                        this.pings[i].type == 'target' &&
                        this.pings[i].id == id
                    ) {
                        match = true;
                    }
                }
                if (match != true) {
                    let addon = [];
                    addon['type'] = 'target';
                    addon['id'] = id;
                    addon['count'] = 0;
                    this.pings.push(addon);
                }
                this.handleSound('target', id);
            }
            if (type == 'bot') {
                var match = false;
                for (var i = 0; i < this.pings.length; i++) {
                    if (this.pings[i].hasOwnProperty('type') && this.pings[i].type == 'bot' && this.pings[i].id == id) {
                        match = true;
                    }
                }
                if (match != true) {
                    let addon = [];
                    addon['type'] = 'bot';
                    addon['id'] = id;
                    addon['count'] = 0;
                    this.pings.push(addon);
                }
                this.handleSound('bot', id);
            }
        },
        deletePing(type, id) {
            for (let i = 0; i < this.pings.length; i++) {
                if (this.pings[i].type == type && this.pings[i].id == id) {
                    this.pings.splice(i, 1);
                }
            }
            return false;
        },
        checkPings(type, id) {
            if (type == 'room') {
                let currentRoom = _.find(this.pings, (o) => {
                    if (o.type == 'room' && o.id == id) {
                        return o.id;
                    }
                });
                if (currentRoom) {
                    return true;
                }
            } else if (type == 'target') {
                let currentTarget = _.find(this.pings, (o) => {
                    if (o.type == 'target' && o.id == id) {
                        return o.id;
                    }
                });
                if (currentTarget) {
                    return true;
                }
            } else if (type == 'bot') {
                let currentBot = _.find(this.pings, (o) => {
                    if (o.type == 'bot' && o.id == id) {
                        return o.id;
                    }
                });
                if (currentBot) {
                    return true;
                }
            }
            return false;
        },
        /* User defaults to System user */
        createMessage(message, save = true, user_id = 1, receiver_id = null, bot_id = null) {
            // Prevent Users abusing BBCode size
            const regex = new RegExp(/\[size=[0-9]{3,}\]/);
            if (regex.test(message) == true) return;
            if (this.tab == 'userlist') return;
            axios
                .post('/api/chat/messages', {
                    user_id: user_id,
                    receiver_id: receiver_id,
                    bot_id: bot_id,
                    chatroom_id: this.room,
                    message: message,
                    save: save,
                    targeted: this.target,
                })
                .then((response) => {
                    if (this.activeTab.substring(0, 3) == 'bot' || this.activeTab.substring(0, 6) == 'target') {
                        this.messages.push(response.data.data);
                    }
                    if (this.messages.length > this.config.message_limit) {
                        _.each(this.messages, (m, i) => {
                            if (this.target > 0) {
                                if (m.receiver && m.receiver > 0) {
                                    this.messages.splice(i, 1);
                                    return false;
                                }
                            } else {
                                if (!m.receiver || m.receiver == 0) {
                                    this.messages.splice(i, 1);
                                    return false;
                                }
                            }
                        });
                    }
                });
        },
        scrollToBottom(force = false) {
            let container = $('.messages .list-group');

            if (this.forced != false && force != true && this.frozen) return;

            if (this.scroll || force) {
                container.animate({ scrollTop: container.prop('scrollHeight') }, 0);
            }

            container.scroll(() => {
                let scrollHeight = container.prop('scrollHeight');
                this.scroll = scrollHeight + 9999;
                this.forced = true;
            });
        },
        listenForChatter() {
            this.chatter = window.Echo.private(`chatter.${this.auth.id}`);
            this.chatter.listen('Chatter', (e) => {
                if (e.type == 'echo') {
                    this.echoes = this.sortEchoes(e.echoes);
                } else if (e.type == 'audible') {
                    this.audibles = e.audibles;
                } else if (e.type == 'new.message') {
                    if (this.activeTab.substring(0, 3) != 'bot' && this.activeTab.substring(0, 6) != 'target')
                        return false;
                    if (e.message.bot && e.message.bot.id != this.bot) return false;
                    if (e.message.target && e.message.target.id != this.target) return false;
                    this.messages.push(e.message);
                    if (this.bot && this.bot > 0) {
                        this.handleMessage('bot', this.bot, e.message);
                    } else {
                        this.handleMessage('target', this.target, e.message);
                    }
                } else if (e.type == 'new.bot') {
                    this.messages.push(e.message);
                    this.handleMessage('bot', this.bot);
                } else if (e.type == 'new.ping') {
                    if (e.ping.type == 'bot') {
                        this.handlePing('bot', e.ping.id);
                    } else {
                        this.handlePing('target', e.ping.id);
                    }
                } else if (e.type == 'delete.message') {
                    if (this.target < 1 && this.bot < 1) return false;
                    let msgs = this.messages;
                    let index = msgs.findIndex((msg) => msg.id === e.message.id);
                    this.messages.splice(index, 1);
                } else if (e.type == 'typing') {
                    if (this.target < 1) return false;
                    if (this.activePeer === false) {
                        this.activePeer = e.username;
                    }
                    setTimeout(() => {
                        this.activePeer = false;
                    }, 15000);
                }
            });
        },
        listenForEvents() {
            this.channel
                .here((users) => {
                    this.state.connecting = false;
                    this.users = users;
                })
                .listen('.new.message', (e) => {
                    if (this.activeTab.substring(0, 4) != 'room') return false;
                    this.messages.push(e.message);
                    this.handleMessage('room', this.room, e.message);
                })
                .listen('.new.ping', (e) => {
                    this.handlePing('room', e.ping.id);
                })
                .listen('.edit.message', (e) => {})
                .listen('.delete.message', (e) => {
                    if (this.target > 0 || this.bot > 0) return false;
                    let msgs = this.messages;
                    let index = msgs.findIndex((msg) => msg.id === e.message.id);
                    this.messages.splice(index, 1);
                })
                .listenForWhisper('typing', (e) => {
                    if (this.target > 0 || this.bot > 0) return false;
                    if (this.activePeer === false) {
                        this.activePeer = e;
                    }
                    setTimeout(() => {
                        this.activePeer = false;
                    }, 15000);
                });
        },
        attachAudible() {
            $(window).off('blur');
            $(window).on('blur', function () {
                $('#chatbody').attr('audio', true);
            });
            $(window).off('focus');
            $(window).on('focus', function () {
                $('#chatbody').attr('audio', false);
            });
        },
    },
    created() {
        this.startup = Date.now();
        this.auth = this.user;
        this.activeRoom = this.auth.chatroom.name;
        this.activeTarget = '';
        this.activeBot = '';
        this.fetchRooms();
        this.fetchBots();
        this.fetchStatuses();
        this.fetchAudibles();
        this.fetchEchoes();
        this.listenForChatter();
        this.attachAudible();
        this.scrollToBottom(true);
    },
};
</script>
