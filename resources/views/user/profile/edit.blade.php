@extends('layout.default')

@section('title')
    <title>{{ $user->username }} {{ __('user.profile') }} - {{ config('other.title') }}</title>
@endsection

@section('breadcrumbs')
    <li class="breadcrumbV2">
        <a href="{{ route('users.show', ['username' => $user->username]) }}" class="breadcrumb__link">
            {{ $user->username }}
        </a>
    </li>
    <li class="breadcrumb--active">
        {{ __('user.edit-profile') }}
    </li>
@endsection

@section('nav-tabs')
    @include('user.buttons.user')
@endsection

@section('content')
    <div class="container">
        <div class="block">
            <div class="some-padding">
                <form role="form" method="POST"
                      action="{{ route('user_edit_profile', ['username' => $user->username]) }}"
                      enctype="multipart/form-data">
                    @csrf
                    <div class="well">

                        <div class="form-group">
                            <label for="image">{{ __('user.avatar') }}</label>
                            <small>{{ __('user.formats-are-supported', ['formats' => '.jpg , .jpeg , .bmp , .png , .tiff ,
                                .gif']) }}</small>
                            <input type="file" name="image" id="image">
                        </div>

                        <div class="form-group">
                            <label for="title">{{ __('user.custom-title') }}</label>
                            <input type="text" name="title" id="title" class="form-control" value="{{ $user->title }}">
                        </div>

                        <div class="form-group">
                            <label for="about">{{ __('user.about-me') }} <span class="badge-extra">BBCode
                                    {{ __('common.is-allowed') }}</span></label>
                            <textarea name="about" id="editor" cols="30" rows="10"
                                      class="form-control">{{ $user->about }}</textarea>
                        </div>

                        <div class="form-group">
                            <label for="signature">{{ __('user.forum-signature') }} <span class="badge-extra">BBCode
                                    {{ __('common.is-allowed') }}</span></label>
                            <textarea name="signature" id="editor" cols="30" rows="10"
                                      class="form-control">{{ $user->signature }}</textarea>
                        </div>

                        @if ( !is_null($user->signature))
                            <div class="text-center">
                                <p>{{ __('user.forum-signature') }} </p> {!! $user->getSignature() !!}
                            </div>
                        @endif
                    </div>
                    <div class="well text-center">
                        <button type="submit" class="btn btn-primary">{{ __('common.submit') }}</button>
                    </div>
                </form>

            </div>
        </div>
    </div>
    </div>
@endsection