@extends('admin.products.product_layout')
@section('content')
    <?php
    $product_categories_parent =  App\Product::get_product_categories_parent();
    $product_categories =  App\Product::get_product_categories();
    $Categories = App\Product::get_sort_categories($product_categories);
    $product_departments = \App\Product::product_departments();
    $parent = (isset($_GET['parent']))?$_GET['parent']:'';
        ?>
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-12">
                    <ol class="breadcrumb float-sm-left">
                        <li class="breadcrumb-item"><a href="{{ route('admin') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active"><a href="{{ route('admin.product_categories') }}">Products Categories</a> ({{ $product_categories->total() }})</li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-4">
                    <!-- general form elements -->
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">Add new product category</h3>
                        </div>
                        <!-- /.card-header -->
                        <!-- form start -->
                        <form role="form" id="form-create-category">
                            <div class="card-body">
                                <div class="form-group">
                                    <label>Category title</label>
                                    <input type="text" class="form-control" name="name" placeholder="Enter name" data-title="Name" data-required="true">
                                    <span class="um-field-error d-none"></span>
                                </div>
                                <div class="form-group">
                                    <label>Description</label>
                                    <textarea class="form-control" name="description" placeholder="Enter description" data-title="Description" data-required="false"></textarea>
                                </div>
                                <div class="form-group">
                                    <label>Select parent</label>
                                    <select class="form-control" name="parent_id" data-title="Parent" data-required="false">
                                        <option value="">No parent</option>
                                        @if($product_categories_parent)
                                            @foreach($product_categories_parent as $category)
                                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Type</label>
                                    <select class="form-control" name="product_department" data-title="Type" data-required="false">
                                        <option value="">Select</option>
                                        @if($product_departments)
                                            @foreach($product_departments as $value=> $name)
                                        <option value="{{ $value }}">{{ $name }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label>Order by</label>
                                    <input type="number" name="loop" min="0" value="0" class="form-control" data-title="Order by" data-required="false">
                                </div>

                                <div class="form-group">
                                    <label>Banner <em>(size: 1680x230)</em></label>
                                    <input type="hidden" class="form-control" name="media" placeholder="Enter value" data-title="Media" data-required="false">
                                    <span class="um-field-error d-none"></span>

                                    <div id="show-images" class="form-group">
                                        <div class="display-media">

                                        </div>
                                        <!-- Button trigger modal -->
                                        <div type="button" class="btn btn-primary button_upload_media" data-media="button_featured_image" data-ftype="image" data-type="image/*" data-toggle="modal" data-target="#MediaModal" data-required="false">
                                            Upload image
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label>Thumbnail <em>(size: 300x590)</em></label>
                                    <input type="hidden" class="form-control" name="feature_thumbnail" placeholder="Enter value" data-title="Thumbnail" data-required="false">
                                    <span class="um-field-error d-none"></span>

                                    <div id="show-feature-thumbnail" class="form-group">
                                        <!-- Button trigger modal -->
                                        <div type="button" class="btn btn-primary button_upload_media" data-media="button_feature_thumbnail" data-ftype="image" data-type="image/*" data-toggle="modal" data-target="#MediaModal" data-required="false">
                                            Upload image
                                        </div>
                                    </div>
                                </div>

                            </div>
                            <!-- /.card-body -->
                            <div class="card-footer">
                                <button type="button" onclick="create_category(this)" id="button-save-category" class="btn btn-primary">Add new <span class="d-none"><i class="fas fa-spinner fa-spin"></i></span></button>
                                <button type="button" onclick="save_category(this)" id="button-update-category" data-action="{{ url('admin/update_product_attribute') }}" class="btn btn-primary d-none">Update <span class="d-none"><i class="fas fa-spinner fa-spin"></i></span></button>
                            </div>
                        </form>
                    </div>

                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Import/Update</h3>
                            <div class="card-tools">
{{--                                <button class="btn btn-primary"><i class="fas fa-file-export" style="font-size: 18px;"></i> Export</button>--}}
                                <button class="btn btn-primary" onclick="import_product_categories('#importFile')"><i class="fas fa-file-import" style="font-size: 18px;"></i> Upload file</button>
                            </div>
                        </div>
                        <!-- /.card-header -->
                        <!-- form start -->
                        <form role="form" id="form-import-category">
                            <div class="card-body">
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input" name="file_import" id="importFile" accept=".xlsx" />
                                    <label class="custom-file-label" for="importFile">Choose file</label>
                                </div>
                            </div>
                            <!-- /.card-body -->

                        </form>
                    </div>


                </div>
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">
                            <div class="d-inline-block">
                                <select class="form-control" id="apply_category">
                                    @if($product_departments)
                                        @foreach($product_departments as $value=> $name)
                                            <option value="{{ $value }}">Change type to {{ $name }}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                            <div class="d-inline-block">
                                <input type="hidden" id="remove_category" name="remove_category"  value="remove_category">
                                <button type="button" class="btn btn-primary mb-1" onclick="apply_posttype_action('#apply_category','#form_products_all','apply_category_action')">Apply</button>
                                <button type="button" class="btn btn-primary mb-1" onclick="alert_all_check('#remove_category','#form_products_all','apply_delete_category')"><i class="fas fa-trash-alt" style="font-size: 20px;"></i></button>
                            </div>
                            <div class="card-tools">
                                <form method="get" action="" class="form-inline">
                                 <div class="input-group mt-0 mr-2" >
                                     <select class="form-control" name="parent">
                                         @foreach([0=>'All','only_parent'=>'Only categories parent'] as $value=> $show_parent)
                                             <option value="{{ $value }}" {{ ($parent == $value)?'selected':'' }}>{{ $show_parent }}</option>
                                         @endforeach
                                             @if($product_departments)
                                                 @foreach($product_departments as $value=> $name)
                                                     <option value="{{ $value }}" {{ ($parent == $value)?'selected':'' }}>{{ $name }}</option>
                                                 @endforeach
                                             @endif
                                     </select>
                                 </div>
                                 <div class="input-group mt-0 mr-2">
                                        <input type="number" name="limit" value="{{ isset($_GET['limit'])?$_GET['limit']:'' }}" min="20" max="60" class="form-control float-right" placeholder="Limit">
                                  </div>
                                <div class="input-group mt-0" style="width: 150px;">
                                    <input type="text" name="search" value="{{ isset($_GET['search'])?$_GET['search']:'' }}" class="form-control float-right" placeholder="Search">

                                    <div class="input-group-append">
                                        <button type="submit" class="btn btn-default"><i class="fas fa-search"></i></button>
                                    </div>
                                </div>
                                </form>
                            </div>
                        </div>
                        <!-- /.card-header -->
                        <div id="form_products_all" class="card-body p-0">
                            <div class="control-actions">

                            </div>
                            <table class="table table-head-fixed text-nowrap table-hover">
                                <thead>
                                <tr>
                                    <th><input type="checkbox" value="off" onclick="checkbox_all(this,'#form_products_all')"></th>
                                    <th class="text-center"><i class="fas fa-image" style="font-size: 20px;"></i></th>
                                    <th>Category</th>
                                    <th>Type</th>
                                    <th>Slug</th>
                                    <th>Order</th>
                                    <th>Products</th>
                                    <th class="text-right">Banner</th>
                                </tr>
                                </thead>
                                <tbody id="display_categories">
                                @if (count($Categories) > 0)
                                    @foreach ($Categories as $category)
                                        {!! DisplayCategories($category) !!}
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="8" class="text-center">No products exist.</td>
                                    </tr>
                                @endif

                                </tbody>
                            </table>
                        </div>
                        <!-- /.card-body -->
                    </div>
                    <!-- /.card -->
                    {{ $product_categories->appends($_GET)->links() }}
                </div>
            </div>
        </div>
    </section>
@endsection
