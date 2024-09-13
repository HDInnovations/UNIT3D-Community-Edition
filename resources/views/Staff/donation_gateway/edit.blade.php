@extends('layout.default')

@section('breadcrumbs')
    <li class="breadcrumbV2">
        <a href="{{ route('staff.dashboard.index') }}" class="breadcrumb__link">
            {{ __('staff.staff-dashboard') }}
        </a>
    </li>
    <li class="breadcrumbV2">
        <a href="{{ route('staff.gateways.index') }}" class="breadcrumb__link">Gateways</a>
    </li>
    <li class="breadcrumb--active">Edit Gateway</li>
@endsection

@section('content')
    <section class="panelV2">
        <header class="panel__header">
            <h2 class="panel__heading">Edit: {{ $gateway->name }}</h2>
        </header>
        <div class="data-table-wrapper">
            <form
                role="form"
                method="POST"
                action="{{ route('staff.gateways.update', ['gateway' => $gateway]) }}"
            >
                @csrf
                @method('PATCH')
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Position</th>
                            <th>Name</th>
                            <th>Address</th>
                            <th>Active</th>
                        </tr>
                    </thead>

                    <tbody>
                        <tr>
                            <td>
                                <input
                                    type="text"
                                    name="position"
                                    value="{{ $gateway->position }}"
                                    class="form__text"
                                />
                            </td>
                            <td>
                                <input
                                    type="text"
                                    name="name"
                                    value="{{ $gateway->name }}"
                                    class="form__text"
                                />
                            </td>
                            <td>
                                <input
                                    type="text"
                                    name="address"
                                    value="{{ $gateway->address }}"
                                    class="form__text"
                                />
                            </td>
                            <td>
                                <input
                                    type="checkbox"
                                    name="is_active"
                                    value="1"
                                    @if ($gateway->is_active) checked @endif
                                />
                            </td>
                        </tr>
                    </tbody>
                </table>
                <button type="submit" class="form__button form__button--filled">Update</button>
            </form>
        </div>
    </section>
@endsection
