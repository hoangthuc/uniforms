<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Orders extends Model
{
    // get all orders
    public static function get_orders(){
        $search = (isset($_GET['search']))?$_GET['search']:'';
        $status = (isset($_GET['status']))?$_GET['status']:'';
        $date_from  = (isset($_GET['date_from']))?$_GET['date_from']:date('Y-m-d H:i',strtotime('-100 days'));
        $date_to  = (isset($_GET['date_to']))?$_GET['date_to']:'';
        $orders = DB::table('product_orders')
            ->join('users', 'users.id','product_orders.user_id')
            ->select('product_orders.*')
            ->where( function($select) use ($search){
                $select->orwhere('product_orders.id','LIKE',"%{$search}%");
                $select->orwhere('users.name','LIKE',"%{$search}%");
                $select->orwhere('users.email','LIKE',"%{$search}%");
            } )
            ->where( function($select) use ($status){
                if($status)$select->where('product_orders.status',$status);
            } )
            ->where( function($select) use ($date_from,$date_to){
                if($date_from)$select->whereDate('product_orders.created_at','>',$date_from);
                if($date_to)$select->whereDate('product_orders.created_at','<',$date_to);
            } )
            ->orderByDesc('updated_at')
            ->paginate(12);
        return $orders;
    }
    // get order by id
    public static function get_order($order_id){
        $order = DB::table('product_orders')->where('id',$order_id)->first();
        return $order;
    }
    // get orders by user
    public static function get_ordersBy_user($user){
        $orders = DB::table('product_orders')->where('user_id',$user)->orderByDesc('updated_at')->paginate(12);
        return $orders;
    }

    // get orders by user and status
    public static function get_ordersBy_user_processing($user,$status=2){
        $orders = DB::table('product_orders')->where('user_id',$user)->where('status',$status)->orderByDesc('updated_at')->paginate();
        return $orders;
    }

    // status order
    public static function order_status(){
        $order_status = [
            1 => 'Failed',
            2 => 'Processing',
            3 => 'Completed',
            4 => 'On hold',
            5 => 'Canceled',
            6 => 'Refunded',
            7 => 'Gift',
            8 => 'Pending payment',
        ];
        return $order_status;
    }

    // status product
    public static function payment_type(){
        $product_type = [
            0 => 'Cash on delivery',
            1 => 'Direct bank transfer',
            2 => 'PayPal',
            3 => 'Authorize',
        ];
        return $product_type;
    }

    // get meta orders
    public static function get_meta_product_order($order_id,$meta_key,$re=''){
        $order_meta = DB::table('product_order_meta')->where('order_id',$order_id)->where('meta_key',$meta_key)->first();
        if($order_meta)return $order_meta->meta_value ;
        return $re;
    }
    // update meta orders
    public static function update_meta_product_order($order_id,$meta_key,$meta_value){
        $order_meta = self::get_meta_product_order($order_id,$meta_key);
        if($order_meta){
            DB::table('product_order_meta')->where('order_id',$order_id)->where('meta_key',$meta_key)->update(['meta_value'=>$meta_value]);
        }else{
            $order_meta =  self::add_meta_product_order($order_id,$meta_key,$meta_value);
        }
        return $order_meta;
    }

    // get meta orders
    public static function add_meta_product_order($order_id,$meta_key,$meta_value){
        $order_meta = self::get_meta_product_order($order_id,$meta_key);
        if(!$order_meta){
            $data = array(
                'order_id' => $order_id,
                'meta_key' => $meta_key,
                'meta_value' => $meta_value,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            );
            $meta_id = DB::table('product_order_meta')->insertGetId($data);
            return $meta_id;
        }
        return false;
    }

    public static function get_data_order($type='week',$time){
        $order = DB::table('product_orders')
            ->select(DB::raw('sum(total) as total'))
            ->where('status',3)
            ->where(function($select) use ($type,$time){
                if($type == 'week'){
                    $select->whereDate('created_at',date('Y-m-d',$time));
                }
                if($type == 'year'){
                   $select->whereMonth('created_at',date('m',$time) );
                    $select->whereYear('created_at','=',date('Y',$time));
                }
            })
            ->first();
        return round($order->total,2);
    }

    public static function get_query_order($query){
        $select = ( isset($query['select']) )?$query['select']:'status, count(*) as total';
        $groupBy = ( isset($query['groupBy']) )?$query['groupBy']:'status';
        $orders = DB::table('product_orders')
            ->select(DB::raw($select))
            ->where(function($select) use ($query){
                if(isset($query['month'])){
                    $select->whereMonth('created_at',date('m',$query['month']) );
                }
            })
            ->groupBy($groupBy)
            ->get();
          // ->toSql();
        return $orders;
    }

    public static function get_top_product_sales($query){
        $orders = DB::table('product_order_meta')
            //'$[*].quantily'
            ->select(DB::raw("order_id, JSON_EXTRACT(meta_value,'$[*].product_id') as products, JSON_EXTRACT(meta_value,'$[*].quantily') as quantily"))
            ->where('meta_key','products')
            ->where(function($select) use ($query){
                if(isset($query['month'])){
                    $select->whereMonth('created_at',date('m',$query['month']) );
                }
                if(isset($query['week'])){
                    $next = strtotime("+7 day",$query['week']);
                    $select->whereDate('created_at','>',date('Y-m-d',$query['week']) );
                    $select->whereDate('created_at','<',date('Y-m-d',$next) );
                }
                if(isset($query['year'])){
                    $select->whereYear('created_at',date('Y',$query['year']) );
                }
            })
          //  ->groupBy('order_id')
            ->get();
        return $orders;
    }

}
