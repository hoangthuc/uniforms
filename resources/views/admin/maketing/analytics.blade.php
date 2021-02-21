@extends('admin.maketing.maketing_layout')
@section('content')
    <?php
    $weeks = [
        'labels'=>['Mon','Tue','Wed','Thu','Fri','Sat','Sun'],
        'data_now'=>[],
        'data_last'=>[],
        'type'=>'week',
        'growth'=>33.1,
        'total'=>100,
    ];
    $months = [
        'labels'=>['Jan','Feb','Mar','Apr','May', 'Jun','Jul','Aug','Sep','Oct','Nov','Dec'],
        'data_now'=>[],
        'data_last'=>[],
        'type'=>'year',
        'growth'=>33.1,
        'total'=>100,
    ];

    $weeks =  get_data_json_analytics($weeks);
    $months =  get_data_json_analytics($months);
    $overviews = online_store_overview();
    $review = get_top_product_reviews([]);
    ?>
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-12">
                    <ol class="breadcrumb ">
                        <li class="breadcrumb-item"><a href="{{ route('admin') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Sales</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- Main content -->
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card" >
                        <div class="card-header border-0">
                            <div class="d-flex justify-content-between">
                                <h3 class="card-title">Sales</h3>
                                <div class="card-tools">
                                    <!-- button with a dropdown -->
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-success btn-sm dropdown-toggle" data-toggle="dropdown" data-offset="-112">
                                            <i class="fas fa-bars"></i></button>
                                        <div class="dropdown-menu" role="menu">
                                            <a onclick="setup_analytic_sales('week')" class="dropdown-item">Week</a>
                                            <a onclick="setup_analytic_sales('year')" class="dropdown-item">Year</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body" id="sales-analytics">
                            <div class="d-flex">
                                <p class="d-flex flex-column">
                                    <span class="text-bold text-lg analytics_total">$18,230.00</span>
                                    <span>Sales Over Time</span>
                                </p>
                                <p class="ml-auto d-flex flex-column text-right">
                    <span class="text-danger growth">
                      <i class="fas fa-arrow-down"></i> <span class="content">33.1%</span>
                    </span>
                                    <span class="text-muted">Since last <span class="analytic_type"></span></span>
                                </p>
                            </div>
                            <!-- /.d-flex -->

                            <div class="position-relative mb-4">
                                <canvas id="sales-chart" height="200"></canvas>
                            </div>

                            <div class="d-flex flex-row justify-content-end">
                  <span class="mr-2">
                    <i class="fas fa-square text-primary"></i> This <span class="analytic_type">{{ $weeks['type'] }}</span>
                  </span>

                                <span>
                    <i class="fas fa-square text-gray"></i> Last <span class="analytic_type">{{ $weeks['type'] }}</span>
                  </span>
                            </div>
                        </div>
                    </div>
                    <!-- /.card -->

                </div>
                <!--- Online Store Overview -->
                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-header border-0">
                            <h3 class="card-title">Online Store Overview</h3>
                            <div class="card-tools">
                                <a href="#" class="btn btn-sm btn-tool">
                                    <i class="fas fa-bars"></i>
                                </a>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center border-bottom mb-3">
                                <p class="text-success text-xl">
                                    <i class="ion ion-ios-refresh-empty"></i>
                                </p>
                                <p class="d-flex flex-column text-right">
                    <span class="font-weight-bold">
                      <i class="ion {{ ($overviews['conversion_rate'] > $overviews['conversion_rate_last_month'])?'ion-android-arrow-up text-success':'ion-android-arrow-down text-danger' }}"></i> {{ $overviews['conversion_rate'] }}%
                    </span>
                                    <span class="text-muted">CONVERSION RATE</span>
                                </p>
                            </div>
                            <!-- /.d-flex -->
                            <div class="d-flex justify-content-between align-items-center border-bottom mb-3">
                                <p class="text-warning text-xl">
                                    <i class="ion ion-ios-cart-outline"></i>
                                </p>
                                <p class="d-flex flex-column text-right">
                    <span class="font-weight-bold">
                      <i class="ion {{ ($overviews['sales_rate'] > 0)?'ion-android-arrow-up text-success':'ion-android-arrow-down text-danger' }} "></i> {{ abs($overviews['sales_rate']) }}%
                    </span>
                                    <span class="text-muted">SALES RATE</span>
                                </p>
                            </div>
                            <!-- /.d-flex -->
                            <div class="d-flex justify-content-between align-items-center mb-0">
                                <p class="text-danger text-xl">
                                    <i class="ion ion-ios-people-outline"></i>
                                </p>
                                <p class="d-flex flex-column text-right">
                    <span class="font-weight-bold">
                      <i class="ion {{ ($overviews['sales_rate'] > 0)?'ion-android-arrow-up text-success':'ion-android-arrow-down text-danger' }}"></i> {{ $overviews['total_current'] }}
                    </span>
                                    <span class="text-muted text-uppercase">Current Orders</span>
                                </p>
                            </div>
                            <!-- /.d-flex -->
                        </div>
                    </div>
                    <!-- /.card -->
                </div>
                <!-- Top product sale -->
                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-header border-0">
                            <h3 class="card-title">Top product sale</h3>
                            <div class="card-tools">
                                <div class="btn-group">
                                    <button type="button" class="btn btn-tool btn-sm dropdown-toggle" data-toggle="dropdown" data-offset="-112">
                                        <i class="fas fa-bars"></i></button>
                                    <div class="dropdown-menu" role="menu">
                                        <a onclick="setup_top_product(this)" data-type="sales" data-time="week" data-return="#top_product_sales" class="dropdown-item">Week</a>
                                        <a onclick="setup_top_product(this)" data-type="sales" data-time="month" data-return="#top_product_sales" class="dropdown-item active">Month</a>
                                        <a onclick="setup_top_product(this)" data-type="sales" data-time="year" data-return="#top_product_sales" class="dropdown-item">Year</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div id="top_product_sales" class="card-body table-responsive p-0">
                            {!! display_top_product_sales('month') !!}
                        </div>
                    </div>
                    <!-- /.card -->
                </div>
                <!--- End Top product sale -->
                <!-- Top product reviews -->
                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-header border-0">
                            <h3 class="card-title">Top product reviews</h3>
                            <div class="card-tools">
                                <div class="btn-group">
                                    <button type="button" class="btn btn-tool btn-sm dropdown-toggle" data-toggle="dropdown" data-offset="-112">
                                        <i class="fas fa-bars"></i></button>
                                    <div class="dropdown-menu" role="menu">
                                        <a onclick="setup_top_product(this)" data-type="reviews" data-time="week" data-return="#top_product_reviews" class="dropdown-item">Week</a>
                                        <a onclick="setup_top_product(this)" data-type="reviews"  data-time="month" data-return="#top_product_reviews" class="dropdown-item active">Month</a>
                                        <a onclick="setup_top_product(this)" data-type="reviews"  data-time="year" data-return="#top_product_reviews" class="dropdown-item">Year</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div id="top_product_reviews" class="card-body table-responsive p-0">
                            {!! display_top_product_sales('month','reviews') !!}
                        </div>
                    </div>
                    <!-- /.card -->
                </div>
                <!-- End Top product reviews -->

                <!-- Top product questions -->
                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-header border-0">
                            <h3 class="card-title">Top product questions</h3>
                            <div class="card-tools">
                                <div class="btn-group">
                                    <button type="button" class="btn btn-tool btn-sm dropdown-toggle" data-toggle="dropdown" data-offset="-112">
                                        <i class="fas fa-bars"></i></button>
                                    <div class="dropdown-menu" role="menu">
                                        <a onclick="setup_top_product(this)" data-type="questions" data-time="week" data-return="#top_product_questions" class="dropdown-item">Week</a>
                                        <a onclick="setup_top_product(this)" data-type="questions"  data-time="month" data-return="#top_product_questions" class="dropdown-item active">Month</a>
                                        <a onclick="setup_top_product(this)" data-type="questions"  data-time="year" data-return="#top_product_questions" class="dropdown-item">Year</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div id="top_product_questions" class="card-body table-responsive p-0">
                            {!! display_top_product_sales('month','questions') !!}
                        </div>
                    </div>
                    <!-- /.card -->
                </div>
                <!-- End Top product questions -->
            </div>
            <!-- /.row -->
        </div>
        <!-- /.container-fluid -->
    </div>
    <!-- /.content -->
@endsection
@section('footer_script')
    <script>
        var data_analytics = {
            week: {!! json_encode($weeks) !!},
            year: {!! json_encode($months) !!},
        };

     function setup_top_product(event) {
         var time = $(event).data('time');
         var type = $(event).data('type');
         var dom = $(event).data('return');
         var settings = {
             url: setting.ajax_url,
             type: 'post',
             data: {time:time ,type: type, action: 'display_top_product_sales',_token:setting.token},
         };
         $('.card-tools [data-type="'+type+'"]').removeClass('active');
         $(event).addClass('active');
         $.ajax(settings).done(function (response) {
             if(response){
                document.querySelector(dom).innerHTML = response;
             }
         });
     }
    </script>
@endsection