@extends('admin.products.product_layout')
@section('content')
    <?php
    $search = isset($_GET['search']) ? $_GET['search'] : '';
    $limit = isset($_GET['limit']) && !empty($_GET['limit']) ? $_GET['limit']:20;
    $cat_id = isset($_GET['category']) ? $_GET['category'] : '';
    $status = isset($_GET['status']) ? $_GET['status'] : '';
    $query = ['search' => $search,'limit'=>$limit,'category'=>$cat_id,'status'=>$status];
    $products = App\Product::get_products($query);
    $product_status = App\Product::product_status();
    $product_categories =  App\Product::get_product_categories_all();
    $product_status = App\Product::product_status();
    ?>
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1><a href="{{ route('admin.add-product') }}" class="btn btn-outline-info">Add new</a></h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active"><a href="{{ route('admin.products') }}">Manage Products</a>
                            ({{ $products->total() }})
                        </li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <form id="form-filter"></form>
                            <div class="card-header">
                                <div class="d-inline-block">
                                    <select class="form-control" id="apply_product">
                                        <option value="edit">Edit</option>
                                        <option value="trash">Trash</option>
                                        <option value="publish">Change to Publish</option>
                                        <option value="draft">Change to Draft</option>
                                    </select>
                                </div>
                                <div class="d-inline-block">
                                    <button type="button" class="btn btn-primary mb-1"
                                            onclick="open_edit_action('#apply_product','#form_products_all','apply_product_action')">
                                        Apply
                                    </button>
                                </div>
                                <div class="d-inline-block">
                                    <button type="button" class="btn btn-primary mb-1" onclick="apply_clear_trash()">
                                        Empty trash <i class="fas fa-trash-alt" style="font-size: 18px;"></i></button>
                                </div>
                                <div class="card-tools">
                                    <form method="get" action="" class="form-inline">
                                        <div class="input-group mt-0 mr-2" >
                                            <select class="form-control" name="category">
                                                @if($product_categories)
                                                    <option value="" >All Categories</option>
                                                    @foreach(App\Product::get_sort_categories($product_categories) as $category)
                                                        <option value="{{ $category['id'] }}" {{ $cat_id == $category['id'] ? 'selected':'' }}>{{ $category['parent_id']?'--'   :'' }} {{ $category['name'] }}</option>
                                                        @if( isset($category['child']) )
                                                            @foreach($category['child'] as $category)
                                                                <option value="{{ $category['id'] }}" {{ $cat_id == $category['id'] ? 'selected':'' }}>{{ $category['parent_id']?'--'   :'' }} {{ $category['name'] }}</option>
                                                            @endforeach
                                                        @endif
                                                    @endforeach
                                                @endif
                                            </select>
                                        </div>
                                        <div class="input-group mt-0 mr-2" >
                                            <select class="form-control" name="status">
                                                @if($product_status)
                                                    <option value="" >All status</option>
                                                    @foreach ($product_status as $key => $val)
                                                        <option value="{{ $key }}" {{ ($key == $status )?'selected':'' }}>{{ $val }}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                        </div>
                                        <div class="input-group mt-0 mr-2">
                                            <input type="number" name="limit"
                                                   value="{{ isset($_GET['limit'])?$_GET['limit']:'' }}" min="20"
                                                   max="60" class="form-control float-right" placeholder="Limit">
                                        </div>
                                        <div class="input-group mt-0" style="width: 150px;">
                                            <input type="text" name="search" class="form-control float-right"
                                                   value="{{ $search }}" placeholder="Search">

                                            <div class="input-group-append">
                                                <button type="submit" class="btn btn-default"><i
                                                            class="fas fa-search"></i></button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        <!-- /.card-header -->
                        <div id="form_products_all" class="card-body p-0">
                            <div class="apply-edit-product d-none">
                                <div class="form-group-edit">
                                    <label>Category</label>
                                    <select class="form-control select2bs4" name="product_category" multiple="multiple" placeholder="No Change" data-title="Category" data-required="false">
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

                                <div class="form-group-edit">
                                    <label for="status" class="control-label">Status</label>
                                    <select class="form-control" id="status" name="status" data-title="Status" data-required="true">
                                        <option value="">No Change</option>
                                        @foreach ($product_status as $key => $val)
                                            <option value="{{ $key }}">{{ $val }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group-edit group-button-apply">
                                    <a href="javascript:save_list_product('#form_products_all .apply-edit-product select','#form_products_all','apply_save_list_product');"  class="btn btn-primary mt-0 d-block">Save <span class="d-none">
                                <i class="fas fa-spinner fa-spin"></i>
                            </span></a>
                                    <a href="javascript:close_object('#form_products_all .apply-edit-product');" class="btn btn-primary mt-1 d-block">Close</a>
                                </div>
                            </div>
                            <table class="table table-head-fixed text-nowrap table-hover">
                                <thead>
                                <tr>
                                    <th><input type="checkbox" value="off"
                                               onclick="checkbox_all(this,'#form_products_all')"></th>
                                    <th class="text-center"><i class="fas fa-image" style="font-size: 20px;"></i></th>
                                    <th>Title</th>
                                    <th>SKU</th>
                                    <th>Category</th>
                                    <th>Author</th>
                                    <th>Status</th>
                                    <th>Last Modified</th>
                                </tr>
                                </thead>
                                <tbody>
                                @if (count($products) > 0)
                                    @foreach ($products as $product)
                                        <tr>
                                            <td><input type="checkbox" name="product_id" value="{{ $product->id }}">
                                            </td>
                                            <td class="product_image column-thumb"><img
                                                        src="{{ isset($product->featured_image)? App\Media::get_url_media($product->featured_image) :url('images/products/prodgr_default_300.png') }}"/>
                                            </td>
                                            <td class="product_name item-attibute">
                                                <div class="title"><a
                                                            href="{{ url('admin/product/'.$product->id.'/edit') }}">{{ $product->name }}</a>
                                                </div>
                                                <div class="action">
                                                    <a class="mt-2" href="{{ url('product/'.$product->slug) }}"
                                                       target="_blank">
                                                        View
                                                    </a>
                                                    <a class="mt-2 ml-2"
                                                       href="{{ url('admin/product/'.$product->id.'/edit') }}">
                                                        Edit
                                                    </a>
                                                    <a class="mt-2 ml-2"
                                                       href="javascript:delete_post('{{$product->id}}','detele_product');">
                                                        Delete
                                                    </a>
                                                </div>
                                            </td>
                                            <td>{{ App\Product::get_meta_product($product->id,'sku') }}</td>
                                            <td class="category_product">{!! display_list_category_product($product->id) !!}</td>
                                            <td>{{ App\User::getUserByID($product->author)->name }}</td>
                                            <td>{{ ($product->status)?$product_status[$product->status]:'Unknown' }}</td>
                                            <td><span data-toggle="tooltip" data-placement="top"
                                                      data-original-title="{{ display_date($product->updated_at) }}">{{ get_current_datetime($product->updated_at) }}</span>
                                            </td>
                                        </tr>
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
                    {{ $products->appends($_GET)->links() }}
                </div>
            </div>
        </div>
    </section>
@endsection
