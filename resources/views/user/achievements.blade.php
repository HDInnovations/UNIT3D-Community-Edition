@extends('layout.default')

@section('breadcrumb')
<li class="active">
  <a href="{{ route('achievements') }}" itemprop="url" class="l-breadcrumb-item-link">
    <span itemprop="title" class="l-breadcrumb-item-link-title">Achievements</span>
  </a>
</li>
@stop

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8">
                <div class="panel panel-default">
                    <div class="panel-heading">Unlocked Achievements</div>
                    <div class="panel-body">
                        <br/>
                        <div class="table-responsive">
                            <table class="table table-borderless">
                                <thead>
                                <tr>
                                    <th>Name</th><th>Description</th><th>Progress</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($achievements as $item)
                                    <tr>
                                        <td><img src="/img/badges/{{ $item->details->name }}.png" data-toggle="tooltip" title="" data-original-title="{{ $item->details->name }}"></td>
                                        <td>{{ $item->details->description }}</td>
                                        @if($item->isUnlocked())
                                           <td><span class="label label-success">Unlocked</span></td>
                                        @else
                                            <td><span class="label label-warning">Progress: {{$item->points}}/{{$item->details->points}}</span></td>
                                        @endif
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-4 text-center">
                <div class="text-green well well-sm">
                <?php $unlocked = Auth::user()->unlockedAchievements()->count() ?>
                    <h3><strong>Unlocked Achievements:</strong>{{ $unlocked }}</h3>
                </div>
                <div class="text-red well well-sm">
                <?php $lock = Auth::user()->lockedAchievements()->count() ?>
                    <h3><strong>Locked Achievements:</strong>{{ $lock }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-8">
                <div class="panel panel-default">
                    <div class="panel-heading">Pending Achievements</div>
                    <div class="panel-body">
                        <br/>
                        <div class="table-responsive">
                            <table class="table table-borderless">
                                <thead>
                                <tr>
                                    <th>Name</th><th>Description</th><th>Progress</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($pending as $p)
                                    <tr>
                                        <td><img src="/img/badges/{{ $p->details->name }}.png" data-toggle="tooltip" title="" data-original-title="{{ $p->details->name }}"></td>
                                        <td>{{ $p->details->description }}</td>
                                        <td><span class="label label-warning">Progress: {{$p->points}}/{{$p->details->points}}</span></td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        <div class="col-md-8">
                <div class="panel panel-default">
                    <div class="panel-heading">Locked Achievements</div>
                    <div class="panel-body">
                        <br/>
                        <div class="table-responsive">
                            <table class="table table-borderless">
                                <thead>
                                <tr>
                                    <th>Name</th><th>Description</th><th>Progress</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($locked as $l)
                                    <tr>
                                        <td><img src="/img/badges/{{ $l->details->name }}.png" data-toggle="tooltip" title="" data-original-title="{{ $l->details->name }}"></td>
                                        <td>{{ $l->details->description }}</td>
                                        <td><span class="label label-danger">Locked</span></td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
    </div>

@endsection
