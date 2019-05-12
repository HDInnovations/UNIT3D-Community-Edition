@extends('layout.default')

@section('title')
    <title>Discount Rules</title>
@endsection

@section('breadcrumb')
    <li>
        <a href="{{ route('staff_dashboard') }}" itemprop="url" class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">Staff Dashboard</span>
        </a>
    </li>
    <li class="active">
        <a href="{{ route('staff.commands.index') }}" itemprop="url" class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">Discount Rules</span>
        </a>
    </li>
@endsection

@section('content')
    <div class="container">
        <div class="block">
            <div class="header gradient silver">
                <div class="inner_content">
                    <h1>Discount Rules</h1>
                </div>
            </div>
            <br>
            <div class="p-20">
                <form class="row" method="post" action="/staff_dashboard/discount-rule/create" >
                    {{csrf_field()}}
                    <div class="width-table">
                        <label class="width-table p-20">
                            @if( isset($types) )
                                Torrent Type:
                                <select class="form-control" name="type" name="type">
                                    <option></option>
                                    @foreach($types as $type)
                                        <option value="{{$type->id}}">
                                            {{$type->name}}
                                        </option>
                                    @endforeach
                                </select>
                            @endif
                        </label>
                    </div>
                    <div class="width-table">
                        <label class="width-75 pl-20">
                            <input type="number" name="min_torrent_size" class="form-control mt-20" placeholder="Min Torrent Size">
                        </label>
                        <label class="width-25 pr-20" style="">
                            <select class="form-control" name="min_torrent_size_unit">
                                <option value="1">B</option>
                                <option value="1024">KiB</option>
                                <option value="1048576">MiB</option>
                                <option value="1073741824‬">GiB</option>
                                <option value="1099511627776‬">TiB</option>
                            </select>
                        </label>
                    </div>
                    <div class="width-table">
                        <label class="width-75 pl-20">
                            <input type="number" name="max_torrent_size" class="form-control" placeholder="Max Torrent Size">
                        </label>
                        <label class="width-25 pr-20">
                            <select class="form-control" name="max_torrent_size_unit">
                                <option value="1">B</option>
                                <option value="1024">KiB</option>
                                <option value="1048576">MiB</option>
                                <option value="1073741824‬">GiB</option>
                                <option value="1099511627776‬">TiB</option>
                            </select>
                        </label>
                    </div>
                    <div class="width-table">
                        <label class="width-table pl-20">
                            <p>Counted Traffic in Percent</p>
                            <input type="number" name="counted_traffic" class="form-control" max="100" placeholder="Counted Traffic in Percent">
                        </label>
                    </div>
                    <div class="width-table pt-10">
                        <label class="pl-20">
                            <p>Freeleech</p>
                            <div class="row pl-20">
                                <input type="radio" name="freeleech"> Yes
                            </div>
                        </label>
                        <label>
                            <div class="row pl-20">
                                <input type="radio" name="freeleech"> No
                            </div>
                        </label>

                        <label class="width-25">
                            <p>
                                Freeleech Duration
                            </p>
                            <input type="number" class="form-control" name="freeleech_time">
                        </label>
                        <label class="width-25">
                            <p>&nbsp;</p>
                            <select class="form-control" name="freeleech_time_unit">
                                <option value="1">Seconds</option>
                                <option value="60">Minutes</option>
                                <option value="3600">Hours</option>
                                <option value="86400">Days</option>
                            </select>
                        </label>
                    </div>
                    <div class="align-right pl-20 pr-20 pt-10">
                        <input type="submit" value="Add" class="btn btn-success">
                    </div>
                </form>
                <table class="width-table mt-20">
                    <thead>
                        <tr>
                            <th>
                                Type
                            </th>
                            <th>
                                Min. Size
                            </th>
                            <th>
                                Max. Size
                            </th>
                            <th>
                                Counted Traffic
                            </th>
                            <th>
                                Freeleech
                            </th>
                            <th>
                                Freeleech Duration
                            </th>
                            <th>

                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @if( isset( $discounts ) )
                            @foreach($discounts as $discount)
                                <tr>
                                    <td>
                                        {{\App\Models\Type::findOrFail($discount->category)->name}}
                                    </td>
                                    <td>
                                        {{\App\Helpers\StringHelper::formatBytes($discount->torrent_min_size)}}
                                    </td>
                                    <td>
                                        {{\App\Helpers\StringHelper::formatBytes($discount->torrent_min_size)}}
                                    </td>
                                    <td>
                                        {{$discount->discount}} %
                                    </td>
                                    <td>
                                        {{ ($discount->freeleech) ? 'YES':'NO' }}
                                    </td>
                                    <td>
                                        {{ $discount->freeleech_time }}
                                    </td>
                                    <td>
                                        <a href="/staff_dashboard/discount-rule/modify/{{$discount->id}}" class="btn btn-warning">Edit</a>
                                        <a href="/staff_dashboard/discount-rule/delete/{{$discount->id}}" class="btn btn-danger">Delete</a>
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                        <tr>

                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection