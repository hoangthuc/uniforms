@extends('admin.order.order_layout')
@section('content')
    <?php
    $order =  App\Orders::get_order($order_id);
    $order_status = App\Orders::order_status();
    $payment_type = App\Orders::payment_type();

    $customer_bill = App\Orders::get_meta_product_order($order_id,'customer_bill');
    $billing_address = App\Orders::get_meta_product_order($order_id,'billing_address');
    $customer_shipping = App\Orders::get_meta_product_order($order_id,'customer_shipping');
    $shipping_address = App\Orders::get_meta_product_order($order_id,'shipping_address');
    $products = App\Orders::get_meta_product_order($order_id,'products');
    if(isset($products)){
        $products = display_product_in_order( json_decode($products) );
        $products['tax'] = ($products['subtotal']*$order->tax)/100;
        $products['total'] = $order->shipping + $products['tax'] + $products['subtotal'];
    }
    ?>
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-12">
                    <ol class="breadcrumb float-sm-left">
                        <li class="breadcrumb-item"><a href="{{ route('admin.orders') }}">Order</a></li>
                        <li class="breadcrumb-item active">Orders Detail</li>
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
                    <!-- Main content -->
                    <div class="invoice p-3 mb-3">
                        <!-- title row -->
                        <div class="row">
                            <div class="col-12">
                                <h4>
                                    <i class="fas fa-globe"></i> UNIPRO
                                    <small class="float-right">Date: {{ date('m/d/Y') }}</small>
                                </h4>
                            </div>
                            <!-- /.col -->
                        </div>
                        <!-- info row -->
                        <div class="row invoice-info">
                            <div class="col-sm-4 invoice-col">
                                Billing Address
                                <address>
                                    <strong>{{  $customer_bill }}</strong><br>
                                    {!! nl2br($billing_address) !!}
                                </address>
                            </div>
                            <!-- /.col -->
                            <div class="col-sm-4 invoice-col">
                                Shipping Address
                                <address>
                                    <strong>{{ $customer_shipping }}</strong><br>
                                    {!! nl2br($shipping_address) !!}
                                </address>
                            </div>
                            <!-- /.col -->
                            <div class="col-sm-4 invoice-col">
                                <b>Order ID #{{ $order_id }}</b><br>
                                <br>
                                <b>Payment type:</b> {{ $payment_type[$order->payment_type]  }}<br>
                                <b>Order Status:</b> {{ $order_status[$order->status] }}<br>
                                <b>Created:</b> {{ display_date($order->created_at) }}
                            </div>
                            <!-- /.col -->
                        </div>
                        <!-- /.row -->

                        <!-- Table row -->
                        <div class="row mt-3">
                            <div class="col-12 table-responsive">
                                <table class="list-product-invoice table table-striped">
                                    <thead>
                                    <tr>
                                        <th>Qty</th>
                                        <th>Product</th>
                                        <th>Attributes</th>
                                        <th>Unit</th>
                                        <th>Total</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @if(isset($products))
                                        {!! $products['html']  !!}
                                    @endif
                                    </tbody>
                                </table>
                            </div>
                            <!-- /.col -->
                        </div>
                        <!-- /.row -->

                        <div class="row">
                            <!-- /.col -->
                            <div class="col-6">
                                <p class="lead">Order total</p>

                                <div class="table-responsive">
                                    <table class="table">
                                        <tr>
                                            <th style="width:50%">Subtotal:</th>
                                            <td>{{  format_currency( $products['subtotal'] ,2,'$') }}</td>
                                        </tr>
                                        <tr>
                                            <th>Tax ({{ $order->tax.'%' }})</th>
                                            <td>{{ $order->tax ? format_currency( ($order->tax / 100 ) * $products['subtotal']  ,2,'$') : '0' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Shipping:</th>
                                            <td>{{ $order->shipping ? format_currency($order->shipping,2,'$') : '0' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Total:</th>
                                            <td>{{ format_currency($products['total'],2,'$') }}</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="lead">Notes:</div>
                                <div class="content">{!! nl2br($order->note)  !!}</div>
                            </div>
                            <!-- /.col -->
                        </div>
                        <!-- /.row -->

                        <!-- this row will not appear when printing -->
                        <div class="row no-print">
                            <div class="col-12">
                                <a href="javascript:window.print()" class="btn btn-default"><i class="fas fa-print"></i> Print</a>
                                <a href="{{ url('admin/order/'.$order_id.'/edit') }}" class="btn btn-default float-right"><i class="far fa-edit"></i> Edit</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection


