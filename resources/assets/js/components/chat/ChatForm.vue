<template>
    <div class="message-input">
        <div class="wrap">
            <span class="badge-extra">
                Tap <strong>ALT</strong> to toggle multi-line (<strong>{{multiline ? 'On' : 'Off'}}</strong>)
            </span>

            <span class="badge-extra">
                Type <strong>:</strong> for emoji
            </span>

            <span class="badge-extra">
                BBcode Allowed
            </span>

            <textarea id="chat-message"
                      name="message"
                      placeholder="Write your message..."
                      cols="30"
                      rows="5">

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
        input: null,
        multiline: false
      }
    },

    methods: {
      sendMessage (e) {

        if (e.which === 18) {
          this.multiline = !this.multiline
        }

        if (e.which === 13 && !this.multiline) {
          let msg = this.editor.bbcode()

          if (msg !== null && msg !== '') {

            this.$emit('message-sent', {
              message: msg,
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

      // Initialize emojis
      emoji.textcomplete()

      this.input = $('.wysibb-body')

      this.input.keyup(this.sendMessage)

    }
  }
</script>