@if( isset($billing_address['address_1']) )
    <span>{!! $billing_address['address_1'] !!}</span><br/>
    <span>{!! $billing_address['address_2'] !!}</span><br/>
    <span>{!! $billing_address['city'].', '.$billing_address['state'].', '.$billing_address['zipcode'] !!}</span><br/>
    <span>{!! $billing_address['email'].', '.$billing_address['phone'] !!}</span>
@endif

@if( isset($shipping_address['address_1']) )
    <span>{!! $shipping_address['address_1'] !!}</span><br/>
    <span>{!! $shipping_address['address_2'] !!}</span><br/>
    <span>{!! $shipping_address['city'].', '.$shipping_address['state'].', '.$shipping_address['zipcode'] !!}</span><br/>
    <span>{!! $shipping_address['email'].', '.$shipping_address['phone'] !!}</span>
@endif

@if( isset($tracking_order['Tracking']) )
    <span>Tracking number: {!! $tracking_order['Tracking'] !!}</span><br/>
    <span>Company: {!! $tracking_order['Carrier'] !!}</span><br/>
    <span>URL: {!! $tracking_order['url_tracking'] !!}</span><br/>
    <span>Ship Date: {!! $tracking_order['ShipDate'] !!}</span><br/>
    <span>Packing List: {!! $tracking_order['PackingList'] !!}</span>
@endif

@if( isset($list_product))
    @if( count($list_product) )
    <ul class="nav flex-column mt-3">
 @foreach($list_product as $product)
     <li class="nav-item">
     <a class="item-product nav-link" data-id="<?= $product->id ?>" onclick="select_product_in_order(this)">
         <?= $product->name  ?>
     </a>
     </li>
 @endforeach
    </ul>
     @else
     <div class="p-2">
        <div class="callout callout-danger">Find not found.</div>
     </div>
     @endif
@endif

@if( isset($single_product) )
    <div class="p-2">
        @if( isset($attributes) )
            <div class="select_variant attributes">
                <div class="form-group form-inline">
                    <label class="mr-2">SKU</label>
                    <div class="badge badge-primary" style="font-size: 18px;">{{ $sku }}</div>
                </div>
            @foreach($attributes as $item_attribute)
                @if( $item_attribute['name'] != 'Weight')
                    <div class="form-group form-inline" Attribute-P{{ $item_attribute['id'] }}>
                        <label class="mr-2">{{ $item_attribute['name'] }}</label>
                        <select class="form-control custom-select" data-name="{{ $item_attribute['name'] }}" oninput="change_attribute_product(this)">
                            @if(isset($item_attribute['list']))
                                @foreach ($item_attribute['list'] as $key=> $attribute)
                                    <option value="{{ $attribute->id }}">{{ $attribute->name }}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                @endif
            <!--end end weight--->
            @endforeach
            </div>
        @endif
        @if(isset($name_plates) && count($name_plates))
            <div class="select_name_plate select_other_attr">
                <div class="form-group form-inline">
                    <label class="mr-2">Name Plates</label>
                    <select class="form-control" data-name="Name plate" >
                        @foreach($name_plates as $value)
                            <?php
                            $color = $value->color;
                            $detail_attribute = \App\Product::get_product_attributes_detail_single($color);
                            $plate = $value->plate;
                            $name_plate = \App\Product::product_name_line();
                            $line = 'line_1';
                            if($plate =='2_even' || $plate =='2_r_small') $line = 'line_2';
                            if($plate =='3_even' || $plate =='3_r_small') $line = 'line_3';
                            ?>
                            <option value="{{ $line }}" data-key="{{ $value->key }}" data-title="{{ $name_plate[$plate].' '.$detail_attribute->name }}">{{ $name_plate[$plate].' '.$detail_attribute->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        @endif
        @if( isset($outstock->Alterations) && count($outstock->Alterations) )
            <div class="select_hemming select_other_attr">
                <div class="form-group form-inline" >
                    <label class="mr-2">Hemming</label>
                    <select class="form-control" data-name="Hemming">
                        @foreach($outstock->Alterations as $key => $hemming)
                            <option value="{{ $hemming->ProductID }}" data-price="{{ $hemming->Price }}" data-stt="{{ $key }}" data-title="{{ $hemming->ItemAlteration }}">{{ $hemming->ItemAlteration.' (+'.format_currency($hemming->Price,2,'$').')' }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        @endif
        <div class="select_quanlity">
                <div class="form-group form-inline" >
                    <label class="mr-2">Quantily</label>
                    <input type="number" name="quantily" oninput="change_attribute_product(this)" class="form-control" min="1" value="1">
                </div>
        </div>
        <div class="add-cart mt-3 form-inline" data-outstock="{{ json_encode($outstock) }}" >
            <input type="hidden" name="product_id" value="{{ $single_product->id }}">
            <input type="hidden" name="link" value="{{ url( 'product/'.$single_product->slug  ) }}">
            <input type="hidden" name="title" value="{{ $single_product->name }}">
            <button type="button" class="btn btn-info btn-flat m-3" onclick="insert_product_in_order(this)">Insert product</button>
            <div class="out-of-stock text-danger font-weight-bold d-none">Out of stock</div>
        </div>
    </div>
@endif
