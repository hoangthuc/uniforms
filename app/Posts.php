<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Posts extends Model
{
    protected $table = 'posts';
    public static function get_posts($where=''){
        $posts = DB::table('posts')->paginate(10);
        return $posts;
    }
    /// query story
    public static function query_stories($where=array(),$orderby='created_at', $order='desc',$limit=10,$paginate=10){
        $stories = DB::table('posts')->where('status',1);
        if(isset($where)){
            foreach ($where as $key => $value){
                $stories->where($value['name'],$value['operator'],$value['value']);
            }
        }
        $stories->orderBy($orderby,$order);
        if($limit)$stories->limit($limit);
        $resulf = $stories->paginate($paginate);
        return $resulf;
    }
    public static function post_category(){
        $post_cateroty = [
            0 => 'General',
            1 => 'Lifestyle',
            2 => 'People',
            3 => 'Places',
        ];
        return $post_cateroty;
    }
// status stories
    public static function post_status(){
        $post_status = [
            0 => 'Draft',
            1 => 'Published',
            2 => 'Hidden',
            3 => 'Deleted',
        ];
        return $post_status;
    }
    // type of stories
    public static function post_types(){
        $post_types = [
            0 => 'Post',
            1 => 'Media',
        ];
        return $post_types;
    }

    // get story by slug
    public static function get_post_by_slug($slug=''){
        $post = DB::table('posts')->where('slug',$slug)->first();
        return $post;
    }

    // get story by id
    public static function get_post($post_id){
        $post = DB::table('posts')->where('id',$post_id)->first();
        return $post;
    }

    public function DeletePost($id)
    {
        DB::table('posts')->where('id',$id)->delete();
    }

    // get meta story
    public static function get_meta_post($post_id,$meta_key,$re=''){
        $post = DB::table('meta_post')->where('post_id',$post_id)->where('meta_key',$meta_key)->first();
        if($post)return $post ;
        return $re;
    }
    // update meta story
    public static function update_meta_post($post_id,$meta_key,$meta_value){
        $post = DB::table('meta_post')->where('post_id',$post_id)->where('meta_key',$meta_key)->update(['meta_value'=>$meta_value]);
        return $post;
    }

    // get meta story
    public static function add_meta_post($post_id,$meta_key,$meta_value){
        $post = self::get_meta_story($post_id,$meta_key);
        if(!$post){
            $data = array(
                'story_id' => $post_id,
                'meta_key' => $meta_key,
                'meta_value' => $meta_value,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            );
            $post_id = DB::table('meta_post')->insertGetId($data);
            return $post_id;
        }
        return false;
    }

    public static function get_categories_post(){
        $categories = DB::table('posts')->select(DB::raw('post_category,count(*) as number'))->groupBy('post_category')->get();
        return $categories;
    }

    public static function add_post_comment($data){
        $data['status'] = 0;
        $data['created_at'] = date('Y-m-d H:i:s');
        $data['updated_at'] = date('Y-m-d H:i:s');
        unset($data['action']);
        unset($data['slug']);
        unset($data['_token']);
        $post_comment =  DB::table('reviews')->insertGetId($data);
        return $post_comment;
    }
    // get comment story
    public static function get_comment_post($post_id){
        $posts_comments = DB::table('reviews')->where('object_id',$post_id)->orderByDesc('created_at')->paginate(10);
        return $posts_comments;
    }

    // get comment story
    public static function get_comment_post_publish($story_id){
        $post_comments = DB::table('reviews')->where('object_id',$story_id)->where('status',1)->orderByDesc('created_at')->paginate(10);
        return $post_comments;
    }
    // get status comment
    public static function comment_status(){
        $comment_status = [
            0 => 'Pending',
            1 => 'Active',
            2 => 'Spam',
        ];
        return $comment_status;
    }


}
