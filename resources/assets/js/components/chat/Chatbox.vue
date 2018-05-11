<template>
    <div class="col-md-10 col-sm-10 col-md-offset-1 chatbox">
        <div class="clearfix visible-sm-block"></div>
        <div class="panel panel-chat">
            <div class="panel-heading">
                <h4>Chatbox</h4>
            </div>

            <div class="panel-body">
                <div id="frame">
                    <div id="sidepanel">
                        <div id="profile">
                            <div class="wrap">

                                <img :src="auth.image ? `/files/img/${auth.image}` : '/img/profile.png'"
                                     @click="showStatuses = !showStatuses"
                                     :style="`border: 2px solid ${statusColor};`"
                                     alt="">

                                <transition name="slide-fade">
                                    <div v-if="showStatuses" class="statuses">
                                        <ul class="list-unstyled">

                                            <li v-for="status in statuses"
                                                class="text-center"
                                                @click="changeStatus(status.id)">

                                                <i :class="status.icon ? status.icon : 'fa fa-dot-circle-o'"
                                                   :style="`color: ${status.color}`"></i>

                                            </li>

                                        </ul>
                                    </div>
                                </transition>
                            </div>
                        </div>

                        <!--<div id="bottom-bar">
                            <button id="channels">
                                <i class="fa fa-cog fa-fw" aria-hidden="true"></i>
                            </button>
                        </div>-->
                    </div>
                    <div class="content">

                        <div class="contact-profile">
                            <chatrooms-dropdown :current="auth.chatroom.id"
                                                :chatrooms="chatrooms"
                                                class="pull-left"
                                                @changedRoom="changeRoom">

                            </chatrooms-dropdown>
                        </div>

                        <chat-messages :messages="messages"></chat-messages>

                    </div>
                </div>
            </div>
            <div class="panel-footer">
                <chat-form
                        @message-sent="(o) => createMessage(o.message, o.save, o.user_id)"
                        :user="auth"></chat-form>
            </div>
        </div>
    </div>
</template>
<style lang="scss">
    .chatbox {
        .panel-body {
            padding: 0;
        }

        .decoda-image {
            min-height: 150px;
            max-height: 230px;
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
        currentRoom: 0,
        scroll: true,
        channel: null,
        limits: {}
      }
    },
    watch: {
      currentRoom (newVal, oldVal) {
        window.Echo.leave(`chatroom.${oldVal}`)

        this.channel = window.Echo.join(`chatroom.${newVal}`)
        this.listenForEvents()
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
        if (this.currentRoom !== 0) {
          return this.currentRoom - 1
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
      fetchRooms () {
        axios.get('/api/chat/rooms').then(response => {
          this.chatrooms = response.data.data

          axios.get(`/api/chat/rooms/${this.auth.chatroom.id}/limits`).then(response => {
            this.limits = response.data

            this.changeRoom(this.auth.chatroom.id)
          })
        })
      },

      changeRoom (id) {
        this.currentRoom = id

        if (this.auth.chatroom.id !== id) {
          /* Update the users chatroom in the database */
          axios.post(`/api/chat/user/${this.auth.id}/chatroom`, {
            'room_id': id
          }).then(response => {
            // reassign the auth variable to the response data
            this.auth = response.data
          })
        }

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
      createMessage (message, save = true, user_id = 1) {

        axios.post('/api/chat/messages', {
          'user_id': user_id,
          'chatroom_id': this.currentRoom,
          'message': message,
          'save': save,
        }).then((response) => {
          let count = this.chatrooms[this.room_index].messages.length
          if (count > this.limits.max_messages) {
            this.chatrooms[this.room_index].messages.splice(0, 1)
          }
        })

      },

      scrollToBottom () {
        let messages = $('.messages .list-group')

        if (this.scroll) {
          messages.animate({scrollTop: messages.prop('scrollHeight')}, 0)
        }

        messages.scroll(() => {
          this.scroll = false

          let scrollTop = messages.scrollTop() + messages.prop('clientHeight')
          let scrollHeight = messages.prop('scrollHeight')

          this.scroll = scrollTop >= scrollHeight - 2
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
            this.chatrooms[this.room_index].messages.push(e.message)
          })
      }
    },
    created () {
      this.auth = this.user

      this.fetchRooms()
      this.fetchStatuses()

      setInterval(() => {
        this.scrollToBottom()
      }, 100)

    },
  }
</script>
