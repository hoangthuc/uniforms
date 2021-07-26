<style>
    .order-product .item-attribute span.color {
        width: 36px;
        height: 36px;
        color: transparent;
        cursor: pointer;
        position: relative;
        box-shadow: 0px 0px 2px #0000008c;
        overflow: hidden;
        display: inline-block;
        font-size: 0;
    }

    .order-product .item-attribute span.number:hover, .order-product .item-attribute span.text:hover, .order-product .item-attribute .active span.number, .order-product .item-attribute .active span.text {
        background-color: #0c1b41;
        border: 1px solid #0c1b41;
        color: #fff;
    }

    .order-product .item-attribute span.number, .order-product .item-attribute span.text {
        color: #cdcdcd;
        background-color: #fff;
        border: 1px solid #cdcdcd;
        cursor: pointer;
        font-weight: normal;
    }

    .order-product .item-attribute .active span.color:after, .order-product .item-attribute span.color:hover:after {
        position: absolute;
        content: "";
        height: 36px;
        width: 36px;
        top: 0;
        left: 0;
        background-color: transparent;
        border: 3px solid #000000;
        border-radius: 3px;
    }

    span.attr_thumbnail {
        display: inline-block;
        background-size: cover;
        background-repeat: no-repeat;
        background-position: center center;
    }
</style>
@if(isset($product))
    <section class="single-product-page " data-id="{{ $product->id }}">
        <div class="container">
            <div class="row">
                <div class="col-12" id="select-product">
                    <div class="order-product">
                        <div class="title_product font-weight-bold mt-2">{{ $product->name }}</div>
                        <div class="price_amount mt-3 mb-3 row">
                            <table class="table table-bordered">
                                <thead>
                                <tr>
                                    <th class="text-center">Unit price</th>
                                    <th class="text-center w-25">Qty</th>
                                    <th class="text-center">Subtotal</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td class="text-center">
                                        <span class="price">
                                            {{ format_currency($productOrderMeta->ListPrice,2,'$') }}
                                        </span>
                                    </td>
                                    <td class="d-flex">
                                        <span class="btn btn-minus" onclick="change_amount(-1)"><i
                                                    class="fas fa-minus"></i></span>
                                        <input onchange="change_product_order_amount()" id="editOrderAmount" type="number" class="form-control text-center" name="amount" min="1"
                                               value="{{ $productOrderMeta->quantily }}"/>
                                        <span class="btn btn-minus" onclick="change_amount(1)"><i
                                                    class="fas fa-plus"></i></span>
                                    </td>
                                    <td class="text-center w-25 ">
                                        <input
                                                onfocusout="focusout_amount()"
                                                onkeyup="change_product_order_amount()"
                                               type="number" name="subtotal" class=" text-center form-control" id="editOrderSubtotal"
                                               value="{{$productOrderMeta->subtotal}}">
                                        <span class="subtotal">
                                </span>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="alert-notification d-none">
                            <div class="alert alert-danger cat-itm-err-msg" style="display: block;">
                                <strong>Missing <span data-number-field>2</span> Fields!</strong>
                                Please make sure you have completed all required fields! (<em class="small">highlighted
                                    in red)</em>
                            </div>
                        </div>
                        <div class="select_variant attributes pt-1 border-top">
                            @if( isset($attributes) )
                                <?php
                                $selectedColor = null;
                                ?>
                                @foreach($attributes as $item_attribute)
                                    @if($item_attribute['name'] != 'Weight')
                                        <div class="item-attribute select_variant"
                                             Attribute-P{{ $item_attribute['id'] }}>
                                            <label>{{ $item_attribute['name'] }}</label>
                                            <span class="ml-1">
                                                @if(isset($productOrderAttribute[$item_attribute['name']]))
                                                    ({{ $productOrderAttribute[$item_attribute['name']] }})
                                                @endif
                                            </span>
                                            <div class="list">

                                                @if(isset($item_attribute['list']))
                                                    @foreach ($item_attribute['list'] as $key=> $attribute)

                                                        <?php
                                                        if (($item_attribute['name'] == 'Color' &&
                                                                isset($productOrderAttribute[$item_attribute['name']]) &&
                                                                $productOrderAttribute[$item_attribute['name']] == $attribute->name)
                                                        ) {
                                                            $selectedColor = $attribute->id;
                                                        }
                                                        ?>
                                                        <div class="item-attribute-list d-inline-block
{{ (( isset($productOrderAttribute[$item_attribute['name']])&&$productOrderAttribute[$item_attribute['name']]== $attribute->name ))?'active':'' }} mb-1"
                                                             Attribute-Type="{{ $item_attribute['type'] }}"
                                                             data-name-parent="{{ $item_attribute['name'] }}"
                                                             data-parent="{{ $item_attribute['id'] }}"
                                                             data-id="{{ $attribute->id }}"
                                                             data-title="{{ $attribute->name }}"
                                                             data-price="{{ isset($price_attribute[$attribute->id])?$price_attribute[$attribute->id]:0 }}"
                                                             onclick="select_attribute(this,false)" Data-Attribute-Product>
                                                            @if(isset($thumbnail_color[$attribute->id]))
                                                                <span class="badge badge-secondary color attr_thumbnail"
                                                                      style="{{ ($thumbnail_color[$attribute->id])?'background-image: url('.\App\Media::get_url_media($thumbnail_color[$attribute->id]).')':'background-color:'.$attribute->data_type }}; font-size: 24px; text-transform: uppercase">{{ $attribute->name }}</span>
                                                            @else
                                                                {!! DisplayAttributeType($attribute->type,$attribute->data_type) !!}
                                                            @endif

                                                        </div>
                                                    @endforeach
                                                @endif
                                            </div>
                                        </div>
                                    @endif
                                <!-- end weight-->
                                @endforeach
                            @endif
                        </div>
                        <div class="select_name_plate">
                            @if($name_plates)
                                @include('admin.order.edit_product_order_nameplate')
                            @endif
                        </div>
                        @if( isset($outstock_hemming->data->Alterations) && count($outstock_hemming->data->Alterations) > 1  )
                            <div class="select_hemming item-attribute-hidden">
                                <label>Hemming</label> <span class="ml-1"></span>
                                <div class="form-group" data-hemming>
                                    <select class="form-control mb-2" data-name="hemming"
                                            onchange="display_hemming(this)"
                                            data-json="{{ json_encode($outstock_hemming->data->Alterations) }}">
                                        @foreach($outstock_hemming->data->Alterations as $key => $hemming)
                                            @if($key==1)
                                                <optgroup
                                                        label="{{ $hemming->AlterationGroup }}">{{ $hemming->AlterationGroup }}</optgroup>
                                            @endif
                                            @if($key>1)

                                                <option {{ ($productAlterations&&$productAlterations->ListID==$hemming->ListID)?"selected":"" }} value="{{ $key }}">{{ $hemming->ItemAlteration.' ('.format_currency($hemming->Price,2,'$').')' }}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        @endif
{{--                        {{ dd($productOrderMeta) }}--}}
                        <div id="form-add-cart" class="add-cart mt-3 d-flex" data-outstock="{{ $outstock }}"
                             data-all-price="{{ $all_price }}">
                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                            <input type="hidden" name="thumbnail"
                                   value="{{ isset($featured_image)?$featured_image:asset('images/products/default.jpg') }}">
                            <input type="hidden" name="link" value="{{ url( 'product/'.$product->slug  ) }}">
                            <input type="hidden" name="title" value="{{ $product->name }}">
                            <input type="hidden" name="subtotal"  value="{{ $productOrderMeta->subtotal }}">
                            <input type="hidden" name="price_default" value="{{ $price }}">

                        </div>
                    </div>
                </div>
            </div>

        </div>
    </section>
@endif


