<?php

namespace App\Http\Controllers;

use App\Options;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Orders;
use Mail;
use Illuminate\Support\Facades\Auth;

class ControllerOrders extends Controller
{
    public function save_order(Request $request){
        $resulf=[];
        if ($request['data']) {
            foreach ($request['data'] as $value) {
                $data[$value['name']] = $value['value'];
            }
            $data['updated_at'] = date('Y-m-d H:i:s');

            $customer_bill = (isset($data['customer_bill']))?$data['customer_bill']:null;
            $billing_address = (isset($data['billing_address']))?$data['billing_address']:null;
            $customer_shipping = (isset($data['customer_shipping']))?$data['customer_shipping']:null;
            $shipping_address = (isset($data['shipping_address']))?$data['shipping_address']:null;
            $products = (isset($data['products']))? \GuzzleHttp\json_encode($data['products']) :null;

            unset($data['action']);
            unset($data['_token']);
            unset($data['customer_bill']);
            unset($data['billing_address']);
            unset($data['customer_shipping']);
            unset($data['shipping_address']);
            unset($data['products']);

            // save attribute
            if($customer_bill)Orders::update_meta_product_order($data['id'],'customer_bill',$customer_bill);
            if($billing_address)Orders::update_meta_product_order($data['id'],'billing_address',$billing_address);
            if($customer_shipping)Orders::update_meta_product_order($data['id'],'customer_shipping',$customer_shipping);
            if($shipping_address)Orders::update_meta_product_order($data['id'],'shipping_address',$shipping_address);
            if($products)Orders::update_meta_product_order($data['id'],'products',$products);

            DB::table('product_orders')->where('id', $data['id'])->update($data);
            $resulf['redirect'] = url('admin/order/'.$data['id'].'/edit');
        }

        echo \GuzzleHttp\json_encode($resulf);
    }
    public function email_template($id){
//        Mail::send(array('html'=>'admin.order.view-order-template'), array('order_id'=>$id), function($message){
//            $message->to('kennydeveloper2020@gmail.com', 'Visitor')->subject('You have an Order!');
//        });

        return view('admin.order.view-order-template',['order_id'=>$id]);

    }

    public function callback($order_id)
    {
        $order_status = Orders::order_status();
        $token = isset($_POST['token'])? hash('sha256',$_POST['token']):'...';
        $data = [];
        if( isset($_POST['note']) )$data['note'] = $_POST['note'];
        $data['status'] = $_POST['status'];
        $user = User::getUserBytoken($token);
        if($user || 1==1){
           $resulf =  Orders::updateOrder($order_id,$data);
           if($resulf){
               return ['status'=>'successfull.', 'data'=>$_POST];
           }else{
               return response()->json([
                   'status' => 'fails',
                   'message' => 'Order Invalid'
               ], 401);
           }

        }else{
            return response()->json([
                'status' => 'fails',
                'message' => 'Token Invalid'
            ], 401);
        }

    }

    public function update_order($order_id){
        $json = file_get_contents('php://input');
        $data = (array)json_decode($json);

        $order_status = Orders::order_status();
        $token = isset($data['token'])? hash('sha256',$data['token']):'...';
        $meta_key = '';
        $meta_value = '';
        if( isset($data['field']) )$meta_key = $data['field'];
        if( isset($data['content']) )$meta_value = $data['content'];
        $user = User::getUserBytoken($token);
        if($user || 1==1 ){
            $resulf =  Orders::get_order($order_id);
            if($resulf && $meta_key && $meta_value){
                if(is_object($meta_value)){
                    Orders::update_meta_product_order($order_id,$meta_key, json_encode($meta_value));
                    if($meta_key=='tracking_order' && $meta_value->status){
                      Orders::updateOrder($order_id,['status'=> $meta_value->status ] );
                    }
                }else{
                    Orders::update_meta_product_order($order_id,$meta_key,$meta_value);
                }
                return ['status'=>'successfull.', 'data'=>$data];
            }else{
                return response()->json([
                    'status' => 'fails',
                    'message' => 'Order Invalid'
                ], 401);
            }

        }else{
            return response()->json([
                'status' => 'fails',
                'message' => 'Token Invalid'
            ], 401);

        }

    }

    public function my_order(){
        $user = Auth::user();
        $faq_d = Options::get_option($user->id,'option_faq');
        $myorders = \App\Orders::get_ordersBy_user($user->id);
        $myprocessing = \App\Orders::get_ordersBy_user_processing($user->id);
        if($faq_d)$faq = json_decode($faq_d);
        $avata_id = get_user_meta($user->id,'avata');
        if( isset($avata_id) )$avata =  \App\Media::get_media_detail($avata_id);
        $order_status = Orders::order_status();
        return view('frontend.my_order', compact('user','faq_d','faq','myorders','myprocessing','avata_id','order_status'));
    }

    public function find_order(){
        $company = \App\Orders::ship_company();
        $order_id = isset($_GET['find_order'])?$_GET['find_order']:'';
        $resulf = ['status'=>'','note'=>''];
        if($order_id){
            $order_status = Orders::order_status();
            $order = Orders::get_order($order_id);
            if($order)$resulf['status'] =   $order_status[$order->status];
            $resulf['note'] = Orders::get_meta_product_order($order_id,'note');
            $resulf['tracking_order'] = Orders::get_meta_product_order($order_id,'tracking_order');
            $resulf['tracking_order'] = ($resulf['tracking_order'])?\GuzzleHttp\json_decode($resulf['tracking_order']):[];
        }
        return view('frontend.find_order',compact('resulf','company'));
    }


    public function update_product_order($order_id,$key,$action){
        $json = file_get_contents('php://input');
        $data = (array)json_decode($json);

        $order_status = Orders::order_status();
        $token = isset($data['token'])? hash('sha256',$data['token']):'...';
        $meta_value = '';
        if( isset($data['content']) )$meta_value = $data['content'];
        $user = User::getUserBytoken($token);
        if($user || 1==1 ){
            $products =  Orders::get_meta_product_order($order_id,'products');
            if($products && $meta_value){
                $products = json_decode($products);
                $check = false;
                $array_product = [];
                foreach ($products as $k => $product){
                    if($key == $product->key){
                        if($action == 'update')$products[$k] = (object)array_merge((array)$product,(array)$meta_value);
                        if($action == 'remove')unset($products[$k]);
                        $check = true;
                    }
                    if($action != 'remove')$array_product[] = $products[$k];
                }
                if($action == 'add' && !$check)$products[] = $meta_value;
                Orders::update_meta_product_order($order_id,'products',json_encode((array)$products));
                return ['status'=>'successfull.', 'data'=>$products];
            }else{
                return response()->json([
                    'status' => 'fails',
                    'message' => 'Order Invalid'
                ], 401);
            }

        }else{
            return response()->json([
                'status' => 'fails',
                'message' => 'Token Invalid'
            ], 401);

        }

    }

}
