@extends('layout.default')

@section('title')
    <title>@lang('notification.notifications') - {{ config('other.title') }}</title>
@endsection

@section('breadcrumb')
    <li>
        <a href="{{ route('notifications.index') }}" itemprop="url" class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">@lang('notification.notifications')</span>
        </a>
    </li>
@endsection

@section('content')
    <!-- Search -->
    <div class="container box">
        <div class="text-center">
            <h3 class="filter-title">Filter By Notification Type</h3>
        </div>
        <form role="form" method="GET" action="NotificationController@index" class="form-condensed">
            @csrf
            <div class="form-group text-center">
                <div class="col-md-12">
                    <span class="badge-user">
                        <label class="inline">
                            <input type="checkbox" id="bon_gifts" value="1" class="filter-checkbox">
                            <i class="{{ config('other.font-awesome') }} fa-coins text-success"></i> Bon Gifts
                        </label>
                    </span>
                    <span class="badge-user">
                        <label class="inline">
                            <input type="checkbox" id="comments" value="1" class="filter-checkbox">
                            <i class="{{ config('other.font-awesome') }} fa-comments text-success"></i> Comments
                        </label>
                    </span>
                    <span class="badge-user">
                        <label class="inline">
                            <input type="checkbox" id="comment_tags" value="1" class="filter-checkbox">
                            <i class="{{ config('other.font-awesome') }} fa-tag text-success"></i> Comment Tags
                        </label>
                    </span>
                    <span class="badge-user">
                        <label class="inline">
                            <input type="checkbox" id="followers" value="1" class="filter-checkbox">
                            <i class="{{ config('other.font-awesome') }} fa-smile-plus text-success"></i> Followers
                        </label>
                    </span>
                    <span class="badge-user">
                        <label class="inline">
                            <input type="checkbox" id="posts" value="1" class="filter-checkbox">
                            <i class="{{ config('other.font-awesome') }} fa-comment-dots text-success"></i> Posts
                        </label>
                    </span>
                    <span class="badge-user">
                        <label class="inline">
                            <input type="checkbox" id="post_tags" value="1" class="filter-checkbox">
                            <i class="{{ config('other.font-awesome') }} fa-tag text-success"></i> Post Tags
                        </label>
                    </span>
                    <span class="badge-user">
                        <label class="inline">
                            <input type="checkbox" id="post_tips" value="1" class="filter-checkbox">
                            <i class="{{ config('other.font-awesome') }} fa-coins text-success"></i> Post Tips
                        </label>
                    </span>
                    <span class="badge-user">
                        <label class="inline">
                            <input type="checkbox" id="request_bounties" value="1" class="filter-checkbox">
                            <i class="{{ config('other.font-awesome') }} fa-crosshairs text-success"></i> Request Bounties
                        </label>
                    </span>
                    <span class="badge-user">
                        <label class="inline">
                            <input type="checkbox" id="request_claims" value="1" class="filter-checkbox">
                            <i class="{{ config('other.font-awesome') }} fa-check-circle text-success"></i> Request Claims
                        </label>
                    </span>
                    <span class="badge-user">
                        <label class="inline">
                            <input type="checkbox" id="request_fills" value="1" class="filter-checkbox">
                            <i class="{{ config('other.font-awesome') }} fa-check-square text-success"></i>Request Fills
                        </label>
                    </span>
                    <span class="badge-user">
                        <label class="inline">
                            <input type="checkbox" id="request_approvals" value="1" class="filter-checkbox">
                            <i class="{{ config('other.font-awesome') }} fa-clipboard-check text-success"></i> Request Approvals
                        </label>
                    </span>
                    <span class="badge-user">
                        <label class="inline">
                            <input type="checkbox" id="request_rejections" value="1" class="filter-checkbox">
                            <i class="{{ config('other.font-awesome') }} fa-times text-success"></i> Request Rejections
                        </label>
                    </span>
                    <span class="badge-user">
                        <label class="inline">
                            <input type="checkbox" id="request_unclaims" value="1" class="filter-checkbox">
                            <i class="{{ config('other.font-awesome') }} fa-times-square text-success"></i> Request Unclaims
                        </label>
                    </span>
                    <span class="badge-user">
                        <label class="inline">
                            <input type="checkbox" id="reseed_requests" value="1" class="filter-checkbox">
                            <i class="{{ config('other.font-awesome') }} fa-question text-success"></i> Reseed Requests
                        </label>
                    </span>
                    <span class="badge-user">
                        <label class="inline">
                            <input type="checkbox" id="thanks" value="1" class="filter-checkbox">
                            <i class="{{ config('other.font-awesome') }} fa-heart text-success"></i> Thanks
                        </label>
                    </span>
                    <span class="badge-user">
                        <label class="inline">
                            <input type="checkbox" id="upload_tips" value="1" class="filter-checkbox">
                            <i class="{{ config('other.font-awesome') }} fa-coins text-success"></i> Tips
                        </label>
                    </span>
                    <span class="badge-user">
                        <label class="inline">
                            <input type="checkbox" id="topics" value="1" class="filter-checkbox">
                            <i class="{{ config('other.font-awesome') }} fa-comment-alt-check text-success"></i> Topics
                        </label>
                    </span>
                    <span class="badge-user">
                        <label class="inline">
                            <input type="checkbox" id="unfollows" value="1" class="filter-checkbox">
                            <i class="{{ config('other.font-awesome') }} fa-frown text-success"></i> Unfollows
                        </label>
                    </span>
                    <span class="badge-user">
                        <label class="inline">
                            <input type="checkbox" id="uploads" value="1" class="filter-checkbox">
                            <i class="{{ config('other.font-awesome') }} fa-upload text-success"></i> Uploads
                        </label>
                    </span>
                </div>
            </div>
        </form>
    </div>
    <!-- /Search -->

    <!-- Mass Options -->
    <div class="text-center">
        <a href="{{ route('notifications.updateall') }}">
            <button type="button" class="btn btn btn-success" data-toggle="tooltip"
                    data-original-title="@lang('notification.mark-all-read')"><i
                        class="{{ config('other.font-awesome') }} fa-eye"></i> @lang('notification.mark-all-read')</button>
        </a>
        <a href="{{ route('notifications.destroyall') }}">
            <button type="button" class="btn btn btn-danger" data-toggle="tooltip"
                    data-original-title="@lang('notification.delete-all')"><i
                        class="{{ config('other.font-awesome') }} fa-times"></i> @lang('notification.delete-all')</button>
        </a>
    </div>
    <!-- /Mass Options -->

    <!-- Results -->
    <div class="container-fluid">
        <div class="block">
            <div class="header gradient silver">
                <div class="inner_content">
                    <h1>@lang('notification.notifications')</h1>
                </div>
            </div>
            <div id="result">
                @include('notification.results')
            </div>
        </div>
    </div>
@endsection

@section('javascripts')
    <script nonce="{{ Bepsvpt\SecureHeaders\SecureHeaders::nonce() }}">
      $('.filter-checkbox').on('change', function() {
        $('.filter-checkbox').not(this).prop('checked', false);
      });
    </script>
    
    <script nonce="{{ Bepsvpt\SecureHeaders\SecureHeaders::nonce() }}">
      var xhr = new XMLHttpRequest();

      function faceted(page) {
        var csrf = "{{ csrf_token() }}";
        var bon_gifts = (function () {
          if ($("#bon_gifts").is(":checked")) {
            return $("#bon_gifts").val();
          }
        })();
        var comments = (function () {
          if ($("#comments").is(":checked")) {
            return $("#comments").val();
          }
        })();
        var comment_tags = (function () {
          if ($("#comment_tags").is(":checked")) {
            return $("#comment_tags").val();
          }
        })();
        var followers = (function () {
          if ($("#followers").is(":checked")) {
            return $("#followers").val();
          }
        })();
        var posts = (function () {
          if ($("#posts").is(":checked")) {
            return $("#posts").val();
          }
        })();
        var post_tags = (function () {
          if ($("#post_tags").is(":checked")) {
            return $("#post_tags").val();
          }
        })();
        var post_tips = (function () {
          if ($("#post_tips").is(":checked")) {
            return $("#post_tips").val();
          }
        })();
        var request_bounties = (function () {
          if ($("#request_bounties").is(":checked")) {
            return $("#request_bounties").val();
          }
        })();
        var request_claims = (function () {
          if ($("#request_claims").is(":checked")) {
            return $("#request_claims").val();
          }
        })();
        var request_fills = (function () {
          if ($("#request_fills").is(":checked")) {
            return $("#request_fills").val();
          }
        })();
        var request_approvals = (function () {
          if ($("#request_approvals").is(":checked")) {
            return $("#request_approvals").val();
          }
        })();
        var request_rejections = (function () {
          if ($("#request_rejections").is(":checked")) {
            return $("#request_rejections").val();
          }
        })();
        var request_unclaims = (function () {
          if ($("#request_unclaims").is(":checked")) {
            return $("#request_unclaims").val();
          }
        })();
        var reseed_requests = (function () {
          if ($("#reseed_requests").is(":checked")) {
            return $("#reseed_requests").val();
          }
        })();
        var thanks = (function () {
          if ($("#thanks").is(":checked")) {
            return $("#thanks").val();
          }
        })();
        var upload_tips = (function () {
          if ($("#upload_tips").is(":checked")) {
            return $("#upload_tips").val();
          }
        })();
        var topics = (function () {
          if ($("#topics").is(":checked")) {
            return $("#topics").val();
          }
        })();
        var unfollows = (function () {
          if ($("#unfollows").is(":checked")) {
            return $("#unfollows").val();
          }
        })();
        var uploads = (function () {
          if ($("#uploads").is(":checked")) {
            return $("#uploads").val();
          }
        })();

        if (xhr !== 'undefined') {
          xhr.abort();
        }
        xhr = $.ajax({
          url: 'filterNotifications',
          data: {
            _token: csrf,
            bon_gifts: bon_gifts,
            comments: comments,
            comment_tags: comment_tags,
            followers: followers,
            posts: posts,
            post_tags: post_tags,
            post_tips: post_tips,
            request_bounties: request_bounties,
            request_claims: request_claims,
            request_fills: request_fills,
            request_approvals: request_approvals,
            request_rejections: request_rejections,
            request_unclaims: request_unclaims,
            reseed_requests: reseed_requests,
            thanks: thanks,
            upload_tips: upload_tips,
            topics: topics,
            unfollows: unfollows,
            uploads: uploads,
            page: page,
          },
          type: 'get',
          beforeSend: function () {
            $("#result").html('<i class="{{ config('other.font-awesome') }} fa-spinner fa-spin fa-3x fa-fw"></i>')
          }
        }).done(function (e) {
          $data = $(e);
          $("#result").html($data);
        });
      }
    </script>

    <script nonce="{{ Bepsvpt\SecureHeaders\SecureHeaders::nonce() }}">
      $(window).on("load", faceted())
    </script>

    <script nonce="{{ Bepsvpt\SecureHeaders\SecureHeaders::nonce() }}">
      $("#bon_gifts, #comments, #comment_tags, #followers, #posts, #post_tags, #post_tips, #request_bounties, #request_claims, #request_fills, #request_approvals, #request_rejections, #request_unclaims, #reseed_requests, #thanks, #upload_tips, #topics, #unfollows, #uploads").on("click", function () {
        faceted();
      });
    </script>

    <script nonce="{{ Bepsvpt\SecureHeaders\SecureHeaders::nonce() }}">
      $(document).on('click', '.pagination a', function (e) {
        e.preventDefault();
        var url = $(this).attr('href');
        var page = url.split('page=')[1];
        window.history.pushState("", "", url);
        faceted(page);
      })
    </script>

    <script nonce="{{ Bepsvpt\SecureHeaders\SecureHeaders::nonce() }}">
      $(document).ajaxComplete(function () {
        $('[data-toggle="tooltip"]').tooltip();
      });
    </script>
@endsection
