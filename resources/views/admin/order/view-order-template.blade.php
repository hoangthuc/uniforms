@extends('admin.order.template_email_layout')
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
    <section style="max-width: 1200px; margin: 0 auto; border: 2px solid #dee2e6; padding: 15px;">
        <div style="">
            <div>
                <div style="max-width: 100%;">
                    <!-- Main content -->
                    <div>
                        <!-- title row -->
                        <div>
                            <div style="display: block">
                                <h4 style="display: flex;">
                                    <a href="{{ url('/') }}"><img src="{{ asset('images/unipro_stars_logo.png') }}" style="width: 100px;" /></a>
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
                                    @if( isset($billing_address['address']) )
                                        <span>{!! $billing_address['address'] !!}</span><br/>
                                        <span>{!! $billing_address['city'].', '.$billing_address['state'].', '.$billing_address['zipcode'] !!}</span><br/>
                                        <span>{!! $billing_address['email'].', '.$billing_address['phone'] !!}</span>
                                    @endif
                                </address>
                            </div>
                            <div style="width: 100%; padding-right: 7.5px;padding-left: 7.5px;">
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

                        <div style="display: flex;margin-top: 30px;">
                            <!-- /.col -->
                            <div class="col-6" style="width: 45%;border-right: 2px solid #19437f66; padding-right: 20px;">
                                <strong style="font-size: 16px">Order total </strong>
                                <div class="table-responsive">
                                    <table class="table" style="width: 100%; border-spacing: 0; margin-top: 10px;">
                                        <tr>
                                            <th style="width:50%; text-align: left; padding: .75rem; border-top: 1px solid #dee2e6;">Subtotal:</th>
                                            <td style="border-top: 1px solid #dee2e6;">{{  format_currency( $products['subtotal'] ,2,'$') }}</td>
                                        </tr>
                                        <tr>
                                            <th style="width:50%; text-align: left; padding: .75rem; border-top: 1px solid #dee2e6;">Tax ({{ $order->tax.'%' }})</th>
                                            <td style="border-top: 1px solid #dee2e6;">{{ $order->tax ? format_currency( ($order->tax / 100 ) * $products['subtotal']  ,2,'$') : '0' }}</td>
                                        </tr>
                                        <tr>
                                            <th style="width:50%; text-align: left; padding: .75rem; border-top: 1px solid #dee2e6;">Shipping:</th>
                                            <td style="border-top: 1px solid #dee2e6;">{{ $order->shipping ? format_currency($order->shipping,2,'$') : '0' }}</td>
                                        </tr>
                                        <tr>
                                            <th style="width:50%; text-align: left; padding: .75rem; border-top: 1px solid #dee2e6;">Total:</th>
                                            <td style="border-top: 1px solid #dee2e6;">{{ format_currency($products['total'],2,'$') }}</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                            <!-- /.col -->
                            <div class="col-6" style="width: 50%;padding: 0 20px;">
                                <div class="lead"> <strong style="font-size: 16px">Note:  </strong></div>
                                <div class="content">{!! nl2br($order->note)  !!}</div>
                            </div>
                        </div>
                        <!-- /.row -->
                        <div style="margin-top: 20px; padding-top: 20px; border-top: 2px solid #dee2e6; text-align: center;font-size: 15px;">
                            <p>If you have any questions or need advice, please contact us via Email: <a href="mailto:info@uniprouniforms.com">info@uniprouniforms.com<a> or Toll free: <a href="tel:(888) 691-6200">(888) 691-6200</a></p>
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </section>
@endsection


