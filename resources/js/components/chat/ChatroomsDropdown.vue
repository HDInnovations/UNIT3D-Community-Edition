<template>
    <div class="form__group">
        <select id="currentChatroom" class="form__select" v-model="selected" @change="changedRoom">
            <option v-for="chatroom in chatrooms" :value="chatroom.id" :selected="chatroom.id == selected">{{ chatroom.name }}</option>
        </select>
        <label for="currentChatroom" class="form__label form__label--floating">Join a room</label>
    </div>
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
        },
    },
    created() {
        this.selected = this.current;
    },
};
</script>
