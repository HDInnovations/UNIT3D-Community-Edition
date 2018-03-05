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
            <div class='title' name='title' id='title' value='{{ $p["name"] }}'>{{ $p['name'] }}</div>
            <div class='price' name='price' id='price' value='{{ $p["price"] }}'>${{ $p['price'] / 100 }}</div>
            <div class='text'>
            <ul class="list-unstyled">
                <li>Supporter For <div class='time' name='time' id='time' value='{{ $p["time"] }}'>{{ $p['time'] }}</div> Days</li>
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
        data-key=""
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
    <div class="row">
    <div class="well col-md-6">
    <h1 class="text-pink text-bold">
        <center>
            Supporter Perks:
        <h3 class="text-pink text-bold">
            Supporter Group includes the following benifits.
            Everything is Freeleech for you,
            You are hit and run immune,
            You are immune to upload moderation, meaning your uploads bypass staff checks before posting to site.
            Last but not least you get a cool badge letting the community know you have supported the site through a donation.
            Thank you for supporting Blutopia!
        </h3>
        </center>
    </h1>
    </div>

    <div class="well col-md-6">
    <h1 class="text-orange text-bold">
        <center>
            Info:
        <h3 class="text-orange text-bold">
            Payments are handled securely via StripeCheckout.
            Stripe accepts the following methods:
            Visa (credit and debit cards),
            MasterCard (credit and debit cards),
            American Express and Discover (US only).
            This allows one to go buy a throw away visa gift card if you want to be more anonymous.
            We do not store any card information. Everything payment wise is handeld via Stripe.
        </h3>
        </center>
    </h1>
    </div>
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
