<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ControllerPosts extends Controller
{
    public function add_post(Request $request){
        $resulf=[];
        if ($request['data']) {
            foreach ($request['data'] as $value) {
                $data[$value['name']] = $value['value'];
            }
            $data['slug'] = check_field_table($data['post_title'],'slug','posts');
            $data['post_date'] = date('Y-m-d H:i:s');
            $data['time_spent'] = 1;
            $data['ts'] = date('Y-m-d H:i:s');
            $data['remember_token'] = $data['_token'];
            $data['created_at'] = date('Y-m-d H:i:s');
            $data['updated_at'] = date('Y-m-d H:i:s');
            $data['post_image'] =  (isset($data['button_featured_image']))?$data['button_featured_image']:'';
            if(isset($data['audio'])){
                $audio = $data['audio'];
            }
            unset($data['action']);
            unset($data['files']);
            unset($data['_token']);
            unset($data['button_featured_image']);
            unset($data['audio']);
           $post_id =  DB::table('posts')->insertGetId($data);
           if($post_id && isset($audio) ){
               add_meta_post($post_id,'audio',$audio);
           }
            $resulf['redirect'] = route('admin.edit_post',['id'=>$post_id]);
        }

        echo \GuzzleHttp\json_encode($resulf);
    }

    public function save_post(Request $request){
        $resulf=[];
        if ($request['data']) {
            foreach ($request['data'] as $value) {
                $data[$value['name']] = $value['value'];
            }
            $data['slug'] = check_field_table($data['post_title'],'slug','posts');
            $data['remember_token'] = $data['_token'];
            $data['updated_at'] = date('Y-m-d H:i:s');
            $data['post_image'] =  (isset($data['button_featured_image']))?$data['button_featured_image']:'';
            if(isset($data['audio'])){
                $audio = $data['audio'];
                unset($data['audio']);
            }
            unset($data['action']);
            unset($data['files']);
            unset($data['_token']);
            unset($data['button_featured_image']);
            DB::table('posts')->where('id', $data['id'])->update($data);
            if(isset($audio) && add_meta_post($data['id'],'audio',$audio) ){
                update_meta_post($data['id'],'audio',$audio);
            }
            $resulf['success'] = 'Successfully';
        }

        echo \GuzzleHttp\json_encode($resulf);
    }
}
