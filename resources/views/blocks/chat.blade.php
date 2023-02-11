<div class="container">
    <h4 class="mb-4">{{ $chatroom->title }}</h4>

    <div class="row">
        <div class="col-md-2">
            <livewire:chat.users :room="$chatroom"/>
        </div>
        <div class="col-md-10">
            <livewire:chat.messages :room="$chatroom" :messages="$messages"/>

            <livewire:chat.new-message :room="$chatroom"/>
        </div>
    </div>
</div>
