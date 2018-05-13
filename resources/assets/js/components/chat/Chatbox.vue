<template>
    <div class="col-md-10 col-sm-10 col-md-offset-1 chatbox">
        <div class="clearfix visible-sm-block"></div>
        <div class="panel panel-chat">
            <div class="panel-heading">
                <h4>
                    Chatbox 2.1 Beta
                    ( <a target="_blank"
                         href="https://trello.com/c/tzHOvz5h/16-chat-20-shoutbox-replacement">Roadmap</a> )
                </h4>
            </div>

            <div class="panel-body">
                <!--<h2 class="text-center text-red text-bold">Chat Box Is Currently Offline For Maintenance</h2>-->
                <div id="frame">
                    <div class="content">
                        <chat-messages :messages="messages"></chat-messages>
                    </div>
                </div>
            </div>
            <div class="panel-footer">
                <div class="typing">
                    <span class="badge-extra" v-if="activePeer">{{ activePeer.username }} is typing ...</span>
                </div>

                <chat-form
                        @changedStatus="changeStatus"
                        @message-sent="(o) => createMessage(o.message, o.save, o.user_id)"
                        @typing="isTyping"
                        :user="auth"></chat-form>
            </div>
        </div>
    </div>
</template>
<style lang="scss">
    .chatbox {
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

        .slide-fade-enter-active {
            transition: all .3s ease;
        }

        .slide-fade-leave-active {
            transition: all .3s cubic-bezier(1.0, 0.5, 0.8, 1.0);
        }

        .slide-fade-enter, .slide-fade-leave-to {
            transform: translateY(10px);
            opacity: 0;
        }
    }
</style>
<script>
  import ChatroomsDropdown from './ChatroomsDropdown'
  import ChatMessages from './ChatMessages'
  import ChatForm from './ChatForm'

  export default {
    props: {
      user: {
        type: Object,
        required: true,
      }
    },
    components: {
      ChatroomsDropdown,
      ChatMessages,
      ChatForm
    },
    data () {
      return {
        auth: {},
        statuses: [],
        status: 0,
        showStatuses: false,
        chatrooms: [],
        room: 0,
        scroll: true,
        channel: null,
        limits: {},
        activePeer: false
      }
    },
    watch: {
      room (newVal, oldVal) {
        window.Echo.leave(`chatroom.${oldVal}`)

        this.channel = window.Echo.join(`chatroom.${newVal}`)
        this.listenForEvents()

        if (this.auth.chatroom.id !== newVal) {
          /* Update the users chatroom in the database */
          axios.post(`/api/chat/user/${this.auth.id}/chatroom`, {
            'room_id': newVal
          }).then(response => {
            // reassign the auth variable to the response data
            this.auth = response.data
          })
        }

        this.fetchMessages()
      },
      status (newVal, oldVal) {
        if (this.auth.chat_status.id !== newVal) {
          /* Update the users chat status in the database */
          axios.post(`/api/chat/user/${this.auth.id}/status`, {
            'status_id': newVal
          }).then(response => {
            // reassign the auth variable to the response data
            this.auth = response.data

            /* Add system message */
            this.createMessage(
              `[url=/${this.auth.username}.${this.auth.id}]${this.auth.username}[/url] has updated their status to [b]${this.auth.chat_status.name}[/b]`
            )

          })
        }
      }
    },
    computed: {
      messages () {
        if (this.chatrooms.length > 0) {
          return this.chatrooms[this.room_index].messages
        }

        return []
      },
      room_index () {
        if (this.room !== 0) {
          return this.room - 1
        }

        return 0
      },
      last_id () {
        if (this.messages > 0) {
          return this.messages[m.length - 1].id
        }

        return 0
      },
      statusColor () {
        if (this.statuses.length > 0) {
          let i = _.findIndex(this.statuses, (o) => {
            return o.id === this.status
          })

          return this.statuses[i].color
        }

        return ''
      }
    },
    methods: {
      isTyping (e) {
        this.channel.whisper('typing', {
          username: e.username
        })
      },

      fetchRooms () {
        axios.get('/api/chat/rooms').then(response => {
          this.chatrooms = response.data.data

          this.changeRoom(this.auth.chatroom.id)
        })
      },

      fetchLimits() {
        axios.get(`/api/chat/rooms/${this.auth.chatroom.id}/limits`).then(response => {
          this.limits = response.data
        })
      },

      changeRoom (id) {
        this.room = id
      },

      fetchMessages () {
        axios.get(`/api/chat/messages/${this.room}`).then(response => {
          this.chatrooms[this.room_index].messages = response.data.data
          this.scrollToBottom(true)
        })
      },

      fetchStatuses () {
        axios.get('/api/chat/statuses').then(response => {
          this.statuses = response.data

          this.changeStatus(this.auth.chat_status.id)
        })
      },

      changeStatus (status_id) {
        this.status = status_id
        this.showStatuses = false
      },

      /* User defaults to System user */
      createMessage (message, save = true, user_id = 1) {
        axios.post('/api/chat/messages', {
          'user_id': user_id,
          'chatroom_id': this.room,
          'message': message,
          'save': save,
        })
      },

      scrollToBottom (force = false) {
        let messages = $('.messages .list-group')

        if (this.scroll || force) {
          messages.animate({scrollTop: messages.prop('scrollHeight')}, 0)
        }

        messages.scroll(() => {
          this.scroll = false

          let scrollTop = messages.scrollTop() + messages.prop('clientHeight')
          let scrollHeight = messages.prop('scrollHeight')

          this.scroll = scrollTop >= scrollHeight - 30
        })
      },

      listenForEvents () {
        this.channel
          .here(users => {
            console.log('CONNECTED TO CHAT ...')
          })
          .joining(user => {
            // this.createMessage(`${user.username} has JOINED the chat ...`)
          })
          .leaving(user => {
            // this.createMessage(`${user.username} has LEFT the chat ...`)
          })
          .listen('.new.message', e => {
            let count = this.chatrooms[this.room_index].messages.push(e.message)

            if (count > this.limits.max_messages) {
              this.chatrooms[this.room_index].messages.splice(0, 1)
            }

            this.scrollToBottom(true)
          })
          .listen('.edit.message', e => {

          })
          .listen('.delete.message', e => {
            let msgs = this.chatrooms[this.room_index].messages
            let index = msgs.findIndex(msg => msg.id === e.message.id)

            this.chatrooms[this.room_index].messages.splice(index, 1)
          })
          .listenForWhisper('typing', e => {
            if (this.activePeer === false) {
              this.activePeer = e
            }

            setTimeout(() => {
              this.activePeer = false
            }, 15000)
          })
      }
    },
    created () {
      this.auth = this.user

      this.fetchRooms()
      this.fetchStatuses()

      setTimeout(() => {
        this.scrollToBottom(true)
      }, 700)

      setInterval(() => {
        this.scrollToBottom()
      }, 100)
    },
  }
</script>
