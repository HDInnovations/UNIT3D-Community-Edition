@extends('layout.default')

@section('title')
<title>{{ trans('donation.packages') }} - {{ Config::get('other.title') }}</title>
@stop

@section('stylesheets')
<link rel="stylesheet" href="{{ url('css/main/donation.css?v=01') }}">
@stop

@section('breadcrumb')
<li>
  <a href="{{ route('packages') }}" itemprop="url" class="l-breadcrumb-item-link">
    <span itemprop="title" class="l-breadcrumb-item-link-title">{{ Config::get('other.title') }} {{ trans('donation.donation') }} {{ trans('donation.packages') }}</span>
  </a>
</li>
@stop

@section('content')
<div class="container box">
  <div class="col-md-12 page">
    <div class="header gradient yellow">
      <div class="inner_content">
        <div class="page-title">
          <h1>{{ Config::get('other.title') }} {{ trans('donation.donation') }} {{ trans('donation.packages') }}</h1></div>
      </div>
    </div>
    <br>
    <br>
    <h2 class="text-center text-bold text-success"><span class="fa fa-star text-gold"></span> {{ Auth::user()->username }} Thank You For Visiting The Donation Page  <span class="fa fa-star text-gold"></span></h2>
    <br>
    <center>
    @foreach(config('donation.packages') as $p)
      <section class='card'>
        <div class='card_inner'>
          <div class='card_inner__circle'>
            <img src='{{ url("img/rocket.png") }}'>
          </div>
          <div class='card_inner__header'>
            <img src="{{ url($p['image']) }}">
          </div>
          <div class='card_inner__content'>
            <div class='title' name='title'>{{ $p['name'] }}</div>
            <div class='price' name='price'>${{ $p['price'] / 100 }}</div>
            <div class='text'>
            <ul class="list-unstyled">
                <li>Supporter For <div name='time'>{{ $p['time'] }}</div> Days</li>
            </ul>
            </div>
          </div>
          <div class='card_inner__cta'>
              <form action="/donation/charge" method="POST">
    @csrf
    <input type="hidden" name="amount" value="{{ $p['price'] }}">
    <input
        src="https://checkout.stripe.com/checkout.js"
        type="submit"
        class="btn btn-primary btn-lg"
        value="{{ trans('donation.get') }}"
        data-key="pk_test_CVYw1q2ylpsDxghdm7YaKsj4"
        data-amount="{{ $p['price'] }}"
        data-currency="usd"
        data-locale="auto"
        data-name="{{ $p['name'] }}"
        data-description="{{ $p['name'] }} Donation Package"
        data-image="https://unit3d.org/img/rocket.png"
        data-email="{{ Auth::user()->email }}"
    />
</form>
          </div>
        </div>
      </section>
      @endforeach
    </center>
    <br>
    <br>
    <br>
    <div class="well">
    <h1 class="text-pink text-bold">
        <center>
            Supporter Group includes the following benifits.
            Everything is Freeleech for you,
            You are hit and run immune,
            You are immune to moderation meaning you uploads bypass staff checks before posting to site
            and last but not least you get a cool badge letting the community you have supported the site through a donation.
        </center>
    </div>
    </div>
  </div>
@endsection

@section('javascripts')
<script src="https://checkout.stripe.com/checkout.js"></script>
<script>
    $(function() {
        $(':submit').on('click', function(event) {
            event.preventDefault();
            var $button = $(this),
                $form = $button.parents('form');
            var opts = $.extend({}, $button.data(), {
                token: function(result) {
                    $form.append($('<input>').attr({ type: 'hidden', name: 'stripeToken', value: result.id })).submit();
                }
            });
            StripeCheckout.open(opts);
        });
    });
</script>
@endsection
