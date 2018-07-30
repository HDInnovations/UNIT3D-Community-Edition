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

                <div v-if="showDevMsg">
                    <h2 class="text-center text-red text-bold">Chat Box Is Currently In Beta</h2>
                    <p class="text-center">
                        Please understand that <strong>Beta</strong> refers to software undergoing testing.
                        Is released to a certain group of peers for real world testing.
                    </p>
                    <p class="text-center">
                        We are working hard to address all your concerns and issues.
                    </p>
                    <p class="text-center">
                        Please be patient and be as detailed as possible when describing an issue you may be having!
                    </p>
                    <p class="text-center">
                        <button @click="showDevMsg = false" class="btn btn-danger">Hide</button>
                    </p>
                </div>

                <div id="frame">
                    <div class="content">
                        <div class="text-center">
                            <h4 v-if="state.connecting" class='text-red'>Connecting ...</h4>
                            <h4 v-else class='text-green'>Connected with {{users.length}} users</h4>
                        </div>

                        <chat-messages v-if="!state.connecting" :messages="messages"></chat-messages>
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
        state: {
          connecting: true
        },
        auth: {},
        statuses: [],
        status: 0,
        showStatuses: false,
        chatrooms: [],
        messages: [],
        users: [],
        room: 0,
        scroll: true,
        channel: null,
        config: {},
        activePeer: false,

        /* Developer Settings */
        showDevMsg: false,
      }
    },
    watch: {
      chatrooms () {
        this.changeRoom(this.auth.chatroom.id)
      },
      statuses () {
        this.changeStatus(this.auth.chat_status.id)
      },
      room (newVal, oldVal) {
        window.Echo.leave(`chatroom.${oldVal}`)

        this.channel = window.Echo.join(`chatroom.${newVal}`)
        this.listenForEvents()
      },
    },
    computed: {
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

          this.fetchConfiguration()
        })
      },

      fetchConfiguration () {
        axios.get(`/api/chat/config`).then(response => {
          this.config = response.data
        })
      },

      changeRoom (id) {
        this.room = id

        if (this.auth.chatroom.id !== id) {
          /* Update the users chatroom in the database */
          axios.post(`/api/chat/user/${this.auth.id}/chatroom`, {
            'room_id': id
          }).then(response => {
            // reassign the auth variable to the response data
            this.auth = response.data

            this.fetchMessages()
          })
        } else {
          this.fetchMessages()
        }
      },

      fetchMessages () {
        axios.get(`/api/chat/messages/${this.room}`).then(response => {
          this.messages = _.reverse(response.data.data)
          this.scrollToBottom(true)

          this.state.connecting = false
        })
      },

      fetchStatuses () {
        axios.get('/api/chat/statuses').then(response => {
          this.statuses = response.data
        })
      },

      changeStatus (status_id) {
        this.status = status_id
        this.showStatuses = false

        if (this.auth.chat_status.id !== status_id) {

          /* Update the users chat status in the database */
          axios.post(`/api/chat/user/${this.auth.id}/status`, {
            'status_id': status_id
          }).then(response => {
            // reassign the auth variable to the response data
            this.auth = response.data

            /* Add system message */
            this.createMessage(
              `[color=#999999][size=13]Updated their status to [b]${this.auth.chat_status.name}[/b][/size][/color]`,
              false,
              this.auth.id
            )

          })
        }
      },

      /* User defaults to System user */
      createMessage (message, save = true, user_id = 1) {
        axios.post('/api/chat/messages', {
          'user_id': user_id,
          'chatroom_id': this.room,
          'message': message,
          'save': save
        })
      },

      scrollToBottom (force = false) {
        let container = $('.messages .list-group')

        if (this.scroll || force) {
          container.animate({scrollTop: container.prop('scrollHeight')}, 0)
        }

        container.scroll(() => {
          this.scroll = false

          let scrollTop = container.scrollTop() + container.prop('clientHeight')
          let scrollHeight = container.prop('scrollHeight')

          this.scroll = scrollTop >= scrollHeight - 50
        })
      },

      listenForEvents () {
        this.channel
          .here(users => {
            this.users = users
            this.state.connecting = false

            setInterval(() => {
              this.scrollToBottom()
            }, 100)
          })
          .joining(user => {
            // this.createMessage(`${user.username} has JOINED the chat ...`)
          })
          .leaving(user => {
            // this.createMessage(`${user.username} has LEFT the chat ...`)
          })
          .listen('.new.message', e => {
            let count = this.messages.push(e.message)

            if (count > this.config.message_limit) {
              this.messages.splice(0, 1)
            }

            this.scrollToBottom(true)
          })
          .listen('.edit.message', e => {

          })
          .listen('.delete.message', e => {
            let msgs = this.messages
            let index = msgs.findIndex(msg => msg.id === e.message.id)

            this.messages.splice(index, 1)
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
    },
  }
</script>
