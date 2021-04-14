@extends('layouts.layout_main')
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
        $product_cart = json_decode($products);
        $products = display_product_in_order( $product_cart );
        $products['tax'] = ($products['subtotal']*$order->tax)/100;
        $products['total'] = $order->shipping + $products['tax'] + $products['subtotal'];
    }
    ?>
    <section class="products-page">
        <div class="container">
            <div class="row" style="background-image:url('{{ asset('images/products/myorder.jpg') }}');">
                <div class="col-md-6">
                    <div class="breadcrumb_iner">
                        Order #{{ $order_id }}
                    </div>
                </div>
                <div class="col-md-6"></div>
            </div>
        </div>
    </section>
    <section class="testimonial_part push-top-25 push-bottom-25">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-xl-5">
                    <div class="section_tittle text-center">
                        <h2 class="orange">Payment Confirmation</h2>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12 text-center">
                    <div class="row justify-content-center">
                        <div class="col-12 text-center">
                            Thank you for your payment. The payment details are below:
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12 py-3">
                            <!-- Main content -->
                            <div class="invoice p-3 mb-3">
                                <!-- title row -->
                                <div class="row">
                                    <div class="col-12">
                                        <h4 class="text-left">
                                            <small>Date: {{ date('m/d/Y') }}</small>
                                        </h4>
                                    </div>
                                    <!-- /.col -->
                                </div>
                                <!-- info row -->
                                <div class="row invoice-info">
                                    <div class="col-sm-6 invoice-col text-left">
                                       <b> Billing Address</b>
                                        <address>
                                            <strong>{{  $customer_bill }}</strong><br>
                                            {!! nl2br($billing_address) !!}
                                        </address>
                                    </div>
                                    <!-- /.col -->
                                    <div class="col-sm-6 invoice-col text-left">
                                       <b> Shipping Address</b>
                                        <address>
                                            <strong>{{ $customer_shipping }}</strong><br>
                                            {!! nl2br($shipping_address) !!}
                                        </address>
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
                                            @if(isset($product_cart))
                                                @foreach($product_cart as $value)
                                                <tr>
                                                    <td>{{ $value->quantily }}</td>
                                                    <td><a href="{{ display_url_product($value->product_id) }}">{{  $value->title }}</a></td>
                                                    <td>{!! $value->attributes !!}</td>
                                                    <td>{{ format_currency( $value->subtotal,2,'$') }}</td>
                                                    <td>{{ format_currency( $value->subtotal * $value->quantily,2,'$') }}</td>
                                                </tr>
                                                  @endforeach
                                            @endif
                                            </tbody>
                                        </table>
                                    </div>
                                    <!-- /.col -->
                                </div>
                                <!-- /.row -->

                                <div class="row mt-5">
                                    <!-- /.col -->
                                    <div class="col-md-6 text-left">
                                        <p class="lead"><b>Order total</b></p>

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
                                                    <td class="font-weight-bold">{{ format_currency($products['total'],2,'$') }}</td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="col-md-6 text-left">
                                        <div class="lead">Notes:</div>
                                        <div class="content">{!! nl2br($order->note)  !!}</div>
                                    </div>
                                    <!-- /.col -->
                                </div>
                                <!-- /.row -->
                            </div>
                            <a type="button" class="btn btn-unipro btn-lg same-window" href="{{ route('shops') }}"><i class="fa fa-home"></i> Return to Shop Page</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
