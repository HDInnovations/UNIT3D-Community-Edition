@extends('layout.default')

@section('content')
<div class="container">
    @include('conversation.partials._header')

    <div class="row">
        <div class="col-md-4">
            <livewire:conversations.conversation-list :conversations="$conversations" />
        </div>
        <div class="col-md-8">
            <livewire:conversations.conversation-create />
        </div>
    </div>
</div>
@endsection
