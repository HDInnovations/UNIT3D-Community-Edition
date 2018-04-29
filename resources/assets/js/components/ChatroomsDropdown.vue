<template>
    <div class="input-group">
	<span class="input-group-addon" for="currentChatroom">Current
		Chatroom:</span> <select id='currentChatroom' v-model="currentChatroomValue"
                                 class="form-control" v-on:change='onChatroomChange'>
        <option v-for="chatroom in chatrooms" :value='chatroom'>{{ chatroom.name }}</option>
    </select>
    </div>
</template>

<style scoped>
    .input-group .input-group-addon {
        background-color: transparent;
        border: none;
    }
</style>

<script>
  export default {
    props: {
      currentchatroom: {
        type: Object,
        required: true,
      },
      chatrooms: {
        type: Array,
      }
    },
    data: function () {
      return {
        currentChatroomValue: this.currentchatroom
      }
    },
    methods: {
      onChatroomChange() {
        this.$emit('changechatroom', this.currentChatroomValue)
      }
    }
  }
</script>