@extends('layout.default')

@section('title')
    <title>{{ trans('user.my-wishlist') }} - {{ config('other.title') }}</title>
@endsection

@section('breadcrumb')
    <li>
        <a href="{{ route('wishlist', ['id' => auth()->user()->id]) }}" itemprop="url" class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">{{ trans('user.my-wishlist') }}</span>
        </a>
    </li>
@endsection

@section('content')

    <div class="container">

        <div class="block">
            <div class="row">
                <div class="col-md-12 text-center">
                    <h1 class="text-blue">Wishes</h1>
                </div>
            </div>
        </div>

        <div class="block">
            <div class="row mb-20">
                <div class="col-md-12">
                    <form action="{{ route('wish-store', ['uid' => auth()->user()->id]) }}"
                          method="post"
                          class="form-inline pull-right">

                        {{ csrf_field() }}

                        <div class="form-group">
                            <input type="text" class="form-control" name="imdb" id="imdb" placeholder="IMDB ID">
                        </div>

                        <div class="form-group">
                            <select title="Type" class="form-control" name="type" id="type">
                                <option value="movie" selected>Movie</option>
                                <option value="series">TV</option>
                            </select>
                        </div>

                        <button type="submit" class="btn btn-success mt-10">
                            <span class="fa fa-plus"></span> {{ trans('common.add') }}
                        </button>

                    </form>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <table class="table table-hover">
                        <thead>
                        <tr>
                            <th>Title</th>
                            <th>IMDB</th>
                            <th>Status</th>
                            <th>Delete</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($wishes as $wish)
                            <tr>
                                <td>
                                    @if($wish->source !== null)
                                        <a href="{{$wish->source}}">
                                            @endif

                                            {{ $wish->title }}

                                            @if($wish->source !== null)
                                        </a>
                                    @endif
                                </td>
                                <td>
                                    <a href="http://www.imdb.com/title/{{$wish->imdb}}" target="_blank">
                                        Link
                                    </a>
                                </td>
                                <td>
                                    @if($wish->source === null)
                                        <i class="fa fa-times red-text"></i>
                                    @else
                                        <i class="fa fa-check green-text"></i>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('wish-delete', ['uid' => auth()->user()->id, 'id' => $wish->id]) }}"
                                       class="btn btn-xs btn-danger">
                                        <i class="fa fa-trash"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>

                    {{ $wishes->links() }}

                    @if(count($wishes) <= 0)
                        <div class="row">
                            <div class="col-md-12 text-center">
                                <h1 class="text-blue"><i class="fa fa-frown-o text-blue"></i> No Wishes</h1>
                            </div>
                        </div>
                    @endif

                </div>
            </div>
        </div>

    </div>
@endsection
