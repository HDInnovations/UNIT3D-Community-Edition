@foreach($articles as $a)
<div class="col-md-10 col-sm-10 col-md-offset-1">
@if(auth()->user()->updated_at->getTimestamp() < $a->created_at->getTimestamp())
<div class="panel panel-danger">
<div class="panel-heading">
  <h4 class="text-center">
  <a class="accordion-toggle collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapse4" style="color:#fff">{{ trans('blocks.new-news') }}</a>
  </h4>
</div>
@else
<div class="panel panel-success">
<div class="panel-heading">
  <h4 class="text-center">
  <a class="accordion-toggle collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapse4" style="color:#fff">{{ trans('blocks.check-news') }}</a>
  </h4>
</div>
@endif
<div id="collapse4" class="panel-collapse collapse" style="height: 0px;">
  <div class="panel-body no-padding">
    <article data-id="{{ $a->id }}" class="article col-md-12">
      <div class="news-blocks">
        <div class="row">
          <br>
          <a href="{{ route('article', ['slug' => $a->slug, 'id' => $a->id]) }}" class="article-thumb col-md-2">
            <!-- Image -->
            @if( ! is_null($a->image))
            <img src="{{ url('files/img/' . $a->image) }}" class="article-thumb-img" alt="{{ $a->title }}"> @else
            <img src="{{ url('img/missing-image.jpg') }}" class="article-thumb-img" alt="{{ $a->title }}"> @endif
            <!-- /Image -->
          </a>

          <div class="col-md-10 article-title">
            <h2><a href="{{ route('article', ['slug' => $a->slug, 'id' => $a->id]) }}">{{ $a->title }}</a></h2>
          </div>

          <div class="artical-time col-md-10">
            <span>{{ trans('articles.published-at') }}</span>
            <time datetime="{{ date(DATE_W3C, $a->created_at->getTimestamp()) }}">{{ date('M d Y', $a->created_at->getTimestamp()) }}</time>
             - ({{ $a->created_at->diffForHumans() }})
          </div>

          <div class="col-md-10 article-content">
             @emojione(preg_replace('#\[[^\]]+\]#', '', str_limit($a->content), 150))...
          </div>
          <br>
          <div class="col-md-12 article-readmore">
            <center>
              <a href="{{ route('article', ['slug' => $a->slug, 'id' => $a->id]) }}" class="btn btn-success">{{ trans('articles.read-more') }}</a>
            </center>
          </div>
        </div>
      </div>
    </article>
    <a href="{{ route('articles') }}" class="btn btn-primary">{{ trans('common.view-all') }}</a>
  </div>
</div>
</div>
</div>
@endforeach
