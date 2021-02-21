@extends('admin.products.product_layout')
@section('content')
    <?php
    $products =  App\Product::get_products();
    $product_status = App\Product::product_status();

    ?>
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="breadcrumb">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Import Products</li>
                </ol>
            </div>
        </div><!-- /.container-fluid -->
    </section>
    <!-- Main content -->
    <section class="content_import_products">
        <div class="container-fluid">
            <div class="card card-primary">
                <!-- form start -->
                <form role="form" id="import_file_products">
                    <div class="card-body">
                        <div class="form-group" id="show_file_import">
                            <div class="custom-file-import">
                                <input type="file" class="custom-file-input" onchange="upload_import_product()" name="folder_images" accept=".zip">
                                <button type="button" class="custom-file-button">Choose file import (.zip && Limit 150MB)</button>
                            </div>

                        </div>
                        <div class="list_fail_product"></div>
                        <div class="form-group d-none" id="show_info">
                            <div class="insert-product">
                                <div class="progress mb-2 d-none">
                                    <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100" style="width: 75%">75%</div>
                                </div>
                            </div>
                            <button type="button" class="btn btn-primary" onclick="load_insert_product()">Insert Product</button>
                            <button type="button" class="btn btn-primary" onclick="show_form_upload()">Change file import</button>
                            <button type="button" class="btn btn-info">
                                Number products <span class="badge badge-light number_product" data-info="number_product">4</span>
                            </button>
                            <button type="button" class="btn btn-info">
                                Images <span class="badge badge-light images" data-info="images">4</span>
                            </button>
                            <button type="button" class="btn btn-danger">
                                Images miss <span class="badge badge-light images_miss" data-info="images_miss">4</span>
                            </button>
                            <button type="button" class="btn btn-info">
                                Size <span class="badge badge-light " data-info="size">2 MB</span>
                            </button>
                            <div class="table_product mt-3 table-responsive">

                            </div>

                        </div>

                    </div>
                    <!-- /.card-body -->
                </form>
            </div>
        </div>
    </section>
@endsection
