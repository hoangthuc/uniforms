@extends('layouts.layout_main')
@section('content')
    <?php
    $cart = Request::session()->get('cart');
    ?>
    <section class="products-page">
        <div class="container">
            <div class="row" style="background-image:url('{{ asset('images/products/cart_page.jpg') }}');">
                <div class="col-md-6">
                    <div class="breadcrumb_iner">
                       Shopping Cart
                    </div>
                </div>
                <div class="col-md-6"></div>
            </div>
        </div>
    </section>
    <section class="cart-info mt-3 mb-5">
        <div class="container">
            <div class="cart_container">
                <div class="container shopping-cart-container">
                    <form id="frmShoppingCart"  action="" method="post" class="form-light" enctype="multipart/form-data" role="form">
                        <input type="hidden" id="action" name="action" value="send_to_checkout">
                        <input type="hidden" id="cart_tax_rate" name="cart_tax_rate" value="0.08">
                        <input type="hidden" id="cart_shipping" name="cart_shipping" value="30.00">
                        <input type="hidden" id="cart_total" name="cart_total" value="35.85">
                        <div class="cart-head pt-3 mb-3 d-none d-sm-none d-md-block">
                            <div class="row cart-title">
                                <div class="col-md-5">
                                    <h6 class="font-weight-bold">Product</h6>
                                </div>
                                <div class="col-md-2">
                                    <h6 class="font-weight-bold">Price</h6>
                                </div>
                                <div class="col-md-2">
                                    <h6 class="font-weight-bold">Quantity</h6>
                                </div>
                                <div class="col-md-2">
                                    <h6 class="font-weight-bold">Total</h6>
                                </div>
                                <div class="col-md-1">
                                    <h6 class="font-weight-bold"></h6>
                                </div>
                            </div>
                        </div>
                        <div id="shopping-cart-items">
                            @if( isset($cart['products']))
                                @foreach($cart['products'] as $key => $item)
                                    <div class="cart-single-item shopping-cart-item-id-{{ $item['key'] }} border-bottom pb-3 mb-3" data-item-id="{{ $item['key'] }}">
                                        <div class="row align-items-center">
                                            <div class="col-md-5 col-12">
                                                <div class="product-item d-flex align-items-center">
                                                    <a class="thumnail-product-cart" href="{{ $item['link'] }}" style="background-image: url({{ $item['thumbnail'] }})">
                                                        <img src="{{ $item['thumbnail'] }}" alt="image">
                                                    </a>
                                                    <h6 class="pl-3"><a class="font-weight-bold" href="{{ $item['link'] }}">{{ $item['title'] }}</a>
                                                       <div class="mt-3">{!! $item['attributes_title'] !!}</div>
                                                    </h6>
                                                </div>
                                            </div>
                                            <div class="col-md-2 col-4">
                                                <div class="price">{{ format_currency( floatval($item['ListPrice']),2,'$')  }}</div>
                                            </div>
                                            <div class="col-md-2 col-4">
                                                <div class="quantity-container d-flex align-items-center mt-15">
                                                    <input type="number" min="1" id="qty-2" name="quantily" data-product-id="{{ $item['key'] }}" class="quantity form-control input-product-qty" onchange="change_quantily(this)" value="{{ $item['quantily'] }}">
                                                </div>
                                            </div>
                                            <div class="col-md-2 d-none d-sm-none d-md-block">
                                                <div class="total-price price">{{ format_currency( floatval($item['ListPrice'])*$item['quantily'] ,2,'$')  }}</div>
                                            </div>
                                            <div class="col-md-1 col-4 text-right">
                                                <button type="button" class="btn btn-link btn-lg btn-remove-product" onclick="remove_product(this)" data-product-id="{{ $item['key'] }}"><i class="fa fa-trash"></i></button>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <div class="product-item d-flex align-items-center">
                                    <h4 class="pink">There are no items in your shopping cart.</h4>
                                </div>
                            @endif

                        </div>
                        <div class="row">
                            <div class="col mb-3">
                                <div class="cart_control_bar d-flex flex-md-row flex-column align-items-start justify-content-start">
                                    <a class="btn btn-unipro button_continue_shopping ml-md-auto text-uppercase" href="{{ route('shops') }}">Continue Shopping <i class="fa fa-arrow-right"></i></a>
                                </div>
                            </div>
                        </div>
                        @if( isset($cart['products']))
                        <div class="row cart_extra">
                            <div class="col-lg-6 mb-3">
                                <div class="cart_coupon d-none">
                                    <div class="cart_title pb-2">Got a Coupon?</div>
                                    <input disabled="" readonly="" type="text" class="form-control" placeholder="Coupon Code">
                                    <button disabled="" type="button" class="button_clear btn btn-unipro text-uppercase mt-2"><i class="fa fa-usd"></i> Apply Coupon</button>
                                </div>
                            </div>
                            <div class="col-lg-5 offset-lg-1 pb-5">
                                <div class="cart_total">
                                    <div class="cart_title">Cart Total</div>
                                    <ul class="mt-3">
                                        <li class="d-flex flex-row align-items-center justify-content-start">
                                            <div class="cart_total_title font-weight-bold">Subtotal</div>
                                            <div class="cart_total_price ml-auto"><span id="shopping-cart-subtotal">$28.50</span></div>
                                        </li>
                                        <li class="d-flex flex-row align-items-center justify-content-start">
                                            <div class="cart_total_title font-weight-bold">Tax</div>
                                            <div class="cart_total_price ml-auto"><span id="shopping-cart-tax">$2.35</span></div>
                                        </li>
                                        <li class="d-flex flex-row align-items-center justify-content-start">
                                            <div class="cart_total_title font-weight-bold">Shipping</div>
                                            <div class="cart_total_price ml-auto"><span id="shopping-cart-shipping">$5.00</span></div>
                                        </li>
                                        <li class="d-flex flex-row align-items-center justify-content-start">
                                            <div class="cart_total_title font-weight-bold">Total</div>
                                            <div class="cart_total_price ml-auto"><span id="shopping-cart-total">$35.85</span></div>
                                        </li>
                                    </ul>
                                    <a href="{{ url('checkout')  }}" id="btnCheckout" class="cart_total_button1 btn btn-unipro text-uppercase btn-block push-top-25">Proceed To Checkout <i class="fa fa-arrow-right"></i></a>
                                </div>
                            </div>
                        </div>
                        @endif
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection
