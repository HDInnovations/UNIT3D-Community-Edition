@extends('layout.default')

@section('breadcrumb')
    <li>
        <a href="{{ route('bonus') }}" itemprop="url" class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">{{ __('bon.bonus') }} {{ __('bon.points') }}</span>
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
                            <div class="button-holder" id="userExtension" extension="bonExtension">
                                <div class="button-left">
                                    <h3>{{ __('bon.earnings') }}</h3>
                                </div>
                                <div class="button-right">
                                    <span class="small">{{ __('bon.extended-stats') }} :</span>
                                    <label for="extended"></label><input type="checkbox" value="1" name="extended"
                                                                         id="extended">
                                </div>
                            </div>
                            <table class="table table-condensed table-striped">
                                <tbody>
                                <tr>
                                    <td>
                                        <strong>{{ __('torrent.dying-torrent') }}</strong><br>
                                        <small>{{ __('torrent.last-seeder') }}</small>
                                    </td>
                                    <td><strong>{{ $dying }} x 2</strong></td>
                                    <td>
                                        {{ $dying * 2 }} {{ __('bon.per-hour') }}<br/>
                                        <span class="bonExtension" style="display: none;">
                                                {{ $dying * 2 * 24 }} {{ __('bon.per-day') }}<br/>
                                                {{ $dying * 2 * 24 * 7 }} {{ __('bon.per-week') }}<br/>
                                                {{ $dying * 2 * 24 * 30 }} {{ __('bon.per-month') }}<br/>
                                            </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <strong>{{ __('torrent.legendary-torrent') }}</strong><br>
                                        <small>{{ __('common.older-than') }} 12
                                            {{ strtolower(__('common.months')) }}</small>
                                    </td>
                                    <td><strong>{{ $legendary }} x 1.5</strong></td>
                                    <td>
                                        {{ $legendary * 1.5 }} {{ __('bon.per-hour') }}<br/>
                                        <span class="bonExtension" style="display: none;">
                                                {{ $legendary * 1.5 * 24 }} {{ __('bon.per-day') }}<br/>
                                                {{ $legendary * 1.5 * 24 * 7 }} {{ __('bon.per-week') }}<br/>
                                                {{ $legendary * 1.5 * 24 * 30 }} {{ __('bon.per-month') }}<br/>
                                            </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <strong>{{ __('torrent.old-torrent') }}</strong><br>
                                        <small>{{ __('common.older-than') }} 6
                                            {{ strtolower(__('common.months')) }}</small>
                                    </td>
                                    <td><strong>{{ $old }} x 1</strong></td>
                                    <td>
                                        {{ $old * 1 }} {{ __('bon.per-hour') }}<br/>
                                        <span class="bonExtension" style="display: none;">
                                                {{ $old * 1 * 24 }} {{ __('bon.per-day') }}<br/>
                                                {{ $old * 1 * 24 * 7 }} {{ __('bon.per-week') }}<br/>
                                                {{ $old * 1 * 24 * 30 }} {{ __('bon.per-month') }}<br/>
                                            </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <strong>{{ __('common.huge') }} {{ __('torrent.torrents') }}</strong><br>
                                        <small>{{ __('torrent.torrent') }} {{ __('torrent.size') }}<span
                                                    class="text-bold">></span> 100GiB
                                        </small>
                                    </td>
                                    <td><strong>{{ $huge }} x 0.75</strong></td>
                                    <td>
                                        {{ $huge * 0.75 }} {{ __('bon.per-hour') }}<br/>
                                        <span class="bonExtension" style="display: none;">
                                                {{ $huge * 0.75 * 24 }} {{ __('bon.per-day') }}<br/>
                                                {{ $huge * 0.75 * 24 * 7 }} {{ __('bon.per-week') }}<br/>
                                                {{ $huge * 0.75 * 24 * 30 }} {{ __('bon.per-month') }}<br/>
                                            </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <strong>{{ __('common.large') }} {{ __('torrent.torrents') }}</strong><br>
                                        <small>{{ __('torrent.torrent') }} {{ __('torrent.size') }}<span
                                                    class="text-bold">>=</span>
                                            25GiB {{ strtolower(__('common.but')) }}
                                            < 100GiB </small></td>
                                    <td><strong>{{ $large }} x 0.50</strong></td>
                                    <td>
                                        {{ $large * 0.5 }} {{ __('bon.per-hour') }}<br/>
                                        <span class="bonExtension" style="display: none;">
                                                {{ $large * 0.5 * 24 }} {{ __('bon.per-day') }}<br/>
                                                {{ $large * 0.5 * 24 * 7 }} {{ __('bon.per-week') }}<br/>
                                                {{ $large * 0.5 * 24 * 30 }} {{ __('bon.per-month') }}<br/>
                                            </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <strong>{{ __('common.everyday') }} {{ __('torrent.torrents') }}</strong><br>
                                        <small>{{ __('torrent.torrent') }} {{ __('torrent.size') }}<span class="text-bold">
                                                    >=</span> 1GiB {{ strtolower(__('common.but')) }}
                                            < 25GiB </small></td>
                                    <td><strong>{{ $regular }} x 0.25</strong></td>
                                    <td>
                                        {{ $regular * 0.25 }} {{ __('bon.per-hour') }}<br/>
                                        <span class="bonExtension" style="display: none;">
                                                {{ $regular * 0.25 * 24 }} {{ __('bon.per-day') }}<br/>
                                                {{ $regular * 0.25 * 24 * 7 }} {{ __('bon.per-week') }}<br/>
                                                {{ $regular * 0.25 * 24 * 30 }} {{ __('bon.per-month') }}<br/>
                                            </span>
                                    </td>
                                </tr>

                                <tr>
                                    <td>
                                        <strong>{{ __('torrent.legendary-seeder') }}</strong><br>
                                        <small>{{ __('torrent.seed-time') }} <span class="text-bold">>=</span>
                                            1 {{ strtolower(__('common.year')) }}</small>
                                    </td>
                                    <td><strong>{{ $legend }} x 2</strong></td>
                                    <td>
                                        {{ $legend * 2 }} {{ __('bon.per-hour') }}<br/>
                                        <span class="bonExtension" style="display: none;">
                                                {{ $legend * 2 * 24 }} {{ __('bon.per-day') }}<br/>
                                                {{ $legend * 2 * 24 * 7 }} {{ __('bon.per-week') }}<br/>
                                                {{ $legend * 2 * 24 * 30 }} {{ __('bon.per-month') }}<br/>
                                            </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <strong>{{ __('torrent.mvp') }} {{ __('torrent.seeder') }}</strong><br>
                                        <small>{{ __('torrent.seed-time') }} <span class="text-bold">>=</span>
                                            6 {{ strtolower(__('common.months')) }}
                                            {{ strtolower(__('common.but')) }}
                                            < 1 {{ strtolower(__('common.year')) }}</small></td>
                                    <td>
                                        <strong>{{ $mvp }} x 1</strong></td>
                                    <td>
                                        {{ $mvp * 1 }} {{ __('bon.per-hour') }}<br/>
                                        <span class="bonExtension" style="display: none;">
                                                {{ $mvp * 1 * 24 }} {{ __('bon.per-day') }}<br/>
                                                {{ $mvp * 1 * 24 * 7 }} {{ __('bon.per-week') }}<br/>
                                                {{ $mvp * 1 * 24 * 30 }} {{ __('bon.per-month') }}<br/>
                                            </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <strong>{{ __('torrent.commited') }} {{ __('torrent.seeder') }}</strong><br>
                                        <small>{{ __('torrent.seed-time') }} <span class="text-bold">>=</span>
                                            3 {{ strtolower(__('common.months')) }}
                                            {{ strtolower(__('common.but')) }}
                                            < 6 {{ strtolower(__('common.months')) }}</small></td>
                                    <td>
                                        <strong>{{ $committed }} x 0.75</strong></td>
                                    <td>
                                        {{ $committed * 0.75 }} {{ __('bon.per-hour') }}<br/>
                                        <span class="bonExtension" style="display: none;">
                                                {{ $committed * 0.75 * 24 }} {{ __('bon.per-day') }}<br/>
                                                {{ $committed * 0.75 * 24 * 7 }} {{ __('bon.per-week') }}<br/>
                                                {{ $committed * 0.75 * 24 * 30 }} {{ __('bon.per-month') }}<br/>
                                            </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <strong>{{ __('torrent.team-player') }} {{ __('torrent.seeder') }}</strong><br>
                                        <small>{{ __('torrent.seed-time') }} <span class="text-bold">>=</span>
                                            2 {{ strtolower(__('common.months')) }}
                                            {{ strtolower(__('common.but')) }}
                                            < 3 {{ strtolower(__('common.months')) }}</small></td>
                                    <td>
                                        <strong>{{ $teamplayer }} x 0.50</strong></td>
                                    <td>
                                        {{ $teamplayer * 0.5 }} {{ __('bon.per-hour') }}<br/>
                                        <span class="bonExtension" style="display: none;">
                                                {{ $teamplayer * 0.5 * 24 }} {{ __('bon.per-day') }}<br/>
                                                {{ $teamplayer * 0.5 * 24 * 7 }} {{ __('bon.per-week') }}<br/>
                                                {{ $teamplayer * 0.5 * 24 * 30 }} {{ __('bon.per-month') }}<br/>
                                            </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <strong>{{ __('torrent.participant') }} {{ __('torrent.seeder') }}</strong><br>
                                        <small>{{ __('torrent.seed-time') }} <span class="text-bold">>=</span>
                                            1 {{ strtolower(__('common.month')) }}
                                            {{ strtolower(__('common.but')) }}
                                            < 2 {{ strtolower(__('common.months')) }}</small></td>
                                    <td>
                                        <strong>{{ $participant }} x 0.25</strong></td>
                                    <td>
                                        {{ $participant * 0.25 }} {{ __('bon.per-hour') }}<br/>
                                        <span class="bonExtension" style="display: none;">
                                                {{ $participant * 0.25 * 24 }} {{ __('bon.per-day') }}<br/>
                                                {{ $participant * 0.25 * 24 * 7 }} {{ __('bon.per-week') }}<br/>
                                                {{ $participant * 0.25 * 24 * 30 }} {{ __('bon.per-month') }}<br/>
                                            </span>
                                    </td>
                                </tr>
                                </tbody>
                                <tfoot>
                                <tr>
                                    <td>
                                        <strong>{{ __('bon.total') }}</strong><br>
                                        <small></small>
                                    </td>
                                    <td>-</td>
                                    <td>
                                        <strong>{{ $total }}</strong> {{ __('bon.per-hour') }}<br/>
                                        <span class="bonExtension" style="display: none;">
                                                <strong>{{ $total * 24 }}</strong> {{ __('bon.per-day') }}<br/>
                                                <strong>{{ $total * 24 * 7 }}</strong> {{ __('bon.per-week') }}<br/>
                                                <strong>{{ $total * 24 * 30 }}</strong> {{ __('bon.per-month') }}<br/>
                                            </span>
                                    </td>
                                </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="text-blue well well-sm text-center">
                            <h2><strong>{{ __('bon.your-points') }}: <br></strong>{{ $userbon }}</h2>
                        </div>
                        <div class="text-orange well well-sm text-center">
                            <h2>{{ __('bon.earning') }} <strong>{{ $total }}</strong> {{ __('bon.per-hour') }}</h2>
                            {{ __('bon.earning-rate') }}
                            <div class="some-padding text-center">
                                <h3><strong>{{ $second }}</strong> {{ __('bon.per-second') }}</h3>
                                <h3><strong>{{ $minute }}</strong> {{ __('bon.per-minute') }}</h3>
                                <h3><strong>{{ $daily }}</strong> {{ __('bon.per-day') }}</h3>
                                <h3><strong>{{ $weekly }}</strong> {{ __('bon.per-week') }}</h3>
                                <h3><strong>{{ $monthly }}</strong> {{ __('bon.per-month') }}</h3>
                                <h3><strong>{{ $yearly }}</strong> {{ __('bon.per-year') }}</h3>
                            </div>
                            <a href="{{ route('user_seeds', ['username' => auth()->user()->username]) }}"
                               class="btn btn-sm btn-primary">
                                {{ __('bon.review-seeds') }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
