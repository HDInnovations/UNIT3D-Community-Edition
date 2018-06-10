@extends('layout.default')

@section('title')
    <title>MassPM - Staff Dashboard - {{ config('other.title') }}</title>
@endsection

@section('meta')
    <meta name="description" content="MassPM - Staff Dashboard">
@endsection

@section('breadcrumb')
    <li>
        <a href="{{ route('staff_dashboard') }}" itemprop="url" class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">Staff Dashboard</span>
        </a>
    </li>
    <li class="active">
        <a href="{{ route('massPM') }}" itemprop="url" class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">MassPM</span>
        </a>
    </li>
@endsection

@section('content')
    <div class="container box">
        <h2>Mass PM</h2>
        <form action="{{ route('sendMassPM') }}" method="post">
            {{ csrf_field() }}
            <div class="form-group">
                <label for="subject">Subject</label>
                <input type="text" class="form-control" name="subject">
            </div>

            <div class="form-group">
                <label for="message">Message</label>
                <textarea id="message" name="message" cols="30" rows="10" class="form-control"></textarea>
            </div>

            <button type="submit" class="btn btn-default">Send</button>
        </form>
    </div>
@endsection

@section('javascripts')
    <script>
      $(document).ready(function () {
        $('#message').wysibb({})
        emoji.textcomplete()
      })
    </script>
@endsection
