@extends('layout.default')

@section('breadcrumb')
    <li>
        <a href="{{ route('staff_dashboard') }}" itemprop="url" class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">Staff Dashboard</span>
        </a>
    </li>
    <li>
        <a href="{{ route('staff_groups_index') }}" itemprop="url" class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">User Groups</span>
        </a>
    </li>
    <li class="active">
        <a href="{{ route('staff_groups_add_form') }}" itemprop="url" class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">Add User Group</span>
        </a>
    </li>
@endsection

@section('content')
    <div class="container box">
        <h2>Add New Group</h2>
        <div class="table-responsive">
            <form role="form" method="POST" action="{{ route('staff_groups_add') }}">
                @csrf
                <div class="table-responsive">
                <table class="table table-condensed table-striped table-bordered table-hover">
                    <thead>
                    <tr>
                        <th>Name</th>
                        <th>Position</th>
                        <th>Level</th>
                        <th>Color</th>
                        <th>Icon</th>
                        <th>Effect</th>
                        <th>Internal</th>
                        <th>Modo</th>
                        <th>Admin</th>
                        <th>Trusted</th>
                        <th>Immune</th>
                        <th>Freeleech</th>
                        <th>Incognito</th>
                        <th>Upload</th>
                        <th>Autogroup</th>
                    </tr>
                    </thead>

                    <tbody>
                    <tr>
                        <td>
                            <input type="text" name="name" value="" placeholder="Name" class="form-control"/>
                        </td>
                        <td>
                            <input type="number" name="position" value="" placeholder="Position"
                                   class="form-control"/>
                        </td>
                        <td>
                            <input type="number" name="level" value="" placeholder="Level"
                                   class="form-control"/>
                        </td>
                        <td>
                            <input type="text" name="color" value="" placeholder="HEX Color ID"
                                   class="form-control"/>
                        </td>
                        <td>
                            <input type="text" name="icon" value="" placeholder="FontAwesome Icon"
                                   class="form-control"/></td>
                        <td>
                            <input type="text" name="effect" value="none" placeholder="GIF Effect"
                                   class="form-control"/>
                        </td>
                        <td>
                            <input type="hidden" name="is_internal" value="0">
                            <input type="checkbox" name="is_internal" value="1">
                        </td>
                        <td>
                            <input type="hidden" name="is_modo" value="0">
                            <input type="checkbox" name="is_modo" value="1">
                        </td>
                        <td>
                            <input type="hidden" name="is_admin" value="0">
                            <input type="checkbox" name="is_admin" value="1">
                        </td>
                        <td>
                            <input type="hidden" name="is_trusted" value="0">
                            <input type="checkbox" name="is_trusted" value="1">
                        </td>
                        <td>
                            <input type="hidden" name="is_immune" value="0">
                            <input type="checkbox" name="is_immune" value="1">
                        </td>
                        <td>
                            <input type="hidden" name="is_freeleech" value="0">
                            <input type="checkbox" name="is_freeleech" value="1">
                        </td>
                        <td>
                            <input type="hidden" name="is_incognito" value="0">
                            <input type="checkbox" name="is_incognito" value="1">
                        </td>
                        <td>
                            <input type="hidden" name="can_upload" value="0">
                            <input type="checkbox" name="can_upload" value="1">
                        </td>
                        <td>
                            <input type="hidden" name="autogroup" value="0">
                            <input type="checkbox" name="autogroup" value="1">
                        </td>
                    </tr>
                    </tbody>
                </table>
                </div>
                <button type="submit" class="btn btn-primary">@lang('common.add')</button>
            </form>
        </div>
    </div>
@endsection
