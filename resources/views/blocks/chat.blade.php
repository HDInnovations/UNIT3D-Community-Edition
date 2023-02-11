<div class="container">
    <h4 class="mb-4">{{ $room->title }}</h4>

    <div class="row">
        <div class="col-md-2">
            <livewire:chat.users :room="$room"/>
        </div>
        <div class="col-md-10">
            <livewire:chat.messages :room="$room" :messages="$messages"/>

            <livewire:chat.new-message :room="$room"/>
        </div>
    </div>
</div>
