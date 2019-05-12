@extends('layout.default')

@section('title')
    <title>Edit Discount Rule</title>
@endsection

@section('breadcrumb')
    <li>
        <a href="{{ route('staff_dashboard') }}" itemprop="url" class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">Staff Dashboard</span>
        </a>
    </li>
    <li class="active">
        <a href="{{ route('staff.commands.index') }}" itemprop="url" class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">Edit Discount Rule</span>
        </a>
    </li>
@endsection

@section('content')
    <div class="container">
        <div class="block">
            <div class="header gradient silver">
                <div class="inner_content">
                    <h1>Edit Discount Rule</h1>
                </div>
            </div>
            <br>
            <div class="p-20">
                <form class="row" method="post" action="/staff_dashboard/discount-rule/modify/{{$discount->id}}" >
                    {{csrf_field()}}
                    <div class="width-table">
                        <label class="width-table p-20">
                            @if( isset($types) )
                                Torrent Type:
                                <select class="form-control" name="type" name="type">
                                    <option></option>
                                    @foreach($types as $type)
                                        <option value="{{$type->id}}" {{($type->id == $discount->category)?'selected':''}}>
                                            {{$type->name}}
                                        </option>
                                    @endforeach
                                </select>
                            @endif
                        </label>
                    </div>
                    @php
                        if (isset($discount)){
                            $min_size_formatted = \App\Helpers\StringHelper::formatBytes($discount->torrent_min_size);
                            $min_size_unit = trim(str_replace([0,1,2,3,4,5,6,7,8,9,',','.'],'',$min_size_formatted));
                            $min_size_count = str_replace(['B','KiB','MiB','GiB','TiB','PiB'],'',$min_size_formatted);
                            $max_size_formatted = \App\Helpers\StringHelper::formatBytes($discount->torrent_max_size);
                            $max_size_unit = str_replace([0,1,2,3,4,5,6,7,8,9,',','.'],'',$max_size_formatted);
                            $max_size_count = str_replace(['B','KiB','MiB','GiB','TiB','PiB'],'',$min_size_formatted);
                            if($discount->freeleech == 1){
                                if($discount->freeleech_time >= 86400){
                                    $freeleech_time_unit = 'Days';
                                    $freeleech_time_count = $discount->freeleech_time / 86400;
                                }else if($discount->freeleech_time >= 3600){
                                	$freeleech_time_unit = 'Hours';
                                    $freeleech_time_count = $discount->freeleech_time / 3600;
                                }else if($discount->freeleech_time >= 60){
                                	$freeleech_time_unit = 'Minutes';
                                    $freeleech_time_count = $discount->freeleech_time / 60;
                                }else{
                                    $freeleech_time_unit = 'Seconds';
                                    $freeleech_time_count = $discount->freeleech_time;
                                }
                            }
                        }
                    @endphp
                    <div class="width-table">
                        <label class="width-75 pl-20">
                            <input type="number" name="min_torrent_size" class="form-control mt-20" value="{{$min_size_count}}" placeholder="Min Torrent Size">
                        </label>
                        <label class="width-25 pr-20">
                            <select class="form-control" name="min_torrent_size_unit">
                                <option {{($min_size_unit == 'B')?'selected':''}} value="1">B</option>
                                <option {{($min_size_unit == 'KiB')?'selected':''}} value="1024">KiB</option>
                                <option {{($min_size_unit == 'MiB')?'selected':''}} value="1048576">MiB</option>
                                <option {{($min_size_unit == 'GiB')?'selected':''}} value="1073741824‬">GiB</option>
                                <option {{($min_size_unit == 'TiB')?'selected':''}} value="1099511627776‬">TiB</option>
                            </select>
                        </label>
                    </div>
                    <div class="width-table">
                        <label class="width-75 pl-20">
                            <input type="number" name="max_torrent_size" class="form-control" value="{{$max_size_count}}" placeholder="Max Torrent Size">
                        </label>
                        <label class="width-25 pr-20">
                            <select class="form-control" name="min_torrent_size_unit">
                                <option {{($max_size_unit == 'B')?'selected':''}} value="1">B</option>
                                <option {{($max_size_unit == 'KiB')?'selected':''}} value="1024">KiB</option>
                                <option {{($max_size_unit == 'MiB')?'selected':''}} value="1048576">MiB</option>
                                <option {{($max_size_unit == 'GiB')?'selected':''}} value="1073741824‬">GiB</option>
                                <option {{($max_size_unit == 'TiB')?'selected':''}} value="1099511627776‬">TiB</option>
                            </select>
                        </label>
                    </div>
                    <div class="width-table">
                        <label class="width-table pl-20 pr-20">
                            <p>Counted Traffic in Percent</p>
                            <input type="number" name="counted_traffic" value="{{$discount->discount}}" class="form-control" max="100" placeholder="Counted Traffic in Percent">
                        </label>
                    </div>
                    <div class="width-table pt-10 pl-20 pr-20">
                        <label class="width-25">
                            <p>Freeleech</p>
                            <div class="row pl-20">
                                <input {{($discount->freeleech == 1)?'checked':''}} type="radio" name="freeleech"> Yes
                                <input {{($discount->freeleech == 0)?'checked':''}} type="radio" name="freeleech"> No
                            </div>
                        </label>
                        <label class="width-25">
                            <p>
                                Freeleech Duration
                            </p>
                            <input type="number" value="{{$freeleech_time_count}}" class="form-control" name="freeleech_time">
                        </label>
                        <label class="width-25">
                            <p>&nbsp;</p>
                            <select class="form-control" name="freeleech_time_unit">
                                <option {{($freeleech_time_unit == 'Seconds')?'selected':''}} value="1">Seconds</option>
                                <option {{($freeleech_time_unit == 'Minutes')?'selected':''}} value="60">Minutes</option>
                                <option {{($freeleech_time_unit == 'Hours')?'selected':''}} value="3600">Hours</option>
                                <option {{($freeleech_time_unit == 'Days')?'selected':''}} value="86400">Days</option>
                            </select>
                        </label>
                    </div>
                    <div class="align-right pl-20 pr-20 pt-10">
                        <input type="submit" value="Update" class="btn btn-success">
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection