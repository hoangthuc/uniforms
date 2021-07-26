@if($name_plates)
    <div class="form-group" data-name-plate>
        <label for="">Nameplate</label>
        <select class="form-control mb-2" data-name="name_plate" onchange="display_name_plate(this)" >
            @foreach($name_plates as $value)

              <option {{ $value['selected']?"selected":"" }}
                      @if(isset($selectedColor) && $selectedColor!=$value['color'])
                      class="d-none"
                      @endif
                      value="{{$value['line'] }}"
                      data-color="{{$value['color']}}"
                      data-key="{{$value['key'] }}">{{$value['name'] }}</option>
            @endforeach
        </select>
        <input type="text" class="form-control mb-2 {{ isset($name_plate_order['line'])&&$name_plate_order['line']=='line_1'?"":"d-none" }}" data-line="1" data-name="line_1"
               value="{{ isset($productOrderAttribute["Engrave_Line_1"])?$productOrderAttribute['Engrave_Line_1']:'' }}"
               placeholder="Engrave Line 1">
        <input type="text" class="form-control mb-2 {{ isset($name_plate_order['line'])&&$name_plate_order['line']=='line_2'?"":"d-none" }}" data-line="1" data-name="line_2"
               value="{{ isset($productOrderAttribute["Engrave_Line_1"])?$productOrderAttribute['Engrave_Line_1']:'' }}"
               placeholder="Engrave Line 1">
        <input type="text" class="form-control mb-2 {{ isset($name_plate_order['line'])&&$name_plate_order['line']=='line_2'?"":"d-none" }}" data-line="2" data-name="line_2"
               value="{{ isset($productOrderAttribute["Engrave_Line_2"])?$productOrderAttribute['Engrave_Line_2']:'' }}"
               placeholder="Engrave Line 2">
        <input type="text" class="form-control mb-2 {{ isset($name_plate_order['line'])&&$name_plate_order['line']=='line_3'?"":"d-none" }}" data-line="1" data-name="line_3"
               value="{{ isset($productOrderAttribute["Engrave_Line_1"])?$productOrderAttribute['Engrave_Line_1']:'' }}"
               placeholder="Engrave Line 1">
        <input type="text" class="form-control mb-2 {{ isset($name_plate_order['line'])&&$name_plate_order['line']=='line_3'?"":"d-none" }}" data-line="2" data-name="line_3"
               value="{{ isset($productOrderAttribute["Engrave_Line_2"])?$productOrderAttribute['Engrave_Line_2']:'' }}"
               placeholder="Engrave Line 2">
        <input type="text" class="form-control mb-2 {{ isset($name_plate_order['line'])&&$name_plate_order['line']=='line_3'?"":"d-none" }}" data-line="2" data-name="line_3"
               value="{{ isset($productOrderAttribute["Engrave_Line_3"])?$productOrderAttribute['Engrave_Line_3']:'' }}"
               placeholder="Engrave Line 3">
    </div>
@endif

