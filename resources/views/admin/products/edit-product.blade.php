@extends('admin.products.product_layout')
@section('content')
    <?php
    $user = Auth::user();
    $medias = App\Media::get_media();
    $product = App\Product::get_product($product_id??'');
    $product_status = App\Product::product_status();
    if($product->featured_image){
        $featured_image = App\Media::get_media_detail($product->featured_image);
    }
    $product_categories =  App\Product::get_product_categories_all();
    $category = App\Relationships::get_relationships($product_id,'product_category_');
    $product_type = App\Product::product_type();
    $product_attributes =  App\Product::product_attributes();

    $price = App\Product::get_meta_product($product->id,'price');
    $shipping = App\Product::get_meta_product($product->id,'shipping');
    $sku = App\Product::get_meta_product($product->id,'sku');
    $weight = App\Product::get_meta_product($product->id,'weight');
    $gallery = App\Product::get_meta_product($product->id,'gallery');
    if($gallery)$galleries = \GuzzleHttp\json_decode($gallery);
    $additional_information = App\Product::get_meta_product($product->id,'additional_information');
    if($additional_information)$additional_informations = \GuzzleHttp\json_decode($additional_information);
    $product_variations = App\Product::get_meta_product($product->id,'product_variations');
    if($product_variations)$product_variations= json_decode($product_variations);
    $select_variations = (array)display_attribute_product($product->id,'all_attributes');

    $data = \App\Product::insert_best_sell_product($product_id,'top_sell_month');

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
                                        <div class="form-check mt-2">
                                            <input class="form-check-input" type="checkbox" value="{{ $attribute_k }}" onclick="change_attribute(this)" id="display_varition_{{ $attribute_k }}" name="display_varition" {{ check_item_variation_product($product->id,$attribute_k) }}>
                                            <label class="form-check-label" for="display_varition_{{ $attribute_k }}">Display Product variations</label>
                                        </div>
                                    </div>
                            @endforeach
                        @endif

                        <!--Type product-->
                            <div class="form-group">
                                <label>Product type</label>
                                <select class="form-control" name="product_type" onchange="change_product_type(this)">
                                    @if($product_type)
                                        @foreach($product_type as $key=> $value)
                                            <option value="{{ $key }}" {{ $key == \App\Product::get_meta_product( $product->id,'product_type' )?'selected':'' }}>{{ $value }}</option>
                                        @endforeach
                                    @endif
                                </select>
                                <div class="alert alert-warning mt-2 d-none" role="alert">Attributes is invalid.</div>
                            </div>

                            <!--Show variations product-->
                            <div class="form-group form-inline">
                                <div class="mr-3 {{ \App\Product::get_meta_product( $product->id,'product_type' ) ==0?'d-none':''  }}" data-Attribute-Variations>
                                    <button type="button" data-json="{{ \App\Product::get_meta_product($product->id,'all_attributes') }}" onclick="add_variation(this)" data-Add-Variation class="btn btn-primary">Add Variations</button>
                                </div>
                                <button type="button" data-id="{{ $product->id }}" onclick="save_variation()" data-Save-Variation class="btn btn-primary">Save Variations</button>
                            </div>



                            <!--Show variations product from attribute-->
                            <div class="form-group pt-3 border-top" data-Product-Variations>
                                @if($product_variations && \App\Product::get_meta_product( $product->id,'product_type' ) ==1)
                                    @foreach($product_variations as $key => $item)
                                <div class="item-product-varition mb-3 pb-3 border-bottom">
                                    <div class="form-inline mb-3">
                                        @if($select_variations)
                                            <?php $sort = 0; $select = $item->select; ?>
                                            @foreach( $select_variations as $name => $option )
                                        <select class="form-control mr-2" name="{{ $name }}" >
                                            @foreach($option->value as $value_option )
                                            <option {{ $sort }} value="{{ $value_option->value }}"
                                                    {{ $value_option->value == $select[$sort]?'selected':'' }} >
                                                {{ $value_option->title }}
                                            </option>
                                            @endforeach
                                        </select>
                                                <?php $sort++; ?>
                                            @endforeach
                                        @endif
                                        <span class="delete_attribute fas fa-trash-alt" onclick="delete_variation(this,{{ $key+1 }})"></span>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="display-media"></div>
                                            <div class="btn button_upload_media {{ isset($item->img)?'':'btn-primary' }}" data-media="varition_featured_image{{ $key+1 }}" data-ftype="image" data-type="image/*" data-toggle="modal" data-target="#MediaModal" data-required="false" onclick="loading_medias(this)">
                                                @if( isset($item->img) )
                                                <div>
                                                    <img src="{{ App\Media::get_media_detail($item->img)->link }}" data-id="{{ $item->img }}">
                                                </div>
                                                <button class="btn btn-app mt-3" onclick='remove_media_attribute(`[data-media="varition_featured_image{{ $key+1 }}"]`)'>
                                                    <i class="far fa-trash-alt" style="font-size: 20px;"></i> Remove</button>
                                                @else
                                                    Upload Image
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-md-9">
                                            <input class="form-control" type="number" name="price" placeholder="Price" value="{{ $item->price }}">
                                            <textarea class="form-control mt-3" name="description" placeholder="Description">{!!  $item->description  !!}</textarea>
                                        </div>
                                    </div>
                                </div>
                                        @endforeach
                                    @endif
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
                                <div type="button" class="btn {{ (isset($featured_image))?'':'btn-primary' }} button_upload_media" data-media="button_featured_image" data-ftype="image" data-type="image/*" data-toggle="{{ (isset($featured_image))?'modal2':'modal' }}" data-target="#MediaModal" data-required=false>
                                    @if( isset($featured_image))
                                        <div><img src="{{ url( $featured_image->path ) }}"></div>
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

                    {{--gallery Image--}}
                    <div class="card card-primary">
                        <!-- /.card-header -->
                        <div class="card-header">
                            <h3 class="card-title">Gallery</h3>
                        </div>
                        <!-- /.card-body -->
                        <div class="card-body">
                            <div  class="form-group">
                                <div class="display-gallery mb-3" data-gallery="button_gallery">
                                    @if(isset($galleries))
                                        @foreach($galleries as $value)
                                            @if( isset($value->id) )
                                            <div class="item-gallery item_button_gallery{{ $value->id }}">
                                                <img src="{{  \App\Media::get_url_media($value->id) }}">
                                                <button class="btn btn-app" onclick="remove_item_gallery('{{ $value->id }}','button_gallery')"><i class="far fa-trash-alt" style="font-size: 20px;"></i></button>
                                            </div>
                                            @endif
                                        @endforeach
                                    @endif
                                </div>
                                <!-- Button trigger modal -->
                                <div type="button" class="btn btn-primary add_gallery_media" data-media="button_gallery" data-ftype="image" data-type="image/*" data-toggle="modal" data-target="#MediaModal" data-required="false">
                                    Upload image
                                </div>
                            </div>
                        </div>
                        <!-- form start -->

                    </div>
                    <!-- /.card -->
                </div>
            </div>
            <!-- /.row -->
        </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
@endsection
