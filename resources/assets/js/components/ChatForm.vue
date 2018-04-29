<template>
    <div class="message-input">
        <div class="wrap">

            <input id="btn-input" type="text" name="message" class="form-control" placeholder="Write your message..."
                   v-model="newMessage"
                   @keyup.enter="sendMessage">

            <button class="submit" id="btn-chat" @click="sendMessage">
                <i class="fa fa-paper-plane" aria-hidden="true"></i>
            </button>
        </div>
    </div>
</template>

<script>
  export default {

    props: ['user'],

    data() {
      return {
        newMessage: ''
      }
    },

    methods: {
      sendMessage() {
        this.$emit('messagesent', {
          user: this.user,
          message: this.newMessage
        });

        this.newMessage = ''
      }
    }
  }
</script>