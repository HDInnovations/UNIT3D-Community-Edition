<template>
  <section
      id="chatbody"
      class="panelV2 chatbox"
      :class="this.fullscreen && `chatbox--fullscreen`"
      audio="false"
  >
    <header class="panel__header" id="chatbox_header">
      <h2 class="panel__heading">
        <i class="fas fa-comment-dots"></i>
        Chatbox v3.0
      </h2>
      <div class="panel__actions">
        <div class="panel__action">
          <button
            class="form__button form__button--text"
            @click.prevent="startBot()"
          >
            <i class="fa fa-robot"></i>
            {{ helpName }}
          </button>
        </div>
        <div class="panel__action">
          <button
            class="form__button form__button--text"
            v-if="target < 1 && bot < 1"
            @click.prevent="changeTab('list', 'userlist')"
          >
            <i class="fa fa-users"></i>
            Users: {{ users.length }}
          </button>
        </div>
        <div class="panel__action">
          <button
            class="form__button form__standard-icon-button form__standard-icon-button--skinny"
            v-if="
              room &&
              room > 0 &&
              bot < 1 &&
              target < 1 &&
              tab != 'userlist'
            "
            @click.prevent="changeAudible('room', room, listening ? 0 : 1)"
            :style="`color: ${listening ? 'rgb(0,102,0)' : 'rgb(204,0,0)'}`"
          >
            <i :class="listening ? 'fa fa-bell' : 'fa fa-bell-slash'"></i>
          </button>
          <button
            class="form__button form__standard-icon-button form__standard-icon-button--skinny"
            v-if="bot && bot >= 1 && target < 1 && tab != 'userlist'"
            @click.prevent="changeAudible('bot', bot, listening ? 0 : 1)"
            :style="`color: ${listening ? 'rgb(0,102,0)' : 'rgb(204,0,0)'}`"
          >
            <i :class="listening ? 'fa fa-bell' : 'fa fa-bell-slash'"></i>
          </button>
          <button
            class="form__button form__standard-icon-button form__standard-icon-button--skinny"
            v-if="target && target >= 1 && bot < 1 && tab != 'userlist'"
            @click.prevent="changeAudible('target', target, listening ? 0 : 1)"
            :style="`color: ${listening ? 'rgb(0,102,0)' : 'rgb(204,0,0)'}`"
          >
            <i :class="listening ? 'fa fa-bell' : 'fa fa-bell-slash'"></i>
          </button>
        </div>
        <div class="panel__action">
          <button
            class="form__button form__standard-icon-button form__standard-icon-button--skinny"
            title="Toggle typing notifications"
            @click.prevent="changeWhispers()"
            :style="`color: ${this.showWhispers ? 'rgb(0,102,0)' : 'rgb(204,0,0)'}`"
          >
            <i :class="this.showWhispers ? `fas fa-keyboard` : `fa fa-keyboard`"></i>
          </button>
        </div>
        <div class="panel__action">
          <chatrooms-dropdown
            :current="auth.chatroom.id"
            :chatrooms="chatrooms"
            @changedRoom="changeRoom"
          >
          </chatrooms-dropdown>
        </div>
        <div class="panel__action">
          <chatstatuses-dropdown
            :current="auth.chat_status.id"
            :chatstatuses="statuses"
            @changedStatus="changeStatus"
          >
          </chatstatuses-dropdown>
        </div>
        <div class="panel__action">
          <button
              id="panel-fullscreen"
              :class="`form__button form__standard-icon-button`"
              title="Toggle Fullscreen"
              @click.prevent="changeFullscreen()"
          >
            <i
              :class="this.fullscreen ? `fas fa-compress` : `fas fa-expand`"
            ></i>
          </button>
        </div>
      </div>
    </header>
    <menu id="chatbox_tabs" class="panel__tabs" role="tablist" v-if="boot == 1">
      <li
        v-for="echo in echoes"
        v-if="echo.room && echo.room.name.length > 0"
        class="panel__tab chatbox__tab"
        role="tab"
        :class="tab != '' && tab === echo.room.name && 'panel__tab--active'"
        @click.prevent="changeTab('room', echo.room.id)"
      >
        <i
          class="fa fa-comment"
          :class="checkPings('room', echo.room.id) ? 'fa-beat text-success' : 'text-danger'"
        ></i>
        {{ echo.room.name }}
        <button
          v-if="tab != '' && tab === echo.room.name"
          class="chatbox__tab-delete-button"
          @click.prevent="leaveRoom(room)"
        >
          <i class="fa fa-times chatbox__tab-delete-icon"></i>
        </button>
      </li>
      <li
        v-for="echo in echoes"
        v-if="echo.target && echo.target.id >= 3 && echo.target.username.length > 0"
        class="panel__tab chatbox__tab"
        :class="target >= 3 && target === echo.target.id && 'panel__tab--active'"
        role="tab"
        @click.prevent="changeTab('target', echo.target.id)"
      >
        <i
          class="fa fa-comment"
          :class="checkPings('target', echo.target.id) ? 'fa-beat text-success' : 'text-danger'"
        ></i>
        @{{ echo.target.username }}
        <button
          v-if="target >= 3 && target === echo.target.id"
          class="chatbox__tab-delete-button"
          @click.prevent="leaveTarget(target)"
        >
          <i class="fa fa-times chatbox__tab-delete-icon"></i>
        </button>
      </li>
      <li
        v-for="echo in echoes"
        v-if="echo.bot && echo.bot.id >= 1 && echo.bot.name.length > 0"
        class="panel__tab chatbox__tab"
        :class="bot > 0 && bot === echo.bot.id && 'panel__tab--active'"
        role="tab"
        @click.prevent="changeTab('bot', echo.bot.id)"
      >
        <i
          class="fa fa-comment"
          :class="checkPings('bot', echo.bot.id) ? 'fa-beat text-success' : 'text-danger'"
        ></i>
        @{{ echo.bot.name }}
        <button
          v-if="bot > 0 && bot === echo.bot.id"
          class="chatbox__tab-delete-button"
          @click.prevent="leaveBot(bot)"
        >
          <i class="fa fa-times chatbox__tab-delete-icon"></i>
        </button>
      </li>
    </menu>
    <div
      class="chatbox__chatroom"
      v-if="!state.connecting"
    >
      <chat-messages
        v-if="tab != ''"
        @pm-sent="(o) => createMessage(o.message, o.save, o.user_id, o.receiver_id, o.bot_id)"
        :messages="msgs"
      >
      </chat-messages>
      <chat-user-list
        v-if="tab === 'userlist'"
        @pm-sent="(o) => createMessage(o.message, o.save, o.user_id, o.receiver_id, o.bot_id)"
        :users="users"
      >
      </chat-user-list>
      <section
        class="chatroom__whispers"
        v-if="showWhispers"
      >
        <span
          v-if="
            target < 1 &&
            bot < 1 &&
            activePeer &&
            activePeer.username != ''
          "
        >
          {{ activePeer ? activePeer.username + ' is typing ...' : '*'  }}
        </span>
      </section>
      <chat-form
        @changedStatus="changeStatus"
        @message-sent="(o) => createMessage(o.message, o.save, o.user_id, o.receiver_id, o.bot_id)"
        @typing="isTyping"
      >
      </chat-form>
    </div>
  </section>
</template>
<script>
import ChatroomsDropdown from './ChatroomsDropdown.vue';
import ChatMessages from './ChatMessages.vue';
import ChatForm from './ChatForm.vue';
import ChatUserList from './ChatUserList.vue';
import ChatstatusesDropdown from "./ChatstatusesDropdown.vue";
import axios from "axios"

export default {
  props: {
    user: {
      type: Object,
      required: true,
    },
  },
  components: {
    ChatstatusesDropdown,
    ChatroomsDropdown,
    ChatMessages,
    ChatForm,
    ChatUserList,
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
      showWhispers: 1,
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
      } else if (typeVal == 'list') {
        this.tab = newVal;
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
        this.state.connecting = false;
      });
    },
    fetchPrivateMessages() {
      axios.get(`/api/chat/private/messages/${this.target}`).then((response) => {
        this.messages = _.reverse(response.data.data);
        this.state.connecting = false;
      });
    },
    fetchMessages() {
      axios.get(`/api/chat/messages/${this.room}`).then((response) => {
        this.messages = _.reverse(response.data.data);
        this.state.connecting = false;
      });
    },
    fetchStatuses() {
      axios.get('/api/chat/statuses').then((response) => {
        this.statuses = response.data;
      });
    },
    forceMessage(name) {
      document.getElementById('chatbox__messages-create').value = '/msg ' + name + ' ';
    },
    forceGift(name) {
      document.getElementById('chatbox__messages-create').value = '/gift ' + name + ' ';
    },
    leaveBot(id) {
      if (id > 0) {
        this.bot = 0;
        this.botName = '';
        this.botId = '';
        /* Update the users bot in the database */
        axios
            .post(`/api/chat/echoes/delete/bot`, {
              bot_id: id,
            })
            .then((response) => {
              // reassign the auth variable to the response data
              this.auth = response.data;
              document.getElementById('currentChatroom').value = '1';
              this.fetchRooms();
            });
      }
    },
    toggleAudible(type, id, nv) {
      if (id != 0) {
        if (type == 'room') {
          axios
              .post(`/api/chat/audibles/toggle/chatroom`, {
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
              .post(`/api/chat/audibles/toggle/target`, {
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
              .post(`/api/chat/audibles/toggle/bot`, {
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
            .post(`/api/chat/echoes/delete/chatroom`, {
              room_id: id,
            })
            .then((response) => {
              // reassign the auth variable to the response data
              this.auth = response.data;
              document.getElementById('currentChatroom').value = '1';
              this.fetchRooms();
            });
      }
    },
    leaveTarget(id) {
      if (id != 1) {
        this.target = 0;
        /* Update the users chatroom in the database */
        axios
            .post(`/api/chat/echoes/delete/target`, {
              target_id: id,
            })
            .then((response) => {
              // reassign the auth variable to the response data
              this.auth = response.data;
              document.getElementById('currentChatroom').value = '1';
              this.fetchRooms();
            });
      }
    },
    changeFullscreen() {
      this.fullscreen = !this.fullscreen
    },
    changeWhispers() {
      this.showWhispers = !this.showWhispers
    },
    changeStatus(status_id) {
      this.status = status_id;
      this.showStatuses = false;
      if (this.auth.chat_status.id !== status_id) {
        /* Update the users chat status in the database */
        axios
            .post(`/api/chat/user/status`, {
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
            .post(`/api/chat/user/chatroom`, {
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
      let audioState = document.getElementById('chatbody').getAttribute('audio');
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
      let audioState = document.getElementById('chatbody').getAttribute('audio');
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
      window.addEventListener('blur', function () {
        document.getElementById('chatbody').setAttribute('audio', true);
      });
      window.addEventListener('focus', function () {
        document.getElementById('chatbody').setAttribute('audio', false);
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
  },
};
</script>