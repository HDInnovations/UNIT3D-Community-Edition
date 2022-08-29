@extends('layout.default')

@section('breadcrumb')
    <li>
        <a href="{{ route('staff.dashboard.index') }}" itemprop="url" class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">{{ __('staff.staff-dashboard') }}</span>
        </a>
    </li>
    <li class="active">
        <a href="#" itemprop="url" class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">Blacklists</span>
        </a>
    </li>
    <li class="active">
        <a href="{{ route('staff.blacklists.clients.index') }}" itemprop="url" class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">Clients</span>
        </a>
    </li>
@endsection

@section('content')
    <div class="container box">
        <h2>Blacklist Clients</h2>
        <a href="{{ route('staff.blacklists.clients.create') }}" class="btn btn-primary">Add new blacklisted client</a><br><br>
        <div class="table-responsive">
            <table class="table table-condensed table-striped table-bordered table-hover" style="table-layout:fixed;">
                <thead>
                <tr>
                    <th width="5%">ID</th>
                    <th>{{ __('common.name') }}</th>
                    <th>Reason</th>
                    <th>Created at</th>
                    <th width="15%">{{ __('common.action') }}</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($clients as $client)
                    <tr>
                        <td>
                            {{ $client->id }}
                        </td>
                        <td>
                            {{ $client->name }}
                        </td>
                        <td style="word-wrap:break-word;">
                            {{ $client->reason }}
                        </td>
                        <td>
                            {{ \Carbon\Carbon::parse($client->created_at)->format('Y-m-d')}}
                        </td>
                        <td>
                            <form action="{{ route('staff.blacklists.clients.destroy', ['id' => $client->id]) }}"
                                  method="POST">
                                @csrf
                                @method('DELETE')
                                <a href="{{ route('staff.blacklists.clients.edit', ['id' => $client->id]) }}"
                                   class="btn btn-warning">{{ __('common.edit') }}</a>
                                <button type="submit" class="btn btn-danger">{{ __('common.delete') }}</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
