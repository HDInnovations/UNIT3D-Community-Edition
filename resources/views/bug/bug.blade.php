@extends('layout.default')

@section('breadcrumb')
<li>
  <a href="{{ route('bug') }}" itemprop="url" class="l-breadcrumb-item-link">
    <span itemprop="title" class="l-breadcrumb-item-link-title">Bug Report</span>
  </a>
</li>
@stop

@section('content')
<div class="container">
    <div class="panel panel-primary">
      <div class="panel-heading">
        <h4 class="text-center">Report A Site Bug</h4></div>
        <table class="table table-bordered">
          <tbody>
            {{ Form::open(array('route' => 'bug')) }}
            <tr>
              <td class="rowhead">Reporter:</td>
              <td>Please enter your username
                <br>
                <input type="text" class="form-control" name="username" size="60" required>
              </td>
            </tr>
            <tr>
              <td class="rowhead">Email:</td>
              <td>Please enter your email
                <br>
                <input type="email" class="form-control" name="email" size="60" required>
              </td>
            </tr>
            <tr>
              <td class="rowhead">Title:</td>
              <td>Please choose a proper title
                <br>
                <input type="text" class="form-control" name="title" size="60" required>
              </td>
            </tr>
            <tr>
              <td class="rowhead">Problem (Bug):</td>
              <td>Describe the problem as best as possible
                <br>
                <textarea cols="60" rows="10" class="form-control" name="problem" required></textarea>
              </td>
            </tr>
            <tr>
              <td class="rowhead">Priority:</td>
              <td>Choose only very high if the bug really is a problem for using the site.
                <br>
                <select class="form-control" name="priority">
                  <option value="0">Select one</option>
                  <option value="low">Low</option>
                  <option value="high">High</option>
                  <option value="veryhigh">Very High</option>
                </select>
              </td>
            </tr>
            <tr>
              <td colspan="2" align="center">
                <button class="btn btn-labeled btn-danger" type="submit"><span class="btn-label"><i class="fa fa-bug"></i></span>Send this bug!</button>
              </td>
            </tr>
            {{ Form::close() }}
          </tbody>
        </table>
    </div>
</div>
@stop
