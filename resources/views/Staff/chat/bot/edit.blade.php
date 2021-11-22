@extends('layout.default')

@section('title')
    <title>@lang('bot.edit-bot') - {{ config('other.title') }}</title>
@endsection

@section('breadcrumb')
    <li>
        <a href="{{ route('staff.dashboard.index') }}" itemprop="url" class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">@lang('staff.staff-dashboard')</span>
        </a>
    </li>
    <li>
        <a href="{{ route('staff.bots.index') }}" itemprop="url" class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">@lang('bot.bots')</span>
        </a>
    </li>
    <li>
        <a href="{{ route('staff.bots.edit', ['id' => $bot->id]) }}" itemprop="url" class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">@lang('bot.edit-bot')</span>
        </a>
    </li>
@endsection


@section('content')
    <div class="container box">
        <h2>@lang('bot.edit-bot')</h2>
        <form role="form" method="POST" action="{{ route('staff.bots.update', ['id' => $bot->id]) }}">
            @csrf
            @method('PATCH')
            <div class="form-group">
                <label for="name">@lang('bot.name')</label>
                <input type="text" class="form-control" id="name" name="name" value="{{ $bot->name }}">
            </div>
            <div class="form-group">
                <label for="name">@lang('common.position')</label>
                <label for="position"></label><input type="number" id="position" name="position"
                    value="{{ $bot->position }}" class="form-control">
            </div>
            <div class="form-group">
                <label for="name">@lang('bot.command')</label>
                <label for="command"></label><input type="text" class="form-control" id="command" name="command"
                    value="{{ $bot->command }}">
            </div>
            <div class="form-group">
                <label for="name">@lang('bot.info')</label>
                <label for="info"></label><input type="text" class="form-control" id="info" name="info"
                    value="{{ $bot->info }}">
            </div>
            <div class="form-group">
                <label for="name">@lang('bot.about')</label>
                <label for="about"></label><input type="text" class="form-control" id="about" name="about"
                    value="{{ $bot->about }}">
            </div>
            <div class="form-group">
                <label for="name">@lang('bot.emoji-code')</label>
                <label for="emoji"></label><input type="text" class="form-control" id="emoji" name="emoji"
                    value="{{ $bot->emoji }}">
            </div>
            <div class="form-group">
                <label for="name">@lang('bot.icon')</label>
                <label for="icon"></label><input type="text" class="form-control" id="icon" name="icon"
                    value="{{ $bot->icon }}">
            </div>
            <div class="form-group">
                <label for="name">@lang('bot.color')</label>
                <label for="color"></label><input type="text" class="form-control" id="color" name="color"
                    value="{{ $bot->color }}">
            </div>
            <div class="form-group">
                <label for="name">@lang('bot.help')</label>
            </div>
            <div class="form-group">
                <label>
                    <textarea name="help" cols="30" rows="10" class="form-control">{{ $bot->help }}</textarea>
                </label>
            </div>
            <br>
            <div class="text-center">
                <button type="submit" class="btn btn-primary">@lang('common.edit')</button>
            </div>
        </form>
    </div>
@endsection
