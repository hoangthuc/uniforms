@extends('admin.products.product_layout')
@section('content')
    <?php
    $user = Auth::user();
    $medias = App\Media::get_media();
    $product_status = App\Product::product_status();
    $product_categories =  App\Product::get_product_categories();
    $product_type = App\Product::product_type();
    $product_attributes =  App\Product::product_attributes();
    ?>
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Add new product</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin.products') }}">Products</a></li>
                        <li class="breadcrumb-item active">Add new Product</li>
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
                                    <input type="text" class="form-control" name="name" placeholder="Enter title" data-title="Title" data-required="true">
                                    <span class="um-field-error d-none"></span>
                                </div>
                                <!--Content-->
                                <div class="form-group">
                                    <label for="story_content" class="control-label">Description <span class="red">*</span></label>
                                    <textarea name="description" class="form-control summernote editor_summernote" rows="10" placeholder="Description..." data-title="Content" data-required="false"></textarea>
                                    <span class="um-field-error d-none"></span>
                                </div>
                                <!--Price & Shipping-->
                                <div class="row">
                                    <!--Price-->
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Price <span>($)</span></label>
                                            <div class="form-control" DataCurrency></div>
                                            <input type="number" class="DataCurrencyGet form-control" name="price" placeholder="Enter price" data-title="Price" data-required="false" onkeyup="load_data_money(this)" onchange="load_data_money(this)" autocomplete="off">
                                            <span class="um-field-error d-none"></span>
                                        </div>
                                    </div>
                                    <!--sku-->
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>SKU <span class="red">*</span></label>
                                            <input type="text" class="form-control" name="sku" value="" placeholder="Enter SKU" data-title="SKU" data-required="true">
                                            <span class="um-field-error d-none"></span>
                                        </div>
                                    </div>



                                </div>



                                <div class="form-group">
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}" data-title="Token" data-required="false">
                                    <input type="hidden" id="action" name="action" value="{{ url('admin/add_product') }}" data-title="action" data-required="false" />
                                    <input type="hidden" id="user_id" name="author" value="<?php echo $user->id?>" data-title="User" data-required="false" />
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
                                            <option value="{{ $attribute_k }}" >{{ $attribute['title'] }}</option>
                                        @endforeach
                                    </select>
                                    <span class="um-field-error d-none"></span>
                                </div>
                                @foreach($product_attributes as $attribute_k => $attribute)
                                    <div class="form-group item-attribute item-attibute-{{ $attribute_k }} d-none" data-attribute="{{ $attribute_k }}" >
                                        <label>{{$attribute['title']}}</label>
                                        <select class="select2bs4 form-control" onchange="change_attribute(this)" multiple="multiple"  name="{{ $attribute_k }}" data-placeholder="Select a {{$attribute['title']}}" data-title="{{$attribute['title']}}" data-required="false">
                                            @foreach($attribute['value'] as $key => $value)
                                                <option value="{{ $key }}">{{ $value }}</option>
                                            @endforeach
                                        </select>
                                        <span class="um-field-error d-none"></span>
                                        <div class="form-check mt-2">
                                            <input class="form-check-input" type="checkbox" value="{{ $attribute_k }}" onclick="change_attribute(this)" id="display_varition_{{ $attribute_k }}" name="display_varition">
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
                                            <option value="{{ $key }}">{{ $value }}</option>
                                        @endforeach
                                    @endif
                                </select>
                                <div class="alert alert-warning mt-2 d-none" role="alert">Attributes is invalid.</div>
                            </div>

                            <!--Show variations product-->
                            <div class="form-group form-inline">
                                <div class="mr-3 d-none" data-Attribute-Variations>
                                    <button type="button" data-json="" onclick="add_variation(this)" data-Add-Variation class="btn btn-primary">Add Variations</button>
                                </div>
                            </div>



                            <!--Show variations product from attribute-->
                            <div class="form-group border-top" data-Product-Variations>
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
                </div>
                {{--colume right--}}
                <div class="col-md-3">
                    <!-- general form controls -->
                    <div class="card card-info create_new_product">
                        <div class="card-header">
                            <h3 class="card-title">Controls</h3>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <!--Status-->
                            <div class="form-group">
                                <label for="status" class="control-label">Status</label>
                                <select class="form-control" id="status" name="status" data-title="Status" data-required="true">

                                    @foreach ($product_status as $key => $val)
                                        <option value="{{ $key }}">{{ $val }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <!--Category-->
                            <div class="form-group">
                                <label>Category</label>
                                <select class="form-control select2bs4" name="product_category" multiple="multiple" data-title="Category" data-required="false">
                                    @if($product_categories)
                                        @foreach(App\Product::get_sort_categories($product_categories) as $category)
                                            <option value="{{ $category['id'] }}" >{{ $category['parent_id']?'--'   :'' }} {{ $category['name'] }}</option>
                                            @if( isset($category['child']) )
                                                @foreach($category['child'] as $category)
                                                    <option value="{{ $category['id'] }}" >{{ $category['parent_id']?'--'   :'' }} {{ $category['name'] }}</option>
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
                    {{--product Image--}}
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
                                <div type="button" class="btn btn-primary button_upload_media" data-media="button_featured_image" data-ftype="image" data-type="image/*" data-toggle="modal" data-target="#MediaModal" data-required="false">
                                    Upload image
                                </div>
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
@section('footer')
    <script>
        var $Attribute = {
          data:{},
          setup: function(dom){
              for(item in this.data){
                  var data = {
                      'action': 'my_action',
                      'data': this.data
                  };
                  $.post(setting.ajax_url, data, function(response) {
                      console.log(response);
                  });
              }

            },
          render: function(){

          }
        };
    </script>
@endsection
