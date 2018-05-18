<template>
    <div class="messages">
        <ul class="list-group">
            <li class="sent" v-for="user in users">
                <h4 class="list-group-item-heading">

                    <span class="badge-user text-bold">

                        <i v-tooltip="user.group.name"
                           :class="user.group.icon">

                        </i>

                        <a v-tooltip="$parent.auth.id !== user.id ? `Private Message` : user.username"
                           @click="pmUser(user)"
                           :style="userStyles(user)">
					        {{ user.username }}
                        </a>

					</span>

                </h4>
            </li>
        </ul>
    </div>
</template>
<script>
  import pmMethods from './mixins/pmMethods'

  export default {
    props: {
      users: {required: true},
    },
    mixins: [
      pmMethods
    ],
    methods: {
      userStyles (user) {
        return `cursor: pointer; color: ${user.group.color}; background-image: ${user.group.effect};`
      }
    }
  }
</script>