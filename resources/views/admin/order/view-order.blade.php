@extends('admin.order.order_layout')
@section('content')
    <?php
    $order =  App\Orders::get_order($order_id);
    $order_status = App\Orders::order_status();
    $payment_type = App\Orders::payment_type();

    $customer_bill = App\Orders::get_meta_product_order($order_id,'customer_bill');
    $customer_shipping = App\Orders::get_meta_product_order($order_id,'customer_shipping');
    $billing_address = App\Orders::get_meta_product_order($order_id,'billing_address');
    $billing_address = ($billing_address)?(array)json_decode($billing_address):[];
    $shipping_address = App\Orders::get_meta_product_order($order_id,'shipping_address');
    $shipping_address = ($shipping_address)?(array)json_decode($shipping_address):[];
    $products = App\Orders::get_meta_product_order($order_id,'products');
    if(isset($products)){
        $products = display_product_in_order( json_decode($products) );
        $products['tax'] = ($products['subtotal']*$order->tax)/100;
        $products['total'] = $order->shipping + $products['tax'] + $products['subtotal'];
    }
    $tracking_order = App\Orders::get_meta_product_order($order_id,'tracking_order');
    $tracking_order = ($tracking_order)?(array)json_decode($tracking_order):[];
    $company = \App\Orders::ship_company();
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
                                    <small class="float-right">Created: {{ display_date($order->created_at) }}</small>
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
                                    @if( isset($billing_address['address']) )
                                    <span>{!! $billing_address['address'] !!}</span><br/>
                                    <span>{!! $billing_address['city'].', '.$billing_address['state'].', '.$billing_address['zipcode'] !!}</span><br/>
                                    <span>{!! $billing_address['email'].', '.$billing_address['phone'] !!}</span>
                                    @endif
                                </address>
                            </div>
                            <!-- /.col -->
                            <div class="col-sm-4 invoice-col">
                                Shipping Address
                                <address>
                                    <strong>{{ $customer_shipping }}</strong><br>
                                    @if( isset($shipping_address['shipping_address_1']) )
                                    <span>{!! $shipping_address['shipping_address_1'] !!}</span><br/>
                                    <span>{!! $shipping_address['shipping_city'].', '.$shipping_address['shipping_state'].', '.$shipping_address['shipping_zipcode'] !!}</span><br/>
                                    <span>{!! $shipping_address['shipping_email'].', '.$shipping_address['shipping_phone'] !!}</span>
                                    @elseif(isset($shipping_address['address']))
                                     <span>{!! $shipping_address['address'] !!}</span><br/>
                                     <span>{!! $shipping_address['city'].', '.$shipping_address['state'].', '.$shipping_address['zipcode'] !!}</span><br/>
                                     <span>{!! $shipping_address['email'].', '.$shipping_address['phone'] !!}</span>
                                    @endif
                                </address>
                            </div>
                            <!-- /.col -->
                            <div class="col-sm-4 invoice-col">
                                <b>Order ID #{{ $order_id }}</b><br>
                                <b>Payment type:</b> {{ $payment_type[$order->payment_type]  }}<br>
                                <b>Order Status:</b> {{ $order_status[$order->status] }}<br>
                                @if( isset($tracking_order['tracking_number']) )
                                <b>Tracking number:</b> {{ $tracking_order['tracking_number']  }}<br>
                                <b>Company:</b> <a href="{{ $tracking_order['url_tracking'] }}" target="_blank">{{ $company[ $tracking_order['company_id'] ][ 'name' ]  }}</a>
                                @endif

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


