@if( isset($hemming) )
    <div class="item_name_plate mb-3 border-top pt-2 row" data_display_hemming="{{ $hemming }}" data-price="{{ $hemming_price }}">
        <div class="display-media-attribute title-attribute col-md-5">
            <span class="badge bg-secondary mr-5"  style="font-size: 18px;">{{ $hemming }}</span>
        </div>
        <div class="col-md-3">
            <span class="badge bg-secondary mr-5"  style="font-size: 18px;">{{ format_currency($hemming_price,2,'$') }}</span>
        </div>
        <div class="col-md-3 ml-5 btn-remove" onclick="$(this).parent().remove()">
            <i class="far fa-times-circle" style="font-size: 28px;"></i>
        </div>
    </div>
@endif