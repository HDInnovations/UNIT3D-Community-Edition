@extends('layout.default')

@section('breadcrumb')
<li>
    <a href="{{ route('bug') }}" itemprop="url" class="l-breadcrumb-item-link">
        <span itemprop="title" class="l-breadcrumb-item-link-title">{{ trans('BONUS.bonus') }} {{ trans('BONUS.points') }}</span>
    </a>
</li>
@stop

@section('content')
<div class="container box">
  <div class="header gradient purple">
    <div class="inner_content">
      <h1>{{ trans('BONUS.BONUS') }} {{ trans('BONUS.store') }}</h1>
    </div>
  </div>
<h3>{{ trans('BONUS.earning') }}</h3>
<div class="row">
<div class="col-sm-9">
<table class="table table-condensed table-striped">
<tbody>
    <tr>
        <td class="col-sm-8">
            <tr>
                <td>
                    <strong>Dying {{ trans('torrent.torrent') }}</strong><br>
                    <small>You Are The Last Remaining Seeder! (has been downloaded atleast 3 times)</small>
                </td>
                <td><strong>{{ $dying }} x 2</strong></td>
                <td>{{ $dying * 2 }} {{ trans('BONUS.per-hour') }}</td>
            </tr>
            <tr>
                <td>
                    <strong>Legendary {{ trans('torrent.torrents') }}</strong><br>
                    <small>Older than 12 months</small>
                </td>
                <td><strong>{{ $legendary }} x 1.5</strong></td>
                <td>{{ $legendary * 1.5 }} {{ trans('BONUS.per-hour') }}</td>
            </tr>
            <tr>
                <td>
                    <strong>Old {{ trans('torrent.torrents') }}</strong><br>
                    <small>Older than 6 months</small>
                </td>
                <td><strong>{{ $old }} x 1</strong></td>
                <td>{{ $old * 1 }} {{ trans('BONUS.per-hour') }}</td>
            </tr>
            <tr>
                <td>
                    <strong>Huge {{ trans('torrent.torrents') }}</strong><br>
                    <small>Torrents Size <span class="text-bold">'>'</span> 100GB</small>
                </td>
                <td><strong>{{ $huge }} x 0.75</strong></td>
                <td>{{ $huge * 0.75 }} {{ trans('BONUS.per-hour') }}</td>
            </tr>
            <tr>
                <td>
                    <strong>Large {{ trans('torrent.torrents') }}</strong><br>
                    <small>Torrents Size <span class="text-bold">'>='</span> 25GB but '<' 100GB</small>
                </td>
                <td><strong>{{ $large }} x 0.50</strong></td>
                <td>{{ $large * 0.50 }} {{ trans('BONUS.per-hour') }}</td>
            </tr>
            <tr>
                <td>
                    <strong>Everyday {{ trans('torrent.torrents') }}</strong><br>
                    <small>Torrents Size <span class="text-bold">'>='</span> 1GB but '<' 25GB</small>
                </td>
                <td><strong>{{ $regular }} x 0.25</strong></td>
                <td>{{ $regular * 0.25 }} {{ trans('BONUS.per-hour') }}</td>
            </tr>

            <tr>
                <td>
                    <strong>Legendary {{ trans('torrent.seeder') }}</strong><br>
                    <small>Seed Time <span class="text-bold">'>='</span> 1 year</small>
                </td>
                <td><strong>{{ $legendary }} x 2</strong></td>
                <td>{{ $legendary * 2 }} {{ trans('BONUS.per-hour') }}</td>
            </tr>
            <tr>
                <td>
                    <strong>MVP {{ trans('torrent.seeder') }}</strong><br>
                    <small>Seed Time <span class="text-bold">'>='</span> 6 months but '<' 1 year</small>
                </td>
                <td><strong>{{ $mvp }} x 1</strong></td>
                <td>{{ $mvp * 1 }} {{ trans('BONUS.per-hour') }}</td>
            </tr>
            <tr>
                <td>
                    <strong>Committed {{ trans('torrent.seeder') }}</strong><br>
                    <small>Seed Time <span class="text-bold">'>='</span> 3 months but '<' 6 months</small>
                </td>
                <td><strong>{{ $commited }} x 0.75</strong></td>
                <td>{{ $commited * 0.75 }} {{ trans('BONUS.per-hour') }}</td>
            </tr>
            <tr>
                <td>
                    <strong>Team Player {{ trans('torrent.seeder') }}</strong><br>
                    <small>Seed Time <span class="text-bold">'>='</span> 2 months but '<' 3 months</small>
                </td>
                <td><strong>{{ $teamplayer }} x 0.50</strong></td>
                <td>{{ $teamplayer * 0.50 }} {{ trans('BONUS.per-hour') }}</td>
            </tr>
            <tr>
                <td>
                    <strong>Participant {{ trans('torrent.seeder') }}</strong><br>
                    <small>Seed Time <span class="text-bold">'>='</span> 1 month but '<' 2 months</small>
                </td>
                <td><strong>{{ $participaint }} x 0.25</strong></td>
                <td>{{ $participaint * 0.25 }} {{ trans('BONUS.per-hour') }}</td>
            </tr>
        </tbody>
        <tfoot>
            <tr>
                <td>
                    <strong>{{ trans('BONUS.total') }}</strong><br>
                    <small></small>
                </td>
                <td>-</td>
                <td><strong>{{ $total }}</strong> {{ trans('BONUS.per-hour') }}</td>
            </tr>
        </tfoot>
    </table>
</div>
<div class="col-sm-3 text-center">
    <div class="text-blue well well-sm">
        <h2><strong>{{ trans('BONUS.your-points') }}: <br></strong>{{ $userbon }}</h2>
    </div>
    <div class="text-green well well-sm">
        <h3>{{ trans('BONUS.earning') }} <strong>{{ $total }}</strong> {{ trans('BONUS.per-hour') }}</h3>
    </div>
</div>
</div>
</div>

<div class="container box">
<h3>{{ trans('BONUS.exchange') }}</h3>
<div class="row">
<div class="col-sm-8">
    <table class="table table-condensed table-striped">
        <thead>
            <tr>
                <th>{{ trans('BONUS.item') }}</th>
                <th>{{ trans('BONUS.points') }}</th>
                <th>{{ trans('BONUS.exchange') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($uploadOptions as $u => $uu)
                <tr>
                    <td>{{ $uu['description'] }}</td>
                    <td>{{ $uu['cost'] }}</td>
                    <td>
                        <a href="{{ route('bonusexchange', array('id' => $uu['id'])) }}" role="button" class="btn btn-sm btn-info btn-exchange">{{ trans('BONUS.exchange') }}</a>
                    </td>
                </tr>
            @endforeach

            {{--@foreach ($downloadOptions as $d => $dO)
                <tr>
                    <td>{{ $dO['description'] }}</td>
                    <td>{{ $dO['cost'] }}</td>
                    <td>
                        <a href="{{ route('bonusexchange', array('id' => $dO['id'])) }}" role="button" class="btn btn-sm btn-info btn-exchange">{{ trans('BONUS.exchange') }}</a>
                    </td>
                </tr>
            @endforeach--}}

            @foreach ($personalFreeleech as $p => $pf)
                <tr>
                    <td>{{ $pf['description'] }}</td>
                    <td>{{ $pf['cost'] }}</td>
                    <td>
                        @if($activefl)
                        <a href="{{ route('bonusexchange', array('id' => $pf['id'])) }}" role="button" class="btn btn-sm btn-success btn-exchange disabled">Activated!</a>
                        @else
                        <a href="{{ route('bonusexchange', array('id' => $pf['id'])) }}" role="button" class="btn btn-sm btn-info btn-exchange">{{ trans('BONUS.exchange') }}</a>
                        @endif
                    </td>
                </tr>
            @endforeach

            @foreach ($invite as $i => $in)
                <tr>
                    <td>{{ $in['description'] }}</td>
                    <td>{{ $in['cost'] }}</td>
                    <td>
                        <a href="{{ route('bonusexchange', array('id' => $in['id'])) }}" role="button" class="btn btn-sm btn-info btn-exchange">{{ trans('BONUS.exchange') }}</a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
<div class="col-sm-4">
    <div class="well well-sm mt-20">
        <p class="lead text-orange text-center">{{ trans('BONUS.exchange-warning') }}<br><strong>{{ trans('BONUS.no-refund') }}</strong>
        </p>
    </div>
</div>
</div>
</div>


<div class="container box">
  <h3>{{ trans('BONUS.gift') }}</h3>
        {{ Form::open(['route' => 'bongift' , 'method' => 'post' , 'role' => 'form' , 'id' => 'send_bonus' , 'class' => 'form-horizontal']) }}

        <div class="form-group">
            <label for="to_username" class="col-sm-3 control-label">{{ trans('BONUS.gift-to') }}</label>
            <div class="col-sm-9">
                <input class="form-control" placeholder="Enter username" name="to_username" type="text" id="to_username">
            </div>
        </div>

        <div class="form-group">
            <label for="bonus_points" class="col-sm-3 control-label">{{ trans('BONUS.BONUS') }} {{ trans('BONUS.points') }}</label>
            <div class="col-sm-9">
                <input class="form-control" placeholder="Enter amount" name="bonus_points" type="text" id="bonus_points">
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

    {{ Form::close() }}
</div>
@stop
