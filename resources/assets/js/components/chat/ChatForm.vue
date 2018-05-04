<template>
    <div class="message-input">
        <div class="wrap">
            <textarea id="chat-message"
                      name="message"
                      placeholder="Write your message..."
                      cols="30"
                      rows="2">

            </textarea>
        </div>
    </div>
</template>
<script>
  export default {

    props: ['user'],

    data () {
      return {
        editor: null,
        input: null
      }
    },

    methods: {
      sendMessage (e) {

        if (e.which === 13 && e.which !== 16) {
          let msg = this.editor.bbcode()

          if (msg !== null && msg !== '') {

            this.$emit('message-sent', {
              message: msg,
              broadcast: true,
              save: true,
              user_id: this.user.id,
            })

            this.input.html('')
          }
        }
      }
    },

    mounted () {
      this.editor = $('#chat-message').wysibb()
      this.input = $('.wysibb-body')

      this.input.keyup(this.sendMessage)
    }
  }
</script>