<template>
  <section :audio="state.audio" v-if="state.user" class="panelV2 chatbox" :class="state.fullscreen && `chatbox--fullscreen`">
    <Header></Header>
    <Tabs></Tabs>
    <Body></Body>
  </section>
</template>
<script>
import axios from "axios";
import { state, startWatchers } from "./state";
import Header from "./components/Header.vue";
import { changeRoom, changeStatus, listenForEvents, loadUser } from "./actions";
import Tabs from "./components/Tabs.vue";
import Body from "./components/Body.vue";

export default {
  name: "Chat",
  components: { Body, Tabs, Header },
  computed: {
    state() {
      return state
    }
  },
  created() {
      state.startup = Date.now();
      state.activeTarget = '';
      state.activeBot = '';
      loadUser()
  },
  watch: {
    'state.chatrooms':{
      handler(){
        changeRoom(state.auth.chatroom.id);
      },
      deep: true
    },
    'state.statuses':{
      handler(){
        changeStatus(state.auth.chat_status.id);
      },
      deep: true
    },
    'state.room':{
      handler(newVal, oldVal){
        window.Echo.leave(`chatroom.${oldVal}`);
        state.channel = window.Echo.join(`chatroom.${newVal}`);
        listenForEvents();
      },
      deep: true
    }
  }
}
</script>