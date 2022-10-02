@extends('layout.default')

@section('title')
    <title>{{ __('ticket.helpdesk') }} - {{ config('other.title') }}</title>
@endsection

@section('breadcrumbs')
    <li class="breadcrumbV2">
        <a href="{{ route('tickets.index') }}" class="breadcrumb__link">
            {{ __('ticket.helpdesk') }}
        </a>
    </li>
    <li class="breadcrumb--active">
        {{ $ticket->subject }}
    </li>
@endsection

@section('page', 'page__ticket--show')

@section('main')
    <section class="panelV2">
        <h2 class="panel__heading">{{ $ticket->subject }}</h2>
        <div class="panel__body">
            {{ $ticket->body }}
        </div>
    </section>
    <livewire:comments :model="$ticket"/>
@endsection

@section('sidebar')
    <section class="panelV2">
        <h2 class="panel__heading">{{ __('common.info') }}</h2>
        <dl class="key-value">
            <dt>ID</dt>
            <dd>{{ $ticket->id }}</dd>
            <dt>{{ __('common.created_at') }}</dt>
            <dd>{{ $ticket->created_at->format('Y-m-d') }}</dd>
            <dt>{{ __('ticket.opened-by') }}</dt>
            <dd>
                <x-user_tag :user="$ticket->user" :anon="false" />
            </dd>
            <dt>{{ __('ticket.category') }}</dt>
            <dd>{{ $ticket->category->name }}</dd>
            <dt>{{ __('ticket.priority') }}</dt>
            <dd>
                @switch($ticket->priority->name)
                    @case ('Low')
                        <i class="fas fa-circle text-yellow"></i>
                        @break
                    @case ('Medium')
                        <i class="fas fa-circle text-orange"></i>
                        @break
                    @case ('High')
                        <i class="fas fa-circle text-red"></i>
                        @break
                @endswitch
                {{ $ticket->priority->name }}
            </dd>
            @if (!empty($ticket->closed_at))
                <dt>{{ __('ticket.closed') }}</dt>
                <dd>{{ $ticket->closed_at->format('m/d/Y') }}</dd>
            @endif
        </dl>
    </section>
    <section class="panelV2">
        <h2 class="panel__heading">{{ __('common.actions') }}</h2>
        <div class="panel__body">
            @if($user->group->is_modo)
                <form
                    class="form form--horizontal"
                    action="{{ route('tickets.assign', ['id' => $ticket->id]) }}"
                    method="POST"
                    x-data
                >
                    @csrf
                    <p class="form__group">
                        <select name="user_id" class="form__select" x-on:change="$root.submit()">
                            <option hidden disabled selected value=""></option>
                            @foreach(App\Models\User::select(['id', 'username'])->whereIn('group_id', App\Models\Group::where('is_modo', 1)->whereNotIn('id', [9])->pluck('id')->toArray())->get() as $user)
                                <option value="{{ $user->id }}" @selected($user->id = $ticket->staff_id)>
                                    {{ $user->username }}
                                </option>
                            @endforeach
                        </select>
                        <label class="form__label form__label--floating">{{ __('ticket.assign') }}</label>
                    </p>
                </form>
                @if(! empty($ticket->staff_id))
                    <form
                        action="{{ route('tickets.unassign', ['id' => $ticket->id]) }}"
                        method="POST"
                    >
                        @csrf
                        <p class="form__group form__group--horizontal">
                            <button class="form__button form__button--filled">
                                {{ __('ticket.unassign') }}
                            </button>
                        </p>
                    </form>
                @endif
                <form
                    action="{{ route('tickets.destroy', ['id' => $ticket->id]) }}"
                    method="POST"
                >
                    @csrf
                    @method('DELETE')
                    <p class="form__group form__group--horizontal">
                        <button class="form__button form__button--filled">
                            <i class="fas fa-times"></i>
                            {{ __('ticket.delete') }}
                        </button>
                    </p>
                </form>
            @endif
            @if(empty($ticket->closed_at))
                <form
                    action="{{ route('tickets.close', ['id' => $ticket->id]) }}"
                    method="POST"
                >
                    <p class="form__group form__group--horizontal">
                        @csrf
                        <button class="form__button form__button--filled">
                            <i class="fas fa-times"></i>
                            {{ __('ticket.close') }}
                        </button>
                    </p>
                </form>
            @endif
        </div>
    </section>
    @livewire('attachment-upload', ['id' => $ticket->id])
@endsection
