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
        {{ __('ticket.create-ticket') }}
    </li>
@endsection

@section('page', 'page__ticket--create')

@section('content')
    <section class="panelV2">
        <h2 class="panel__heading">
            <i class="fas fa-plus"></i>
            {{ __('ticket.create-ticket') }}
        </h2>
        <div class="panel__body">
            @if(session('errors'))
                <div class="alert alert-danger">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h6><b>{{ __('ticket.fix-errors') }}</b></h6>
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <form class="form" action="{{ route('tickets.store') }}" method="POST">
                @csrf
                <p class="form__group">
                    <select
                        id="category"
                        class="form__text"
                        name="category"
                        required
                    >
                        <option hidden disabled selected value=""></option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                    <label for="category" class="form__label form__label--floating">
                        {{ __('ticket.category') }}
                    </label>
                </p>
                <p class="form__group">
                    <select
                        name="priority"
                        id="priority"
                        class="form__select"
                        required
                    >
                        <option hidden disabled selected value=""></option>
                        @foreach($priorities as $priority)
                            <option value="{{ $priority->id }}">{{ $priority->name }}</option>
                        @endforeach
                    </select>
                    <label for="priority" class="form__label form__label--floating">
                        {{ __('ticket.priority') }}
                    </label>
                </p>
                <p class="form__group">
                    <input
                        id="ticket_subject"
                        class="form__text"
                        name="subject"
                        required
                    >
                    <label for="ticket_subject" class="form__label form__label--floating">
                        {{ __('ticket.subject') }}
                    </label>
                </p>
                <p class="form__group">
                    <textarea
                        id="body"
                        class="form__textarea"
                        name="body"
                        required
                    ></textarea>
                    <label for="body" class="form__label form__label--floating">
                        {{ __('ticket.body') }}
                    </label>
                </p>
                <p class="form__group">
                    <button class="form__button form__button--filled">
                        {{ __('common.submit') }}
                    </button>
                </p>
            </form>
        </div>
    </div>
@endsection
