<?php

namespace App;

use http\Env\Request;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class DataForm extends Model
{
    // get all orders
    public static function get_data($where=''){
        $data_form = DB::table('data_form');
        if(isset($where['search']))$data_form = $data_form->where('name','LIKE',"%{$where['search']}%");
        if(isset($where['type']))$data_form = $data_form->where('type',$where['type']);
        $data_form = $data_form->orderByDesc('updated_at')->paginate(12)->appends(request()->except('page'));
        return $data_form;
    }
    // get order by id
    public static function get_data_detail($id){
        $data = DB::table('data_form')->where('id',$id)->first();
        return $data;
    }

    public static function add_data_form($request){
        $resulf=[];
        if ($request['data']) {
            foreach ($request['data'] as $value) {
                $data[$value['name']] = $value['value'];
            }

            $data['created_at'] = date('Y-m-d H:i:s');
            $data['updated_at'] = date('Y-m-d H:i:s');
            unset($data['action']);
            unset($data['files']);
            unset($data['_token']);

            $id =  DB::table('data_form')->insertGetId($data);
            $resulf['success'] = 'Successfully';
        }

        echo \GuzzleHttp\json_encode($resulf);
    }

    public static function update_data_form($request){
        $resulf=[];
        if ($request['data']) {
            foreach ($request['data'] as $value) {
                $data[$value['name']] = $value['value'];
            }

            $data['created_at'] = date('Y-m-d H:i:s');
            $data['updated_at'] = date('Y-m-d H:i:s');
            unset($data['action']);
            unset($data['files']);
            unset($data['_token']);

            $id =  DB::table('data_form')->where('id', $data['id'])->update($data);;
            $resulf['success'] = url('admin/data-form/'.$id.'/edit');
        }

        echo \GuzzleHttp\json_encode($resulf);

    }

}
