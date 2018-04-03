@extends('layout.default')

@section('title')
	<title>Staff Dashboard - {{ config('other.title') }}</title>
@endsection

@section('meta')
	<meta name="description" content="Moderation Dashboard">
@endsection

@section('breadcrumb')
<li>
    <a href="{{ route('staff_dashboard') }}" itemprop="url" class="l-breadcrumb-item-link">
        <span itemprop="title" class="l-breadcrumb-item-link-title">Staff Dashboard</span>
    </a>
</li>
@endsection

@section('content')
<div class="container box">
	<div class="header gradient pink">
		<div class="inner_content">
			<h1>Staff Dashboard</h1>
		</div>
	</div>
    <div class="row">
			<a href="{{ route('moderation') }}">
        <div class="col-lg-3 col-md-6 col-sm-6 margin_10 animated fadeInLeftBig">
            <!-- Trans label pie charts strats here-->
            <div class="lightbluebg no-radius">
                <div class="panel-body squarebox square_boxs">
                    <div class="col-xs-12 pull-left nopadmar">
                        <div class="row">
                            <div class="square_box col-xs-7 text-right">
                                <span>Total Torrents</span>
                                <div class="number" id="myTargetElement1"></div>
                            </div>
                            <i class="livicon  pull-right" data-name="doc-portrait" data-l="true" data-c="#fff" data-hc="#fff" data-s="70"></i>
                        </div>
                        <div class="row">
                            <div class="col-xs-6">
                                <small class="stat-label">Pending</small>
                                <h4 id="myTargetElement1.1"></h4>
                            </div>
                            <div class="col-xs-6 text-right">
                                <small class="stat-label">Rejected</small>
                                <h4 id="myTargetElement1.2"></h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
			</a>
        <div class="col-lg-3 col-md-6 col-sm-6 margin_10 animated fadeInDownBig">
            <!-- Trans label pie charts strats here-->
            <div class="redbg no-radius">
                <div class="panel-body squarebox square_boxs">
                    <div class="col-xs-12 pull-left nopadmar">
                        <div class="row">
                            <div class="square_box col-xs-7 pull-left">
                                <span>Peers</span>
                                <div class="number" id="myTargetElement2"></div>
                            </div>
                            <i class="livicon pull-right" data-name="wifi-alt" data-l="true" data-c="#fff" data-hc="#fff" data-s="70"></i>
                        </div>
                        <div class="row">
                            <div class="col-xs-6">
                                <small class="stat-label">Seeders</small>
                                <h4 id="myTargetElement2.1"></h4>
                            </div>
                            <div class="col-xs-6 text-right">
                                <small class="stat-label">Leechers</small>
                                <h4 id="myTargetElement2.2"></h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-sm-6 col-md-6 margin_10 animated fadeInDownBig">
            <!-- Trans label pie charts strats here-->
            <div class="goldbg no-radius">
                <div class="panel-body squarebox square_boxs">
                    <div class="col-xs-12 pull-left nopadmar">
                        <div class="row">
                            <div class="square_box col-xs-7 pull-left">
                                <span>Seedboxes</span>
                                <div class="number" id="myTargetElement3"></div>
                            </div>
                            <i class="livicon pull-right" data-name="servers" data-l="true" data-c="#fff" data-hc="#fff" data-s="70"></i>
                        </div>
                        <div class="row">
                            <div class="col-xs-6">
                                <small class="stat-label">Users</small>
                                <h4 id="myTargetElement3.1"></h4>
                            </div>
                            <div class="col-xs-6 text-right">
                                <small class="stat-label">Torrents</small>
                                <h4 id="myTargetElement3.2"></h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
				<a href="{{ route('user_search') }}">
        <div class="col-lg-3 col-md-6 col-sm-6 margin_10 animated fadeInRightBig">
            <!-- Trans label pie charts strats here-->
            <div class="palebluecolorbg no-radius">
                <div class="panel-body squarebox square_boxs">
                    <div class="col-xs-12 pull-left nopadmar">
                        <div class="row">
                            <div class="square_box col-xs-7 pull-left">
                                <span>Users</span>
                                <div class="number" id="myTargetElement4"></div>
                            </div>
                            <i class="livicon pull-right" data-name="users" data-l="true" data-c="#fff" data-hc="#fff" data-s="70"></i>
                        </div>
                        <div class="row">
                            <div class="col-xs-6">
                                <small class="stat-label">Validating</small>
                                <h4 id="myTargetElement4.1"></h4>
                            </div>
                            <div class="col-xs-6 text-right">
                                <small class="stat-label">Banned</small>
                                <h4 id="myTargetElement4.2"></h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
			</a>
		</div>
			<div class="row">
			<div class="col-lg-3 col-sm-6 col-md-6 col-md-offset-3 margin_10 animated fadeInUpBig">
					<!-- Trans label pie charts strats here-->
					<div class="purplebg no-radius">
							<div class="panel-body squarebox square_boxs">
									<div class="col-xs-12 pull-left nopadmar">
											<div class="row">
													<div class="square_box col-xs-7 pull-left">
															<span>Reports</span>
															<div class="number" id="myTargetElement5"></div>
													</div>
													<i class="livicon pull-right" data-name="list" data-l="true" data-c="#fff" data-hc="#fff" data-s="70"></i>
											</div>
											<div class="row">
													<div class="col-xs-6">
															<small class="stat-label">Solved</small>
															<h4 id="myTargetElement5.1"></h4>
													</div>
													<div class="col-xs-6 text-right">
															<small class="stat-label">Unsolved</small>
															<h4 id="myTargetElement5.2"></h4>
													</div>
											</div>
									</div>
							</div>
					</div>
			</div>
			<div class="col-lg-3 col-sm-6 col-md-6 margin_10 animated fadeInUpBig">
					<!-- Trans label pie charts strats here-->
					<div class="yellowbg no-radius">
							<div class="panel-body squarebox square_boxs">
									<div class="col-xs-12 pull-left nopadmar">
											<div class="row">
													<div class="square_box col-xs-7 pull-left">
															<span>Polls</span>
															<div class="number" id="myTargetElement6">0</div>
													</div>
													<i class="livicon pull-right" data-name="graph" data-l="true" data-c="#fff" data-hc="#fff" data-s="70"></i>
											</div>
											<div class="row">
													<div class="col-xs-6">
															<small class="stat-label">Total Votes</small>
															<h4 id="myTargetElement6.1">0</h4>
													</div>
													<div class="col-xs-6 text-right">
															<small class="stat-label">Active Polls</small>
															<h4 id="myTargetElement6.2">0</h4>
													</div>
											</div>
									</div>
							</div>
					</div>
			</div>
    </div>
	</div>
<div class="container">
  <div class="row">
	 @include('partials.dashboardmenu')

	 <div class="col-sm-8 col-lg-9">
		 <div class="block">
				 <div class="panel-heading">
					 <h3 class="panel-title">
							 <i class="livicon" data-name="dashboard" data-size="20" data-loop="true" data-c="#F89A14"
									data-hc="#F89A14"></i>
							 System Information
					 </h3>
				 </div>
				 <div class="panel-body">
					 <span class="text-red text-bold text-center"><h3>Currently Running {{ config('other.codebase') }}</h3></span>

				 </div>
			 </div>
		 </div>

    <div class="col-sm-8 col-lg-9">
      <div class="block">
          <div class="panel-heading">
            <h3 class="panel-title">
								<i class="livicon" data-name="dashboard" data-size="20" data-loop="true" data-c="#F89A14"
									 data-hc="#F89A14"></i>
								Realtime Server Load
								<small>- Fake Data For Now!</small>
						</h3>
          </div>
          <div class="panel-body">
            <div id="realtimechart" style="height:350px;"></div>
          </div>
        </div>
      </div>

  </div>
</div>
@endsection

@section('javascripts')
<!--   Realtime Server Load  -->
<script src="{{ url('js/vendor/flotchart/js/jquery.flot.js') }}" type="text/javascript"></script>
<script src="{{ url('js/vendor/flotchart/js/jquery.flot.resize.js') }}" type="text/javascript"></script>
<script>
/* realtime chart */
var data = [], totalPoints = 300;
		function getRandomData() {
				if (data.length > 0)
						data = data.slice(1);

				// do a random walk
				while (data.length < totalPoints) {
						var prev = data.length > 0 ? data[data.length - 1] : 50;
						var y = prev + Math.random() * 10 - 5;
						if (y < 0)
								y = 0;
						if (y > 100)
								y = 100;
						data.push(y);
				}

				// zip the generated y values with the x values
				var res = [];
				for (var i = 0; i < data.length; ++i)
						res.push([i, data[i]])
				return res;
		}

		// setup control widget
		var updateInterval = 30;
		$("#updateInterval").val(updateInterval).change(function () {
				var v = $(this).val();
				if (v && !isNaN(+v)) {
						updateInterval = +v;
						if (updateInterval < 1)
								updateInterval = 1;
						if (updateInterval > 2000)
								updateInterval = 2000;
						$(this).val("" + updateInterval);
				}
		});


		 if($("#realtimechart").length)
		{
				var options = {
						series: { shadowSize: 1 },
						lines: { fill: true, fillColor: { colors: [ { opacity: 1 }, { opacity: 0.1 } ] }},
						yaxis: { min: 0, max: 100 },
						xaxis: { show: false },
						colors: ["rgba(65,139,202,0.5)"],
						grid: { tickColor: "#dddddd",
										borderWidth: 0
						}
				};
				var plot = $.plot($("#realtimechart"), [ getRandomData() ], options);
				function update() {
						plot.setData([ getRandomData() ]);
						// since the axes don't change, we don't need to call plot.setupGrid()
						plot.draw();

						setTimeout(update, updateInterval);
				}

				update();
		}
</script>

<script type="text/javascript" src="{{ url('js/vendor/countUp.js') }}"></script>
<script>
var useOnComplete = false,
		useEasing = false,
		useGrouping = false,
		options = {
				useEasing : useEasing, // toggle easing
				useGrouping : useGrouping, // 1,000,000 vs 1000000
				separator : ',', // character to use as a separator
				decimal : '.' // character to use as a decimal
		};

		// Torrents
		var demo = new CountUp("myTargetElement1", 0, {{ $num_torrent }}, 0, 4, options);
			demo.start();
		var demo = new CountUp("myTargetElement1.1", 0, {{ $pending }}, 0, 4, options);
			demo.start();
		var demo = new CountUp("myTargetElement1.2", 0, {{ $rejected }}, 0, 4, options);
			demo.start();

		// Peers
		var demo = new CountUp("myTargetElement2", 0, {{ $peers }}, 0, 4, options);
			demo.start();
		var demo = new CountUp("myTargetElement2.1", 0, {{ $seeders }}, 0, 4, options);
			demo.start();
		var demo = new CountUp("myTargetElement2.2", 0, {{ $leechers }}, 0, 4, options);
			demo.start();

		// Seedboxes
		var demo = new CountUp("myTargetElement3", 0, {{ $seedboxes }}, 0, 4, options);
			demo.start();
		var demo = new CountUp("myTargetElement3.1", 0, {{ $highspeed_users }}, 0, 4, options);
			demo.start();
		var demo = new CountUp("myTargetElement3.2", 0, {{ $highspeed_torrents }}, 0, 4, options);
			demo.start();

	 // Users
	 var demo = new CountUp("myTargetElement4", 0, {{ $num_user }}, 0, 4, options);
		 demo.start();
	 var demo = new CountUp("myTargetElement4.1", 0, {{ $validating }}, 0, 4, options);
		 demo.start();
	 var demo = new CountUp("myTargetElement4.2", 0, {{ $banned }}, 0, 4, options);
		 demo.start();

	 // Reports
	 var demo = new CountUp("myTargetElement5", 0, {{ $reports }}, 0, 4, options);
		demo.start();
	 var demo = new CountUp("myTargetElement5.1", 0, {{ $solved }}, 0, 4, options);
	 	demo.start();
	 var demo = new CountUp("myTargetElement5.2", 0, {{ $unsolved }}, 0, 4, options);
		demo.start();

		// Reports
		var demo = new CountUp("myTargetElement6", 0, {{ $pollCount }}, 0, 4, options);
		 demo.start();
</script>
@endsection
