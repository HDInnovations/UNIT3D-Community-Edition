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
import { loadUser } from "./actions";
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
      startWatchers()
      state.startup = Date.now();
      state.activeTarget = '';
      state.activeBot = '';
      loadUser()
  }
}
</script>