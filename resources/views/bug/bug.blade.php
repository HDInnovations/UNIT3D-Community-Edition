@extends('layout.default')

@section('breadcrumb')
<li>
  <a href="{{ route('bug') }}" itemprop="url" class="l-breadcrumb-item-link">
    <span itemprop="title" class="l-breadcrumb-item-link-title">{{ trans('bug.bug-report') }}</span>
  </a>
</li>
@endsection

@section('content')
<div class="container">
    <div class="panel panel-primary">
      <div class="panel-heading">
        <h4 class="text-center">{{ trans('bug.bug-report-description') }}</h4></div>
        <table class="table table-bordered">
          <tbody>
            <form role="form" method="POST" action="{{ route('bug') }}">
            {{ csrf_field() }}
            <tr>
              <td class="rowhead">{{ trans('common.reporter') }}:</td>
              <td>{{ trans('bug.enter-username') }}
                <br>
                <input type="text" class="form-control" name="username" value="{{ auth()->user()->username }}" size="60" required>
              </td>
            </tr>
            <tr>
              <td class="rowhead">{{ trans('common.email') }}:</td>
              <td>{{ trans('bug.enter-email') }}
                <br>
                <input type="email" class="form-control" name="email" value="{{ auth()->user()->email }}" size="60" required>
              </td>
            </tr>
            <tr>
              <td class="rowhead">{{ trans('common.title') }}:</td>
              <td>{{ trans('bug.enter-title') }}
                <br>
                <input type="text" class="form-control" name="title" size="60" required>
              </td>
            </tr>
            <tr>
              <td class="rowhead">{{ trans('common.description') }}:</td>
              <td>{{ trans('bug.enter-description') }}
                <br>
                <textarea cols="60" rows="10" class="form-control" name="problem"></textarea>
              </td>
            </tr>
            <tr>
              <td class="rowhead">{{ trans('bug.priority') }}:</td>
              <td>{{ trans('bug.priority-description') }}
                <br>
                <select class="form-control" name="priority">
                  <option value="0">{{ trans('common.select') }}</option>
                  <option value="low">{{ trans('bug.low') }}</option>
                  <option value="high">{{ trans('bug.high') }}</option>
                  <option value="veryhigh">{{ trans('bug.very-high') }}</option>
                </select>
              </td>
            </tr>
            <tr>
              <td colspan="2" align="center">
                <button class="btn btn-labeled btn-danger" type="submit"><span class="btn-label"><i class="fa fa-bug"></i></span>{{ trans('common.submit') }}</button>
              </td>
            </tr>
            </form>
          </tbody>
        </table>
    </div>
</div>
@endsection
