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
    <li class="breadcrumb--active">Create Gateway</li>
@endsection

@section('content')
    <section class="panelV2">
        <header class="panel__header">
            <h2 class="panel__heading">Add New Gateway</h2>
        </header>
        <div class="data-table-wrapper">
            <form role="form" method="POST" action="{{ route('staff.gateways.store') }}">
                @csrf
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
                                    type="number"
                                    name="position"
                                    value=""
                                    placerholder="0"
                                    class="form__text"
                                />
                            </td>
                            <td>
                                <input
                                    type="text"
                                    name="name"
                                    value=""
                                    placeholder="Name"
                                    class="form__text"
                                />
                            </td>
                            <td>
                                <input
                                    type="text"
                                    name="address"
                                    value=""
                                    placeholder="Address"
                                    class="form__text"
                                />
                            </td>
                            <td>
                                <input name="is_active" type="hidden" value="0" />
                                <input
                                    id="is_active"
                                    class="form__checkbox"
                                    name="is_active"
                                    type="checkbox"
                                    value="1"
                                />
                            </td>
                        </tr>
                    </tbody>
                </table>
                <button type="submit" class="form__button form__button--filled">Create</button>
            </form>
        </div>
    </section>
@endsection
