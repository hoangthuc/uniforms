@extends('admin.order.order_layout')
@section('content')
    <?php
    $user = Auth::user();
    $order = App\Orders::get_order($order_id);
    $order_status = App\Orders::order_status();
    $payment_type = App\Orders::payment_type();

    $customer_bill = App\Orders::get_meta_product_order($order_id,'customer_bill');
    $customer_shipping = App\Orders::get_meta_product_order($order_id,'customer_shipping');
    $billing_address = App\Orders::get_meta_product_order($order_id,'billing_address');
    $billing_address = ($billing_address)?(array)json_decode($billing_address):[];
    $shipping_address = App\Orders::get_meta_product_order($order_id,'shipping_address');
    $shipping_address = ($shipping_address)?(array)json_decode($shipping_address):[];
    $list_products = App\Orders::get_meta_product_order($order_id,'products');

    if( isset($list_products) ){
        $products = display_product_in_order( json_decode($list_products),$order_id );
        $products['tax'] = ($products['subtotal']*$order->tax)/100;
        $products['total'] = $order->shipping + $products['tax'] + $products['subtotal'];
    }

    $tracking_field = ["Tracking", "Carrier", "url_tracking", "ShipDate", "PackingList"];
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
                        <li class="breadcrumb-item active">Edit Order</li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-8">
                    <!-- general form elements -->
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">Order #{{ $order_id }}</h3>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body save_order">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Billing Address</label>
                                        <input type="text" name="customer_bill" class="form-control"
                                               placeholder="Customer name" data-title="Customer name"
                                               data-required="false" value="{{ $customer_bill }}">
                                        <span class="um-field-error d-none"></span>
                                        <address class="p-2" data-field="billing_address">
                                            <div class="content">
                                            @if( isset($billing_address['address_1']) )
                                                <span>{!! $billing_address['address_1'] !!}</span><br/>
                                                <span>{!! $billing_address['address_2'] !!}</span><br/>
                                                <span>{!! $billing_address['city'].', '.$billing_address['state'].', '.$billing_address['zipcode'] !!}</span><br/>
                                                <span>{!! $billing_address['email'].', '.$billing_address['phone'] !!}</span>
                                            @endif
                                            </div>
                                                <a onclick="edit_address_order(this)" data-json="{!! \GuzzleHttp\json_encode($billing_address) !!}" data-field="billing_address"><i class="far fa-edit" style="font-size: 20px;"></i></a>
                                        </address>
                                        <div class="d-none p-2" data-field="edit-billing_address">
                                            @foreach($billing_address as $name => $field)
                                                @if($name !='name')
                                                <div class="form-group">
                                                    <input type="text" data-name="{{ $name }}" class="form-control" placeholder="{{ $name }}"
                                                           data-required="true" value="{{ $field }}">
                                                    <span class="um-field-error d-none"></span>
                                                </div>
                                               @endif
                                            @endforeach
                                            <a class="btn btn-app" data-dom="billing_address" data-order="{{ $order_id }}" onclick="send_address_order(this)">
                                                <i class="fa fa-save fa-right-5"></i> Save
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Shipping Address</label>
                                        <input type="text" name="customer_shipping" class="form-control"
                                               placeholder="Customer name" data-title="Customer name"
                                               data-required="true" value="{{ $customer_shipping }}">
                                        <span class="um-field-error d-none"></span>
                                        <address class="p-2" data-field="shipping_address">
                                            <div class="content">
                                                    @if( isset($shipping_address['address_1']) )
                                                        <span>{!! $shipping_address['address_1'] !!}</span><br/>
                                                        <span>{!! $shipping_address['address_2'] !!}</span><br/>
                                                        <span>{!! $shipping_address['city'].', '.$shipping_address['state'].', '.$shipping_address['zipcode'] !!}</span><br/>
                                                        <span>{!! $shipping_address['email'].', '.$shipping_address['phone'] !!}</span>
                                                    @endif
                                            </div>
                                            <a onclick="edit_address_order(this)" data-json="{!! \GuzzleHttp\json_encode($shipping_address) !!}" data-field="shipping_address"><i class="far fa-edit" style="font-size: 20px;"></i></a>
                                        </address>
                                        <div class="d-none p-2" data-field="edit-shipping_address">
                                            @foreach($shipping_address as $name => $field)
                                                @if($name !='name')
                                                    <div class="form-group">
                                                        <input type="text" data-name="{{ $name }}" class="form-control" placeholder="{{ $name }}"
                                                               data-required="true" value="{{ $field }}">
                                                        <span class="um-field-error d-none"></span>
                                                    </div>
                                                @endif
                                            @endforeach
                                            <a class="btn btn-app" data-dom="shipping_address" data-order="{{ $order_id }}" onclick="send_address_order(this)">
                                                <i class="fa fa-save fa-right-5"></i> Save
                                            </a>
                                        </div>

                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Tracking Order</label>
                                        <div class="p-2" data-field="tracking_order">
                                            <div class="content">
                                                @if( isset($tracking_order['Tracking']) )
                                                    <span>Tracking number: {!! $tracking_order['Tracking'] !!}</span><br/>
                                                    <span>Company: {!! isset($tracking_order['Carrier'])? $company[$tracking_order['Carrier']]['name']:'' !!}</span><br/>
                                                    <span>URL: {!! $tracking_order['url_tracking'] !!}</span><br/>
                                                    <span>Ship Date: {!! $tracking_order['ShipDate'] !!}</span><br/>
                                                    <span>Packing List: {!! $tracking_order['PackingList'] !!}</span>
                                                @endif
                                            </div>
                                            <a onclick="edit_address_order(this)" data-json="{!! \GuzzleHttp\json_encode($shipping_address) !!}" data-field="tracking_order"><i class="far fa-edit" style="font-size: 20px;"></i></a>
                                        </div>
                                        <div class="d-none p-2" data-field="edit-tracking_order">
                                            @foreach($tracking_field as $field)
                                                    <div class="form-group">
                                                        @if($field == 'Carrier')
                                                            <select data-name="{{ $field }}" class="form-control">
                                                            @foreach( $company  as $id => $com )
                                                                <option value="{{ $id }}" {{ ( isset($tracking_order[$field]) && $tracking_order[$field] == $id)?'selected':'' }}>{{ $com['name'] }}</option>
                                                            @endforeach
                                                            </select>
                                                        @else
                                                        <input type="text" data-name="{{ $field }}" class="form-control" placeholder="{{ $field }}"
                                                               data-required="true" value="{{ isset($tracking_order[$field])?$tracking_order[$field]:'' }}">
                                                        @endif
                                                        <span class="um-field-error d-none"></span>
                                                    </div>
                                            @endforeach
                                            <a class="btn btn-app" data-dom="tracking_order" data-order="{{ $order_id }}" onclick="send_tracking_order(this)">
                                                <i class="fa fa-save fa-right-5"></i> Save
                                            </a>
                                        </div>

                                    </div>
                                </div>
                            </div>
                            <!-- Table row -->
                            <div class="row">
                                <!-- accepted payments column -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Payment type</label>
                                        <select class="form-control select2" name="payment_type"
                                                data-title="Payment Type" data-required="false">
                                            @if($payment_type)
                                                @foreach($payment_type as $key=>$value)
                                                    <option value="{{ $key }}" {{ $key == $order->payment_type ? 'selected':'' }}>{{ $value }}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label>Status</label>
                                        <select class="form-control select2" name="status"
                                                data-title="Status" data-required="false">
                                            @if($order_status)
                                                @foreach($order_status as $key=>$value)
                                                    <option value="{{ $key }}" {{ $key == $order->status ? 'selected':'' }}>{{ $value }}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>

                                </div>
                                <!-- /.col -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Tax <em>(%)</em></label>
                                        <input type="number" name="tax" class="form-control" placeholder="Enter tax"
                                               data-title="Tax" data-required="true" min="0" step="1" value="{{ $order->tax }}" readonly>
                                        <span class="um-field-error d-none"></span>
                                    </div>

                                    <div class="form-group">
                                        <label>Shipping</label>
                                        <input type="number" name="shipping" class="form-control"
                                               placeholder="Enter shipping" data-title="Shipping" data-required="true"
                                               min="0" value="{{ $order->shipping }}" readonly>
                                        <span class="um-field-error d-none"></span>
                                    </div>
                                </div>
                                <!-- /.col -->
                            </div>

                            <div class="row">
                                <div class="col-12 table-responsive">
                                    <label>Products</label>
                                    <table class="table table-striped table-hover">
                                        <thead>
                                        <tr>
                                            <th>Qty</th>
                                            <th>Product</th>
                                            <th>Attributes</th>
                                            <th>Unit</th>
                                            <th>Subtotal</th>
                                            <th></th>
                                        </tr>
                                        </thead>
                                        <tbody data-display-ajax-product>
                                        @if(isset($products))
                                            {!! $products['html']  !!}
                                        @endif
                                        </tbody>
                                    </table>
                                </div>
                                <!-- /.col -->
                            </div>
                            <!-- /.row -->

                            <div class="form-group">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}" data-title="Token"
                                       data-required="false">
                                <input type="hidden" id="action" name="action" value="{{ url('admin/edit_order') }}"
                                       data-title="action" data-required="false"/>
                                <input type="hidden" name="id" value="{{ $order_id }}" data-title="ID"
                                       data-required="false"/>
                            </div>
                            <!-- Note -->
                            <div class="form-group">
                                <label>Note</label>
                                <textarea class="form-control" name="note" rows="5" data-title="Note"
                                          data-required="false" placeholder="Note">{!!  $order->note !!}</textarea>
                            </div>
                            <!-- /.row -->

                        </div>
                    </div>
                    <!-- /.card -->


                </div>
                <!-- right colume-->
                <div class="col-sm-4">
                    <!-- Form Element sizes -->
                    <div class="card card-info">
                        <div class="card-header">
                            <h3 class="card-title">Controls</h3>
                        </div>
                        <div class="card-body">
                            <p class="lead">Date {{ date('m/d/Y') }}</p>

                            <div class="table-responsive">
                                <table class="table">
                                    <tr>
                                        <th style="width:50%">Subtotal:</th>
                                        <td>{{ $products ? format_currency($products['subtotal'],2,'$'):0 }}</td>
                                    </tr>
                                    <tr>
                                        <th>Tax:</th>
                                        <td>{{ $products ? format_currency($products['tax'],2,'$'):0 }}</td>
                                    </tr>
                                    <tr>
                                        <th>Shipping:</th>
                                        <td>{{ $products ? format_currency( $order->shipping ,2,'$'):0 }}</td>
                                    </tr>
                                    <tr>
                                        <th>Total:</th>
                                        <td>{{ $products ? format_currency($products['total'],2,'$'):0 }}</td>
                                    </tr>
                                </table>
                            </div>

                            <a class="btn btn-app" href="{{ url('admin/order/'.$order_id.'/view') }}">
                                <i class="fas fa-file-invoice"></i> View
                            </a>
                            <a class="btn btn-app" href="javascript:delete_post('{{$order_id}}','detele_order');">
                                <i class="fas fa-trash"></i> Delete
                            </a>
                        </div>
                        <!-- /.card-body -->
                        <div class="card-footer">
                            <div class="form-group">
                                <a href="{{ route('admin.orders') }}" class="btn btn-outline-info btn-flat"><i
                                            class="fas fa-chevron-circle-left"></i> Cancel</a>
                                <a href="javascript:save_order(this)" data-products='{{ App\Orders::get_meta_product_order($order_id,'products') }}' id="button-save-order"
                                   class="btn btn-outline-info btn-flat float-right"><i
                                            class="fa fa-save fa-right-5"></i> Save <span class="d-none">
                                <i class="fas fa-spinner fa-spin"></i>
                            </span></a>
                            </div>

                        </div>
                        <!-- /.card-footer -->
                    </div>
                    <!-- /.card -->
                </div>
            </div>
        </div>
    </section>
    @include('admin.order.edit-order-product-modal')
@endsection


