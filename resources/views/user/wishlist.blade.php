@extends('layout.default')

@section('title')
    <title>{{ $user->username }} {{ __('user.wishlist') }} - {{ config('other.title') }}</title>
@endsection

@section('breadcrumb')
    <li>
        <a href="{{ route('users.show', ['username' => $user->username]) }}" itemprop="url"
           class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">{{ $user->username }}</span>
        </a>
    </li>
    <li>
        <a href="{{ route('wishes.index', ['username' => $user->username]) }}" itemprop="url"
           class="l-breadcrumb-item-link">
            <span itemprop="title"
                  class="l-breadcrumb-item-link-title">{{ $user->username }} {{ __('user.wishlist') }}</span>
        </a>
    </li>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="block">
            @include('user.buttons.other')
            <div class="some-padding">
                <div class="row mb-20">
                    <div class="col-md-12">
                        <form action="{{ route('wishes.store') }}" method="POST" class="form-inline pull-right">
                            @csrf

                            <div class="form-group mt-5">
                                <label for="tmdb"></label><input type="text" class="form-control" name="tmdb" id="tmdb"
                                                                 placeholder="TMDB ID">
                            </div>

                            <button type="submit" class="btn btn-success mt-10">
                                <span class="{{ config('other.font-awesome') }} fa-plus"></span> {{ __('common.add') }}
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
                                    <th>{{ __('torrent.title') }}</th>
                                    <th>TMDB</th>
                                    <th>{{ __('common.status') }}</th>
                                    <th>{{ __('common.delete') }}</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach ($wishes as $wish)
                                    <tr>
                                        <td>
                                            @if ($wish->source !== null)
                                                <a href="{{ $wish->source }}">
                                                    @endif

                                                    {{ $wish->title }}

                                                    @if ($wish->source !== null)
                                                </a>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('mediahub.movies.show', ['id' => $wish->tmdb]) }}"
                                               target="_blank">
                                                MediaHub
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
                                            <form action="{{ route('wishes.destroy', ['id' => $wish->id]) }}"
                                                  method="POST" style="display: inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-xs btn-danger">
                                                    <i class="{{ config('other.font-awesome') }} fa-trash"></i>
                                                </button>
                                            </form>
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
                                    <h1 class="text-blue"><i
                                                class="{{ config('other.font-awesome') }} fa-frown text-blue"></i>
                                        No Wishes</h1>
                                </div>
                            </div>
                        @endif

                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection
