@if($detail_attribute)
<div class="item_name_plate mb-3 border-top pt-2" data_display_name_plate="{{ $detail_attribute->id.'_'.$plate }}">
    <div class="display-media-attribute title-attribute d-inline-block">
        <span class="badge bg-secondary mr-5"  style="font-size: 18px;">{{ $name_plate.' '.$detail_attribute->name }}</span>

        <div class="btn btn-primary btn-sm"
             data-html-button='<i class="fas fa-image" style="font-size: 20px;">'
             data-media="varition_name_plate_thumbnail_{{ $detail_attribute->id.'_'.$plate }}"
             data-ftype="image"
             data-type="image/*" data-toggle="modal" data-target="#MediaModal"
             data-required="false" onclick="loading_medias(this)" Data_Thumbnail_Color_Name_plate><i
                    class="fas fa-image" style="font-size: 20px;"></i>
        </div>
    </div>
    <div class="d-inline-block ml-5" onclick="$(this).parent().remove()">
        <i class="far fa-times-circle" style="font-size: 28px;"></i>
    </div>
</div>
@endif