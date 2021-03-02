@extends('admin.order.template_email_layout')
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
    <style>
        table td, table th {
            padding: .75rem;
            vertical-align: top;
            border-top: 1px solid #dee2e6;
            text-align: center;
        }
    </style>
    <!-- Main content -->
    <section>
        <div style="">
            <div>
                <div style="max-width: 100%;">
                    <!-- Main content -->
                    <div>
                        <!-- title row -->
                        <div>
                            <div style="display: block">
                                <h4 style="display: flex;">
                                    <img src="{{ asset('images/unipro_stars_logo.png') }}" style="width: 100px;" />
                                    <small style="width: 100%; text-align: right;    font-size: 16px;">Date: {{ date('m/d/Y') }}</small>
                                </h4>
                            </div>
                            <!-- /.col -->
                        </div>
                        <!-- info row -->
                        <div style="display: flex;">
                            <div style="width: 100%; padding-right: 7.5px;padding-left: 7.5px;">
                                Billing Address
                                <address>
                                    <strong>{{  $customer_bill }}</strong><br>
                                    {!! nl2br($billing_address) !!}
                                </address>
                            </div>
                            <div style="width: 100%; padding-right: 7.5px;padding-left: 7.5px;">
                                Shipping Address
                                <address>
                                    <strong>{{ $customer_shipping }}</strong><br>
                                    {!! nl2br($shipping_address) !!}
                                </address>
                            </div>
                            <div style="width: 100%; padding-right: 7.5px;padding-left: 7.5px;">
                                <b>Order ID #{{ $order_id }}</b><br>
                                <br>
                                <b>Payment type:</b> {{ $payment_type[$order->payment_type]  }}<br>
                                <b>Order Status:</b> {{ $order_status[$order->status] }}<br>
                                <b>Created:</b> {{ display_date($order->created_at) }}
                            </div>
                        </div>
                        <!-- /.row -->

                        <!-- Table row -->
                        <div style="margin-top: 15px">
                            <div style="flex: 0 0 100%;max-width: 100%;">
                                <table  style="    width: 100%;
    margin-bottom: 1rem;
    color: #212529;
    background-color: transparent;">
                                    <thead>
                                    <tr>
                                        <th style="vertical-align: bottom;border-bottom: 2px solid #dee2e6;">Qty</th>
                                        <th style="vertical-align: bottom;border-bottom: 2px solid #dee2e6;">Product</th>
                                        <th style="vertical-align: bottom;border-bottom: 2px solid #dee2e6;">Attributes</th>
                                        <th style="vertical-align: bottom;border-bottom: 2px solid #dee2e6;">Unit</th>
                                        <th style="vertical-align: bottom;border-bottom: 2px solid #dee2e6;">Total</th>
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

                        <div>
                            <!-- /.col -->
                            <div class="col-6">
                                <strong style="font-size: 16px">Order total </strong>
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
                            <!-- /.col -->
                        </div>
                        <!-- /.row -->

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection


