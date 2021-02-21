<?php

namespace App;

use http\Env\Request;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class Options extends Model
{
    // get meta option
    public static function get_option($user_id,$meta_key,$re=''){
        $optiont_meta = DB::table('options')->where('user_id',$user_id)->where('meta_key',$meta_key)->first();
        if($optiont_meta)return $optiont_meta->meta_value ;
        return $re;
    }

    // get meta option field
    public static function get_field_option($meta_key,$re=null){
        $optiont_meta = DB::table('options')->where('meta_key',$meta_key)->first();
        if($optiont_meta)return $optiont_meta->meta_value ;
        return $re;
    }
    // update meta product
    public static function update_option($user_id,$meta_key,$meta_value){
        $optiont_meta = self::get_option($user_id,$meta_key);
        if($optiont_meta){
            DB::table('options')->where('user_id',$user_id)->where('meta_key',$meta_key)->update(['meta_value'=>$meta_value]);
        }else{
            $optiont_meta =  self::add_option($user_id,$meta_key,$meta_value);
        }
        return $optiont_meta;
    }

    // get meta product
    public static function add_option($user_id,$meta_key,$meta_value){
        $optiont_meta = self::get_option($user_id,$meta_key);
        if(!$optiont_meta){
            $data = array(
                'user_id' => $user_id,
                'meta_key' => $meta_key,
                'meta_value' => $meta_value,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            );
            $meta_id = DB::table('options')->insertGetId($data);
            return $meta_id;
        }
        return false;
    }
    // Get list all question FAQ
    public static function list_faq(){
        $faq = (object)[
            (object)['title'=>'Frequently Asked Questions','value'=>[]],
            (object)['title'=>'Account','value'=>[]],
            (object)['title'=>'Stories','value'=>[]],
            (object)['title'=>'Media','value'=>[]],
            (object)['title'=>'Shopping','value'=>[]],
            (object)['title'=>'General Help','value'=>[]],
        ];
        return $faq;
    }
    // save FAQ
    public static function save_faq($request){
        $user_id = Auth::id();
        $meta_key = 'option_faq';
        $meta_value = \GuzzleHttp\json_encode( $request['data'] );
        self::update_option($user_id,$meta_key,$meta_value);
        $resulf['success'] = 'Successfully';
        return $resulf;
    }

    // List contact form default
    public static function display_ctf(){
        $data = (object)[
            'sign-up'=> 'Sign up',
            'contact-us'=> 'Contact us',
            'subscribers'=> 'Subscribers',
            'send-free-gift'=> 'Send Free Gift',
        ];
        $user_id = Auth::id();
      //  $list_form = self::get_option($user_id,'option_list_ctf');
       if(isset($list_form))$data = \GuzzleHttp\json_decode($list_form);
        self::update_option($user_id,'option_list_ctf',\GuzzleHttp\json_encode($data));
        return $data;
    }

    public static function get_menu(){
        $menu = [
            (object)['href'=>'posts','text'=>'Departments','deth'=>2,'menu2'=>[
                (object)['href'=>'post-1','text'=>'Post 1'],
                (object)['href'=>'post-2','text'=>'Post 2'],
            ]],
            (object)['href'=>'gift-shop','text'=>'Categories','deth'=>1],
            (object)['href'=>'faq','text'=>'Brand','deth'=>1],
            (object)['href'=>'about','text'=>'Accessories & equipment','deth'=>1],
        ];
        return $menu;
    }



}
