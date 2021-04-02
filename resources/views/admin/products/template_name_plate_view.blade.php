@if( isset($detail_attribute) )
<div class="item_name_plate mb-3 border-top pt-2 row" data_display_name_plate="{{ $detail_attribute->id.'_'.$plate }}" data-color="{{ $detail_attribute->id }}" data-line="{{ $plate }}">
    <div class="display-media-attribute title-attribute col-md-5">
        <span class="badge bg-secondary mr-5"  style="font-size: 18px;">{{ $name_plate.' '.$detail_attribute->name }}</span>
    </div>
    <div class="display-media-attribute title-attribute col-md-2">
        @if($img)
            <div class="btn button_upload_media_i ml-2"
                 data-html-button='<i class="fas fa-image" style="font-size: 20px;">'
                 data-media="varition_name_plate_thumbnail_{{ $detail_attribute->id.'_'.$plate }}"
                 data-ftype="image"
                 data-type="image/*" data-toggle="modals" data-target="#MediaModal"
                 data-required="false" onclick="loading_medias(this)" Data_Thumbnail_Color_Name_plate>
                <div>
                    <img src="{{ \App\Media::get_url_media($img) }}"
                         data-id="{{ $img }}">
                </div>
                <button class="btn btn-app mt-3"
                        onclick='remove_media(`[data-media="varition_name_plate_thumbnail_{{ $detail_attribute->id.'_'.$plate }}"]`)'>
                    <i class="far fa-trash-alt" style="font-size: 20px;"></i>
                </button>
            </div>
        @else
            <div class="btn btn-primary btn-sm"
                 data-html-button='<i class="fas fa-image" style="font-size: 20px;">'
                 data-media="varition_name_plate_thumbnail_{{ $detail_attribute->id.'_'.$plate }}"
                 data-ftype="image"
                 data-type="image/*" data-toggle="modal" data-target="#MediaModal"
                 data-required="false" onclick="loading_medias(this)" Data_Thumbnail_Color_Name_plate><i
                        class="fas fa-image" style="font-size: 20px;"></i>
            </div>
        @endif
    </div>
    <div class="col-md-3 ml-5 btn-remove" onclick="$(this).parent().remove()">
        <i class="far fa-times-circle" style="font-size: 28px;"></i>
    </div>
</div>
@endif