<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class Reviews extends Model
{
    public static function add_review($data){
        $data['status'] = 1;
        $data['created_at'] = date('Y-m-d H:i:s');
        $data['updated_at'] = date('Y-m-d H:i:s');
        unset($data['action']);
        unset($data['slug']);
        unset($data['_token']);
        $review =  DB::table('reviews')->insertGetId($data);
        return $review;
    }
    // get comment story
    public static function get_review_detail($post_id){
        $review = DB::table('reviews')->where('object_id',$post_id)->orderByDesc('created_at')->paginate(10);
        return $review;
    }

    // get comment story
    public static function get_review_publish($object_id){
        $reviews = DB::table('reviews')->where('object_id',$object_id)->where('status',2)->orderByDesc('created_at')->paginate(10);
        return $reviews;
    }
    // get status comment
    public static function review_status(){
        $status = [
            1 => 'Pending',
            2 => 'Active',
            3 => 'Spam',
            4 => 'Trash',
        ];
        return $status;
    }

    // get comment story
    public static function get_reviews($query){
        $page = isset($query['page'])?$query['page']:1;
        $search = isset($query['search'])?$query['search']:'';
        $reviews = DB::table('reviews')
            ->join('products','products.id','=','reviews.object_id')
            ->select('reviews.*','name')
            ->where(
             function($select) use ($search) {
                 if ($search) {
                     $select->orwhere('content','LIKE', '%'.$search.'%');
                     $select->orwhere('name','LIKE', '%'.$search.'%');
                 }
             })
            ->where(
                function($select) use ($query) {
                    if (isset($query['object_id'])) {
                        $select->where('object_id', $query['object_id']);
                    }
                })
            ->where( function($select) use ($query){
                $select->where('reviews.status','<>',5);
                if( $query['status']){
                    $select->where('reviews.status',$query['status']);
                }
            })
            ->where(function($select) use($query){
                if($query['rating'])foreach ($query['rating'] as $rate){
                    $select->orwhere('rating',$rate);
                }
            })
            ->where('type',$query['type'])
            ->orderByDesc('reviews.created_at')
            ->paginate(10,['*'] ,'page',$page);
        return $reviews;
    }


    // get comment story
    public static function get_check_review($object_id){
        if(!Auth::check())return false;
        $user = Auth::user();
        $reviews = DB::table('reviews')
            ->where('object_id',$object_id)
            ->where('user_id',$user->id)
            ->orderByDesc('created_at')
            ->get();
        if( count($reviews) )return false;
        return true;
    }
    /// Update review
    public static function update_review($id,$data){
        DB::table('reviews')->where('id',$id)->update($data);
    }

    /// addd question new
    public static function add_question($data){
        $data['status'] = 1;
        $data['created_at'] = date('Y-m-d H:i:s');
        $data['updated_at'] = date('Y-m-d H:i:s');
        unset($data['action']);
        unset($data['slug']);
        unset($data['_token']);
        $review =  DB::table('question_answer')->insertGetId($data);
        return $review;
    }

    public static function check_question($object_id){
        if(!Auth::check())return false;
        $user = Auth::user();
        $reviews = DB::table('question_answer')
            ->where('object_id',$object_id)
            ->where('user_id',$user->id)
            ->where('parent_id',null)
            ->where('created_at','>', date('Y-m-d') )
            ->orderByDesc('created_at')
            ->get();
        if( count($reviews) )return false;
        return true;
    }

    // get comment story
    public static function get_question_publish($object_id){
        $reviews = DB::table('question_answer')->where('object_id',$object_id)->where('status',2)->orderByDesc('created_at')->paginate(10);
        return $reviews;
    }

    // get comment story
    public static function get_question_reply($parent_id){
        $questions = DB::table('question_answer')->where('parent_id',$parent_id)->orderByDesc('created_at')->first();
        return $questions;
    }

    // get questions
    public static function get_questions($query){
        $page = isset($query['page'])?$query['page']:1;
        $search = isset($query['search'])?$query['search']:'';
        $reviews = DB::table('question_answer')
            ->join('products','products.id','=','question_answer.object_id')
            ->select('question_answer.*','name')
            ->where(
                function($select) use ($search) {
                    if ($search) {
                        $select->orwhere('content','LIKE', '%'.$search.'%');
                        $select->orwhere('name','LIKE', '%'.$search.'%');
                    }
                })
            ->where('parent_id',null)
            ->where(
                function($select) use ($query) {
                    if (isset($query['object_id'])) {
                        $select->where('object_id', $query['object_id']);
                    }
                })
            ->where( function($select) use ($query){
                $select->where('question_answer.status','<>',5);
                if( $query['status']){
                    $select->where('question_answer.status',$query['status']);
                }
            })
            ->where('type',$query['type'])
            ->orderByDesc('question_answer.created_at')
            ->paginate(10,['*'] ,'page',$page);
        return $reviews;
    }

    /// Update question
    public static function update_question($id,$data){
        DB::table('question_answer')->where('id',$id)->update($data);
    }

    // get top product reviews
    public static function get_top_product_review($query){
        $type = (isset($query['type']))?$query['type']:'product';
        $reviews = DB::table('reviews')
            ->select( DB::raw('object_id as product_id, count(object_id) as quantily') )
            ->where('type',$type)
            ->where('parent',null)
            ->where('status',2)
            ->where(function($select) use ($query){
                if(isset($query['month'])){
                    $select->whereMonth('created_at',date('m',$query['month']) );
                }
                if(isset($query['week'])){
                    $next = strtotime("+7 day",$query['week']);
                    $select->whereDate('created_at','>=',date('Y-m-d',$query['week']) );
                    $select->whereDate('created_at','<=',date('Y-m-d',$next) );
                }
                if(isset($query['year'])){
                    $select->whereYear('created_at',date('Y',$query['year']) );
                }
            })
            ->groupBy('object_id')
            ->orderByDesc('created_at')
            ->get();
        return $reviews;
    }


    // get top product reviews
    public static function get_top_product_question($query){
        $type = (isset($query['type']))?$query['type']:'product';
        $questions = DB::table('question_answer')
            ->select( DB::raw('object_id as product_id, count(object_id) as quantily') )
            ->where('type',$type)
            ->where('parent_id',null)
            ->where('status',2)
            ->where(function($select) use ($query){
                if(isset($query['month'])){
                    $select->whereMonth('created_at',date('m',$query['month']) );
                }
                if(isset($query['week'])){
                    $next = strtotime("+7 day",$query['week']);
                    $select->whereDate('created_at','>=',date('Y-m-d',$query['week']) );
                    $select->whereDate('created_at','<=',date('Y-m-d',$next) );
                }
                if(isset($query['year'])){
                    $select->whereYear('created_at',date('Y',$query['year']) );
                }
            })
            ->groupBy('object_id')
            ->orderByDesc('created_at')
            ->get();
        return $questions;
    }


}
