@extends('admin.products.product_layout')
@section('content')
    <?php
    $attribute_detail =  App\Product::get_product_attributes_detail_man($attribute_id);
    $product_attributes =  $attribute_detail->child;
    $attribute_type =  App\Product::attribute_type();
    ?>
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-12">
                    <ol class="breadcrumb float-sm-left">
                        <li class="breadcrumb-item"><a href="{{ route('admin.product_attributes') }}">Attributes</a></li>
                        <li class="breadcrumb-item font-weight-bold active">{{ $attribute_detail->name }} ({{ $product_attributes->total() }})</li>
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
                            <h3 class="card-title">Item attributes</h3>
                        </div>
                        <!-- /.card-header -->
                        <!-- form start -->
                        <form role="form" id="form-create-attribute" action="{{ url('admin/add_product_attribute') }}">
                            <div>
                            <input type="hidden" name="parent_id" value="{{ $attribute_id }}" data-title="Parent attribute" data-required="true">
                            <input type="hidden" name="type" value="{{ $attribute_detail->type }}" data-title="Type attribute" data-required="true">
                            </div>
                            <div class="card-body">
                                <div class="form-group">
                                    <label>Attribute</label>
                                    <input type="text" class="form-control" name="name" placeholder="Enter name" data-title="Name" data-required="true">
                                    <span class="um-field-error d-none"></span>
                                </div>
                                <div class="form-group">
                                    <label>Description</label>
                                    <textarea class="form-control" name="description" placeholder="Enter description" data-title="Description" data-required="false"></textarea>
                                </div>
                                <div class="form-group">
                                    <label>{{ $attribute_type[$attribute_detail->type]['title'] }}</label>
                                    <input type="{{ $attribute_type[$attribute_detail->type]['type'] }}" class="form-control" name="data_type" placeholder="Enter value" data-title="Data" data-required="true">
                                    <span class="um-field-error d-none"></span>
                                    @if($attribute_detail->type == 1)
                                    <div id="show-images" class="form-group">
                                        <div class="display-media">

                                        </div>
                                        <!-- Button trigger modal -->
                                        <div type="button" class="btn btn-primary button_upload_media" data-media="button_featured_image" data-ftype="image" data-type="image/*" data-toggle="modal" data-target="#MediaModal" data-required="false">
                                            Upload image
                                        </div>
                                    </div>
                                        @endif
                                </div>
                            </div>
                            <!-- /.card-body -->
                            <div class="card-footer">
                                <button type="button" onclick="create_attribute(this)" id="button-add-category" class="btn btn-primary">Add new <span class="d-none"><i class="fas fa-spinner fa-spin"></i></span></button>
                                <button type="button" onclick="update_attribute(this)" id="button-update-category" data-action="{{ url('admin/update_product_attribute') }}" class="btn btn-primary d-none">Update <span class="d-none"><i class="fas fa-spinner fa-spin"></i></span></button>
                            </div>
                        </form>
                    </div>

                    <!---Import Attributes-->
                    @if($attribute_detail->type != 1)
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Import/Update</h3>
                            <div class="card-tools">
                                <button class="btn btn-primary" onclick="import_product_attribute(this)" data-input="#importFile" data-type="{{ $attribute_detail->type }}" data-id="{{ $attribute_id }}"><i class="fas fa-file-import" style="font-size: 18px;"></i> Upload file</button>
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
                    @endif
                    <!---End Import Attributes-->

                </div>
                <div class="col-md-8">
                    <div class="card list-attributes">
                        <!-- /.card-header -->
                        <div class="card-header">
                            <div class="card-tools">
                                <form method="get" action="" class="form-inline">
                                    <div class="input-group mt-0" style="width: 150px;">
                                        <input type="text" name="search" value="{{ isset($_GET['search'])?$_GET['search']:'' }}" class="form-control float-right" placeholder="Search">

                                        <div class="input-group-append">
                                            <button type="submit" class="btn btn-default"><i class="fas fa-search"></i></button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div class="card-body p-0">
                            <table class="table table-head-fixed text-nowrap table-hover">
                                <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Data</th>
                                    <th class="text-right">Products</th>
                                </tr>
                                </thead>
                                <tbody id="display_categories">
                                @if (count($product_attributes) > 0)
                                    @foreach ($product_attributes as $attribute)
                                        <tr class="item-attibute" data-category-id="{{ $attribute->id }}">
                                            <td>
                                                <div class="title font-weight-bold">{{ $attribute->name }}</div>
                                                <div class="action">
                                                    <a class="mt-2" href="javascript:edit_attribute({{ json_encode($attribute) }})">Edit</a>
                                                    <a class="mt-2 ml-2" href="javascript:delete_attribute({{ $attribute->id }})">Delete</a>
                                                </div>

                                            </td>
                                            <td><div class="d-inline-block" title="{{ $attribute_type[$attribute->type]['title'] }}" data-toggle="tooltip" data-placement="top">
                                                    {!! DisplayAttributeType($attribute->type,$attribute->data_type) !!}
                                                </div></td>
                                            <td class="text-right">{{  getNumberProductbyTax($attribute->id,'product_attribute') }}</td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="8" class="text-center">No product attribute exist.</td>
                                    </tr>
                                @endif

                                </tbody>
                            </table>
                        </div>
                        <!-- /.card-body -->
                    </div>

                 {{ $product_attributes->appends($_GET)->links() }}
                    <!-- /.card -->
                </div>
            </div>
        </div>
    </section>
@endsection
