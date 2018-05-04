<template>
    <div class="col-md-10 col-sm-10 col-md-offset-1">
        <div class="clearfix visible-sm-block"></div>
        <div class="panel panel-chat">
            <div class="panel-heading">
                <h4>Chatbox</h4>
            </div>

            <div id="frame">
                <div id="sidepanel">
                    <div id="profile">
                        <div class="wrap">

                            <img :src="auth.image ? auth.image : '/img/profile.png'"
                                 @click="showStatuses = !showStatuses"
                                 :class="status.toLowerCase()"
                                 alt="">

                            <div v-if="showStatuses" class="statuses">
                                <ul class="list-unstyled">
                                    <li class="text-center" @click="statusChanged('Online')">
                                        <i class="fa fa-dot-circle-o text-green"></i>
                                    </li>
                                    <li class="text-center" @click="statusChanged('Away')">
                                        <i class="fa fa-dot-circle-o text-yellow"></i>
                                    </li>
                                    <li class="text-center" @click="statusChanged('Busy')">
                                        <i class="fa fa-dot-circle-o text-red"></i>
                                    </li>
                                    <li class="text-center" @click="statusChanged('Offline')">
                                        <i class="fa fa-dot-circle-o"></i>
                                    </li>
                                </ul>
                            </div>

                        </div>
                    </div>

                    <div id="bottom-bar">
                        <button id="channels">
                            <i class="fa fa-cog fa-fw" aria-hidden="true"></i>
                        </button>
                    </div>
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

                    <chat-form @message-sent="createMessage" :user="auth"></chat-form>

                </div>
            </div>
        </div>
    </div>
</template>
<style lang="scss">
    .decoda-image {
        min-height: 150px;
        max-height: 230px;
        max-width: 500px;
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
        status: 'Online',
        showStatuses: false,
        chatrooms: [],
        currentRoom: 0,
        scroll: true,
        channel: null,
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
      room_index () {
        return this.currentRoom - 1
      },
      messages () {
        return this.chatrooms.length > 0 ? this.chatrooms[this.room_index].messages : []
      },
      last_id() {
        if (this.messages > 0) {
          return this.messages[m.length -1].id
        }

        return 0;
      }
    },
    methods: {
      statusChanged (status) {
        this.status = status
        this.showStatuses = false
      },

      fetchRooms () {
        axios.get('/api/chat/rooms').then(response => {
          this.chatrooms = response.data.data
        })
      },

      changeRoom (id) {
        this.currentRoom = id

        if (this.auth.chatroom.id !== id) {
          /* Update the users chatroom in the database */
          axios.put(`/api/chat/user/${this.auth.id}/chatroom`, {
            'room_id': this.currentRoom
          }).then(response => {
            // reassign the auth variable to the response data
            this.auth = response.data
          })
        }

      },

      createMessage (message) {

        /* Create a new message in the database */
        axios.post('/api/chat/messages', {
          'user_id': this.auth.id,
          'chatroom_id': this.currentRoom,
          'message': message.message
        });

      },

      systemMessage (message) {

        this.chatrooms[this.room_index].messages.push({
          'id': this.last_id +1,
          'message': message,
          'user': {
            'id': 1
          }
        });

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

          this.scroll = scrollTop >= scrollHeight
        })
      },

      listenForEvents () {
        this.channel
          .here(users => {
            console.log('here')
          })
          .joining(user => {
            console.log('joining')
            this.systemMessage(`${user.username} has JOINED the chat ...`)
          })
          .leaving(user => {
            console.log('leaving')
            this.systemMessage(`${user.username} has LEFT the chat ...`)
          })
          .listen('.new.message', e => {
            console.log(e)
            //push the new message on to the array
            this.chatrooms[this.room_index].messages.push(e.message)
          })
      }
    },
    created () {
      this.auth = this.user

      this.fetchRooms()
      this.changeRoom(this.auth.chatroom.id)
      this.scrollToBottom()

      setInterval(() => {
        this.scrollToBottom()
      }, 100)

    },
  }
</script>
