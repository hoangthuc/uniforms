<div Display-Attribute-Product>
    <div class="bd-callout bd-callout-warning"><label class="font-weight-bold">{{ $detail_attribute->name }}</label>
    </div>
    <div class="border p-3">
        @if( isset($item['value'])) @foreach( $item['value'] as $attr )
            <div class="row mb-3 pb-3 border-bottom">
                <div class="{{ $detail_attribute->type?'col-md-2':'col-md-4' }} title">
                    <div data-check-default="{{ $detail_attribute->id }}"
                         class="{{ isset($default_attribute[$attr['value']])?'active':'none' }}"
                         onclick="add_product_varition_default(this)" data-select="{{ $attr['value'] }}"
                         data-title="{{ $detail_attribute->name }}" data-value="{{ $attr['title'] }}">
                        <i class="fas fa-star"></i>
                        <i class="far fa-star"></i>
                    </div>
                    <div class="title-attribute d-inline-block">
                        <span class="badge bg-secondary">{{ isset($attr['title'])?$attr['title']:'' }}</span>
                    </div>
                    @if(!$detail_attribute->type)
                        <div class="display-media-attribute d-inline-block ml-2"
                             data-attribute-id="{{ $attr['value'] }}" Data-Thumbnail-Attribute>
                            @if( isset($thumbnail_color[$attr['value']]) )
                                <div class="btn button_upload_media_i ml-2" data-attribute-id="{{ $attr['value'] }}"
                                     data-html-button='<i class="fas fa-image" style="font-size: 20px;">'
                                     data-media="varition_featured_image_thumbnail_{{ $attr['value'] }}"
                                     data-ftype="image"
                                     data-type="image/*" data-toggle="modals" data-target="#MediaModal"
                                     data-required="false" onclick="loading_medias(this)" Data_Thumbnail_Color_Min>
                                    <div>
                                        <img src="{{ \App\Media::get_url_media($thumbnail_color[$attr['value']]) }}"
                                             data-id="{{ $thumbnail_color[$attr['value']] }}">
                                    </div>
                                    <button class="btn btn-app mt-3"
                                            onclick='remove_media_attribute(`[data-media="varition_featured_image_thumbnail_{{ $attr['value'] }}"]`)'>
                                        <i class="far fa-trash-alt" style="font-size: 20px;"></i>
                                    </button>
                                </div>
                            @else
                                <div class="btn btn-primary btn-sm button_upload_media"
                                     data-attribute-id="{{ $attr['value'] }}"
                                     data-html-button='<i class="fas fa-image" style="font-size: 20px;">'
                                     data-media="varition_featured_image_thumbnail_{{ $attr['value'] }}"
                                     data-ftype="image"
                                     data-type="image/*" data-toggle="modal" data-target="#MediaModal"
                                     data-required="false" onclick="loading_medias(this)" Data_Thumbnail_Color_Min><i
                                            class="fas fa-image" style="font-size: 20px;"></i>
                                </div>
                            @endif
                        </div>
                    @endif

                </div>
                <div class="col-md-6 price text-left">
                    <div class="form-group">
                        <div class="form-control"
                             DataCurrency>{{ isset( $price_attribute[$attr['value']] )?format_currency($price_attribute[$attr['value']],2,'$'):'$0.00' }}</div>
                        <input type="number" class="DataCurrencyGet form-control"
                               data-attribute-id="{{ $attr['value'] }}"
                               value="{{ isset($price_attribute[$attr['value']])?$price_attribute[$attr['value']]:0 }}"
                               placeholder="Enter price" data-title="Price" data-required="false"
                               onkeyup="load_data_money(this)" onchange="load_data_money(this)" autocomplete="off"
                               data-price-attribute>
                    </div>
                </div>
            </div>
            @if(!$detail_attribute->type)
                <div class="color-gallery mb-3">
                    <div class="d-inline-block mb-3" data-gallery="button_gallery_{{ $attr['value'] }}"
                         data-attribute-id="{{ $attr['value'] }}">
                        @if( isset($thumbnail_attribute[$attr['value']]))
                            @foreach($thumbnail_attribute[$attr['value']] as $image)
                            <div class="d-inline-block item-gallery item_button_gallery_{{ $attr['value'].$image }}">
                                <img src="{{ \App\Media::get_url_media($image) }}" data-attribute-id="{{ $attr['value'] }}" data-id="{{ $image }}" data_thumbnail_product>
                                <button class="btn-item-gallery"
                                        onclick="remove_media_gallery( '{{ $attr['value'] }}','{{ $image }}','button_gallery_{{ $attr['value'] }}')">
                                    <i class="far fa-trash-alt" style="font-size: 20px;"></i>
                                </button>
                            </div>
                            @endforeach
                        @endif
                    </div>
                    <div type="button" class="d-inline-block add_gallery_media" onclick="add_gallery_media(this)"
                         data-media="button_gallery_{{ $attr['value'] }}" data-ftype="image" data-type="image/*"
                         data-toggle="modal" data-target="#MediaModal" data-required="false" data-insert="gallery">
                        Add image
                    </div>
                </div>
            @endif
        @endforeach
        @endif
    </div>
</div>
