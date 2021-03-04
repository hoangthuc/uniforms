<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Orders;
use Mail;

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


}
