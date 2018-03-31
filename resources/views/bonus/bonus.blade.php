@extends('layout.default')

@section('breadcrumb')
<li>
    <a href="{{ route('bug') }}" itemprop="url" class="l-breadcrumb-item-link">
        <span itemprop="title" class="l-breadcrumb-item-link-title">{{ trans('bon.bonus') }} {{ trans('bon.points') }}</span>
    </a>
</li>
@endsection

@section('content')
<div class="container box">
  <div class="header gradient purple">
    <div class="inner_content">
      <h1>{{ trans('bon.bon') }} {{ trans('bon.store') }}</h1>
    </div>
  </div>
<h3>{{ trans('bon.earning') }}</h3>
<div class="row">
<div class="col-sm-9">
<table class="table table-condensed table-striped">
<tbody>
    <tr>
        <td class="col-sm-8">
            <tr>
                <td>
                    <strong>{{ trans('torrent.dying-torrent') }}</strong><br>
                    <small>{{ trans('torrent.last-seeder') }}</small>
                </td>
                <td><strong>{{ $dying }} x 2</strong></td>
                <td>{{ $dying * 2 }} {{ trans('bon.per-hour') }}</td>
            </tr>
            <tr>
                <td>
                    <strong>{{ trans('torrent.legendary-torrent') }}</strong><br>
                    <small>{{ trans('common.older-than') }} 12 {{ strtolower(trans('common.months')) }}</small>
                </td>
                <td><strong>{{ $legendary }} x 1.5</strong></td>
                <td>{{ $legendary * 1.5 }} {{ trans('bon.per-hour') }}</td>
            </tr>
            <tr>
                <td>
                    <strong>{{ trans('torrent.old-torrent') }}</strong><br>
                    <small>{{ trans('common.older-than') }} 6 {{ strtolower(trans('common.months')) }}</small>
                </td>
                <td><strong>{{ $old }} x 1</strong></td>
                <td>{{ $old * 1 }} {{ trans('bon.per-hour') }}</td>
            </tr>
            <tr>
                <td>
                    <strong>{{ trans('common.huge') }} {{ trans('torrent.torrents') }}</strong><br>
                    <small>{{ trans('torrent.torrents') }} {{ strtolower(trans('torrent.size')) }} <span class="text-bold">></span> 100GB</small>
                </td>
                <td><strong>{{ $huge }} x 0.75</strong></td>
                <td>{{ $huge * 0.75 }} {{ trans('bon.per-hour') }}</td>
            </tr>
            <tr>
                <td>
                    <strong>{{ trans('common.large') }} {{ trans('torrent.torrents') }}</strong><br>
                    <small>{{ trans('torrent.torrents') }} {{ strtolower(trans('torrent.size')) }} <span class="text-bold">>=</span> 25GB {{ strtolower(trans('common.but')) }} < 100GB</small>
                </td>
                <td><strong>{{ $large }} x 0.50</strong></td>
                <td>{{ $large * 0.50 }} {{ trans('bon.per-hour') }}</td>
            </tr>
            <tr>
                <td>
                    <strong>{{ trans('common.everyday') }} {{ trans('torrent.torrents') }}</strong><br>
                    <small>{{ trans('torrent.torrents') }} {{ strtolower(trans('torrent.size')) }} <span class="text-bold">>=</span> 1GB {{ strtolower(trans('common.but')) }} < 25GB</small>
                </td>
                <td><strong>{{ $regular }} x 0.25</strong></td>
                <td>{{ $regular * 0.25 }} {{ trans('bon.per-hour') }}</td>
            </tr>

            <tr>
                <td>
                    <strong>{{ trans('torrent.legendary-seeder') }}</strong><br>
                    <small>{{ trans('torrent.seed-time') }} <span class="text-bold">>=</span> 1 {{ strtolower(trans('common.year')) }}</small>
                </td>
                <td><strong>{{ $legendary }} x 2</strong></td>
                <td>{{ $legendary * 2 }} {{ trans('bon.per-hour') }}</td>
            </tr>
            <tr>
                <td>
                    <strong>{{ trans('torrent.mvp') }} {{ trans('torrent.seeder') }}</strong><br>
                    <small>{{ trans('torrent.seed-time') }} <span class="text-bold">>=</span> 6 {{ strtolower(trans('common.months')) }} {{ strtolower(trans('common.but')) }} < 1 {{ strtolower(trans('common.year')) }}</small>
                </td>
                <td><strong>{{ $mvp }} x 1</strong></td>
                <td>{{ $mvp * 1 }} {{ trans('bon.per-hour') }}</td>
            </tr>
            <tr>
                <td>
                    <strong>{{ trans('torrent.commited') }} {{ trans('torrent.seeder') }}</strong><br>
                    <small>{{ trans('torrent.seed-time') }} <span class="text-bold">>=</span> 3 {{ strtolower(trans('common.months')) }} {{ strtolower(trans('common.but')) }} < 6 {{ strtolower(trans('common.months')) }}</small>
                </td>
                <td><strong>{{ $commited }} x 0.75</strong></td>
                <td>{{ $commited * 0.75 }} {{ trans('bon.per-hour') }}</td>
            </tr>
            <tr>
                <td>
                    <strong>{{ trans('torrent.team-player') }} {{ trans('torrent.seeder') }}</strong><br>
                    <small>{{ trans('torrent.seed-time') }} <span class="text-bold">>=</span> 2 {{ strtolower(trans('common.months')) }} {{ strtolower(trans('common.but')) }} < 3 {{ strtolower(trans('common.months')) }}</small>
                </td>
                <td><strong>{{ $teamplayer }} x 0.50</strong></td>
                <td>{{ $teamplayer * 0.50 }} {{ trans('bon.per-hour') }}</td>
            </tr>
            <tr>
                <td>
                    <strong>{{ trans('torrent.participant') }} {{ trans('torrent.seeder') }}</strong><br>
                    <small>{{ trans('torrent.seed-time') }} <span class="text-bold">>=</span> 1 {{ strtolower(trans('common.months')) }} {{ strtolower(trans('common.but')) }} < 2 {{ strtolower(trans('common.months')) }}</small>
                </td>
                <td><strong>{{ $participaint }} x 0.25</strong></td>
                <td>{{ $participaint * 0.25 }} {{ trans('bon.per-hour') }}</td>
            </tr>
        </tbody>
        <tfoot>
            <tr>
                <td>
                    <strong>{{ trans('bon.total') }}</strong><br>
                    <small></small>
                </td>
                <td>-</td>
                <td><strong>{{ $total }}</strong> {{ trans('bon.per-hour') }}</td>
            </tr>
        </tfoot>
    </table>
</div>
<div class="col-sm-3 text-center">
    <div class="text-blue well well-sm">
        <h2><strong>{{ trans('bon.your-points') }}: <br></strong>{{ $userbon }}</h2>
    </div>
    <div class="text-green well well-sm">
        <h3>{{ trans('bon.earning') }} <strong>{{ $total }}</strong> {{ trans('bon.per-hour') }}</h3>
    </div>
</div>
</div>
</div>

<div class="container box">
<h3>{{ trans('bon.exchange') }}</h3>
<div class="row">
<div class="col-sm-8">
    <table class="table table-condensed table-striped">
        <thead>
            <tr>
                <th>{{ trans('bon.item') }}</th>
                <th>{{ trans('bon.points') }}</th>
                <th>{{ trans('bon.exchange') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($uploadOptions as $u => $uu)
                <tr>
                    <td>{{ $uu['description'] }}</td>
                    <td>{{ $uu['cost'] }}</td>
                    <td>
                        <a href="{{ route('bonusexchange', array('id' => $uu['id'])) }}" role="button" class="btn btn-sm btn-info btn-exchange">{{ trans('bon.exchange') }}</a>
                    </td>
                </tr>
            @endforeach

            {{--@foreach ($downloadOptions as $d => $dO)
                <tr>
                    <td>{{ $dO['description'] }}</td>
                    <td>{{ $dO['cost'] }}</td>
                    <td>
                        <a href="{{ route('bonusexchange', array('id' => $dO['id'])) }}" role="button" class="btn btn-sm btn-info btn-exchange">{{ trans('bon.exchange') }}</a>
                    </td>
                </tr>
            @endforeach--}}

            @foreach ($personalFreeleech as $p => $pf)
                <tr>
                    <td>{{ $pf['description'] }}</td>
                    <td>{{ $pf['cost'] }}</td>
                    <td>
                        @if($activefl)
                        <a href="{{ route('bonusexchange', array('id' => $pf['id'])) }}" role="button" class="btn btn-sm btn-success btn-exchange disabled">{{ trans('bon.activated') }}!</a>
                        @else
                        <a href="{{ route('bonusexchange', array('id' => $pf['id'])) }}" role="button" class="btn btn-sm btn-info btn-exchange">{{ trans('bon.exchange') }}</a>
                        @endif
                    </td>
                </tr>
            @endforeach

            @foreach ($invite as $i => $in)
                <tr>
                    <td>{{ $in['description'] }}</td>
                    <td>{{ $in['cost'] }}</td>
                    <td>
                        <a href="{{ route('bonusexchange', array('id' => $in['id'])) }}" role="button" class="btn btn-sm btn-info btn-exchange">{{ trans('bon.exchange') }}</a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
<div class="col-sm-4">
    <div class="well well-sm mt-20">
        <p class="lead text-orange text-center">{{ trans('bon.exchange-warning') }}<br><strong>{{ trans('bon.no-refund') }}</strong>
        </p>
    </div>
</div>
</div>
</div>


<div class="container box">
  <h3>{{ trans('bon.gift') }}</h3>
    <form role="form" method="POST" action="{{ route('bongift') }}" id="send_bonus">
        {{ csrf_field() }}

        <div class="form-group">
            <label for="to_username" class="col-sm-3 control-label">{{ trans('bon.gift-to') }}</label>
            <div class="col-sm-9">
                <select class="form-control user-select-placeholder-single" name="to_username">
                  @foreach($users as $user)
                    <option value="{{ $user->username }}">{{ $user->username }}</option>
                  @endforeach
                </select>
            </div>
        </div>

        <div class="form-group">
            <label for="bonus_points" class="col-sm-3 control-label">{{ trans('bon.bon') }} {{ trans('bon.points') }}</label>
            <div class="col-sm-9">
                <input class="form-control" placeholder="{{ trans('common.enter') }} {{ strtolower(trans('common.amount')) }}" name="bonus_points" type="number" id="bonus_points" required>
            </div>
        </div>

        <div class="form-group">
            <label for="bonus_message" class="col-sm-3 control-label">{{ trans('pm.message') }}</label>
            <div class="col-sm-9">
                <textarea class="form-control" name="bonus_message" cols="50" rows="10" id="bonus_message"></textarea>
            </div>
        </div>

        <div class="form-group">
            <div class="col-sm-9 col-sm-offset-3">
                <input class="btn btn-primary" type="submit" value="{{ trans('common.submit') }}">
            </div>
        </div>

    </form>
</div>

@section('javascripts')
    <script type="text/javascript">
        $('.user-select-placeholder-single').select2({
            placeholder: "Select A User",
            allowClear: true
        });
    </script>
@endsection

@endsection
