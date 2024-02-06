<template>
  <div class="form__group">
    <select
      id="currentChatstatus"
      class="form__select"
      v-model="selected"
      @change="changedStatus"
    >
      <option
        v-for="chatstatus in chatstatuses"
        :value="chatstatus.id"
        :selected="chatstatus.id == selected"
      >
        <i
          :class="chatstatus.icon ? chatstatus.icon + ' pointee mr-5' : 'fa fa-dot-circle-o pointee mr-5'"
          :style="`color: ${chatstatus.color}`"
        ></i>
        {{ chatstatus.name }}
      </option>
    </select>
    <label for="currentChatroom" class="form__label form__label--floating">
      Status
    </label>
  </div>
</template>

<script>
export default {
    props: {
        current: { type: Number, default: 1 },
        chatstatuses: { required: true },
    },
    data() {
        return {
            selected: 1,
        };
    },
    methods: {
        changedStatus(event) {
            this.$emit('changedStatus', this.selected);
        },
    },
    created() {
        this.selected = this.current;
    },
};
</script>
