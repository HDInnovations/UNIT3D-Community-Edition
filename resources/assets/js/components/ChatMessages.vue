<template>
    <div class="messages">
        <ul class="list-group">
            <li class="sent" v-for="message in messages">
                <img src="https://unit3d.org/img/profile.png" alt=""/>
                <h4 class="list-group-item-heading"><span class="badge-user text-bold"><i class="fa fa-android"
                                                                                          data-toggle="tooltip"
                                                                                          title=""
                                                                                          data-original-title="Bot"></i>
                    &nbsp;<a data-toggle="tooltip"
                             style="cursor: pointer; color:#FF9966;">{{ message.user.username }}</a> - <a href="#">Profile</a>
					<i class="fa fa-circle text-green" data-toggle="tooltip" title="" data-original-title="Online!"></i>
					</span>&nbsp;<span class="text-muted"><small><em>1 hour ago</em></small></span>
                </h4>
                <p>{{ message.message }}</p>
            </li>
        </ul>
    </div>
</template>

<script>
  export default {
    props: ['messages']
  };
</script>