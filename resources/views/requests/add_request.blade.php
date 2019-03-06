@extends('layout.default')

@section('title')
    <title>@lang('request.add-request') - {{ config('other.title') }}</title>
@endsection

@section('breadcrumb')
    <li>
        <a href="{{ url('requests') }}" itemprop="url" class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">@lang('request.requests')</span>
        </a>
    </li>
    <li>
        <a href="{{ url('add_request_form') }}" itemprop="url" class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">@lang('request.add-request')</span>
        </a>
    </li>
@endsection

@section('content')
    <div class="container">
        @if ($user->can_request == 0)
            <div class="container">
                <div class="jumbotron shadowed">
                    <div class="container">
                        <h1 class="mt-5 text-center">
                            <i class="{{ config('other.font-awesome') }} fa-times text-danger"></i> @lang('request.no-privileges')
                        </h1>
                        <div class="separator"></div>
                        <p class="text-center">@lang('request.no-privileges-desc')!</p>
                    </div>
                </div>
            </div>
        @else
            <div class="col-sm-12">
                <div class="well well-sm mt-20">
                    <p class="lead text-orange text-center"><strong>@lang('request.no-imdb-id')</strong></p>
                </div>
            </div>
            <h1 class="upload-title">@lang('request.add-request')</h1>
            <form role="form" method="POST" action="{{ route('add_request') }}">
                @csrf
                <div class="block">
                    <div class="upload col-md-12">
                        <div class="form-group">
                            <label for="name">@lang('request.title')</label>
                            <input type="text" name="name" class="form-control" required>
                        </div>

                        <div class="form-group">
                            <label for="name">IMDB ID <b>(@lang('request.required'))</b></label>
                            <input type="number" name="imdb" value="0" class="form-control" required>
                        </div>

                        <div class="form-group">
                            <label for="name">TMDB ID <b>(@lang('request.required'))</b></label>
                            <input type="number" name="tmdb" value="{{ Request::query('tmdb', 0) }}" class="form-control" required>
                        </div>

                        <div class="form-group">
                            <label for="name">TVDB ID (Optional)</label>
                            <input type="number" name="tvdb" value="0" class="form-control" required>
                        </div>

                        <div class="form-group">
                            <label for="name">MAL ID (Optional)</label>
                            <input type="number" name="mal" value="0" class="form-control" required>
                        </div>

                        <div class="form-group">
                            <label for="category_id">@lang('request.category')</label>
                            <select name="category_id" class="form-control">
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="type">@lang('request.type')</label>
                            <select name="type" class="form-control">
                                @foreach ($types as $type)
                                    <option value="{{ $type->name }}">{{ $type->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="description">@lang('request.description')</label>
                            <textarea id="request-form-description" name="description" cols="30" rows="10"
                                      class="form-control"></textarea>
                        </div>

                        <div class="form-group">
                            <label for="bonus_point">@lang('request.reward') <small><em>(@lang('request.reward-desc'))</em></small></label>
                            <input class="form-control" name="bounty" type="number" min='100' value="100" required>
                        </div>

                        <label for="anon" class="control-label">Anonymous Torrent Request?</label>
                        <div class="radio-inline">
                            <label><input type="radio" name="anon" value="1">@lang('common.yes')</label>
                        </div>
                        <div class="radio-inline">
                            <label><input type="radio" name="anon" checked="checked" value="0">@lang('common.no')</label>
                        </div>
                    </div>

                    <br>
                    <div class="text-center">
                        <button type="submit" class="btn btn-primary">@lang('common.submit')</button>
                    </div>
                </div>
            </form>
        @endif
    </div>
@endsection

@section('javascripts')
    <script nonce="{{ Bepsvpt\SecureHeaders\SecureHeaders::nonce() }}">
      $(document).ready(function () {
        $('#request-form-description').wysibb({});
        emoji.textcomplete()
      })
    </script>
@endsection
