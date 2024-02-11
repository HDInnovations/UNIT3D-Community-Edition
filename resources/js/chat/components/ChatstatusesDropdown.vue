<template>
  <div class="form__group">
    <select
      id="currentChatstatus"
      class="form__select"
      v-model="selected"
      @change="changeStatus(selected)"
    >
      <option v-for="status in state.statuses" :value="status.id" :selected="status.id === selected"   >
        <i
          :class="status.icon ? status.icon + ' pointee mr-5' : 'fa fa-dot-circle-o pointee mr-5'"
          :style="`color: ${status.color}`"
        ></i>
        {{ status.name }}
      </option>
    </select>
    <label for="currentChatroom" class="form__label form__label--floating">
      Status
    </label>
  </div>
</template>

<script>
import { state } from "../state";
import { changeStatus } from "../actions";

export default {
  computed: {
    state() {
      return state
    }
  },
  methods: { changeStatus },
    data() {
        return {
            selected: 1,
        };
    },
    mounted() {
        this.selected = state.auth.chat_status.id;
    },
};
</script>
