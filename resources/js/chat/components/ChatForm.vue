<template>
  <form class="form chatroom__new-message">
    <p class="form__group">
        <textarea
          @keyup="keyup"
          @keydown.enter.prevent="keydown"
          v-model="state.input"
          id="chatbox__messages-create"
          class="form__textarea"
          name="message"
          placeholder=" "
          send="true"
        ></textarea>
      <label class="form__label form__label--floating" for="chatbox__messages-create">
        Write your message...
      </label>
    </p>
  </form>
</template>
<script>

import { state } from "../state";

export default {
  emits: ['typing', 'message-sent'],
  computed: {
    state() {
      return state
    },
    receiver_id() {
      return state.receiver_id;
    },
    bot_id() {
      return state.bot_id;
    },
  },
  methods: {
    keyup() {
      this.$emit('typing', state.user);
    },
    keydown(e) {
      if (!e.shiftKey) {
        this.sendMessage();
      }
    },
    sendMessage() {
      let msg = state.input.trim();
      if (msg !== null && msg !== '') {
        this.$emit('message-sent', {
          message: msg,
          save: true,
          user_id: state.auth.id,
          receiver_id: state.receiver_id,
          bot_id: state.bot_id,
        });
        state.input = '';
      }
    },
  }
};
</script>