<template>
    <div class="col-md-10 col-sm-10 col-md-offset-1">
        <div class="clearfix visible-sm-block"></div>
        <div class="panel panel-chat shoutbox">
            <div class="panel-heading">
                <h4>Chatbox</h4>
            </div>

            <div id="frame">
                <div id="sidepanel">
                    <div id="profile">
                        <div class="wrap">
                            <img id="profile-img" src="/img/profile.png" class="online"
                                 alt=""/>
                            <!--<p>HDVinnie</p>
                            <i class="fa fa-chevron-down expand-button" aria-hidden="true"></i>
                            <div id="status-options">
                                <ul>
                                    <li id="status-online" class="active"><span class="status-circle"></span>
                                        <p>Online</p></li>
                                    <li id="status-away"><span class="status-circle"></span>
                                        <p>Away</p></li>
                                    <li id="status-busy"><span class="status-circle"></span>
                                        <p>Busy</p></li>
                                    <li id="status-offline"><span class="status-circle"></span>
                                        <p>Offline</p></li>
                                </ul>
                            </div>
                            <div id="expanded">

                            </div>-->
                        </div>
                    </div>
                    <div id="contacts">
                        <ul>

                        </ul>
                    </div>
                    <div id="bottom-bar">
                        <button id="channels"><i class="fa fa-cog fa-fw" aria-hidden="true"></i></button>
                    </div>
                </div>
                <div class="content">
                    <div class="contact-profile">
                        <chatrooms-dropdown @changechatroom="changeChatroom"
                                            :currentchatroom='chatroom'
                                            :chatrooms="chatrooms">

                        </chatrooms-dropdown>
                    </div>
                    <chat-messages :messages="messages"></chat-messages>
                    <chat-form @messagesent="addMessage" :user="user"></chat-form>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
  import ChatroomsDropdown from './ChatroomsDropdown'
  import ChatMessages from './ChatMessages'
  import ChatForm from './ChatForm'

  export default {

    data () {
      return {
        messages: [],
        chatrooms: [],
        currentChatroom: this.chatroom
      }
    },

    components: {
      ChatroomsDropdown,
      ChatMessages,
      ChatForm
    },

    props: {
      user: {
        type: Object,
        required: true,
      },
      chatroom: {
        type: Object,
        required: true,
      }
    },

    mounted () {
      this.fetchChatrooms()
      this.fetchMessages()

      Echo.private('chatroom.' + this.currentChatroom.id)
        .listen('MessageSent', (e) => {
          this.messages.push({
            message: e.message.message,
            user: e.user
          })
        })
    },

    methods: {
      fetchChatrooms () {
        axios.get('chatbox/chatrooms').then(response => {
          this.chatrooms = response.data
        })
      },
      changeChatroom (chatroom) {
        Echo.leave('chatroom.' + this.currentChatroom.id)
        this.currentChatroom = chatroom

        axios.post('chatbox/change-chatroom', {'chatroom': chatroom}).then(response => {
          this.fetchMessages()
          Echo.private('chatroom.' + chatroom.id)
            .listen('MessageSent', (e) => {
              this.messages.push({
                message: e.message.message,
                user: e.user
              })
            })
        })
      },
      fetchMessages () {
        axios.get('chatbox/messages').then(response => {
          this.messages = response.data
        })
      },

      addMessage (message) {
        this.messages.push(message)

        axios.post('chatbox/messages', message).then(response => {
          console.log(response.data)
        })
      }
    }
  }
</script>