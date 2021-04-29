@extends('layouts.layout_main')
@section('content')
    <section class="products-page">
        <div class="container">
            <div class="border-brand row" style="background-image:url('{{ asset('images/products/brands.png') }}');">
                <div class="col-md-6">
                    <div class="breadcrumb_iner">
                       @if( isset($data_brand->img) )
                           <img class="logo-partner-top" src="{{ $data_brand->img }}"/>
                       @endif
                    </div>
                </div>
                <div class="col-md-6"></div>
            </div>
        </div>
    </section>
    <section class="product-page-taxonomy">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="breadcrumb-page">
                        <a href="{{ url('/') }}">Home</a>/<a>{{ isset($parent_brand)?$parent_brand->name:'' }}</a>/<span>{{ $data_brand->name }}</span>
                    </div>
                </div>
            </div>
            <div class="row mt-3">
                <div class="col-md-3">
                    <div class="filter-products">
                        <a href="javascript:hide_filter_product(this);" data-display="true" class="display-control"><i class="fas fa-chevron-left"></i> <span>Show Filter</span></a>
                    </div>
                </div>
                <div class="col-md-9">
                    <a class="sort-product">Sort by: </a>
                    <select name="sort_by" onchange="start_filter_product()" class="sort-filter">
                        <option value="sku" selected>Item Number / SKU</option>
                        <option value="newest">Newest</option>
                        <option value="price_desc">Price: High to Low</option>
                        <option value="price_asc">Price: Low to High</option>
                        <option value="top_sell_week">Best sell in week</option>
                        <option value="top_sell_month" >Best sell in month</option>
                        <option value="top_sell_year">Best sell in year</option>
                    </select>
                    <span Data-Resulfs-Count>{{ format_currency($filter_products['pagition']['total'],0).' results' }}</span>
                    <div class="pagition-product float-right mt-2 mt-sm-0">{!! DisplayPagition($filter_products['pagition']) !!}</div>
                </div>
            </div>

            <div class="row mt-3">
                <div class="col-md-3 d-none" Data-Filter-Product>
                    <input type="hidden" name="slug" value=""/>
                    <div class="show_content_filter">
                        <div class="fa-3x text-center">
                            <i class="fas fa-spinner fa-pulse"></i>
                        </div>
                    </div>
                </div>
                <div class="col-md-12" Data-Products-Resulf>
                    <div class="row">
                        @if($filter_products['data'])
                            @foreach($filter_products['data'] as $key=>$item)
                                <div class="item-product col-md-3" Data-Item-Product>
                                    <div class="item-content bg-white">
                                        {!! showItemProduct($item) !!}
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="content col-md-12"><div class="alert alert-info" role="alert">Please contact our customer support for this item email.</div></div>
                        @endif
                    </div>
                </div>
            </div>
            <div class="bottom-products mb-5">
                <div class="pagition-product float-right">{!! DisplayPagition($filter_products['pagition']) !!}</div>
            </div>

        </div>
    </section>
@endsection
@section('footer_layout')
    <script>
        var cat_default = {!! json_encode( list_ob_to_array($cat) ) !!};
        var brand_default = {!! '{type:"'.$brand.'",value:"'.$brand_id.'"}' !!};
        var query_filter = {!! json_encode( $query ) !!};
        display_filter_product();
    </script>
    <style>
        {{--.item-filter[data-type="{{ $brand }}"]{display: none};--}}
    </style>
@endsection