@extends('layout.default')

@section('breadcrumb')
<li>
    <a href="{{ route('profil', ['username' => $user->username, 'id' => $user->id]) }}" itemprop="url" class="l-breadcrumb-item-link">
        <span itemprop="title" class="l-breadcrumb-item-link-title">{{ $user->username }}</span>
    </a>
</li>
<li>
    <a href="{{ route('user_edit_profil', ['username' => $user->username, 'id' => $user->id]) }}" itemprop="url" class="l-breadcrumb-item-link">
        <span itemprop="title" class="l-breadcrumb-item-link-title">Edit</span>
    </a>
</li>
@stop

@section('content')
<div class="container">
<div class="block block-form">
        <h1>Edit Profile</h1>

        {{ Form::open(array('route' => array('user_edit_profil', 'username' => $user->username, 'id' => $user->id), 'files' => true)) }}
        {{ csrf_field() }}
            <div class="form-group">
                <label for="image">Avatar</label><small>.jpg , .jpeg , .bmp , .png , .tiff , .gif are supported</small>
                <input type="file" name="image">
            </div>

            <div class="form-group">
                <label for="title">Custom Title</label>
                <input type="text" name="title" class="form-control" value="{{ $user->title }}">
            </div>

            <div class="form-group">
                <label for="about">About Me (500 Characters Max!) <span class="badge-extra">BBCode is allowed</span></label>
                <textarea name="about" cols="30" rows="10" maxlength="500" class="form-control">{{ $user->about }}</textarea>
            </div>

            <div class="form-group">
                <label for="signature">Forum Signature <span class="badge-extra">BBCode is allowed</span></label>
                <textarea name="signature" id="" cols="30" rows="10" class="form-control">{{ $user->signature }}</textarea>
            </div>

            @if( !is_null($user->signature))
            <center><p>Forum Signature </p> {!! $user->getSignature() !!}</center>
            @endif

            <button type="submit" class="btn btn-primary">Update</button>
        {{ Form::close() }}

    </div>
</div>
@stop
