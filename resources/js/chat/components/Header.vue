<script>
import state from "../state";
import { changeAudible, changeRoom, changeStatus, changeTab, changeWhispers, startBot } from "../actions";
import ChatroomsDropdown from "./ChatroomsDropdown.vue";
import ChatstatusesDropdown from "./ChatstatusesDropdown.vue";

export default {
  name: "Header",
  components: { ChatstatusesDropdown, ChatroomsDropdown },
  methods: { changeStatus, changeRoom, changeWhispers, changeAudible, changeTab, startBot },
  computed: {
    state() {
      return state
    }
  }
};
</script>

<template>
  <header class="panel__header" id="chatbox_header">
    <h2 class="panel__heading">
      <i class="fas fa-comment-dots"></i>
      Chatbox v3.0
    </h2>
    <div class="panel__actions">
      <div class="panel__action">
        <button class="form__button form__button--text" @click.prevent="startBot">
          <i class="fa fa-robot"></i>
          {{ state.helpName }}
        </button>
      </div>
      <div class="panel__action">
        <button
          class="form__button form__button--text"
          v-if="state.target < 1 && state.bot < 1"
          @click.prevent="changeTab('list', 'userlist')"
        >
          <i class="fa fa-users"></i>
          Users: {{ state.users.length }}
        </button>
      </div>
      <div class="panel__action">
        <button
          class="form__button form__standard-icon-button form__standard-icon-button--skinny"
          v-if="
              state.room &&
              state.room > 0 &&
              state.bot < 1 &&
              state.target < 1 &&
              state.tab !== 'userlist'
            "
          @click.prevent="changeAudible('room', state.room, state.listening ? 0 : 1)"
          :style="`color: ${state.listening ? 'rgb(0,102,0)' : 'rgb(204,0,0)'}`"
        >
          <i :class="state.listening ? 'fa fa-bell' : 'fa fa-bell-slash'"></i>
        </button>
        <button
          class="form__button form__standard-icon-button form__standard-icon-button--skinny"
          v-if="state.bot && state.bot >= 1 && state.target < 1 && state.tab != 'userlist'"
          @click.prevent="changeAudible('bot', state.bot, state.listening ? 0 : 1)"
          :style="`color: ${state.listening ? 'rgb(0,102,0)' : 'rgb(204,0,0)'}`"
        >
          <i :class="state.listening ? 'fa fa-bell' : 'fa fa-bell-slash'"></i>
        </button>
        <button
          class="form__button form__standard-icon-button form__standard-icon-button--skinny"
          v-if="state.target && state.target >= 1 && state.bot < 1 && state.tab != 'userlist'"
          @click.prevent="changeAudible('target', state.target, state.listening ? 0 : 1)"
          :style="`color: ${state.listening ? 'rgb(0,102,0)' : 'rgb(204,0,0)'}`"
        >
          <i :class="state.listening ? 'fa fa-bell' : 'fa fa-bell-slash'"></i>
        </button>
      </div>
      <div class="panel__action">
        <button
          class="form__button form__standard-icon-button form__standard-icon-button--skinny"
          title="Toggle typing notifications"
          @click.prevent="changeWhispers"
          :style="`color: ${state.showWhispers ? 'rgb(0,102,0)' : 'rgb(204,0,0)'}`"
        >
          <i :class="state.showWhispers ? `fas fa-keyboard` : `fa fa-keyboard`"></i>
        </button>
      </div>
      <div class="panel__action">
        <ChatroomsDropdown/>
      </div>
      <div class="panel__action">
        <chatstatuses-dropdown
          @changedStatus="changeStatus"
        >
        </chatstatuses-dropdown>
      </div>
      <div class="panel__action">
        <button
          id="panel-fullscreen"
          :class="`form__button form__standard-icon-button`"
          title="Toggle Fullscreen"
          @click.prevent="state.fullscreen = !state.fullscreen"
        >
          <i
            :class="state.fullscreen ? `fas fa-compress` : `fas fa-expand`"
          ></i>
        </button>
      </div>
    </div>
  </header>
</template>