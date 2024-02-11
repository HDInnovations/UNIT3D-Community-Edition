<template>
  <section :audio="state.audio" v-if="state.user" class="panelV2 chatbox" :class="state.fullscreen && `chatbox--fullscreen`">
    <Header></Header>
  </section>
</template>
<script>
import axios from "axios";
import state from "./state";
import Header from "./components/Header.vue";
import {
  attachAudible,
  fetchAudibles, fetchBots,
  fetchEchoes,
  fetchRooms,
  fetchStatuses,
  listenForChatter,
  loadUser
} from "./actions";

export default {
  name: "Chat",
  components: { Header },
  computed: {
    state() {
      return state
    }
  },
  methods: {
  },
  mounted() {
    state.startup = Date.now();
    state.activeTarget = '';
    state.activeBot = '';
    fetchRooms();
    fetchBots();
    fetchStatuses();
    fetchAudibles();
    fetchEchoes();
    listenForChatter();
    attachAudible();
  },
  created() {
      loadUser()
  }
}
</script>