<script>
import { state } from "../state";
import { changeTab, checkPings, leaveBot, leaveRoom, leaveTarget } from "../actions";

export default {
  name: "Tabs",
  methods: { leaveBot, leaveTarget, leaveRoom, checkPings, changeTab },
  computed: {
    state() {
      return state
    }
  }
};
</script>

<template>
  <menu id="chatbox_tabs" class="panel__tabs" role="tablist" v-if="state.boot === 1">
    <template v-for="echo in state.echoes">
      <li
        v-if="echo.room && echo.room.name.length > 0"
        class="panel__tab chatbox__tab"
        role="tab"
        :class="state.tab !== '' && state.tab === echo.room.name && 'panel__tab--active'"
        @click.prevent="changeTab('room', echo.room.id)"
      >
        <i
          class="fa fa-comment"
          :class="checkPings('room', echo.room.id) ? 'fa-beat text-success' : 'text-danger'"
        ></i>
        {{ echo.room.name }}
        <button
          v-if="state.tab !== '' && state.tab === echo.room.name"
          class="chatbox__tab-delete-button"
          @click.prevent="leaveRoom(echo.room.id)"
        >
          <i class="fa fa-times chatbox__tab-delete-icon"></i>
        </button>
      </li>
    </template>
    <template v-for="echo in state.echoes">
      <li
        v-if="echo.target && echo.target.id >= 3 && echo.target.username.length > 0"
        class="panel__tab chatbox__tab"
        :class="state.target >= 3 && state.target === echo.target.id && 'panel__tab--active'"
        role="tab"
        @click.prevent="changeTab('target', echo.target.id)"
      >
        <i
          class="fa fa-comment"
          :class="checkPings('target', echo.target.id) ? 'fa-beat text-success' : 'text-danger'"
        ></i>
        @{{ echo.target.username }}
        <button
          v-if="state.target >= 3 && state.target === echo.target.id"
          class="chatbox__tab-delete-button"
          @click.prevent="leaveTarget(state.target)"
        >
          <i class="fa fa-times chatbox__tab-delete-icon"></i>
        </button>
      </li>
    </template>
    <template v-for="echo in state.echoes">
      <li
        v-if="echo.bot && echo.bot.id >= 1 && echo.bot.name.length > 0"
        class="panel__tab chatbox__tab"
        :class="state.bot > 0 && state.bot === echo.bot.id && 'panel__tab--active'"
        role="tab"
        @click.prevent="changeTab('bot', echo.bot.id)"
      >
        <i
          class="fa fa-comment"
          :class="checkPings('bot', echo.bot.id) ? 'fa-beat text-success' : 'text-danger'"
        ></i>
        @{{ echo.bot.name }}
        <button
          v-if="state.bot > 0 && state.bot === echo.bot.id"
          class="chatbox__tab-delete-button"
          @click.prevent="leaveBot(state.bot)"
        >
          <i class="fa fa-times chatbox__tab-delete-icon"></i>
        </button>
      </li>
    </template>
  </menu>
</template>