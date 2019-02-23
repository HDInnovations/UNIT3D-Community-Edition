@extends('layout.default')

@section('title')
    <title>{{ $user->username }} @lang('user.wishlist') - {{ config('other.title') }}</title>
@endsection

@section('breadcrumb')
    <li>
        <a href="{{ route('profile', ['slug' => $user->slug, 'id' => $user->id]) }}" itemprop="url"
           class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">{{ $user->username }}</span>
        </a>
    </li>
    <li>
        <a href="{{ route('user_wishlist', ['slug' => $user->slug, 'id' => $user->id]) }}" itemprop="url"
           class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">{{ $user->username }} @lang('user.wishlist')</span>
        </a>
    </li>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="block">
            @include('user.buttons.other')
            <div class="header gradient pink">
                <div class="inner_content">
                    <h1>{{ $user->username }} @lang('user.wishlist')</h1>
                </div>
            </div>
            <div class="some-padding">
            <div class="row mb-20">
                <div class="col-md-12">
                    <form action="{{ route('wish-store', ['uid' => auth()->user()->id]) }}"
                          method="post"
                          class="form-inline pull-right">

                        @csrf

                        <div class="form-group mt-5">
                            <input type="text" class="form-control" name="imdb" id="imdb" placeholder="IMDB ID">
                        </div>

                        <button type="submit" class="btn btn-success mt-10">
                            <span class="{{ config('other.font-awesome') }} fa-plus"></span> @lang('common.add')
                        </button>

                    </form>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="table-responsive">
                        <table class="table table-condensed table-striped table-bordered">
                        <thead>
                        <tr>
                            <th>Title</th>
                            <th>IMDB</th>
                            <th>Status</th>
                            <th>Delete</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($wishes as $wish)
                            <tr>
                                <td>
                                    @if ($wish->source !== null)
                                        <a href="{{$wish->source}}">
                                            @endif

                                            {{ $wish->title }}

                                            @if ($wish->source !== null)
                                        </a>
                                    @endif
                                </td>
                                <td>
                                    <a href="http://www.imdb.com/title/{{$wish->imdb}}" target="_blank">
                                        Link
                                    </a>
                                </td>
                                <td>
                                    @if ($wish->source === null)
                                        <i class="{{ config('other.font-awesome') }} fa-times red-text"></i>
                                    @else
                                        <i class="{{ config('other.font-awesome') }} fa-check green-text"></i>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('wish-delete', ['uid' => auth()->user()->id, 'id' => $wish->id]) }}"
                                       class="btn btn-xs btn-danger">
                                        <i class="{{ config('other.font-awesome') }} fa-trash"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                    </div>

                    <div class="text-center">
                        {{ $wishes->links() }}
                    </div>

                    @if (count($wishes) <= 0)
                        <div class="row">
                            <div class="col-md-12 text-center">
                                <h1 class="text-blue"><i class="{{ config('other.font-awesome') }} fa-frown text-blue"></i> No Wishes</h1>
                            </div>
                        </div>
                    @endif

                </div>
            </div>
        </div>
        </div>

    </div>
@endsection
