@extends('layout.default')

@section('title')
    <title>Polls - {{ config('other.title') }}</title>
@endsection

@section('breadcrumbs')
    <li class="breadcrumbV2">
        <a href="{{ route('staff.dashboard.index') }}" class="breadcrumb__link">
            {{ __('staff.staff-dashboard') }}
        </a>
    </li>
    <li class="breadcrumb--active">
        {{ __('poll.polls') }}
    </li>
@endsection

@section('page', 'page__poll-admin--index')

@section('main')
    <section class="panelV2">
        <header class="panel__header">
            <h2 class="panel__heading">{{ __('poll.poll') }}</h2>
            <a href="{{ route('staff.polls.create') }}" class="form__button form__button--text">
                {{ __('common.add') }}
            </a>
        </header>
        <div class="data-table-wrapper">
            <table class="data-table">
                <thead>
                <tr>
                    <th>{{ __('poll.title') }}</th>
                    <th>{{ __('common.date') }}</th>
                    <th>{{ __('common.action') }}</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($polls as $poll)
                    <tr>
                        <td>
                            <a href="{{ route('staff.polls.show', ['id' => $poll->id]) }}">
                                {{ $poll->title }}
                            </a>
                        </td>
                        <td>
                            <time datetime="{{ $poll->created_at }}" title="{{ $poll->created_at }}">
                                {{ date('d M Y', $poll->created_at->getTimestamp()) }}
                            </time>
                        </td>
                        <td>
                            <menu class="data-table__actions">
                                <li class="data-table__action">
                                    <a
                                        href="{{ route('poll', ['id' => $poll->id]) }}"
                                        class="form__button form__button--text"
                                    >
                                        {{ __('common.view') }}
                                    </a>
                                </li>
                                <li class="data-table__action">
                                    <a
                                        href="{{ route('staff.polls.edit', ['id' => $poll->id]) }}"
                                        class="form__button form__button--text"
                                    >
                                        {{ __('common.edit') }}
                                    </a>
                                </li>
                                <li class="data-table__action">
                                    <form
                                        action="{{ route('staff.polls.destroy', ['id' => $poll->id]) }}"
                                        method="POST"
                                        x-data
                                    >
                                        @csrf
                                        @method('DELETE')
                                        <button 
                                            x-on:click.prevent="Swal.fire({
                                                title: 'Are you sure?',
                                                text: 'Are you sure you want to delete this poll: {{ $poll->title }}?',
                                                icon: 'warning',
                                                showConfirmButton: true,
                                                showCancelButton: true,
                                            }).then((result) => {
                                                if (result.isConfirmed) {
                                                    $root.submit();
                                                }
                                            })"
                                            class="form__button form__button--text"
                                        >
                                            {{ __('common.delete') }}
                                        </button>
                                    </form>
                                </li>
                            </menu>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </section>
@endsection
