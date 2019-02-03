<template>
    <div class="col-md-10 col-sm-10 col-md-offset-1 chatbox">
        <div class="clearfix visible-sm-block"></div>
        <div class="panel panel-chat">
            <div class="panel-heading">
                <h4>
                    Chatbox 2.5 Beta
                    ( <a target="_blank"
                         href="https://trello.com/c/tzHOvz5h/16-chat-20-shoutbox-replacement">Roadmap</a> )
                </h4>
            </div>

            <div class="panel-body">
                <div id="frame">
                    <div class="content">
                        <ul role="tablist" class="nav nav-tabs mb-5">
                            <li :class="tab === 'chatbox' ? 'active' : null">
                                <a href="" role="tab" @click.prevent="tab = 'chatbox'">
                                    <i class="fa fa-comments text-blue"></i> Chatbox
                                </a>
                            </li>
                            <li :class="tab === 'userlist' ? 'active' : null">
                                <a href="" role="tab" @click.prevent="tab = 'userlist'">
                                    <i class="fa fa-users text-success"></i> Active Users ({{users.length}})
                                </a>
                            </li>
                            <li v-for="(value, username) in pms" v-if="value.length > 0"
                                :class="tab === username ? 'active' : null">

                                <a href="" role="tab" @click.prevent="tab = username">
                                    <i class="fa fa-comment fa-beat text-danger"></i> {{ username }}
                                    <i class="fa fa-times text-red"></i>
                                </a>

                            </li>
                        </ul>

                        <chat-messages v-if="!state.connecting && tab === 'chatbox'"
                                       @pm-sent="(o) => createMessage(o.message, o.save, o.user_id, o.receiver_id)"
                                       :messages="msgs">

                        </chat-messages>

                        <chat-user-list v-else-if="!state.connecting && tab === 'userlist'"
                                        @pm-sent="(o) => createMessage(o.message, o.save, o.user_id, o.receiver_id)"
                                        :users="users">

                        </chat-user-list>

                        <chat-pms v-else-if="!state.connecting" :pms="pms[tab]"></chat-pms>

                    </div>
                </div>
            </div>
            <div class="panel-footer">
                <div class="typing">
                    <span class="badge-extra" v-if="activePeer">{{ activePeer.username }} is typing ...</span>
                </div>

                <chat-form v-if="tab !== 'userlist'"
                           @changedStatus="changeStatus"
                           @message-sent="(o) => createMessage(o.message, o.save, o.user_id, o.receiver_id)"
                           @typing="isTyping">

                </chat-form>
            </div>
        </div>
    </div>
</template>
<style lang="scss">
    .chatbox {
        .nav-tabs {
            overflow-y: hidden;
        }

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
    }
</style>
<script>
  import ChatroomsDropdown from './ChatroomsDropdown'
  import ChatMessages from './ChatMessages'
  import ChatForm from './ChatForm'
  import ChatPms from './ChatPms'
  import ChatUserList from './ChatUserList'

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
      ChatForm,
      ChatUserList,
      ChatPms
    },
    data () {
      return {
        tab: 'chatbox',
        state: {
          connecting: true
        },
        auth: {},
        statuses: [],
        status: 0,
        chatrooms: [],
        messages: [],
        users: [],
        room: 0,
        scroll: true,
        channel: null,
        config: {},
        activePeer: false,
        receiver_id: null
      }
    },
    watch: {
      tab (newVal, oldVal) {
        this.scrollToBottom(true)

        if (newVal !== 'chatbox' && newVal !== 'userlist') {
          let i = _.findIndex(this.pms[newVal], (o) => {
            return o.user.username === newVal
          })

          this.receiver_id = this.pms[newVal][i].user.id
        }
      },
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
      msgs () {
        return _.filter(this.messages, (o) => {
          return !o.receiver
        })
      },
      pms () {
        let m = _.filter(this.messages, (o) => {
          return o.receiver ? (o.receiver.id === this.auth.id || o.user.id === this.auth.id) : null
        })

        return _.groupBy(m, (o) => {
          return o.user.username === this.auth.username ? o.receiver.username : o.user.username
        })
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
        if (this.tab === 'chatbox') {
          this.channel.whisper('typing', {
            username: e.username
          })
        }
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
              `[url=/${this.auth.username}.${this.auth.id}]${this.auth.username}[/url] has updated their status to [b]${this.auth.chat_status.name}[/b]`
            )

          })
        }
      },

      /* User defaults to System user */
      createMessage (message, save = true, user_id = 1, receiver_id = null) {
        console.log(message, user_id, receiver_id)

        axios.post('/api/chat/messages', {
          'user_id': user_id,
          'receiver_id': receiver_id,
          'chatroom_id': this.room,
          'message': message,
          'save': save,
        }).then(response => {
          this.scrollToBottom(true)

          if (this.messages.length > this.config.message_limit) {
            _.each(this.messages, (m, i) => {
              if (!m.receiver) {
                this.messages.splice(i, 1)
                return false
              }
            })
          }
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
            this.state.connecting = false
            this.users = users

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
            this.messages.push(e.message)
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
