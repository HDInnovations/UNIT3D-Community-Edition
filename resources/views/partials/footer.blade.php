<!-- Footer -->
<div id="l-footer" style="background-image: url('/img/footer/<?php echo $bgchange; ?>');">
  <div class="container">
    <div class="col-md-3 l-footer-section">
      <h2 class="l-footer-section-title"><span class="text-bold">{{ Config::get('other.title') }}</span></h2>
      <footer>{{ Config::get('other.meta_description') }}</footer>
      <br>
      <i class="fa fa-tv disk-good" style="font-size: 90px;"></i>
    </div>

    <!-- Account -->
    <div class="col-md-2 l-footer-section">
      <h2 class="l-footer-section-title">{{ trans('common.account') }}</h2>
      <ul>
        @if(Auth::check())
        <li><a href="{{ route('profil', array('username' => Auth::user()->username, 'id' => Auth::user()->id)) }}">My Profile</a></li>
        <li>
          <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a>
          <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">{{ csrf_field() }}</form>
        </li>
        @endif
      </ul>
    </div>
    <!-- /Account -->

    <!-- Community -->
    <div class="col-md-2 l-footer-section">
      <h2 class="l-footer-section-title">{{ trans('common.community') }}</h2>
      <ul>
        <li><a href="{{ route('forum_index') }}">Forums</a></li>
        <li><a href="{{ route('members') }}">{{ trans('common.members') }}</a></li>
        <li><a href="{{ route('articles') }}">News</a></li>
        <li><a href="{{ route('about') }}">About Us</a></li>
      </ul>
    </div>
    <!-- /Community -->

    <!-- Pages -->
    <div class="col-md-2 l-footer-section">
      <h2 class="l-footer-section-title">Pages</h2>
      <ul>
        <li><a href="{{ route('home') }}/p/rules.1">Rules</a></li>
        <li><a href="{{ route('home') }}/p/faq.3">FAQ</a></li>
        <li><a href="{{ route('home') }}/p/suggested-clients.4">Suggested Clients</a></li>
        <li><a href="{{ route('home') }}/p/tracker-codes.6">Tracker Codes</a></li>
        <li><a href="{{ route('home') }}/p/upload-guide.5">Upload Guide</a></li>
      </ul>
    </div>
    <!-- /Pages -->

    <!-- Legal -->
    <div class="col-md-2 l-footer-section">
      <h2 class="l-footer-section-title">Legal</h2>
      <ul>
        <li><a href="{{ route('home') }}/p/terms_of_use.7">Terms Of Use</a></li>
        <li><a href="#">Privacy Policy</a></li>
      </ul>
    </div>
    <!-- /Legal -->

    <!-- Other -->
    <div class="col-md-1 l-footer-section">
      <h2 class="l-footer-section-title">Other</h2>
      <a href="https://anon.to/?https://www.patreon.com/UNIT3D" class="btn btn-xs btn-primary">Become A Patron</a>
      <a href="https://anon.to/?https://github.com/UNIT3D/UNIT3D" class="btn btn-xs btn-primary">Powered By UNIT3D</a>
    </div>
    <!-- /Other -->

  </div>
</div>
<!-- /Footer -->
