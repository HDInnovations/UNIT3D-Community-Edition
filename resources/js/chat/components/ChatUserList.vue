<template>
  <section class="chatroom__users">
    <h2 class="chatroom-users__heading">Users</h2>
    <ul class="chatroom-users__list">
      <li
        class="chatroom-users__list-item"
        v-for="user in users"
      >
        <span
          class="chatroom-users__user user-tag"
          :style="user.group?.effect && `backgroundImage: ${user.group.effect }`"
        >
            <a
              class="chatroom-users__user-link user-tag__link"
              :class="user.group?.icon"
              :href="`/users/${user.username}`"
              :style="`color: ${user.group?.color }`"
              :title="user.group?.name"
            >
              {{ user.username }}
            </a>
        </span>
        <menu class="chatroom-users__buttons" v-if="$parent.auth.id !== user.id">
          <li>
            <button
              class="chatroom-users__button"
              title="Gift user bon (/gift <username> <amount> <message>)"
              @click.prevent="$parent.forceGift(user.username)"
            >
              <i
                class="fas fa-gift"
              ></i>
            </button>
          </li>
          <li>
            <button
              class="chatroom-users__button"
              title="Send chat PM (/msg <username> <message>)"
              @click.prevent="$parent.forceMessage(user.username)"
            >
              <i
                class="fas fa-envelope"
              ></i>
            </button>
          </li>
        </menu>
      </li>
    </ul>
  </section>
</template>
<script>

export default {
  props: {
    users: { required: true },
  },
};
</script>