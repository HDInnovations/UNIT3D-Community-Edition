@extends('layout.default')

@section('breadcrumb')
    <li>
        <a href="{{ route('staff.dashboard.index') }}" itemprop="url" class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">{{ __('staff.staff-dashboard') }}</span>
        </a>
    </li>
    <li>
        <a href="{{ route('staff.pages.index') }}" itemprop="url" class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">{{ __('staff.pages') }}</span>
        </a>
    </li>
    <li class="active">
        <a href="{{ route('staff.types.create') }}" itemprop="url" class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">
                {{ __('common.add') }}
                {{ trans_choice('common.a-an-art',false) }}
                {{ __('common.new-adj') }}
                {{ __('staff.page') }}
            </span>
        </a>
    </li>
@endsection

@section('content')
    <div class="container box">
        <h2>
            {{ __('common.add') }}
            {{ trans_choice('common.a-an-art',false) }}
            {{ __('common.new-adj') }}
            {{ __('staff.page') }}
        </h2>
        <form role="form" method="POST" action="{{ route('staff.pages.store') }}">
            @csrf
            <div class="form-group">
                <label for="name">{{ __('staff.page') }} {{ __('common.name') }}</label>
                <label>
                    <input type="text" name="name" class="form-control">
                </label>
            </div>

            <div class="form-group">
                <label for="content">{{ __('common.content') }}</label>
                <textarea name="content" id="content" cols="30" rows="10" class="form-control"></textarea>
            </div>

            <button type="submit" class="btn btn-default">{{ __('common.submit') }}</button>
        </form>
    </div>
@endsection

@section('javascripts')
    <script nonce="{{ Bepsvpt\SecureHeaders\SecureHeaders::nonce('script') }}">
      $(document).ready(function () {
        $('#content').wysibb({})
      })

    </script>
@endsection
