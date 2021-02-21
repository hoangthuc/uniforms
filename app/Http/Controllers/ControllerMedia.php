<?php

namespace App\Http\Controllers;

use App\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\User;
use ZanySoft\Zip\Zip;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Input;
use App\SimpleXLSX;

class ControllerMedia extends Controller
{
    public function doUpload(Request $request)
    {
        //Kiểm tra file
        if ($request->hasFile('UploadMedia')) {
            $file = $request->UploadMedia;
            $path = 'uploads/'.date('Y').'/'.date('m');
            //Name files
            $filename = explode('.',$file->getClientOriginalName())[0];
            $filename = check_field_table($filename,'title','media');
            // End file
            $end_file = '.'.$file->getClientOriginalExtension();
            //Type file
            $type =  $file->getMimeType();
            // insert file
           $errors =  $file->move($path, $filename.$end_file);

            // save database
            $user_id = Auth::id();
            $media = [
            'user_id' => $user_id,
            'path' => $path.'/'.$filename.$end_file,
            'type' => $type,
            'title' => $filename,
            'link' => url($path.'/'.$filename.$end_file),
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
            ];

            $media_id = DB::table('media')->insertGetId($media);
            $media['id'] = $media_id;
            $media['end_file'] = $file->getClientOriginalExtension();
            $media['name_media'] = $filename.$end_file;
            $media['size'] = get_size_media($media['path']);
            $media['ftype'] = get_type_media($media['type']);
            $media['description'] = '';
            $media['author'] = Auth::user()->name;
            $media['success'] = 'successfully';
            echo \GuzzleHttp\json_encode($media);

        }
        die();
    }

    public function UploadFileImport(Request $request)
    {
        $head = ['id','sku','name','description','price','category','featured_image','gallery','color','size','number_size','weight'];
        $info = ['images'=>0];

            $file = $request->folder_images;
            //Name files
            $filename = explode('.',$file->getClientOriginalName())[0];
            // End file
            $end_file = '.'.$file->getClientOriginalExtension();
            //Type file
            $type =  $file->getMimeType();
            $path = 'imports/'.date('Y').'/'.date('m').'/'.$filename;
            // insert file
            $errors =  $file->move($path, $filename.$end_file);
        $extract_path = $path.'/'.$filename.$end_file;
        $zip = Zip::open($extract_path);
        $zip->extract($path);
        $zip->close();
        delete_file_media($extract_path);
        if ( $xlsx = SimpleXLSX::parse( public_path($path.'/import_product.xlsx') ) ) {
            $header = [];
            $body = [];
            foreach ( $xlsx->rows() as $r => $row ) {
                if($r==0)$header = $row;
                if($r > 0){
                    foreach ( $row as $c => $cell ) {
                        if($header[$c])$body[$r][$header[$c]] = format_text_cell($cell);
                        if($header[$c] == 'featured_image' | $header[$c] == 'gallery'){
                            $info['images'] += count(get_images_import($cell));
                        }
                    }
                }

            }
            /// set attributes
            $header_attr = [];
            $body_attr = [];
            foreach ( $xlsx->rows(1) as $r => $row ) {
                if($r==0)$header_attr = $row;
                if($r > 0){
                    foreach ( $row as $c => $cell ) {
                        if($header_attr[$c] && $c>0)$body_attr[$row[0]][$header_attr[$c]] = format_text_cell($cell);
                    }
                }

            }
            // set variants
            $header_var = [];
            $body_var = [];
            foreach ( $xlsx->rows(2) as $r => $row ) {
                if($r==0)$header_var = $row;
                if($r > 0){
                    $col = [];
                    foreach ( $row as $c => $cell ) {
                        if($header_var[$c]){
                            $col[$header_var[$c]] = format_text_cell($cell);
                        }
                    }
                    $body_var[$row[0]][] =$col;
                }

            }
            $info['head'] = remove_empty_array($header);
            $info['number_product'] = count($body);
            $info['size'] =  folder_Size(public_path($path));
            $info['data'] = $body;
            $info['path'] = $path;
            $info['head_attr'] = remove_empty_array($header_attr);
            $info['data_attr'] = $body_attr;
            $info['product_variations'] = $body_var;
            $info['html'] = show_product_import($info);
            $info['success'] = 'Successfullly';
            $product_variations = [];
            return \GuzzleHttp\json_encode($info);
        }else{
            $info['error'] = 'File not found import_product.xlsx';
            return \GuzzleHttp\json_encode($info);
        }

        die();
    }
}
