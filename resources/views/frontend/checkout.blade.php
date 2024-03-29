@extends('layouts.layout_main')
@section('paypal')
<script src="https://www.paypal.com/sdk/js?client-id=Aas1X1_ghVD4YeQR2Y_kYVe5Y_WHeXU3BaW588PkuzMlGWtfPknjdSy8cwZeW3E-hG-s79v4FEv25vAt"></script>
@endsection
@section('content')
    <?php
    $cart = Request::session()->get('cart');
    $total = cart_total($cart);
    $user_id = Auth::id();
    $user_data = Auth::user()
    ?>
    <section class="products-page">
        <div class="container">
            <div class="row" style="background-image:url('{{ asset('images/products/checkout.jpg') }}');">
                <div class="col-md-6">
                    <div class="breadcrumb_iner">
                        Express Checkout
                    </div>
                </div>
                <div class="col-md-6"></div>
            </div>
        </div>
    </section>
    <section class="checkout_page mb-5">
        <div class="container-fluid">
            <div class="row justify-content-center">
                <div class="col-lg-12">
                    <div class="container">
                        <form id="frmCheckout" name="frmCheckout" method="post" class="billing-form">
                            <input type="hidden" id="cart_tax_rate" name="cart_tax_rate" value="0.08">
                            <input type="hidden" id="cart_shipping" name="cart_shipping" value="30.00">
                            <div class="row">
                                <div class="col-md-6">
                                    <h3 class="billing-title mt-20 mb-10"><i class="far fa-address-card"></i> Billing Details</h3>
                                    @if(!Auth::check())
                                    <div class="row">
                                        <div class="alert alert-info alert-dismissible fade show ml-3" role="alert">
                                            <a href="{{ url('login?redirect_url='.url('checkout') ) }}" class="alert-link">Log in </a> if you have an account or continue as a new customer.
                                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                    </div>
                                    @endif
                                    <div class="row" id="billing-form">
                                        <div class="col-sm-12 col-xs-12">
                                            <input type="text" name="name" value="{{ ($user_data)?$user_data->name:'' }}" placeholder="Name" class="form-control common-input" data-required="true">
                                        </div>
                                        <div class="col-12">
                                            <input type="text" name="address_1" value="{{ get_user_meta($user_id,'address') }}" placeholder="Address 1" class="form-control common-input" data-required="true">
                                        </div>
                                        <div class="col-12">
                                            <input type="text" name="address_2" value="{{ get_user_meta($user_id,'address_2') }}" placeholder="Address 2" class="form-control common-input" data-required="false">
                                        </div>
                                        <div class="col-lg-4 col-sm-12 col-xs-12">
                                            <input type="text" name="city"  value="{{ get_user_meta($user_id,'city') }}" placeholder="City" class="form-control common-input" data-required="true">
                                        </div>
                                        <div class="col-lg-4 col-sm-12 col-xs-12">
                                            <input type="text"  name="state"  value="{{ get_user_meta($user_id,'state') }}" placeholder="State" class="form-control common-input" data-required="true">
                                        </div>
                                        <div class="col-lg-4 col-sm-12 col-xs-12">
                                            <input type="number" name="zipcode"  value="{{ get_user_meta($user_id,'zipcode') }}" placeholder="Zipcode" class="form-control common-input" data-required="true">
                                        </div>
                                        <div class="col-lg-6 col-sm-12 col-xs-12">
                                            <input type="email" name="email" value="{{ ($user_data)?$user_data->email:'' }}" placeholder="Email Address" class="form-control common-input" data-required="true">
                                        </div>
                                        <div class="col-lg-6 col-sm-12 col-xs-12">
                                            <input type="number" name="phone" pattern="[0-9]{3}-[0-9]{2}-[0-9]{3}"  value="{{ get_user_meta($user_id,'phone') }}" placeholder="Phone Number" class="form-control common-input" data-required="true">
                                        </div>
                                    </div>
                                    <h3 class="billing-title mt-5 mb-2"><i class="fa fa-truck fa-right-5"></i> Shipping Details</h3>
                                    <div class="mt-20">
                                        <input type="checkbox" class="pixel-checkbox" value="1" id="same_as_billing" name="same_as_billing">
                                        <label for="same_as_billing">Shipping to the same address?</label>
                                    </div>
                                    <div class="row" data-show="same_as_billing"  id="shipping-form">
                                        <div class="col-sm-12 col-xs-12">
                                            <input type="text" name="name" value="" placeholder="Name" class="form-control common-input" data-required="true">
                                        </div>
                                        <div class="col-12">
                                            <input type="text" name="address_1" value="" placeholder="Address 1" class="form-control common-input" data-required="true">
                                        </div>
                                        <div class="col-12">
                                            <input type="text" name="address_2" value="" placeholder="Address 2" class="form-control common-input" data-required="false">
                                        </div>
                                        <div class="col-lg-4 col-sm-12 col-xs-12">
                                            <input type="text"  name="city"  value="" placeholder="City" class="form-control common-input" data-required="true">
                                        </div>
                                        <div class="col-lg-4 col-sm-12 col-xs-12">
                                            <input type="text" name="state"  value="" placeholder="State" class="form-control common-input" data-required="true">
                                        </div>
                                        <div class="col-lg-4 col-sm-12 col-xs-12">
                                            <input type="number" name="zipcode"  value="" placeholder="Zipcode" class="form-control common-input" data-required="true">
                                        </div>
                                        <div class="col-lg-6 col-sm-12 col-xs-12">
                                            <input type="email"  name="email" value="" placeholder="Email Address" class="form-control common-input" data-required="true">
                                        </div>
                                        <div class="col-lg-6 col-sm-12 col-xs-12">
                                            <input type="number" name="phone" pattern="[0-9]{3}-[0-9]{2}-[0-9]{3}"  value="" placeholder="Phone Number" class="form-control common-input" data-required="true">
                                        </div>
                                    </div>
                                    <h3 class="billing-title mt-5 mb-2"><i class="far fa-file-alt"></i> Special Comments</h3>
                                    <textarea id="notes" name="notes" placeholder="Order Notes" class="form-control common-textarea"></textarea>
                                </div>
                                <div class="col-md-6">
                                    <div class="order-wrapper mt-50">
                                        <h3 class="billing-title mb-10"><i class="fa fa-shopping-cart fa-right-5"></i> Your Order</h3>
                                        <div class="order-list">
                                            <div class="list-row d-none d-md-flex card-head font-weight-bold">
                                                <div class="col-7">Product</div>
                                                <div class="col-2 text-center">Qty</div>
                                                <div class="col-3 text-right">Total</div>
                                            </div>
                                            @if($cart['products'])
                                                @foreach($cart['products'] as $key => $item)
                                            <div class="item-product d-md-flex border-bottom mt-2 pb-2">
                                                <div class="col-md-7">
                                                    <a href="{{ $item['link'] }}">{{ $item['title'] }}</a>
                                                   <span>{!! $item['attributes_title'] !!}</span>
                                                </div>
                                                <div class="col-md-2 p-md-0 amount text-md-center text-truncate"><label class="font-weight-bold d-inline-block d-md-none">Quantity: </label> {{ $item['quantily'] }}</div>
                                                <div class="col-md-3 p-md-0 price text-md-right text-truncate"><label class="font-weight-bold d-inline-block d-md-none">Total: </label> {{ format_currency( floatval($item['subtotal'])*$item['quantily'] ,2,'$')  }}</div>
                                            </div>
                                                @endforeach
                                            @endif
                                            <div class="list-row d-flex justify-content-between mt-3">
                                                <h6>Subtotal</h6>
                                                <div id="shopping-cart-subtotal" class="field-subtotal">$21.64</div>
                                            </div>
                                            <div class="list-row d-flex justify-content-between">
                                                <h6>Sales Tax</h6>
                                                <div id="shopping-cart-tax" class="field-subtotal">$1.79</div>
                                            </div>
                                            <div class="list-row d-flex justify-content-between">
                                                <h6>Shipping</h6>
                                                <div id="shopping-cart-shipping" class="field-subtotal">$5.00</div>
                                            </div>
                                            <div class="list-row d-flex justify-content-between">
                                                <h6>Total</h6>
                                                <div id="shopping-cart-total" class="total field-subtotal">$28.43</div>
                                            </div>
                                            <div class="payment">
                                                <button type="button" onclick="payment_order()" class="cart_total_button1 btn btn-unipro text-uppercase btn-block mt-3 text-center">Payment <i class="fa fa-cart-arrow-down"></i></button>
                                            </div>
                                            <div id="payment-gate" class="d-none mt-3">
                                                <div class="accordion" id="ChooseGatePayment">
                                                    <div class="card">
                                                        <div class="card-header" id="headingOne">
                                                            <h2 class="mb-0">
                                                                <a class="btn btn-link btn-block text-left font-weight-bold" type="button" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                                                    Paypal
                                                                </a>
                                                            </h2>
                                                        </div>

                                                        <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#ChooseGatePayment">
                                                            <div class="card-body">
                                                                <div class="getway-payment">
                                                                    <div id="paypal-button-container"></div>
                                                                    <script>
                                                                        paypal.Buttons({
                                                                            createOrder: function(data, actions)
                                                                            {
                                                                                return actions.order.create({
                                                                                    purchase_units: [{
                                                                                        amount: {
                                                                                            value: {{ round($total['total'], 2) }}
                                                                                        }
                                                                                    }]
                                                                                });
                                                                            },
                                                                            onApprove: function(data, actions) {
                                                                                return actions.order.capture().then(function(details){
                                                                                    let data = [];
                                                                                    data[0] = {name:'order',value: details};
                                                                                    data[1] = {name:'payment',value: payment_order()};
                                                                                    data[2] = {name:'products',value: cart.products};
                                                                                    data[3] = {name:'total',value: {!! json_encode($total) !!} };
                                                                                    data[4] = {name:'payment_type',value: 2 };
                                                                                    send_order(data);
                                                                                });
                                                                            }
                                                                        }).render('#paypal-button-container');
                                                                    </script>
                                                                </div>
                                                            </div>
                                                            </div>
                                                        </div>
                                                    <div class="card">
                                                        <div class="card-header" id="headingTwo">
                                                            <h2 class="mb-0">
                                                                <a class="btn btn-link btn-block text-left font-weight-bold collapsed" type="button" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                                                    Cash on delivery
                                                                </a>
                                                            </h2>
                                                        </div>
                                                        <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#ChooseGatePayment">
                                                            <div class="card-body">
                                                                Cash on Delivery (COD) is a payment gateway that required no payment be made online. Orders using Cash on Delivery are set to Processing until payment is made upon delivery of the order by you or your shipping method.
                                                                <button type="button" data-total = '{!! json_encode($total) !!}' onclick="payment_with_cash(this)" class="btn btn-unipro mt-3">Continue</button>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="card">
                                                        <div class="card-header" id="headingThree">
                                                            <h2 class="mb-0">
                                                                <a class="btn btn-link btn-block text-left font-weight-bold collapsed" type="button" data-toggle="collapse" data-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                                                                    Authorize.net
                                                                </a>
                                                            </h2>
                                                        </div>
                                                        <div id="collapseThree" class="collapse" aria-labelledby="headingThree" data-parent="#ChooseGatePayment">
                                                            <div class="card-body">
                                                                <div data-payment="paymentFormAuthorize" data-total="{{ json_encode($total) }}">
                                                                    <form id="paymentForm"
                                                                          method="POST"
                                                                          action="">
                                                                        <input type="hidden" name="dataValue" id="dataValue" />
                                                                        <input type="hidden" name="dataDescriptor" id="dataDescriptor" />
                                                                        <button type="button"
                                                                                class="AcceptUI"
                                                                                data-billingAddressOptions='{"show":false, "required":false}'
                                                                                data-apiLoginID="{{ env('AUTHORIZE_NET_LOGIN_ID') }}"
                                                                                data-clientKey="{{ env('AUTHORIZE_NET_CLIENT_KEY') }}"
                                                                                data-acceptUIFormBtnTxt="Submit"
                                                                                data-acceptUIFormHeaderTxt="Card Information"
                                                                                data-paymentOptions='{"showCreditCard": true}'
                                                                                data-responseHandler="responseHandler">Pay
                                                                        </button>
                                                                    </form>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>


                                                </div>


                                            </div>

                                        </div>
                                    </div>
                                    <div class="mt-4">
                                        <div class="col">
                                            <div class="cart_control_bar d-flex flex-md-row flex-column align-items-start justify-content-start">
                                                <a class="btn btn-unipro button_continue_shopping ml-md-auto text-uppercase" href="{{ route('shops') }}">Continue Shopping <i class="fa fa-arrow-right"></i></a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
@section('footer_layout')
    <script type="text/javascript" src="{{ asset('js/AcceptUI.js') }}" charset="utf-8"></script>
<script>
    var data_payment = {
        "createTransactionRequest": {
            "merchantAuthentication": {
                "name": "{{ env('AUTHORIZE_NET_LOGIN_ID') }}",
                "transactionKey": "{{ env('AUTHORIZE_NET_TRANSACTION_KEY') }}"
            },
            "refId": "{{ uniqid(rand(0,99)) }}",
            "transactionRequest": {
                "transactionType": "authCaptureTransaction",
                "amount": "{{ round($total['total'], 2) }}",
                "payment": {
                    "opaqueData": {
                        "dataDescriptor": "COMMON.ACCEPT.INAPP.PAYMENT",
                        "dataValue": "9471056021205027705001",
                    }
                }
            }
        }
    };
</script>
@endsection
