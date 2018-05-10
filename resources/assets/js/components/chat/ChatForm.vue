<template>
    <div class="message-input">
        <div class="wrap">

            <div class="info">
                <span class="badge-extra">
                    Tap <strong>ALT</strong> to toggle multi-line (<strong>{{multiline ? 'On' : 'Off'}}</strong>)
                </span>

                <span class="badge-extra">
                    Type <strong>:</strong> for emoji
                </span>

                <span class="badge-extra">
                    BBcode Allowed
                </span>
            </div>

            <textarea id="chat-message"
                      name="message"
                      placeholder="Write your message..."
                      cols="30"
                      rows="5">

            </textarea>
        </div>
    </div>
</template>
<style lang="scss" scoped>
    .info {
        .badge-extra {
            margin-bottom: .5em;
        }
    }
</style>
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
      keyup (e) {

        // Alt
        if (e.which === 18) {
          this.multiline = !this.multiline
        }

        // Enter
        if (e.which === 13 && !this.multiline) {
          this.sendMessage()
        }

      },
      keydown (e) {

      },
      sendMessage () {

        let msg = this.editor.bbcode().trim()

        if (msg !== null && msg !== '') {

          this.$emit('message-sent', {
            message: msg,
            save: true,
            user_id: this.user.id,
          })

          this.input.html('')
        }

      }
    },

    mounted () {
      this.editor = $('#chat-message').wysibb()

      // Initialize emojis
      emoji.textcomplete()

      this.input = $('.wysibb-body')

      this.input.keyup(this.keyup)
      this.input.keydown(this.keydown)

    }
  }
</script>