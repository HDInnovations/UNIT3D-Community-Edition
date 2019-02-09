<template>
    <div class="input-group chat-dropdown">
        <select id="currentChatroom" v-model="selected" class="form-control" @change="changedRoom">
            <option value="0" selected disabled>Join a room...</option>
            <option v-for="chatroom in chatrooms" :value="chatroom.id">{{ chatroom.name }}</option>
        </select>
    </div>
</template>
<style lang="scss" scoped>
.chat-dropdown {
    width: 100%;

    .form-control {
        margin: 0;
    }

    .input-group-addon {
        background-color: transparent;
        border: none;
    }
}
</style>
<script>
export default {
    props: {
        current: { type: Number, default: 1 },
        chatrooms: { required: true }
    },
    data() {
        return {
            selected: 1,
        };
    },
    methods: {
        changedRoom(event) {
            this.$emit('changedRoom', this.selected);
            this.selected = 0;
        },
    },
    created() {
        this.selected = 0;
    },
};
</script>
