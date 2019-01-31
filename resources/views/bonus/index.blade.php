@extends('layout.default')

@section('breadcrumb')
    <li>
        <a href="{{ route('bonus') }}" itemprop="url" class="l-breadcrumb-item-link">
            <span itemprop="title"
                  class="l-breadcrumb-item-link-title">@lang('bon.bonus') @lang('bon.points')</span>
        </a>
    </li>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="block">
            @include('bonus.buttons')
            <div class="header gradient purple">
                <div class="inner_content">
                    <h1>@lang('bon.bon') @lang('bon.points')</h1>
                </div>
            </div>
            <div class="some-padding">
            <div class="row">
                <div class="col-sm-8">
                    <div class="well">
                    <h3>@lang('bon.earnings')</h3>
                    <table class="table table-condensed table-striped">
                        <tbody>
                        <tr>
                            <td class="col-sm-8">
                        <tr>
                            <td>
                                <strong>@lang('torrent.dying-torrent')</strong><br>
                                <small>@lang('torrent.last-seeder')</small>
                            </td>
                            <td><strong>{{ $dying }} x 2</strong></td>
                            <td>
                            {{ $dying * 2 }} @lang('bon.per-hour')<br />
                            {{ ($dying * 2)*24 }} @lang('bon.per-day')<br />
                            {{ ($dying * 2)*24*7 }} @lang('bon.per-week')<br />
                            {{ ($dying * 2)*24*30 }} @lang('bon.per-month')<br />
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <strong>@lang('torrent.legendary-torrent')</strong><br>
                                <small>@lang('common.older-than') 12 {{ strtolower(trans('common.months')) }}</small>
                            </td>
                            <td><strong>{{ $legendary }} x 1.5</strong></td>
                            <td>
                                {{ $legendary * 1.5 }} @lang('bon.per-hour')<br />
                                {{ ($legendary * 1.5)*24 }} @lang('bon.per-day')<br />
                                {{ ($legendary * 1.5)*24*7 }} @lang('bon.per-week')<br />
                                {{ ($legendary * 1.5)*24*30 }} @lang('bon.per-month')<br />
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <strong>@lang('torrent.old-torrent')</strong><br>
                                <small>@lang('common.older-than') 6 {{ strtolower(trans('common.months')) }}</small>
                            </td>
                            <td><strong>{{ $old }} x 1</strong></td>
                            <td>
                                {{ $old * 1 }} @lang('bon.per-hour')<br />
                                {{ ($old * 1)*24 }} @lang('bon.per-day')<br />
                                {{ ($old * 1)*24*7 }} @lang('bon.per-week')<br />
                                {{ ($old * 1)*24*30 }} @lang('bon.per-month')<br />
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <strong>@lang('common.huge') @lang('torrent.torrents')</strong><br>
                                <small>@lang('torrent.torrents') {{ strtolower(trans('torrent.size')) }} <span
                                            class="text-bold">></span> 100GB
                                </small>
                            </td>
                            <td><strong>{{ $huge }} x 0.75</strong></td>
                            <td>
                                {{ $huge * 0.75 }} @lang('bon.per-hour')<br />
                                {{ ($huge * 0.75)*24 }} @lang('bon.per-day')<br />
                                {{ ($huge * 0.75)*24*7 }} @lang('bon.per-week')<br />
                                {{ ($huge * 0.75)*24*30 }} @lang('bon.per-month')<br />
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <strong>@lang('common.large') @lang('torrent.torrents')</strong><br>
                                <small>@lang('torrent.torrents') {{ strtolower(trans('torrent.size')) }} <span
                                            class="text-bold">>=</span> 25GB {{ strtolower(trans('common.but')) }} < 100GB
                                </small>
                            </td>
                            <td><strong>{{ $large }} x 0.50</strong></td>
                            <td>
                                {{ $large * 0.5 }} @lang('bon.per-hour')<br />
                                {{ ($large * 0.5)*24 }} @lang('bon.per-day')<br />
                                {{ ($large * 0.5)*24*7 }} @lang('bon.per-week')<br />
                                {{ ($large * 0.5)*24*30 }} @lang('bon.per-month')<br />
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <strong>@lang('common.everyday') @lang('torrent.torrents')</strong><br>
                                <small>@lang('torrent.torrents') {{ strtolower(trans('torrent.size')) }} <span
                                            class="text-bold">>=</span> 1GB {{ strtolower(trans('common.but')) }} < 25GB
                                </small>
                            </td>
                            <td><strong>{{ $regular }} x 0.25</strong></td>
                            <td>
                                {{ $regular * 0.25 }} @lang('bon.per-hour')<br />
                                {{ ($regular * 0.25)*24 }} @lang('bon.per-day')<br />
                                {{ ($regular * 0.25)*24*7 }} @lang('bon.per-week')<br />
                                {{ ($regular * 0.25)*24*30 }} @lang('bon.per-month')<br />
                            </td>
                        </tr>

                        <tr>
                            <td>
                                <strong>@lang('torrent.legendary-seeder')</strong><br>
                                <small>@lang('torrent.seed-time') <span class="text-bold">>=</span>
                                    1 {{ strtolower(trans('common.year')) }}</small>
                            </td>
                            <td><strong>{{ $legend }} x 2</strong></td>
                            <td>
                                {{ $legend * 2 }} @lang('bon.per-hour')<br />
                                {{ ($legend * 2)*24 }} @lang('bon.per-day')<br />
                                {{ ($legend * 2)*24*7 }} @lang('bon.per-week')<br />
                                {{ ($legend * 2)*24*30 }} @lang('bon.per-month')<br />
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <strong>@lang('torrent.mvp') @lang('torrent.seeder')</strong><br>
                                <small>@lang('torrent.seed-time') <span class="text-bold">>=</span>
                                    6 {{ strtolower(trans('common.months')) }} {{ strtolower(trans('common.but')) }} <
                                    1 {{ strtolower(trans('common.year')) }}</small>
                            </td>
                            <td><strong>{{ $mvp }} x 1</strong></td>
                            <td>
                                {{ $mvp * 1 }} @lang('bon.per-hour')<br />
                                {{ ($mvp * 1)*24 }} @lang('bon.per-day')<br />
                                {{ ($mvp * 1)*24*7 }} @lang('bon.per-week')<br />
                                {{ ($mvp * 1)*24*30 }} @lang('bon.per-month')<br />
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <strong>@lang('torrent.commited') @lang('torrent.seeder')</strong><br>
                                <small>@lang('torrent.seed-time') <span class="text-bold">>=</span>
                                    3 {{ strtolower(trans('common.months')) }} {{ strtolower(trans('common.but')) }} <
                                    6 {{ strtolower(trans('common.months')) }}</small>
                            </td>
                            <td><strong>{{ $committed }} x 0.75</strong></td>
                            <td>
                                {{ $committed * 0.75 }} @lang('bon.per-hour')<br />
                                {{ ($committed * 0.75)*24 }} @lang('bon.per-day')<br />
                                {{ ($committed * 0.75)*24*7 }} @lang('bon.per-week')<br />
                                {{ ($committed * 0.75)*24*30 }} @lang('bon.per-month')<br />
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <strong>@lang('torrent.team-player') @lang('torrent.seeder')</strong><br>
                                <small>@lang('torrent.seed-time') <span class="text-bold">>=</span>
                                    2 {{ strtolower(trans('common.months')) }} {{ strtolower(trans('common.but')) }} <
                                    3 {{ strtolower(trans('common.months')) }}</small>
                            </td>
                            <td><strong>{{ $teamplayer }} x 0.50</strong></td>
                            <td>
                                {{ $teamplayer * 0.5 }} @lang('bon.per-hour')<br />
                                {{ ($teamplayer * 0.5)*24 }} @lang('bon.per-day')<br />
                                {{ ($teamplayer * 0.5)*24*7 }} @lang('bon.per-week')<br />
                                {{ ($teamplayer * 0.5)*24*30 }} @lang('bon.per-month')<br />
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <strong>@lang('torrent.participant') @lang('torrent.seeder')</strong><br>
                                <small>@lang('torrent.seed-time') <span class="text-bold">>=</span>
                                    1 {{ strtolower(trans('common.months')) }} {{ strtolower(trans('common.but')) }} <
                                    2 {{ strtolower(trans('common.months')) }}</small>
                            </td>
                            <td><strong>{{ $participant }} x 0.25</strong></td>
                            <td>
                                {{ $participant * 0.25 }} @lang('bon.per-hour')<br />
                                {{ ($participant * 0.25)*24 }} @lang('bon.per-day')<br />
                                {{ ($participant * 0.25)*24*7 }} @lang('bon.per-week')<br />
                                {{ ($participant * 0.25)*24*30 }} @lang('bon.per-month')<br />
                            </td>
                        </tr>
                        </tbody>
                        <tfoot>
                        <tr>
                            <td>
                                <strong>@lang('bon.total')</strong><br>
                                <small></small>
                            </td>
                            <td>-</td>
                            <td>
                                <strong>{{ $total }}</strong> @lang('bon.per-hour')<br />
                            <strong>{{ $total*24 }}</strong> @lang('bon.per-day')<br />
                            <strong>{{ $total*24*7 }}</strong> @lang('bon.per-week')<br />
                            <strong>{{ $total*24*30 }}</strong> @lang('bon.per-month')</td>
                        </tr>
                        </tfoot>
                    </table>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="text-blue well well-sm text-center">
                        <h2><strong>@lang('bon.your-points'): <br></strong>{{ $userbon }}</h2>
                    </div>
                    <div class="text-orange well well-sm text-center">
                        <h2>@lang('bon.earning') <strong>{{ $total }}</strong> @lang('bon.per-hour')</h2>
                        @lang('bon.earning-rate')
                        <div class="some-padding text-center">
                            <h3><strong>{{ $second }}</strong> @lang('bon.per-second')</h3>
                            <h3><strong>{{ $minute }}</strong> @lang('bon.per-minute')</h3>
                            <h3><strong>{{ $daily }}</strong> @lang('bon.per-day')</h3>
                            <h3><strong>{{ $weekly }}</strong> @lang('bon.per-week')</h3>
                            <h3><strong>{{ $monthly }}</strong> @lang('bon.per-month')</h3>
                            <h3><strong>{{ $yearly }}</strong> @lang('bon.per-year')</h3>
                        </div>
                        <a href="{{ route('user_seeds', ['slug'=>auth()->user()->slug,'id'=>auth()->user()->id]) }}" class="btn btn-sm btn-primary">
                            @lang('bon.review-seeds')
                        </a>
                    </div>
                </div>
            </div>
            </div>
        </div>
    </div>

@endsection

@section('javascripts')
    <script type="text/javascript">
        $(document).ready(function () {
            $('.user-select-placeholder-single').select2()
        })
    </script>
@endsection