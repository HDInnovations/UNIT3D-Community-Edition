<template>
  <div class="message-input">
    <div class="wrap" id="frameWrap">
      <div class="width-table" style="margin: auto; padding-bottom: 5px; height: 22px">
        <div class="width-75" style="padding: 0; height: 22px">
          <div
              style="padding-left: 0; margin-left: 0; padding-bottom: 0; margin-bottom: 0"
              v-if="$parent.bot > 0 && $parent.activeTab.substr(0, 3) == 'bot'"
          >
                        <span class="badge-extra" style="margin-left: 0; padding-left: 5px; margin-bottom: 0"
                        >{{ $parent.botName }} can accept messages from any tab if you type:
                            <strong>/{{ $parent.botCommand }} help</strong></span
                        >
          </div>
          <div
              class="typing pull-left"
              v-if="
                            $parent.target < 1 &&
                            $parent.bot < 1 &&
                            $parent.activePeer &&
                            $parent.activePeer.username != ''
                        "
              style="padding-left: 0; margin-left: 0; padding-bottom: 0; margin-bottom: 0"
          >
                        <span class="badge-extra" style="margin-left: 0; padding-left: 5px; margin-bottom: 0">{{
                            $parent.activePeer ? $parent.activePeer.username + ' is typing ...' : '*'
                          }}</span>
          </div>
          <div v-if="$parent.tab != 'userlist'" style="margin-right: 5px; display: inline-block">
            <span>Audio: </span>
            <i
                v-if="
                                $parent.room &&
                                $parent.room > 0 &&
                                $parent.bot < 1 &&
                                $parent.target < 1 &&
                                $parent.tab != 'userlist'
                            "
                @click.prevent="$parent.changeAudible('room', $parent.room, $parent.listening ? 0 : 1)"
                :class="$parent.listening ? 'fa fa-bell pointee' : 'fa fa-bell-slash pointee'"
                :style="`color: ${$parent.listening ? 'rgb(0,102,0)' : 'rgb(204,0,0)'}`"
            ></i>

            <i
                v-if="$parent.bot && $parent.bot >= 1 && $parent.target < 1 && $parent.tab != 'userlist'"
                @click.prevent="$parent.changeAudible('bot', $parent.bot, $parent.listening ? 0 : 1)"
                :class="$parent.listening ? 'fa fa-bell pointee' : 'fa fa-bell-slash pointee'"
                :style="`color: ${$parent.listening ? 'rgb(0,102,0)' : 'rgb(204,0,0)'}`"
            ></i>

            <i
                v-if="$parent.target && $parent.target >= 1 && $parent.bot < 1 && $parent.tab != 'userlist'"
                @click.prevent="$parent.changeAudible('target', $parent.target, $parent.listening ? 0 : 1)"
                :class="$parent.listening ? 'fa fa-bell pointee' : 'fa fa-bell-slash pointee'"
                :style="`color: ${$parent.listening ? 'rgb(0,102,0)' : 'rgb(204,0,0)'}`"
            ></i>
          </div>
          <div style="margin-right: 5px; display: inline-block">
            <span style="margin-right: 5px">Status: </span>
            <i
                v-for="status in $parent.statuses"
                @click="$emit('changedStatus', status.id)"
                :class="status.icon ? status.icon + ' pointee mr-5' : 'fa fa-dot-circle-o pointee mr-5'"
                :style="`color: ${status.color}`"
            ></i>
            <span>
                            <chatrooms-dropdown
                                :current="user.chatroom.id"
                                :chatrooms="$parent.chatrooms"
                                class="pull-right"
                                @changedRoom="$parent.changeRoom"
                            >
                            </chatrooms-dropdown>
                        </span>
          </div>
        </div>
      </div>
    </div>
    <div class="row" style="margin: auto">
      <div class="chatbox__textarea-container form__group">
            <textarea
                id="chat-message"
                class="chatbox__textarea form__textarea"
                name="message"
                placeholder=" "
                send="true"
            ></textarea>
        <label class="form__label form__label--floating">Write your message...</label>
      </div>
    </div>
  </div>
</template>
<style lang="scss" scoped>
.col-md-4,
.col-md-6,
.col-md-12 {
  padding: 0;
}
.pointee {
  cursor: pointer;
}
.mr-5 {
  margin-right: 5px;
}
.info {
  .badge-extra {
    width: auto;
    margin: 0 8px 0 0;
  }
  i {
    &.fa {
      &:hover {
        cursor: pointer;
      }
    }
  }
}
</style>
<script>
import ChatroomsDropdown from './ChatroomsDropdown';

export default {
  components: {
    ChatroomsDropdown,
  },
  data() {
    return {
      user: null,
      editor: null,
      input: null,
    };
  },
  computed: {
    receiver_id() {
      return this.$parent.receiver_id;
    },
    bot_id() {
      return this.$parent.bot_id;
    },
  },
  methods: {
    keyup(e) {
      this.$emit('typing', this.user);
    },
    keydown(e) {
      if (e.keyCode === 13 && !e.shiftKey) {
        e.preventDefault();
        this.sendMessage();
      }
    },
    sendMessage() {
      let msg = this.input.value = this.input.value.trim();

      if (msg !== null && msg !== '') {
        this.$emit('message-sent', {
          message: msg,
          save: true,
          user_id: this.user.id,
          receiver_id: this.receiver_id,
          bot_id: this.bot_id,
        });

        this.input.value = '';
      }
    },
  },
  created() {
    this.user = this.$parent.auth;
  },
  mounted() {
    this.editor = document.getElementById('chat-message').value;
    this.input = document.getElementById('chat-message');
    this.input.addEventListener("keyup", this.keyup);
    this.input.addEventListener("keydown", this.keydown);
  },
};
</script>