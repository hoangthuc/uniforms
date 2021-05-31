@extends('layouts.layout_main')
@section('content')
    <section class="products-page">
        <div class="container">
            <div class="row" style="background-image:url('{{ asset('images/products/cart_page.jpg') }}');">
                <div class="col-md-6">
                    <div class="breadcrumb_iner">
                        Find Order
                    </div>
                </div>
                <div class="col-md-6"></div>
            </div>
        </div>
    </section>
    <section class="cart-info mt-3 mb-5">
        <div class="container">
            <div class="cart_container">
                <div class="order-container">
                    <form class="form-row" action="" method="get">
                        <div class="form-group col-md-8">
                            <input type="number" class="form-control" name="find_order" value="{{ isset($_GET['find_order'])?$_GET['find_order']:'' }}" placeholder="Enter Order ID">
                        </div>
                        <div class="form-group col-md-4">
                        <button type="submit" class="btn btn-primary form-control mb-2">Filter</button>
                        </div>
                        <div class="form-group col-md-12 text-left">
                            @if($resulf['status'])
                            <div class="alert alert-success" role="alert">
                                <span>Order <strong>#{!! $_GET['find_order'] !!}</strong> is {{ $resulf['status'] }}</span><br/>
                                @if( $resulf['tracking_order'] )
                                    <strong>Tracking number: </strong> <span>{!! $resulf['tracking_order']->Tracking !!}</span> <br/>
                                    <strong>Company: </strong> <a href="{!! $resulf['tracking_order']->url_tracking !!}" target="_blank"><strong>{!! $company[ $resulf['tracking_order']->Carrier ]['name'] !!}</strong></a><br/>
                                    <strong>Ship Date: </strong> <span>{!! $resulf['tracking_order']->ShipDate !!}</span><br/>
                                    <strong>Packing List: </strong> <span>{!! $resulf['tracking_order']->PackingList !!}</span><br/>
                                    <a href="{!! $resulf['tracking_order']->url_tracking !!}" target="_blank"><span>(View tracking)</span></a>
                                @endif
                                @if($resulf['note'])
                                    {{ nl2br($resulf['note']) }}
                                @endif
                            </div>
                            @endif
                            @if(!$resulf['status'] && isset($_GET['find_order']))
                                    <div class="alert alert-info" role="alert">
                                        <p>Not found order.</p>
                                    </div>

                            @endif

                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection
