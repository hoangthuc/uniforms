@extends('admin.products.product_layout')
@section('content')
    <?php
    $products =  App\Product::get_products();
    $product_status = App\Product::product_status();

//    $xlsx = \App\SimpleXLSX::parse( public_path('imports/extra/get_image.xlsx') );
//    $body = [];
//    foreach ( $xlsx->rows() as $r => $row ) {
//        $media = \App\Media::get_media_first([ 'search'=>format_text_cell($row[4])]);
//        $product_id = \App\Product::check_product_bysku($row[0]);
//        if($r>0 && $row[4] && $media && $product_id){
//            $body[$row[0]]['sku'] = format_text_cell($row[0]);
//            $body[$row[0]]['img'] = format_text_cell($row[4]);
//            $body[$row[0]]['product_id'] = $product_id;
//            $body[$row[0]]['media_id'] = $media->id;
//            $data['featured_image'] = $media->id;
//            DB::table('products')->where('id',$product_id)->update($data);
//        }
//
//    }
//    var_dump($body);

//
//    $xlsx = \App\SimpleXLSX::parse( public_path('imports/2021/03/unipro/import_product.xlsx') );
//    $header = [];
//    $attr_data = [];
//    $body = [];
//    $attribute = [];
//    foreach ( $xlsx->rows(1) as $r => $row ) {
//        if($r<1){
//            $header = $row;
//            foreach ($row as $stt => $item){
//                $id = \App\Product::get_product_attributes_bylug($item);
//                if($id){
//                    foreach (App\Product::get_product_attributes_detail_man($id,1000)->child as $item_attr){
//                        $attr_data[$item][$item_attr->data_type] =  ['title'=>$item_attr->name,'value'=>$item_attr->id];
//                    }
//                }
//            }
//        }
//        if($r>0){
//            foreach ($row as $stt => $item){
//                if($item)$body[$r][ $header[$stt]  ]= $item;
//            }
//        }
//
//    }
//    foreach ( $xlsx->rows(2) as $r => $row ) {
//        if($r>0){
//            $attribute[$row[0]][]= $row[7];
//        }
//
//    }
//foreach ($body as $r=>$item){
//    foreach ($body[$r] as $key_attr => $value){
//        $id = \App\Product::get_product_attributes_bylug($key_attr);
//        if($id){
//            $body[$r]['attributes'][]=$id;
//            $array_attr = explode(',',$value);
//            foreach ($array_attr as $attr_item){
//                if(isset($attr_data[$key_attr][$attr_item])) {
//                    $body[$r]['all_attributes'][$id]['value'][]=$attr_data[$key_attr][$attr_item];
//                }
//
//            }
//            $body[$r]['all_attributes'][$id]['display'] = true;
//
//        }
//
//    }
//
//    $body[$r]['product_id'] =   \App\Product::check_product_bysku($body[$r][ 'sku'  ]);
//    $body[$r]['attributes'] =   \GuzzleHttp\json_encode($body[$r]['attributes'] );
//    $body[$r]['all_attributes'] =   \GuzzleHttp\json_encode($body[$r]['all_attributes'] );
//    $body[$r]['length_attr'] = ( isset($attribute[ $body[$r]['sku'] ]) )?$attribute[ $body[$r]['sku'] ]:[];
//}
//
//foreach ($body as $r=>$item){
//    $product_variant = \App\Product::get_meta_product($body[$r]['product_id'],'product_variations');
//    $product_variant = ($product_variant)?\GuzzleHttp\json_decode($product_variant):[];
//    foreach ($product_variant as $stt=> $attr_item){
//        if(isset($body[$r]['length_attr'][$stt])){
//            $length_attr =  $body[$r]['length_attr'][$stt];
//           if(isset($attr_data['length'][$length_attr]['value']) && !array_search($attr_data['length'][$length_attr]['value'],$product_variant[$stt]->select))$product_variant[$stt]->select[]=$attr_data['length'][$length_attr]['value'];
//        }
//        //$product_variant[$stt]->select  = array_merge($product_variant[$stt]->select,[ $body[$r]['length_attr'][$stt] ]);
//    }
//    $body[$r]['product_variations']  = \GuzzleHttp\json_encode($product_variant);
//    }
//
//    foreach ($body as $r=>$item){
//        var_dump($body[$r]['product_id']);
//        var_dump($body[$r]['sku']);
//        var_dump($body[$r]['attributes']);
//        var_dump($body[$r]['all_attributes']);
//        var_dump($body[$r]['product_variations']);
//        \App\Product::update_meta_product($body[$r]['product_id'],'attributes',$body[$r]['attributes']);
//        \App\Product::update_meta_product($body[$r]['product_id'],'all_attributes',$body[$r]['all_attributes']);
//        \App\Product::update_meta_product($body[$r]['product_id'],'product_variations',$body[$r]['product_variations']);
////        \App\Product::delete_meta_product($body[$r]['product_id'],'attribute');
////        \App\Product::delete_meta_product($body[$r]['product_id'],'all_attribute');
//    }
//    // set variants
//    $header_var = [];
//    $body_var = [];
//    foreach ( $xlsx->rows(2) as $r => $row ) {
//        if($r==0)$header_var = $row;
//        if(!isset($body_var[$row[0].$row[4]]) && $r > 0 && !file_exists( 'imports/2021/03/unipro/'.format_text_cell($row[3])) ){
//            $body_var[$row[0].$row[4]]['sku'] =format_text_cell($row[0]);
//            $body_var[$row[0].$row[4]]['color'] = \App\Product::get_product_attributes_by_datatype(format_text_cell($row[4]),1)->name;
//            $body_var[$row[0].$row[4]]['featured_image'] = format_text_cell($row[3]);
//        }else if(!isset($body_var[$row[0].$row[4]]) && $r > 0 && $row[4] && empty($row[3])  ){
//            $body_var[$row[0].$row[4]]['sku'] =format_text_cell($row[0]);
//            $body_var[$row[0].$row[4]]['color'] = \App\Product::get_product_attributes_by_datatype(format_text_cell($row[4]),1)->name;
//            $body_var[$row[0].$row[4]]['featured_image'] = format_text_cell($row[3]);
//        }else{
//            $body_var[$row[0].$row[4]]['sku'] =format_text_cell($row[0]);
//            $body_var[$row[0].$row[4]]['color'] = format_text_cell($row[4]);
//            $body_var[$row[0].$row[4]]['featured_image'] = format_text_cell($row[3]);
//            $body_var[$row[0].$row[4]]['status'] = 'OK';
//        }
//
//
//    }
//    echo '<table>';
//    foreach ($body as  $tr){
//        echo '<tr>
//<td>'.$tr['sku'].'</td>
//<td>'.$tr['name'].'</td>
//<td>'.$tr['featured_image'].'</td>
//</tr>';
//    }
//    echo '</table>';
//
//echo '<table>';
//    foreach ($body_var as $k=> $tr){
//        if( !isset($tr['status']) ){
//            echo '<tr>
//<td>'.$tr['sku'].'</td>
//<td>'.$tr['color'].'</td>
//<td>'.$tr['featured_image'].'</td>
//</tr>';
//        }
//
//    }
//    echo '</table>';
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
