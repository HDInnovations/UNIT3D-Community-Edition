<template>
  <div class="form__group">
    <select
      class="form__select"
      :class="state.chatrooms.some(chatroom => chatroom.id === state.auth.chatroom.id) || 'form__select--default'"
      v-model="state.selectedRoom"
      @change="changeRoom(state.selectedRoom)"
    >
      <option v-for="chatroom in state.chatrooms" :value="chatroom.id" >
        {{ chatroom.name }}
      </option>
    </select>
    <label class="form__label form__label--floating" for="currentChatroom">
      Room
    </label>
  </div>
</template>

<script>
import { state } from "../state";
import { changeRoom } from "../actions";

export default {
  methods: { changeRoom },
  computed: {
    state() {
      return state
    }
  },
  mounted() {
    state.selectedRoom = state.auth.chatroom.id;
  },
};
</script>