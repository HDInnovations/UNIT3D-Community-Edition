@extends('layout.default')

@section('content')
<div class="container box">
  <h2>Blocks</h2>
  <table class="table table-bordered table-hover">
    <div class="togglebutton switch-sidebar-image">
      <label>Search Block:
        <input type="checkbox">
      </label>
    </div>
    <div class="togglebutton switch-sidebar-image">
      <label>Hottest Content Block:
        <input type="checkbox" checked="">
      </label>
    </div>
    <div class="togglebutton switch-sidebar-image">
      <label>Lastest Torrents Block:
        <input type="checkbox" checked="">
      </label>
    </div>
    <div class="togglebutton switch-sidebar-image">
      <label>Latest Forums Block:
        <input type="checkbox" checked="">
      </label>
    </div>
    <div class="togglebutton switch-sidebar-image">
      <label>Newest Members Block:
        <input type="checkbox" checked="">
      </label>
    </div>
    <div class="togglebutton switch-sidebar-image">
      <label>Site Stats Block:
        <input type="checkbox" checked="">
      </label>
    </div>
    <div class="togglebutton switch-sidebar-image">
      <label>Online Block:
        <input type="checkbox" checked="">
      </label>
    </div>
  </table>
</div>
@stop
