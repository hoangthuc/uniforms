@extends('admin.products.product_layout')
@section('content')
    <?php
    $products =  App\Product::get_products();
    $product_status = App\Product::product_status();

//    $xlsx = \App\SimpleXLSX::parse( public_path('imports/extra/Unipro_Import_image_color.xlsx') );
//    $body = [];
//    $thumbnail_color = [];
//    $thumbnail_attribute= [];
//    foreach ( $xlsx->rows() as $r => $row ) {
//        $product_id = \App\Product::check_product_bysku($row[0]);
//        $thumbnail = null;
//        $media = '';
//        if($r>0 && $product_id){
//            $data_attr = ['name'=>format_text_cell($row[1]),'parent_id'=>1];
//            $data_attr['slug'] = check_field_table($row[1], 'slug', 'product_attributes');
//            if($row[2])$thumbnail = \App\Media::get_media_first([ 'search'=>'/'.format_text_cell($row[2])]);
//            if($row[3])$media = \App\Media::get_media_first([ 'search'=>'/'.format_text_cell($row[3])]);
//            $color =  \App\Product::check_attributes($data_attr);
//            $thumbnail = ($thumbnail)?$thumbnail->id:null;
//            $media = ($media)?$media->id:'';
//            $thumbnail_color[$product_id][$color]= $thumbnail;
//            if($media)$thumbnail_attribute[$product_id][$color][$media]= $media;
//        }
//
//    }
//    foreach($thumbnail_color as $product_id => $json){
//        var_dump( $product_id );
//        var_dump( json_encode($json) );
//        \App\Product::update_meta_product($product_id,'thumbnail_color',json_encode($json));
//    }
//    foreach($thumbnail_attribute as $product_id => $json){
//        \App\Product::update_meta_product($product_id,'thumbnail_attribute',json_encode($json));
//    }

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
