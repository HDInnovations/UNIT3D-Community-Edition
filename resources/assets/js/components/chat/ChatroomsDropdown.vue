<template>
    <div class="input-group chat-dropdown">
        <span class="input-group-addon">
            Current Chatroom:
        </span>
        <select id='currentChatroom'
                v-model="selected"
                class="form-control"
                @change='changedRoom'>

            <option v-for="chatroom in chatrooms" :value='chatroom.id'>{{ chatroom.name }}</option>
        </select>
    </div>
</template>

<script>
  export default {
    props: {
      current: {type: Number, default: 1},
      chatrooms: {required: true}
    },
    data () {
      return {
        selected: 1
      }
    },
    methods: {
      changedRoom (event) {
        this.$emit('changedRoom', this.selected)
      }
    },
    created () {
      this.selected = this.current
    }
  }
</script>

<style lang="scss" scoped>
    .chat-dropdown {

        width: 800px;

        .input-group-addon {
            background-color: transparent;
            border: none;
        }
    }
</style>