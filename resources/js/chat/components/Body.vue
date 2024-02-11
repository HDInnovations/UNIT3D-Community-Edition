<script>
import ChatForm from "./ChatForm.vue";
import ChatMessages from "./ChatMessages.vue";
import ChatUserList from "./ChatUserList.vue";
import { state } from "../state";
import { changeStatus, createMessage, isTyping } from "../actions";
import { findIndex, filter } from "lodash";

export default {
  name: "Body",
  methods: { isTyping, changeStatus, createMessage },
  computed: {
    state() {
      return state
    },
    statusColor() {
      if (state.statuses.length > 0) {
        let i = findIndex(state.statuses, (o) => {
          return o.id === state.status;
        });
        return state.statuses[i].color;
      }
      return '';
    },
  },
  components: { ChatUserList, ChatMessages, ChatForm },
};
</script>

<template>
  <div
    class="chatbox__chatroom"
    v-if="!state.connecting"
  >
    <chat-messages
      v-if="state.tab !== ''"
      @pm-sent="(o) => createMessage(o.message, o.save, o.user_id, o.receiver_id, o.bot_id)"
      :messages="state.messages"
    >
    </chat-messages>
    <chat-user-list
      v-if="state.tab === 'userlist'"
      @pm-sent="(o) => createMessage(o.message, o.save, o.user_id, o.receiver_id, o.bot_id)"
      :users="state.users"
    >
    </chat-user-list>
    <section
      class="chatroom__whispers"
      v-if="state.showWhispers"
    >
        <span
          v-if="
            state.target < 1 &&
            state.bot < 1 &&
            state.activePeer &&
            state.activePeer.username !== ''
          "
        >
          {{ state.activePeer ? state.activePeer.username + ' is typing ...' : '*'  }}
        </span>
    </section>
    <chat-form
      @message-sent="(o) => createMessage(o.message, o.save, o.user_id, o.receiver_id, o.bot_id)"
      @typing="isTyping"
    >
    </chat-form>
  </div>
</template>
