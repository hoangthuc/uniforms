@extends('admin.products.product_layout')
@section('content')
    <?php
    $user = Auth::user();
    $medias = App\Media::get_media();
    if(!$product_id)$product_id= '';
    $product = App\Product::get_product($product_id);
    $product_status = App\Product::product_status();
    $featured_image = isset($product->featured_image)? App\Media::get_media_detail($product->featured_image):'';
    $product_categories =  App\Product::get_product_categories_all();
    $category = App\Relationships::get_relationships($product_id,'product_category_');
    $product_type = App\Product::product_type();
    $product_attributes =  App\Product::product_attributes();

    $price = App\Product::get_meta_product($product->id,'price');
    $shipping = App\Product::get_meta_product($product->id,'shipping');
    $sku = App\Product::get_meta_product($product->id,'sku');
    $weight = App\Product::get_meta_product($product->id,'weight');
    $additional_information = App\Product::get_meta_product($product->id,'additional_information');
    if($additional_information)$additional_informations = \GuzzleHttp\json_decode($additional_information);
    $select_variations = (array)display_attribute_product($product->id,'all_attributes');
    $data = \App\Product::insert_best_sell_product($product_id,'top_sell_month');

    $display_data_attribute = \App\Product::get_meta_product($product->id,'all_attributes');
    $name_plates = \App\Product::get_meta_product($product->id,'name_plate');
    $attr_hemming = \App\Product::get_meta_product($product->id,'attr_hemming');

    $galleries = \App\Product::get_meta_product($product->id,'gallery');
    $galleries= ($galleries)?(array)\GuzzleHttp\json_decode($galleries):[];

    ?>
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Edit product <a href="{{ route('admin.add-product') }}" class="btn btn-outline-info">Add new</a></h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin.products') }}">Products</a></li>
                        <li class="breadcrumb-item active"><a href="{{ url('product/'.$product->slug) }}" target="_blank">View Product</a></li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                {{--colume left--}}
                <div class="col-md-9">
                    <!-- general form elements -->
                    {{--update info--}}
                    <div class="card card-info">
                        <div class="card-header">
                            <h3 class="card-title">Product Information</h3>
                        </div>
                        <!-- /.card-header -->
                        <!-- form start -->
                        <form class="form-horizontal create_new_product" name="frmAdd" method="post" enctype="multipart/form-data" role="form">
                            <div class="card-body">
                                <!--Title-->
                                <div class="form-group">
                                    <label>Title <span class="red">*</span></label>
                                    <input type="text" class="form-control" name="name" placeholder="Enter title" data-title="Title" data-required="true" value="{{ $product->name }}">
                                    <span class="um-field-error d-none"></span>
                                </div>
                                <!--Content-->
                                <div class="form-group">
                                    <label for="story_content" class="control-label">Description <span class="red">*</span></label>
                                    <textarea name="description" class="form-control summernote editor_summernote" rows="10" placeholder="Description..." data-title="Content" data-required="false">{{ $product->description }}</textarea>
                                    <span class="um-field-error d-none"></span>
                                </div>

                                <!--Price & Shipping-->
                                <div class="row">
                                    <!--Price-->
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Price <span>($)</span></label>
                                            <div class="form-control" DataCurrency>{{ ($price)?format_currency($price,2,'$'):'' }}</div>
                                            <input type="number" class="DataCurrencyGet form-control" name="price" value="{{ $price }}" placeholder="Enter price" data-title="Price" data-required="false" onkeyup="load_data_money(this)" onchange="load_data_money(this)" autocomplete="off">
                                            <span class="um-field-error d-none"></span>
                                        </div>
                                    </div>
                                    <!--sku-->
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>SKU <span class="red">*</span></label>
                                            <input type="text" class="form-control" name="sku" value="{{ $sku }}" placeholder="Enter SKU" data-title="SKU" data-required="true">
                                            <span class="um-field-error d-none"></span>
                                        </div>
                                    </div>


                                </div>



                                <div class="form-group">
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}" data-title="Token" data-required="false">
                                    <input type="hidden" id="action" name="action" value="{{ url('admin/edit_product') }}" data-title="action" data-required="false" />
                                    <input type="hidden" id="user_id" name="author" value="{{ $user->id }}" data-title="User" data-required="false" />
                                    <input type="hidden"  name="id" value="{{ $product->id }}" data-title="ID" data-required="false" />
                                </div>


                            </div>
                        </form>
                    </div>
                    <!-- /.card -->

                    <!-- Attributes Information -->
                    <div class="card card-info">
                        <div class="card-header">
                            <h3 class="card-title">Attributes</h3>
                        </div>
                        <div class="card-body ">
                            <!--Attributes-->
                            @if($product_attributes)
                                <div class="form-group">
                                    <label>Attribute</label>
                                    <select class="select2bs4 form-control" onchange="change_attribute(this,'attribute')" multiple="multiple"  name="attributes" data-placeholder="Select a Attribute" data-json="{{ json_encode($product_attributes)  }}" data-title="Attribute" data-required="false">
                                        @foreach($product_attributes as $attribute_k => $attribute)
                                            <option value="{{ $attribute_k }}" {{ check_search_array($attribute_k,display_attribute_product($product->id,'attributes'))?'selected':'' }}>{{ $attribute['title'] }}</option>
                                        @endforeach
                                    </select>
                                    <span class="um-field-error d-none"></span>
                                </div>
                                @foreach($product_attributes as $attribute_k => $attribute)
                                    <div class="form-group item-attribute item-attibute-{{ $attribute_k }} {{ $attribute_k == check_search_array($attribute_k,display_attribute_product($product->id,'attributes'))?'selected':'d-none' }}" data-attribute="{{ $attribute_k }}" >
                                        <label>{{$attribute['title']}}  <span class="red">*</span></label>
                                        <?php display_item_attribute_product($product->id,$attribute_k) ?>
                                        <select class="select2bs4 form-control" onchange="change_attribute(this)" multiple="multiple"  name="{{ $attribute_k }}" data-placeholder="Select a {{$attribute['title']}}" data-title="{{$attribute['title']}}" data-required="false">
                                            @foreach($attribute['value'] as $key => $value)
                                                <option value="{{ $key }}" {{ check_search_array($key,display_item_attribute_product($product->id,$attribute_k))?'selected':'' }}>{{ $value }}</option>
                                            @endforeach
                                        </select>
                                        <span class="um-field-error d-none"></span>
                                    </div>
                            @endforeach
                        @endif
                            <div class="form-group item-attributes item-attibute-99 row">
                                <label class="col-md-2" style="min-width: 110px;">Name plate</label>
                                <div class="col-md-3">
                                    <select class="form-control" name="color_name_plate" style="min-width: 110px;" data_color_name_plate>

                                    </select>
                                </div>
                                <div class="col-md-3">
                                <select class="form-control" name="number_line" style="min-width: 110px;">
                                    @foreach($plate = \App\Product::product_name_line() as $key_n => $value_n)
                                        <option value="{{ $key_n }}">{{ $value_n }}</option>
                                    @endforeach
                                </select>
                                </div>
                                <div class="col-md-2">
                                    <button class="btn btn-primary" type="button" onclick="$Attribute.setup_name_plate(this)">Add name plate</button>
                                </div>
                            </div>
                            <!---Name plate-->
                            <div class="form-group pt-3" display_name_plate_render_control>
                                @if($name_plates)
                                    @foreach($plate_item = \GuzzleHttp\json_decode($name_plates) as $key_n => $value_n)
                                     <?php
                                        $detail_attribute = \App\Product::get_product_attributes_detail_single($value_n->color);
                                        $plate = $value_n->plate;
                                        $name_plate = \App\Product::product_name_line()[$plate];
                                        $img= $value_n->img;
                                     ?>
                                        @include('admin.products.template_name_plate_view')
                                    @endforeach
                                @endif
                            </div>

                            <!---Hemming-->
                            <div class="form-group item-attributes item-attibute-99 row border-top pt-3">
                                <label class="col-md-2" style="min-width: 110px;">Hemming</label>
                                <div class="col-md-3">
                                    <input type="text" class="form-control" name="hemming" placeholder="Enter Hemming">
                                </div>
                                <div class="col-md-3">
                                    <div class="form-control" DataCurrency>$0</div>
                                    <input type="number" class="DataCurrencyGet form-control" name="hemming_price" value="0" placeholder="Enter price" data-title="Price" data-required="false" onkeyup="load_data_money(this)" onchange="load_data_money(this)" autocomplete="off">
                                    <span class="um-field-error d-none"></span>
                                </div>
                                <div class="col-md-2">
                                    <button class="btn btn-primary ml-1" type="button" onclick="$Attribute.setup_hemming(this)">Add Hemming</button>
                                </div>
                            </div>
                            <div class="form-group pt-3" display_hemming_render_control>
                                @if($attr_hemming)
                                    @foreach($data_hemming = \GuzzleHttp\json_decode($attr_hemming) as $key_n => $value_n)
                                        <?php
                                        $hemming = $value_n->hemming;
                                        $hemming_price = $value_n->hemming_price;
                                        ?>
                                        @include('admin.products.template_hemming_view')
                                    @endforeach
                                @endif
                            </div>

                            <!--Show variations product render control-->
                            <div class="form-group pt-3 border-top" display_attribute_render_control>
                            </div>








                        </div>

                    </div>
                    <!-- /.card -->

                    <!-- Additional Information -->
                    <div class="card card-info create_new_product item-faq-additional_information">
                        <div class="card-header">
                            <h3 class="card-title">Additional Information</h3>
                        </div>
                        <div class="card-body ">
                        @if(isset($additional_informations))
                            @foreach($additional_informations as $item)
                                <!---show title-->
                                    @if( $item->name == 'title')
                            <div class="form-group mb-4">
                                <button type="button" class="btn btn-tool" onclick="remove_item(this)">
                                    <i class="far fa-times-circle"></i> Delete
                                </button>
                                <input type="text" class="form-control mt-1 mb-1" value="{{ $item->value }}" name="title" data-faq="title_additional" data-title="Title" data-required="true" placeholder="Title">
                                <span class="um-field-error d-none"></span>
                            @endif
                            <!--show content-->
                            @if($item->name == 'content')
                            <!--show content-->
                                    <textarea class="form-control mt-3 mb-1" name="content" data-faq="content_additional" data-title="Content" data-required="true" placeholder="Content">{{ $item->value }}</textarea>
                                <span class="um-field-error d-none"></span>
                            </div>
                                    @endif
                                @endforeach
                            @endif
                        </div>
                        <div class="card-footer">
                            <div class="form-group text-right">
                                <button type="button" class="btn btn-primary btn-sm" onclick="add_item_fqa('additional_information')">
                                    <i class="fas fa-plus-circle"></i> Add new
                                </button>
                            </div>
                        </div>

                    </div>
                    <!-- /.card -->


                </div>
                {{--colume right--}}
                <div class="col-md-3">
                    <!-- general form controls -->
                    <div class="card card-info create_new_product">
                        <div class="card-header">
                            <h3 class="card-title">Controls</h3>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body ">
                            <!--Status-->
                            <div class="form-group">
                                <label for="status" class="control-label">Status</label>
                                <select class="form-control" id="status" name="status" data-title="Status" data-required="true">

                                    @foreach ($product_status as $key => $val)
                                        <option value="{{ $key }}" {{ ($key == $product->status)?'selected':'' }}>{{ $val }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <!--Category-->
                            <div class="form-group">
                                <label>Category</label>
                                <select class="form-control select2bs4" name="product_category" multiple="multiple" data-title="Category" data-required="false">
                                    @if($product_categories)
                                        @foreach(App\Product::get_sort_categories($product_categories) as $category)
                                            <option value="{{ $category['id'] }}" {{ get_relationships($product_id,'product_category_'.$category['id']) ? 'selected':'' }}>{{ $category['parent_id']?'--'   :'' }} {{ $category['name'] }}</option>
                                            @if( isset($category['child']) )
                                                @foreach($category['child'] as $category)
                                                <option value="{{ $category['id'] }}" {{ get_relationships($product_id,'product_category_'.$category['id']) ? 'selected':'' }}>{{ $category['parent_id']?'--'   :'' }} {{ $category['name'] }}</option>
                                                @endforeach
                                            @endif
                                        @endforeach
                                    @endif
                                </select>
                            </div>

                            <a href="{{ route('admin.products') }}" class="btn btn-outline-info btn-flat"><i class="fas fa-chevron-circle-left"></i> Cancel</a>
                            <a href="javascript:save_product()" id="button-save-product"  class="btn btn-outline-info btn-flat float-right"><i class="fa fa-save fa-right-5"></i> Save <span class="d-none">
                                <i class="fas fa-spinner fa-spin"></i>
                            </span></a>
                        </div>
                        <!-- /.card-body -->
                    </div>
                    <!-- /.card -->
                    {{--Story Image--}}
                    <div class="card card-primary">
                        <!-- /.card-header -->
                        <div class="card-header">
                            <h3 class="card-title">Featured Image</h3>
                        </div>
                        <!-- /.card-body -->
                        <div class="card-body">
                            <div id="show-images" class="form-group">
                                <div class="display-media">

                                </div>
                                <!-- Button trigger modal -->
                                <div type="button" onclick="single_upload_media(this)" class="btn {{ (isset($featured_image))?'':'btn-primary' }} button_upload_media" data-media="button_featured_image" data-ftype="image" data-type="image/*" data-toggle="{{ (isset($featured_image))?'modal2':'modal' }}" data-target="#MediaModal" data-insert="single_image" data-required=false>
                                    @if( $featured_image)
                                        <div><img src="{{ url( $featured_image->path ) }}" data-id="{{$product->featured_image}}"></div>
                                        <button class="btn btn-app mt-3" onclick='remove_media(`[data-media="button_featured_image"]`)'><i class="far fa-trash-alt" style="font-size: 20px;"></i> Remove</button>
                                    @else
                                        Upload Image
                                    @endif
                                </div>
                                <!--end button-->
                            </div>
                        </div>
                        <!-- form start -->

                    </div>

                    <!-- Gallery -->
                    <div class="card card-primary">
                        <!-- /.card-header -->
                        <div class="card-header">
                            <h3 class="card-title">Gallery</h3>
                        </div>
                        <!-- /.card-body -->
                        <div class="card-body">
                            <div id="show-images-gallery" class="form-group">
                                <div class="d-inline-block mb-3" data-gallery="button_gallery_product_main">
                                    @if( isset($galleries) )
                                        @foreach($galleries as $image)
                                            <div class="d-inline-block item-gallery item_button_gallery_product_main{{ $image }}">
                                                <img src="{{ \App\Media::get_url_media($image) }}" data-attribute-id="{{ $image }}" data-id="{{ $image }}" data_thumbnail_product>
                                                <button class="btn-item-gallery"
                                                        onclick="remove_media_gallery( '','{{ $image }}','button_gallery_product_main')">
                                                    <i class="far fa-trash-alt" style="font-size: 20px;"></i>
                                                </button>
                                            </div>
                                        @endforeach
                                    @endif

                                </div>
                                <div type="button" class="d-inline-block add_gallery_media" onclick="add_gallery_media(this)"
                                     data-media="button_gallery_product_main" data-ftype="image" data-type="image/*"
                                     data-toggle="modal" data-target="#MediaModal" data-required="false" data-insert="gallery">
                                    Add image
                                </div>
                        </div>
                        <!-- form start -->

                        </div>
                    </div>

                    <!-- End Gallery -->

                </div>
            </div>
            <!-- /.row -->
        </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
@endsection
@section('footer')
    <script>
        var product_id = {{ $product_id }};
        var $Attribute = {
            data:{!! ($display_data_attribute)?$display_data_attribute:'{}' !!},
            price_attr:{},
            default_attr:{},
            thumbnail_attr:{},
            thumbnail_color:{},
            gallery:{},
            name_plate:{!! ($name_plates)?$name_plates:'{}' !!},
            attr_hemming:{!! ($attr_hemming)?$attr_hemming:'{}' !!},
            setup: function(dom){
                dom.innerHTML = '';
                for(item in this.data){
                    var data = {
                        'action': 'get_attribute_ajax_view',
                        '_token': setting.token,
                        'item_data': this.data[item],
                        'id_attr': item,
                        'product_id': product_id,remove_media_attribute
                    };
                    $.post(setting.ajax_url, data, function(response) {
                        let data = document.createElement('div');
                        data.innerHTML = response;
                        dom.appendChild(data);
                        $Attribute.update();
                        $('[data-toggle-show="tooltip"]').tooltip();
                    });
                }
            },
            render: function(){
                if(this.data){
                    var html = document.querySelector('[display_attribute_render_control]');
                    this.setup(html);
                }
            },
            update: function (){
                this.price_attr = {};
                this.default_attr = {};
                this.thumbnail_attr = {};
                this.thumbnail_color = {};
                this.name_plate = {};
                this.attr_hemming = {};
                this.gallery = {};
                document.querySelectorAll('[data-price-attribute]').forEach(p_attr=>{
                    this.price_attr[ p_attr.getAttribute('data-attribute-id') ] = Number(p_attr.value);
                });
// full image
                document.querySelectorAll('[display-attribute-product] [data_thumbnail_product]').forEach(t_attr=>{
                    if(this.thumbnail_attr[ t_attr.getAttribute('data-attribute-id') ]){
                        this.thumbnail_attr[ t_attr.getAttribute('data-attribute-id') ][t_attr.getAttribute('data-id')] = t_attr.getAttribute('data-id');
                    }else{
                        let img = {};
                        img[t_attr.getAttribute('data-id')] = t_attr.getAttribute('data-id');
                        this.thumbnail_attr[ t_attr.getAttribute('data-attribute-id') ] = img;
                    }

                });
                // thumbnail color
                document.querySelectorAll('[display-attribute-product] [data_thumbnail_color_min]').forEach(t_attr=>{
                    let thumbnail =  t_attr.querySelector('img');
                    if(thumbnail){
                        this.thumbnail_color[ t_attr.getAttribute('data-attribute-id') ] = thumbnail.getAttribute('data-id');
                    }else{
                        this.thumbnail_color[ t_attr.getAttribute('data-attribute-id') ] = '';
                    }
                });
                this.default_attr = {};
                document.querySelectorAll('.active[data-check-default]').forEach(d_attr=>{
                    this.default_attr[d_attr.getAttribute('data-select')] = {id:d_attr.getAttribute('data-select'),value:d_attr.getAttribute('data-value'),title:d_attr.getAttribute('data-title')};
                });

                /// Name plate
                var select_color = document.querySelector('[name="color_name_plate"]');
                select_color.innerHTML = '';
                for(var color in this.thumbnail_color ){
                    var color_detail = document.querySelector('[display-attribute-product] [data-select="'+color+'"]');
                    if(color_detail){
                        var option  = document.createElement('option');
                        option.setAttribute('value',color);
                        option.textContent = color_detail.getAttribute('data-value');
                        select_color.appendChild(option);
                    }
                }
                document.querySelectorAll('[display_name_plate_render_control] [data_display_name_plate]').forEach(nl=>{
                    var key = nl.getAttribute('data_display_name_plate');
                    var color = nl.getAttribute('data-color');
                    var line = nl.getAttribute('data-line');
                    var img = nl.querySelector('img');
                    var img_id = '';
                    if(img)img_id = img.getAttribute('data-id');
                    this.name_plate[key] = {key:key,color:color,plate:line,img: img_id};
                });

                // update feature image
                var button_featured_image = document.querySelector('[data-media="button_featured_image"] img');
                if(button_featured_image)products['button_featured_image'] = {name:'button_featured_image',label:'Image',required:false, value:button_featured_image.getAttribute('data-id') };

                // Gallery
                document.querySelectorAll('#show-images-gallery [data_thumbnail_product]').forEach(t_attr=>{
                    this.gallery[ t_attr.getAttribute('data-id') ] = t_attr.getAttribute('data-id');
                });

                // Hemming
                document.querySelectorAll('[display_hemming_render_control] [data_display_hemming]').forEach(nl=>{
                    var hemming = nl.getAttribute('data_display_hemming');
                    var hemming_price = nl.getAttribute('data-price');
                    hemming_price = Number(hemming_price);
                    this.attr_hemming[hemming] = {hemming:hemming,hemming_price:hemming_price};
                });


            },
            send:function(data,name){
                var data = {
                    'action': 'update_field_atrribute_product',
                    '_token': setting.token,
                    'data': data,
                    'name': name,
                    'product_id': product_id,
                };
                $.post(setting.ajax_url, data, function(response) {
                    console.log(response);
                });
            },
            setup_name_plate: function(event){
                var select_color = document.querySelector('[name="color_name_plate"]');
                var plate = document.querySelector('[name="number_line"]');
                var check = document.querySelector('[data_display_name_plate="'+select_color.value+'_'+plate.value+'"]');
                if(!select_color.value)Swal.fire('Color is required.');
                if(!check && select_color){
                    var data = {
                        'action': 'get_name_plate_ajax_view',
                        '_token': setting.token,
                        'color': select_color.value,
                        'plate': plate.value,
                        'product_id': product_id,
                    };
                    $.post(setting.ajax_url, data, function(response) {
                        var display = document.querySelector('[display_name_plate_render_control]');
                        display.insertAdjacentHTML('afterbegin',response);

                    });
                }else{
                    Swal.fire('Name plate is exist.');
                }

            },
            setup_hemming: function(event){
                var hemming = document.querySelector('[name="hemming"]');
                var hemming_price = document.querySelector('[name="hemming_price"]');
                var check = document.querySelector('[data_display_hemming="'+hemming.value+'"]');
                if(!hemming.value)Swal.fire('Hemming is required.');
                if(!check && hemming){
                    var data = {
                        'action': 'get_hemming_ajax_view',
                        '_token': setting.token,
                        'hemming': hemming.value,
                        'hemming_price': hemming_price.value,
                    };
                    $.post(setting.ajax_url, data, function(response) {
                        var display = document.querySelector('[display_hemming_render_control]');
                        display.insertAdjacentHTML('afterbegin',response);

                    });
                }else{
                    Swal.fire('Hemming is exist.');
                }

            }
        };
        $Attribute.render();

    </script>
@endsection