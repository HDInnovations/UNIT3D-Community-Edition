<template>
  <form class="form chatroom__new-message">
    <p class="form__group">
        <textarea
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

export default {
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
    this.editor = document.getElementById('chatbox__messages-create').value;
    this.input = document.getElementById('chatbox__messages-create');
    this.input.addEventListener("keyup", this.keyup);
    this.input.addEventListener("keydown", this.keydown);
  },
};
</script>