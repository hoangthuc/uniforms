 <div>
        <div class="bd-callout bd-callout-warning"><label class="font-weight-bold">{{ $detail_attribute->name }}</label></div>
        <div class="border p-3">
            @if( isset($item['value'])) @foreach( $item['value'] as $attr )
            <div class="row mb-1">
                <div class="col-md-2 title">
                    <div data-check-default="{{ $detail_attribute->id }}" class="{{ isset($default_attribute[$attr['value']])?'active':'none' }}" onclick="add_product_varition_default(this)" data-select="{{ $attr['value'] }}" data-title="{{ $detail_attribute->name }}" data-value="{{ $attr['title'] }}">
                        <i class="fas fa-star"></i>
                        <i class="far fa-star"></i>
                    </div>
                    <span class="badge bg-secondary">{{ isset($attr['title'])?$attr['title']:'' }}</span>
                </div>
                <div class="col-md-8 price text-left">
                    <div class="form-group">
                        <div class="form-control" DataCurrency>{{ isset( $price_attribute[$attr['value']] )?format_currency($price_attribute[$attr['value']],2,'$'):'$0.00' }}</div>
                        <input type="number" class="DataCurrencyGet form-control" data-attribute-id="{{ $attr['value'] }}" value="{{ isset($price_attribute[$attr['value']])?$price_attribute[$attr['value']]:0 }}" placeholder="Enter price" data-title="Price" data-required="false" onkeyup="load_data_money(this)" onchange="load_data_money(this)" autocomplete="off" data-price-attribute>
                    </div>
                </div>
            </div>
                @endforeach
            @endif
        </div>
    </div>
