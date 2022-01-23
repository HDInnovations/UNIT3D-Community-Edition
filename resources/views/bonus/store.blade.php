@extends('layout.default')

@section('breadcrumb')
    <li>
        <a href="{{ route('bonus') }}" itemprop="url" class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">{{ __('bon.bonus') }} {{ __('bon.points') }}</span>
        </a>
    </li>
    <li>
        <a href="{{ route('bonus_store') }}" itemprop="url" class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">{{ __('bon.bonus') }} {{ __('bon.store') }}</span>
        </a>
    </li>
@endsection

@section('content')
    <div class="container">
        <div class="block">
            @include('bonus.buttons')
            <div class="some-padding">
                <div class="row">
                    <div class="col-sm-8">
                        <div class="well">
                            <h3>{{ __('bon.exchange') }}</h3>
                            <table class="table table-condensed table-striped">
                                <thead>
                                <tr>
                                    <th>{{ __('bon.item') }}</th>
                                    <th>{{ __('bon.points') }}</th>
                                    <th>{{ __('bon.exchange') }}</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach ($uploadOptions as $u => $uu)
                                    <tr>
                                        <td>{{ $uu['description'] }}</td>
                                        <td>{{ $uu['cost'] }}</td>
                                        <td>
                                            <form method="POST"
                                                  action="{{ route('bonus_exchange', ['id' => $uu['id']]) }}">
                                                @csrf
                                                <button type="sumbit"
                                                        class="btn btn-sm btn-info btn-exchange">{{ __('bon.exchange') }}</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach

                                @foreach ($personalFreeleech as $p => $pf)
                                    <tr>
                                        <td>{{ $pf['description'] }}</td>
                                        <td>{{ $pf['cost'] }}</td>
                                        <td>
                                            @if ($activefl)
                                                <button type="submit"
                                                        class="btn btn-sm btn-success btn-exchange disabled">
                                                    {{ __('bon.activated') }}!
                                                </button>
                                            @else
                                                <form method="POST"
                                                      action="{{ route('bonus_exchange', ['id' => $pf['id']]) }}">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-info btn-exchange">
                                                        {{ __('bon.exchange') }}
                                                    </button>
                                                </form>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach

                                @foreach ($invite as $i => $in)
                                    <tr>
                                        <td>{{ $in['description'] }}</td>
                                        <td>{{ $in['cost'] }}</td>
                                        <td>
                                            <form method="POST"
                                                  action="{{ route('bonus_exchange', ['id' => $in['id']]) }}">
                                                @csrf
                                                <button class="btn btn-sm btn-info btn-exchange"
                                                        type="submit">{{ __('bon.exchange') }}</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="col-sm-4">

                        <div class="text-blue well well-sm text-center">
                            <h2><strong>{{ __('bon.your-points') }}: <br></strong>{{ $userbon }}</h2>
                        </div>

                        <div class="well well-sm mt-20">
                            <p class="lead text-orange text-center">{{ __('bon.exchange-warning') }}
                                <br><strong>{{ __('bon.no-refund') }}</strong>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
