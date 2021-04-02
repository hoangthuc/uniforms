@if($name_plates)
    <div class="form-group" data-name-plate>
        <select class="form-control mb-2" data-name="name_plate" onchange="display_name_plate(this)" >
            @foreach($name_plates as $value)
                <?php
                $color = $value->color;
                $detail_attribute = \App\Product::get_product_attributes_detail_single($color);
                $plate = $value->plate;
                $name_plate = \App\Product::product_name_line()[$plate];
                ?>
              <option value="{{ ($plate==1)?'line_1':'line_2' }}">{{ $name_plate.' '.$detail_attribute->name }}</option>
            @endforeach
        </select>
        <input type="text" class="form-control mb-2 d-none" data-line="1" data-name="line_1" placeholder="Engrave Line 1">
        <input type="text" class="form-control mb-2 d-none" data-line="1" data-name="line_2" placeholder="Engrave Line 1">
        <input type="text" class="form-control mb-2 d-none" data-line="2" data-name="line_2" placeholder="Engrave Line 2">
    </div>
@endif

