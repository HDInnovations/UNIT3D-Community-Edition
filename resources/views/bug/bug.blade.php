@extends('layout.default')

@section('breadcrumb')
    <li>
        <a href="{{ route('bug') }}" itemprop="url" class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">@lang('bug.bug-report')</span>
        </a>
    </li>
@endsection

@section('content')
    <div class="container">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <h4 class="text-center">@lang('bug.bug-report-description')</h4></div>
            <form role="form" method="POST" action="{{ route('postBug') }}">
            <table class="table table-bordered">
                <tbody>
                    @csrf
                    <tr>
                        <td class="rowhead">@lang('common.reporter'):</td>
                        <td>@lang('bug.enter-username')
                            <br>
                            <label>
                                <input type="text" class="form-control" name="username"
                                       value="{{ auth()->user()->username }}" size="60" required>
                            </label>
                        </td>
                    </tr>
                    <tr>
                        <td class="rowhead">@lang('common.email'):</td>
                        <td>@lang('bug.enter-email')
                            <br>
                            <label>
                                <input type="email" class="form-control" name="email" value="{{ auth()->user()->email }}"
                                       size="60" required>
                            </label>
                        </td>
                    </tr>
                    <tr>
                        <td class="rowhead">@lang('common.title'):</td>
                        <td>@lang('bug.enter-title')
                            <br>
                            <label>
                                <input type="text" class="form-control" name="title" size="60" required>
                            </label>
                        </td>
                    </tr>
                    <tr>
                        <td class="rowhead">@lang('common.description'):</td>
                        <td>@lang('bug.enter-description')
                            <br>
                            <label>
                                <textarea cols="60" rows="10" class="form-control" name="problem"></textarea>
                            </label>
                        </td>
                    </tr>
                    <tr>
                        <td class="rowhead">@lang('bug.priority'):</td>
                        <td>@lang('bug.priority-description')
                            <br>
                            <label>
                                <select class="form-control" name="priority">
                                    <option value="0">@lang('common.select')</option>
                                    <option value="low">@lang('bug.low')</option>
                                    <option value="high">@lang('bug.high')</option>
                                    <option value="veryhigh">@lang('bug.very-high')</option>
                                </select>
                            </label>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            <button class="btn btn-labeled btn-danger" type="submit"><span class="btn-label"><i
                                            class="{{ config('other.font-awesome') }} fa-bug"></i></span>@lang('common.submit')</button>
                        </td>
                    </tr>
                </tbody>
            </table>
            </form>
        </div>
    </div>
@endsection
