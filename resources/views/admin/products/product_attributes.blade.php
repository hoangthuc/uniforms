@extends('admin.products.product_layout')
@section('content')
    <?php
    $product_attributes =  App\Product::get_product_attributes();
    $attribute_type =  App\Product::attribute_type();
    $attribute_loop = ['Hide','Show'];
    ?>
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-12">
                    <ol class="breadcrumb float-sm-left">
                        <li class="breadcrumb-item"><a href="{{ route('admin') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">All Attribute</li>
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
                            <h3 class="card-title">Add new attributes</h3>
                        </div>
                        <!-- /.card-header -->
                        <!-- form start -->
                        <form role="form" id="form-create-attribute" action="{{ url('admin/add_product_attribute') }}">
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
                                    <label>Select Type</label>
                                    <select class="form-control" name="type" data-title="Type" data-required="false">
                                        @if($attribute_type)
                                            @foreach($attribute_type as $key=> $attribute)
                                                <option value="{{ $key }}">{{ $attribute['title'] }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label>Display in filter</label>
                                    <select class="form-control" name="loop" data-title="Loop" data-required="false">
                                        @if($attribute_loop)
                                            @foreach($attribute_loop as $key => $attribute)
                                                <option value="{{ $key }}">{{ $attribute }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                            <!-- /.card-body -->
                            <div class="card-footer">
                                <button type="button" onclick="create_attribute(this)" id="button-add-category" class="btn btn-primary">Add new <span class="d-none"><i class="fas fa-spinner fa-spin"></i></span></button>
                                <button type="button" onclick="update_attribute(this)" id="button-update-category" data-action="{{ url('admin/update_product_attribute') }}" class="btn btn-primary d-none">Update <span class="d-none"><i class="fas fa-spinner fa-spin"></i></span></button>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="col-md-8">
                    <div class="card list-attributes">
                        <!-- /.card-header -->
                        <div class="card-body table-responsives p-0">
                            <table class="table table-head-fixed text-nowrap table-hover">
                                <thead>
                                <tr>
                                    <th>Attributes ({{ $product_attributes->total() }})</th>
                                    <th>Slug</th>
                                    <th>Type</th>
                                    <th>Description</th>
                                </tr>
                                </thead>
                                <tbody id="display_categories">
                                @if (count($product_attributes) > 0)
                                    @foreach ($product_attributes as $attribute)
                                        <tr class="item-attibute" data-category-id="{{ $attribute->id }}">
                                            <td>
                                               <div class="title font-weight-bold"> <a class="mt-2" href="{{ url('admin/product_attribute/'.$attribute->id ) }}">{{ $attribute->name }}</a></div>
                                                <div class="action">
                                                    <a class="mt-2" href="javascript:edit_attribute({{ json_encode($attribute) }})">Edit</a>
                                                    <a class="mt-2 ml-2" href="javascript:delete_attribute({{ $attribute->id }})">Delete</a>
                                                    <a class="mt-2 ml-2" href="{{ url('admin/product_attribute/'.$attribute->id ) }}">View item</a>
                                                </div>

                                            </td>
                                            <td>{{ $attribute->slug }}</td>
                                            <td><i title="{{ $attribute_type[$attribute->type]['title'] }}" class="{{ $attribute_type[$attribute->type]['icon'] }}" data-toggle="tooltip" data-placement="top"></i></td>
                                            <td style="width: 50%;"><p style="white-space: normal;">{!! $attribute->description !!}</p></td>
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
                    <!-- /.card -->
                </div>
            </div>
        </div>
    </section>
@endsection
