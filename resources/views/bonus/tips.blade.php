@extends('layout.default')

@section('title')
    <title>{{ $user->username }} {{ __('user.tips') }} - {{ config('other.title') }}</title>
@endsection

@section('breadcrumbs')
    <li class="breadcrumbV2">
        <a href="{{ route('users.show', ['username' => $user->username]) }}" class="breadcrumb__link">
            {{ $user->username }}
        </a>
    </li>
    <li class="breadcrumbV2">
        <a href="{{ route('earnings.index', ['username' => $user->username]) }}" class="breadcrumb__link">
            {{ __('bon.bonus') }} {{ __('bon.points') }}
        </a>
    </li>
    <li class="breadcrumb--active">
        {{ __('bon.tips') }}
    </li>
@endsection

@section('nav-tabs')
    @include('user.buttons.user')
@endsection

@section('page', 'page__bonus--tips')

@section('main')
    <section class="panelV2">
        <h2 class="panel__heading">{{ __('bon.tips') }}</h2>
        <table class="table table-condensed table-striped">
            <thead>
            <tr>
                <th>{{ __('bon.sender') }}</th>
                <th>{{ __('bon.receiver') }}</th>
                <th>{{ __('bon.points') }}</th>
                <th>{{ __('bon.date') }}</th>
            </tr>
            </thead>
            <tbody>
            @foreach($bontransactions as $b)
                <tr>
                    <td>
                        <a href="{{ route('users.show', ['username' => $b->senderObj->username]) }}">
                            <span class="badge-user text-bold">{{ $b->senderObj->username }}</span>
                        </a>
                    </td>
                    <td>
                        @if($b->whereNotNull('torrent_id'))
                            @php $torrent = App\Models\Torrent::select(['anon'])->find($b->torrent_id) @endphp
                        @endif
                        @if(isset($torrent) && $torrent->anon === 1 && $b->receiver !== $user->id)
                            <span class="badge-user text-bold">{{ __('common.anonymous') }}</span>
                        @else
                            <a href="{{ route('users.show', ['username' => $b->receiverObj->username]) }}">
                                <span class="badge-user text-bold">{{ $b->receiverObj->username }}</span>
                            </a>
                        @endif
                    </td>
                    <td>{{ $b->cost }}</td>
                    <td>{{ $b->date_actioned }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
        <div class="some-padding text-center">
            {{ $bontransactions->links() }}
        </div>
    </section>
@endsection

@section('sidebar')
    <section class="panelV2">
        <h2 class="panel__heading">{{ __('bon.your-points') }}</h2>
        <div class="panel__body">{{ $userbon }}</div>
    </section>
    <section class="panelV2">
        <h2 class="panel__heading">{{ __('bon.total-tips') }}</h2>
        <dl class="key-value">
            <dt>{{ __('bon.you-have-received-tips') }}</dt>
            <dd>{{ $tips_received }}</dd>
            <dt>{{ __('bon.you-have-sent-tips') }}</dt>
            <dd>{{ $tips_sent }}</dd>
        </div>
    </section>
@endsection
