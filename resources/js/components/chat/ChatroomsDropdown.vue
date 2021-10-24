<template>
    <select id="currentChatroom" v-model="selected" @change="changedRoom">
        <option value="0" selected disabled>Join a room</option>
        <option v-for="chatroom in chatrooms" :value="chatroom.id">{{ chatroom.name }}</option>
    </select>
</template>

<script>
export default {
    props: {
        current: { type: Number, default: 1 },
        chatrooms: { required: true },
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
