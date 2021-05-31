@extends('admin.order.order_layout')
@section('content')
    <?php
    $search = (isset($_GET['search']))?$_GET['search']:'';
    $status  = (isset($_GET['status']))?$_GET['status']:'';
    $date_from  = (isset($_GET['date_from']))?$_GET['date_from']:'';
    $date_to  = (isset($_GET['date_to']))?$_GET['date_to']:'';
    $orders =  App\Orders::get_orders();
    $order_status = App\Orders::order_status();
    $payment_type = App\Orders::payment_type();
    $count_date =  ($date_from)?round(abs(time() - strtotime($date_from))/86400 - 1):100;
    ?>
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-12">
                    <ol class="breadcrumb float-sm-left">
                        <li class="breadcrumb-item"><a href="{{ route('admin') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active"><a href="{{ route('admin.orders') }}">Manage Orders</a></li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Orders ({{ $orders->total() }})</h3>

                            <div class="card-tools">
                                <form class="form-inline" id="form-filter-order">
                                    <div class="input-group mr-1">
                                        <div id="reportrange" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 100%">
                                            <i class="fa fa-calendar"></i>&nbsp;
                                            <span>{{ $date_from? date("F d, Y", strtotime($date_from)) :'' }} - {{ $date_to? date("F d, Y", strtotime($date_to)) :'' }}</span> <i class="fa fa-caret-down"></i>
                                            <input type="hidden" name="date_from" value="{{ $date_from }}" oninput="this.form.submit()">
                                            <input type="hidden" name="date_to" value="{{ $date_to }}" oninput="this.form.submit()">
                                        </div>

                                    </div>
                                 <div class="input-group input-group-sm mr-1" >
                                      <select name="status" class="form-control" onchange="this.form.submit()">
                                          <option value="">All</option>
                                        @foreach( $order_status as $key => $option )
                                            <option value="{{ $key }}" @if($key == $status) selected @endif >{{ $option }}</option>
                                        @endforeach
                                      </select>
                                 </div>
                                <div class="input-group input-group-sm">
                                    <input type="text" name="search" value="{{ $search }}" onchange="this.form.submit()" class="form-control float-right" placeholder="Search">

                                    <div class="input-group-append">
                                        <button type="submit" class="btn btn-default"><i class="fas fa-search"></i></button>
                                    </div>
                                </div>
                                </form>
                            </div>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body table-responsive p-0">
                            <table class="table table-head-fixed text-nowrap table-hover">
                                <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Buyer</th>
                                    <th>Amount</th>
                                    <th>Payment</th>
                                    <th>Status</th>
                                    <th>Date</th>
                                    <th>Last Modified</th>
                                    <th>Actions</th>
                                </tr>
                                </thead>
                                <tbody>
                                @if (count($orders) > 0)
                                    @foreach ($orders as $order)
                                        <tr>
                                            <td>#{{ $order->id }}</td>
                                            <td>{{  \App\Orders::get_meta_product_order($order->id,'customer_bill') }}</td>
                                            <td>{{ format_currency($order->total,2,'$') }}</td>
                                            <td>{{ $payment_type[$order->payment_type]  }}</td>
                                            <td>{{ $order_status[$order->status] }}</td>
                                            <td>{{ display_date($order->created_at) }}</td>
                                            <td>{{ display_date($order->updated_at) }}</td>
                                            <td>
                                                <a class="btn btn-primary btn-sm" href="{{ url('admin/order/'.$order->id.'/view') }}">
                                                    <i class="fas fa-folder">
                                                    </i>
                                                    View
                                                </a>
                                                <a class="btn btn-info btn-sm" href="{{ url('admin/order/'.$order->id.'/edit') }}">
                                                    <i class="fas fa-pencil-alt">
                                                    </i>
                                                    Edit
                                                </a>
                                                <a class="btn btn-danger btn-sm" href="javascript:delete_post('{{$order->id}}','detele_order');">
                                                    <i class="fas fa-trash">
                                                    </i>
                                                    Delete
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="8" class="text-center">No order exist.</td>
                                    </tr>
                                @endif

                                </tbody>
                            </table>
                        </div>
                        <!-- /.card-body -->
                    </div>
                    <!-- /.card -->
                    {{ $orders->appends($_GET)->links() }}
                </div>
            </div>
        </div>
    </section>
@endsection
@section('script_footer')
    <script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
    <script type="text/javascript">
        jQuery(function($) {

            var start = moment().subtract({{ $count_date }}, 'days');
            var end = moment();
            var date_from = $('#reportrange [name="date_to"]').val();

            function cb(start, end) {
                $('#reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
                $('#reportrange [name="date_from"]').val(start.format('YYYY-MM-DD')+' 00:00');
                $('#reportrange [name="date_to"]').val(end.format('YYYY-MM-DD')+' 23:59');
                document.querySelector('#form-filter-order').submit();
            }

            $('#reportrange').daterangepicker({
                startDate: start,
                endDate: end,
                ranges: {
                    'Today': [moment(), moment()],
                    'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                    'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                    'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                    'This Month': [moment().startOf('month'), moment().endOf('month')],
                    'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
                }
            }, cb);

            if(!date_from)cb(start, end);

        });
    </script>
@endsection
