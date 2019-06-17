@extends('layout.default')

@section('breadcrumb')
    <li>
        <a href="{{ route('page', ['slug' => $page->slug, 'id' => $page->id]) }}" itemprop="url"
           class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">{{ $page->name }}</span>
        </a>
    </li>
@endsection

@section('content')
    <div class="container box">
        <div class="col-md-12 page">
            <div class="header gradient silver">
                <div class="inner_content">
                    <div class="page-title"><h1>{{ $page->name }}</h1></div>
                </div>
            </div>
            <article class="page-content">
                @emojione($page->getContentHtml())
            </article>
        </div>
    </div>
@endsection

@section('javascripts')
    @if(request()->url() === config('other.rules_url') && auth()->user()->read_rules == 0)
        <script nonce="{{ Bepsvpt\SecureHeaders\SecureHeaders::nonce() }}">
          window.onscroll = function() {
            let scrollHeight, totalHeight;
            scrollHeight = document.body.scrollHeight;
            totalHeight = window.scrollY + window.innerHeight;

            if (totalHeight >= scrollHeight) {
              Swal.fire({
                title: '<strong>Read The <u>Rules?</u></strong>',
                text: "Do You Fully Understand Our Rules?",
                type: "question",
                confirmButtonText: '<i class="fa fa-thumbs-up"></i> I Do!',
              }).then(function() {
                $.ajax({
                  url: "/accept-rules",
                  type: "post",
                  data: {
                    _token: '{{csrf_token()}}'
                  },
                  success: function(response) {
                    const Toast = Swal.mixin({
                      toast: true,
                      position: 'top-end',
                      showConfirmButton: false,
                      timer: 3000
                    });

                    Toast.fire({
                      type: 'success',
                      title: 'Thanks For Accepting Our Rules!'
                    })
                  },
                  failure: function(response) {
                    const Toast = Swal.mixin({
                      toast: true,
                      position: 'top-end',
                      showConfirmButton: false,
                      timer: 3000
                    });

                    Toast.fire({
                      type: 'error',
                      title: 'Something Went Wrong!'
                    })
                  }
                });
              })
            }
          }
        </script>
    @endif
@endsection
