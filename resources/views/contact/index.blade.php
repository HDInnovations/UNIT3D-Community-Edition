@extends('layout.default')

@section('title')
    <title>@lang('common.contact') - {{ config('other.title') }}</title>
@endsection

@section('meta')
    <meta name="description" content="@lang('common.contact') {{ config('other.title') }}.">
@endsection

@section('breadcrumb')
    <li>
        <a href="{{ route('contact') }}" itemprop="url" class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">@lang('common.contact')</span>
        </a>
    </li>
@endsection

@section('content')
    <div class="container box">
        <div class="row">
            <div class="col-md-4 box centered-form">
                <form role="form" method="POST" action="{{ route('sendContact') }}">
                    @csrf
                    <div class="form-group">
                        <input type="text" name="contact-name" placeholder="@lang('common.name')"
                               value="{{ auth()->user()->username }}" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <input type="email" name="email" placeholder="@lang('common.email')"
                               value="{{ auth()->user()->email }}" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <textarea name="message" placeholder="@lang('common.message')" class="form-control"
                                  cols="30" rows="10"></textarea>
                    </div>

                    <button type="submit" class="btn btn-lg btn-primary btn-block">@lang('common.submit')</button>
                </form>
            </div>

            <div class="col-sm-8">
                <div class="well well-sm mt-0">
                    <p class="lead text-green text-center"><i class="{{ config('other.font-awesome') }} fa-star"></i>
                        <strong>@lang('common.contact-header')</strong> <i class="{{ config('other.font-awesome') }} fa-star"></i></p>
                    <p class="lead text-orange text-center">@lang('common.contact-desc').</p>
                </div>
            </div>
        </div>
    </div>
@endsection
