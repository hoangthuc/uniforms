@extends('admin.products.product_layout')
@section('content')
    <?php

  //  $products = App\Product::get_products();
    $products_all = DB::table('products')->get();
//    $xlsx = \App\SimpleXLSX::parse(public_path('imports/extra/UniPro_ComPriceList.xlsx'));
//    $body = [];
//    foreach ($xlsx->rows() as $r => $row) {
//        if ($r > 0 && $row[0] && $row[1]) {
//            $body[$row[0]][$row[3]] = round($row[5],2);
//        }
//    }
////    var_dump($body);
//    echo '<pre>'.json_encode($body).'</pre>';
// $id = \App\Product::get_product_attributes_bylug('size');
//    $string = file_get_contents( public_path('imports/extra/data.json'));
//    $string = json_decode($string, true);
//    $body= [];
//   foreach($string as $key => $size){
//        $product_id = \App\Product::check_product_bysku($key);
//       $body[$product_id] = $size;
//
//   }
//        foreach($body as $product_id => $json){
//            var_dump( $product_id );
//            var_dump( json_encode($json) );
//            var_dump(end($json));
//            \App\Product::update_meta_product($product_id,'price',end($json));
//            \App\Product::update_meta_product($product_id,'price_attribute','{}');
//            \App\Product::update_meta_product($product_id,'all_price',json_encode($json));
//        }

    ?>
{{--    <table>--}}
{{--        <thead>--}}
{{--        <tr>--}}
{{--            <th>sku</th>--}}
{{--            <th>type</th>--}}
{{--            <th>attribute</th>--}}
{{--            <th>default</th>--}}
{{--            <th>thumbnail</th>--}}
{{--            <th>gallery</th>--}}
{{--        </tr>--}}
{{--        </thead>--}}
{{--        @if(isset($products_all))--}}
{{--            @foreach ($products_all as $product)--}}
{{--                <?php--}}
{{--                $sku = \App\Product::get_meta_product($product->id, 'sku');--}}
{{--                $all_attributes = \App\Product::get_meta_product($product->id, 'all_attributes');--}}
{{--                $list_price = \App\Product::get_meta_product($product->id, 'price_attribute');--}}
{{--                $list_price = ($list_price) ? (array)\GuzzleHttp\json_decode($list_price) : [];--}}

{{--                $thumbnail_color = \App\Product::get_meta_product($product->id, 'thumbnail_color');--}}
{{--                $thumbnail_color = ($thumbnail_color)?(array)json_decode($thumbnail_color):[];--}}
{{--                $thumbnail_color = ($thumbnail_color)?get_title_media_array($thumbnail_color):[];--}}


{{--                $thumbnail_attribute = \App\Product::get_meta_product($product->id, 'thumbnail_attribute');--}}
{{--                $thumbnail_attribute = ($thumbnail_attribute)?(array)json_decode($thumbnail_attribute):[];--}}
{{--                ?>--}}
{{--                @if($all_attributes)--}}
{{--                    @foreach( (array)\GuzzleHttp\json_decode($all_attributes) as $attribute_parent => $attribute_child )--}}
{{--                        <?php--}}
{{--                        $type = \App\Product::get_product_attributes_detail_single($attribute_parent);--}}
{{--                        ?>--}}
{{--                        @if( isset($attribute_child->value) )--}}
{{--                            @foreach($attribute_child->value as $item_detail)--}}
{{--                                <tr>--}}
{{--                                    <td>{{ $sku }}</td>--}}
{{--                                    <td>{{ isset($type)?$type->type:'' }}</td>--}}
{{--                                    <td>{{ $item_detail->title }}</td>--}}
{{--                                    <td>0</td>--}}
{{--                                    <td>{{ ( isset($thumbnail_color[ $item_detail->value]) )? $thumbnail_color[$item_detail->value] :'' }}</td>--}}
{{--                                    <td>{{ isset($thumbnail_attribute[ $item_detail->value ])? implode(',', get_title_media_array( (array)$thumbnail_attribute[$item_detail->value]) ) :'' }}</td>--}}
{{--                                </tr>--}}
{{--                            @endforeach--}}
{{--                        @endif--}}
{{--                    @endforeach--}}
{{--                @endif--}}
{{--            @endforeach--}}
{{--        @endif--}}
{{--    </table>--}}
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
                                <input type="file" class="custom-file-input" onchange="upload_import_product()"
                                       name="folder_images" accept=".zip">
                                <button type="button" class="custom-file-button">Choose file import (.zip && Limit
                                    150MB)
                                </button>
                            </div>

                        </div>
                        <div class="list_fail_product"></div>
                        <div class="form-group d-none" id="show_info">
                            <div class="insert-product">
                                <div class="progress mb-2 d-none">
                                    <div class="progress-bar progress-bar-striped progress-bar-animated"
                                         role="progressbar" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100"
                                         style="width: 75%">75%
                                    </div>
                                </div>
                            </div>
                            <button type="button" class="btn btn-primary" onclick="load_insert_product()">Insert
                                Product
                            </button>
                            <button type="button" class="btn btn-primary" onclick="show_form_upload()">Change file
                                import
                            </button>
                            <button type="button" class="btn btn-info">
                                Number products <span class="badge badge-light number_product"
                                                      data-info="number_product">4</span>
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
