<?php

namespace App;

use Faker\Provider\File;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class Media extends Model
{
    public $table = 'media';
    public static function get_media($query=[],$page=1){
        $type = isset($query['type'])?$query['type']:'';
        $search = isset($query['search'])?$query['search']:'';
        $medias = DB::table('media')
            ->where( function($select) use ($type){
                $select->where('type','LIKE', $type.'%');
            } )
            ->where( function($select) use ($search){
                $select->orwhere('title','LIKE', "%{$search}%");
                $select->orwhere('description','LIKE', "%{$search}%");
                $select->orwhere('path','LIKE', "%{$search}%");
            } )
            ->orderBy('id', 'desc')->paginate(12,['*'],'page',$page);
        return $medias;
    }

    public static function get_media_detail($id){
        $media = DB::table('media')->where('id', $id)->first();
        return $media;
    }

    public static function get_url_media($id){
        $media = DB::table('media')->where('id', $id)->first();
        if(!$media || !file_exists($media->path))return asset('images/products/default.jpg');
        return url($media->path);
    }

    public static function create_media_from_path($path_upload,$filename){
        if(!$filename || !file_exists($path_upload))return null;
        $path = 'uploads/'.date('Y').'/'.date('m');
        $filename = explode('.',$filename);
        $filename = check_field_table($filename[0],'title','media').'.'.end($filename);
        if(!is_dir($path)){
            /* Directory does not exist, so lets create it. */
            \Illuminate\Support\Facades\File::makeDirectory( $path,$mode = 0777, true, true);
        }
       copy( $path_upload , $path.'/'.$filename);
        $user_id = Auth::id();
        $media = [
            'user_id' => $user_id,
            'path' => $path.'/'.$filename,
            'type' => 'image/jpeg',
            'title' => $filename,
            'link' => url($path.'/'.$filename),
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ];
        $media_id = DB::table('media')->insertGetId($media);
        return $media_id;
    }


    public static function get_media_first($query=[],$page=1){
        $type = isset($query['type'])?$query['type']:'';
        $search = isset($query['search'])?$query['search']:'';
        $medias = DB::table('media')
            ->where( function($select) use ($type){
                $select->where('type','LIKE', $type.'%');
            } )
            ->where( function($select) use ($search){
                $select->orwhere('title','LIKE', "%{$search}%");
                $select->orwhere('description','LIKE', "%{$search}%");
                $select->orwhere('path','LIKE', "%{$search}%");
            } )
            ->orderBy('id', 'desc')->first();
        return $medias;
    }
               
}
