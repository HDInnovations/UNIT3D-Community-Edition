import { reactive, watch } from "vue";
import { changeRoom, changeStatus, listenForEvents } from "./actions";

export let state = reactive({
  audio: true,
  user: false,
  tab: '',
  fullscreen: 0,
  connecting: true,
  auth: {},
  statuses: [],
  status: 0,
  echoes: [],
  bots: [],
  chatrooms: [],
  messages: [],
  users: [],
  pings: [],
  audibles: [],
  boot: 0,
  audioLoaded: 0,
  selectedRoom: 1,
  room: 0,
  startup: 0,
  check: 0,
  target: 0,
  forced: false,
  bot: 0,
  activeTab: '',
  activeBot: '',
  activeRoom: '',
  activeTarget: '',
  activePeer: false,
  peerRoom: 0,
  botName: '',
  botId: 0,
  listening: 1,
  botCommand: '',
  helpName: '',
  helpCommand: '',
  frozen: false,
  push: false,
  helpId: 0,
  scroll: true,
  channel: null,
  chatter: null,
  config: {},
  receiver_id: null,
  bot_id: null,
  showWhispers: 1,
})

export function startWatchers () {

  watch( state.chatrooms, function() {
    console.log("Chatrooms updated")
    changeRoom(state.auth.chatroom.id);
  })

  watch( state.statuses, function() {
    console.log("Statuses updated")
    changeStatus(state.auth.chat_status.id)
  })

  watch( state.room, function(newVal, oldVal) {
    console.log("Room updated")
    window.Echo.leave(`chatroom.${oldVal}`);
    state.channel = window.Echo.join(`chatroom.${newVal}`);
    listenForEvents();
  })

}
