@extends('layout.default')

@section('breadcrumb')
<li>
    <a href="{{ route('profile', ['username' => $user->username, 'id' => $user->id]) }}" itemprop="url" class="l-breadcrumb-item-link">
        <span itemprop="title" class="l-breadcrumb-item-link-title">{{ $user->username }}</span>
    </a>
</li>
<li>
    <a href="{{ route('user_edit_profile', ['username' => $user->username, 'id' => $user->id]) }}" itemprop="url" class="l-breadcrumb-item-link">
        <span itemprop="title" class="l-breadcrumb-item-link-title">{{ trans('common.edit') }}</span>
    </a>
</li>
@endsection

@section('content')
<div class="container">
<div class="block block-form">
        <h1>{{ trans('user.edit-profile') }}</h1>

        {{ Form::open(array('route' => array('user_edit_profile', 'username' => $user->username, 'id' => $user->id), 'files' => true)) }}
        {{ csrf_field() }}
            <div class="form-group">
                <label for="image">{{ trans('user.avatar') }}</label><small>{{ trans('user.formats-are-supported', ['formats' => '.jpg , .jpeg , .bmp , .png , .tiff , .gif']) }}</small>
                <input type="file" name="image">
            </div>

            <div class="form-group">
                <label for="title">{{ trans('user.custom-title') }}</label>
                <input type="text" name="title" class="form-control" value="{{ $user->title }}">
            </div>

            <div class="form-group">
                <label for="about">{{ trans('user.about-me') }} <span class="badge-extra">BBCode {{ trans('common.is-allowed') }}</span></label>
                <textarea name="about" cols="30" rows="10" maxlength="500" class="form-control">{{ $user->about }}</textarea>
            </div>

            <div class="form-group">
                <label for="signature">{{ trans('user.forum-signature') }} <span class="badge-extra">BBCode {{ trans('common.is-allowed') }}</span></label>
                <textarea name="signature" id="" cols="30" rows="10" class="form-control">{{ $user->signature }}</textarea>
            </div>

            @if( !is_null($user->signature))
            <center><p>{{ trans('user.forum-signature') }} </p> {!! $user->getSignature() !!}</center>
            @endif

            <button type="submit" class="btn btn-primary">{{ trans('common.submit') }}</button>
        {{ Form::close() }}

    </div>
</div>
@endsection
