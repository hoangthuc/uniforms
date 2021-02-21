<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Relationships extends Model
{
    // add relationships
    public static function save_relationships($object_id,$term_id,$type=""){
        $resulf = [];
        $data = array(
            'object_id' => $object_id,
            'term_id' => $term_id,
            'type' => $type.$term_id,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        );
        DB::table('relationships')->insert($data);
        $resulf['update'] = 'successfully' ;
        return \GuzzleHttp\json_encode($resulf);

    }
    // insert
    public static function insert_relationships_attr($object_id,$term_id,$type=""){
        $resulf = [];
        $data = array(
            'object_id' => $object_id,
            'term_id' => $term_id,
            'type' => $type.$term_id,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        );
        DB::table('relationships')->insert($data);
//        return \GuzzleHttp\json_encode($data);
        $resulf['update'] = 'successfully' ;
        return \GuzzleHttp\json_encode($resulf);

    }
    // get relationships
    public static function get_relationships($object_id,$type="",$number=1){
        $where = DB::table('relationships')
            ->join('product_categories','product_categories.id','=','relationships.term_id')
            ->select('product_categories.*')
            ->where('object_id',$object_id)
            ->groupBy('product_categories.id');
        if(isset($type))$where->where('type','LIKE',$type.'%');
        if($number==1)$relationships = $where->first();
        if($number>1)$relationships = $where->get();
        if($relationships) return $relationships ;
        return false;
    }

    //delete relationship
    public static function delete_relationship($object_id,$type=""){
        $where = DB::table('relationships')->where('object_id',$object_id)->where('type','like',$type.'%')->delete();
    }


}
