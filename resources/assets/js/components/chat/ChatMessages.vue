<template>
    <div class="messages">
        <ul class="list-group">
            <li class="sent" v-for="message in messages">

                <img v-if="message.user.id !== 1" class="chat-user-image" :src="message.user.image ? message.user.image : '/img/profile.png'" alt=""/>

                <h4 v-if="message.user.id !== 1" class="list-group-item-heading">

                    <span class="badge-user text-bold">
                        <i class="fa fa-android"
                           data-toggle="tooltip"
                           title=""
                           data-original-title="Bot"></i>

                        <a data-toggle="tooltip" style="cursor: pointer; color:#FF9966;">
                            {{ message.user.username }}
                        </a> - <a href="#">Profile</a>

                        <i class="fa fa-circle text-green"
                           data-toggle="tooltip"
                           title=""
                           data-original-title="Online!"></i>

					</span>

                    <span class="text-muted">
                        {{ message.created_at }}
                    </span>

                </h4>

                <p :class="message.user.id === 1 ? 'system' : null" v-html="message.message"></p>
            </li>
        </ul>
    </div>
</template>

<script>
  export default {
    props: {
      messages: {required: true},
    }
  }
</script>