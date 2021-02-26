<?php

namespace App\Http\Controllers;
use App\Menu;
use App\Posts;
use App\Product;
use App\Relationships;
use App\Reviews;
use function GuzzleHttp\Promise\queue;
use Illuminate\Http\Request;
use App\Stories;
use App\Media;
use App\Options;
use App\DataForm;
use App\User;
use App\Orders;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\SimpleXLSX;

class ControllerAjax extends Controller
{
    public function admin_ajax(Request $request){
        // delete story by id
        if(isset($request['action']) && $request['action']=='detele_story'){
            $resulf=[];
            DB::table('posts')->where('id',$request['id'])->delete();
            $resulf['redirect']= route('admin.posts');
            echo \GuzzleHttp\json_encode($resulf);
        }

        // delete story by id
        if(isset($request['action']) && $request['action']=='delete_comment'){
            $resulf=[];
            DB::table('posts_comment')->where('id',$request['id'])->delete();
            $resulf['reload']= true;
            echo \GuzzleHttp\json_encode($resulf);
        }


        // update status comment stories
        if(isset($request['action']) && $request['action']=='status_comment'){
            $resulf=[];
            DB::table('posts_comment')->where('id',$request['id'])->update(['status'=>$request['value']]);
            $resulf['success']= true;
            echo \GuzzleHttp\json_encode($resulf);
        }


        // update media
        if(isset($request['action']) && $request['action']=='update_media'){
            $resulf=[];
            DB::table('media')->where('id',$request['id'])->update([ 'title'=>$request['title'],'description'=>$request['description'] ]);
            $resulf['success']= true;
            echo \GuzzleHttp\json_encode($resulf);
        }

        // delete media
        if(isset($request['action']) && $request['action']=='delete_media'){
            $resulf=[];
            DB::table('media')->where('id',$request['id'])->delete();
            $resulf['success']= delete_file_media($request['path']);
            echo \GuzzleHttp\json_encode($resulf);
        }


        // get media
        if(isset($request['action']) && $request['action']=='get_medias'){
            $page = $request['page']?$request['page']:1;
            $resulf =  Media::get_media($request['type'],$page);
            $html = display_media_modal($resulf);
            echo $html;
        }


        // delete category by id
        if(isset($request['action']) && $request['action']=='delete_category'){
            $resulf=[];
            $data['id'] = $request['id'];
            Product::remove_category($data);
            $resulf['success']= true;
            echo \GuzzleHttp\json_encode($resulf);
        }
        /// delete many category
        if(isset($request['action']) && $request['action']=='apply_delete_category'){
            $resulf=[];
            foreach ($request['reviews'] as $id){
                $data['id'] = $id;
                Product::remove_category($data);
            }
            $resulf['success']= true;
            echo \GuzzleHttp\json_encode($resulf);
        }

        // delete attribute by id
        if(isset($request['action']) && $request['action']=='delete_attribute'){
            $resulf=[];
            DB::table('product_attributes')
                ->where(function ($query) use ($request) {
                $query->where('id', '=', $request['id'])
                    ->orWhere('parent_id', '=', $request['id']);
                    })
                ->delete();

            DB::table('relationships')
                ->where('type','product_attribute_'.$request['id'])
                ->delete();
            $resulf['success']= true;
            echo \GuzzleHttp\json_encode($resulf);
        }


        // update category
        if(isset($request['action']) && $request['action']=='update_product_category'){
            foreach ($request['data'] as $value) {
                $data[$value['name']] = $value['value'];
            }
            if($data['feature_thumbnail']){
                $user_id = Auth::id();
                Options::update_option($user_id,'product_categories_thumbnail_'.$data['id'],$data['feature_thumbnail']);
            }
           unset($data['feature_thumbnail']);
            $data['updated_at'] = date('Y-m-d H:i:s');
            DB::table('product_categories')->where('id',$data['id'])->update($data);
            $data['success']= true;
            echo \GuzzleHttp\json_encode($data);
        }

        // delete story by id
        if(isset($request['action']) && $request['action']=='detele_product'){
            $resulf=[];
            DB::table('products')->where('id',$request['id'])->delete();
            $resulf['redirect']= route('admin.products');
            echo \GuzzleHttp\json_encode($resulf);
        }

        // delete story by id
        if(isset($request['action']) && $request['action']=='detele_order'){
            $resulf=[];
            DB::table('product_orders')->where('id',$request['id'])->delete();
            $resulf['redirect']= route('admin.orders');
            echo \GuzzleHttp\json_encode($resulf);
        }


        // update contact form
        if(isset($request['action']) && $request['action']=='update_data_ctf'){
            $resulf=[];
            foreach ($request['data'] as $value) {
                $data[$value['name']] = $value['value'];
            }
            unset($data['action']);
            unset($data['files']);
            $user_id = Auth::id();
            $meta_key = 'option_ctf_'.$data['option_ctf'];
            $meta_value = \GuzzleHttp\json_encode($data);
            Options::update_option($user_id,$meta_key,$meta_value);
            $resulf['success']= route('admin.contact-form');
            $resulf['data']= $data;
            echo \GuzzleHttp\json_encode($resulf);
        }

        // add data form
        if(isset($request['action']) && $request['action']=='add_data_form_ctf'){
            DataForm::add_data_form($request);
        }

        // update data form
        if(isset($request['action']) && $request['action']=='update_data_form_ctf'){
            DataForm::update_data_form($request);
        }

        // delete data form customer by id
        if(isset($request['action']) && $request['action']=='detele_data_form'){
            $resulf=[];
            DB::table('data_form')->where('id',$request['id'])->delete();
            $resulf['redirect']= route('admin.data-form');
            echo \GuzzleHttp\json_encode($resulf);
        }


        // update contact form
        if(isset($request['action']) && $request['action']=='update_system_settings'){
            $resulf=[];
            foreach ($request['data'] as $value) {
                $data[$value['name']] = $value['value'];
            }
            unset($data['action']);
            $user_id = Auth::id();
            foreach ($data as $meta_key => $meta_value){
                if($meta_value)Options::update_option($user_id,$meta_key,$meta_value);
            }

            $resulf['success']= route('admin.settings');
            $resulf['data']= $data;
            echo \GuzzleHttp\json_encode($resulf);
        }


        // add commment story
        if(isset($request['action']) && $request['action']=='add_comment_post'){
            $resulf = [];
            $data = $request['data'];
            $id = Posts::add_post_comment($data);
            if($id)$resulf['success'] = 'successfully';
           echo  \GuzzleHttp\json_encode($resulf);

        }

        // get all user meta
        if(isset($request['action']) && $request['action']=='get_all_user_meta'){
            $resulf = [];
            $data = $request['data'];
            $user_id = Auth::id();
            foreach ($data as $key=>$value){
                $data[$key]['value'] = get_user_meta($user_id,$value['name']);
            }
            echo  \GuzzleHttp\json_encode($data);

        }

        // update account
        if(isset($request['action']) && $request['action']=='update_account'){
            $resulf = [];
            $data = $request['data'];
            $user_id = Auth::id();
            $data_user = [];
            foreach ($data as $key=>$value){
                if($value['type'] == 'meta')update_user_meta($user_id,$value['name'],$value['value']);
                if($value['type'] == 'main')$data_user[$value['name']] = $value['value'];
            }
            if(isset($data_user['password']))$data_user['password'] = bcrypt($data_user['password']);
            if($data_user)User::update_user($user_id,$data_user);
            $resulf['success'] = 'successfully';
            echo  \GuzzleHttp\json_encode($resulf);

        }


        // add to cart
        if(isset($request['action']) && $request['action']=='add_to_cart'){
            $resulf = [];
            $data['products'] = $request['data'];
            $user = Auth::check();
            if($user)$data['user'] = Auth::user();
            session()->put('cart', $data);

            $resulf['success'] = 'successfully';
            echo  \GuzzleHttp\json_encode($resulf);

        }

        // add menu
        if(isset($request['action']) && $request['action']=='add_menu'){
            $resulf = [];
            $data['name']  = $request['data']['name'];
           if($data['name']){
               $data['data_menu'] = '';
               $data['location'] = '';
               $data['created_at'] = date('Y-m-d H:i:s');
               $data['updated_at'] = date('Y-m-d H:i:s');
               Menu::add_menu($data);
               $resulf['success'] = 'successfully';
           }else{
               $resulf['success'] = 'Invalid';
           }
            echo  \GuzzleHttp\json_encode($resulf);
        }

        // delete menu by id
        if(isset($request['action']) && $request['action']=='delete_menu'){
            $resulf=[];
            Menu::DeleteMenu($request['id']);
            $resulf['success'] = 'successfully';
            echo \GuzzleHttp\json_encode($resulf);
        }

        // save menu by id
        if(isset($request['action']) && $request['action']=='save_menu'){
            $resulf=[];
            $data['id'] = $request['id'];
            $data['data_menu'] = \GuzzleHttp\json_encode($request['data_menu']);
            $data['name'] = $request['name_menu'];
            $data['location'] = $request['location_menu'];
            $data['updated_at'] = date('Y-m-d H:i:s');
            Menu::UpdateMenu($data);
            $resulf['success'] = 'successfully';
            echo \GuzzleHttp\json_encode($resulf);
        }
        /// save attribute variation in product
        if(isset($request['action']) && $request['action']=='save_product_attributes'){
            $product_id = $request['id'];
            $all_attribute = $request['data'];
            Product::update_meta_product($product_id,'product_type',$all_attribute['product_type']);
            if( isset($all_attribute['optional']) )Product::update_meta_product($product_id,'product_variations',\GuzzleHttp\json_encode($all_attribute['optional']));
            if(isset($all_attribute['attributes'])){
                Product::update_meta_product($product_id,'all_attributes', \GuzzleHttp\json_encode($all_attribute['attributes']) ) ;
                $attributes = [];
                Relationships::delete_relationship($product_id,'product_attribute');
                foreach ($all_attribute['attributes'] as $key=>$value){
                    $attributes[] = $key;
                    if($value['value']){
                        foreach ($value['value'] as $item){
                            Relationships::insert_relationships_attr($product_id,$item['value'],'product_attribute_');
                        }
                    }
                }
                Product::update_meta_product($product_id,'attributes', \GuzzleHttp\json_encode($attributes) ) ;
            }
            $resulf['success'] = 'successfully';
            echo \GuzzleHttp\json_encode($resulf);
        }

        // filter product ajax start_filter_product
        if(isset($request['action']) && $request['action']=='filter_product_page'){
            $resulf= ['data'=>'','pagition'=>''];
            $colume = $request['colume']?$request['colume']:'item-product col-md-4';
            foreach ($request['data'] as $value) {
                $data[$value['name']] = $value['value'];
            }
            $data['product'] = $request['product'];
            $data['product_attribute'] = $request['attribute'];
            $data['sort'] = $request['sort'];
            $data['slug'] = $request['slug'];
            $filter_products = getProductFilterPage( $data);
            $resulf['pagition'] = DisplayPagition($filter_products['pagition']);
            $resulf['total'] = format_currency($filter_products['pagition']['total']).' Resulfs';

            if($filter_products['data']){
                foreach($filter_products['data'] as $key=>$item){
                    $resulf['data'] .= '<div class="'.$colume.'" Data-Item-Product> <div class="item-content bg-white">'.showItemProduct($item).'</div></div>';
                }
            }else{
                $resulf['data'] = '<div class="content col-md-12"><div class="alert alert-primary" role="alert">Not found product!</div></div>';
            }
            echo ($resulf)?json_encode($resulf):'';
        }



        // add to cart
        if(isset($request['action']) && $request['action']=='add_to_order'){
            $resulf = [];
            foreach ($request['data'] as $value) {
                $data[$value['name']] = $value['value'];
            }
            foreach ($data['payment'] as $value) {
                $payment[$value['name']] = $value['value'];
            }
            $order_data = array(
                'payment_type'=>$data['payment_type'],
                'payment_status'=>3,
                'status'=>2,
                'tax'=>$data['total']['tax'],
                'shipping'=>$data['total']['shipping'],
                'total'=>$data['total']['total'],
                'created_at'=>date('Y-m-d H:i:s'),
                'updated_at'=>date('Y-m-d H:i:s'),
            );
            if(Auth::check())$order_data['user_id'] = Auth::id();
            if(isset($payment['notes']))$order_data['note'] = $payment['notes'];
            $address = $payment['address']." \r ";
            $address .= $payment['city'].', '.$payment['state'].', '.$payment['zipcode']." \r ";
            $address .= $payment['email'].', '.$payment['phone'];

            $order_id =  DB::table('product_orders')->insertGetId($order_data);
            if($order_id){
                Orders::update_meta_product_order($order_id,'products',  json_encode($data['products']));
                Orders::update_meta_product_order($order_id,'customer_bill',  $payment['name']);
                Orders::update_meta_product_order($order_id,'billing_address',  $address);
                $shipping_name = (($payment['same_bill']) == 'false')?$payment['shipping_first_name'].' '.$payment['shipping_last_name']:$payment['name'];
                $shipping_address =  $address;
               if(($payment['same_bill']) == 'false'){
                   $shipping_address =    $payment['shipping_address_1']." \r ";
                   $shipping_address .= $payment['shipping_city'].','.$payment['shipping_state'].','.$payment['shipping_zipcode']." \r ";
                   $shipping_address .= $payment['shipping_email'].','.$payment['shipping_phone'];
                }
                Orders::update_meta_product_order($order_id,'customer_shipping', $shipping_name );
                Orders::update_meta_product_order($order_id,'shipping_address', $shipping_address );
                if(isset($data['order']))Orders::update_meta_product_order($order_id,'orders',  json_encode($data['order']));
                Orders::update_meta_product_order($order_id,'payment',  json_encode($payment));
                Orders::update_meta_product_order($order_id,'total',  json_encode($data['total']));
                $resulf['success'] = url('/order/'.$order_id);
                session()->forget('cart');
            }



            echo  \GuzzleHttp\json_encode($resulf);

        }

        //Search product ajax
        if(isset($request['action']) && $request['action']=='search_product_ajax'){
            $query = ['search'=>$request['search']];
            $query['cat'] = list_ob_to_array(get_categories_bytype($request['cat']));
            $products = Product::get_search_products($query);
            return display_resulf_ajax_search($products);
        }

        //insert product from excel ajax
        if(isset($request['action']) && $request['action']=='insert_product_excel'){
            $resulf = [];
            $resulf['sku'] = $request['data']['sku'];
            $resulf['product_id'] = Product::add_product_excel($request['data']);
            if( isset($resulf['product_id']) ){
                $resulf['success'] = 'Successfully';
                return  \GuzzleHttp\json_encode($resulf);
            }else{
                $resulf['error'] = $request['_token'];
                throw new \GuzzleHttp\json_encode($resulf);
            }


        }


        // add review product
        if(isset($request['action']) && $request['action']=='add_data_review'){
            $resulf = [];
            $user = Auth::user();
            $data['object_id'] = $request['product'];
            $data['rating'] = $request['review'];
            $data['content'] = $request['description'];
            $data['title'] = $request['title'];
            $data['user_id'] = $user->id;
            $data['type'] = 'product';
           Reviews::add_review($data);
           $resulf['success'] = 'Successfully';
           return  \GuzzleHttp\json_encode($resulf);

        }

        /// Get reviews product
        if(isset($request['action']) && $request['action']=='get_data_reviews_product'){
            $resulf = ['html_reviews'=>'','html_pagition'=>''];
            $query = [];
            $query['type'] = $request['type'];
            $query['status'] = $request['status'];
            $query['object_id'] = $request['object_id'];
            $query['rating'] = $request['rating'];
            $query['page'] = $request['page'];
            $reviews = Reviews::get_reviews($query);
            $pagition = [
                'total'=> $reviews->total(),
                'perPage'=> $reviews->perPage(),
                'currentPage'=> $reviews->currentPage(),
            ];
            if(isset($reviews))foreach ($reviews as $item_review){
                $resulf['html_reviews'] .= display_item_review($item_review);
            }
            $resulf['html_pagition'] = DisplayPagitionReview($pagition);
            return  \GuzzleHttp\json_encode($resulf);
        }

        //// apply_reviews_action
        if(isset($request['action']) && $request['action']=='apply_reviews_action'){
            $resulf = [];
            $action = $request['apply'];
            $reviews = $request['reviews'];
            foreach ($reviews as $review){
                    if($action == 'delete') {
                        $data = [];
                        $data['status'] = 5;
                        Reviews::update_review($review, $data);
                    }

                    if($action == 'active') {
                    $data = [];
                    $data['status'] = 2;
                    Reviews::update_review($review, $data);
                    }
             }
            $resulf['reviews'] = $reviews;
            $resulf['success'] = 'Successfully';
            return \GuzzleHttp\json_encode($resulf);


        }

        //// apply_reviews_action
        if(isset($request['action']) && $request['action']=='apply_product_action'){
            $resulf = [];
            $action = $request['apply'];
            $reviews = $request['reviews'];
            foreach ($reviews as $review){
                if($action == 'trash') {
                    $data = [];
                    $data['status'] = 4;
                    Product::update_product($review, $data);
                }

                if($action == 'publish') {
                    $data = [];
                    $data['status'] = 2;
                    Product::update_product($review, $data);
                }

                if($action == 'draft') {
                    $data = [];
                    $data['status'] = 1;
                    Product::update_product($review, $data);
                }
            }
            $resulf['reviews'] = $reviews;
            $resulf['success'] = 'Successfully';
            return \GuzzleHttp\json_encode($resulf);


        }

        //// send data question
        if(isset($request['action']) && $request['action']=='send_data_question'){
            $resulf = [];
            $data['object_id'] = $request['object_id'];
            $data['user_id'] = Auth::id();
            $data['type'] = 'product';
            $data['parent_id'] = ($request['parent_id'])?$request['parent_id']:null;
            $data['content'] = $request['content'];
            if(check_question($data['object_id'])){
                Reviews::add_question($data);
                $resulf['success'] = 'Successfully';
            }else{
                $resulf['error'] = 'You can only ask questions once a day.';
            }
            return \GuzzleHttp\json_encode($resulf);

        }

        //// apply_reviews_action
        if(isset($request['action']) && $request['action']=='apply_questions_action'){
            $resulf = [];
            $action = $request['apply'];
            $reviews = $request['reviews'];
            foreach ($reviews as $review){
                if($action == 'delete') {
                    $data = [];
                    $data['status'] = 5;
                    Reviews::update_question($review, $data);
                }

                if($action == 'active') {
                    $data = [];
                    $data['status'] = 2;
                    Reviews::update_question($review, $data);
                }
            }
            $resulf['reviews'] = $reviews;
            $resulf['success'] = 'Successfully';
            return \GuzzleHttp\json_encode($resulf);
        }


        // get reply questions
        if(isset($request['action']) && $request['action']=='get_reply_question'){
            $reply = get_question_reply( $request['parent_id'] );
            return (isset($reply))?\GuzzleHttp\json_encode($reply):\GuzzleHttp\json_encode(['content'=>'']);
        }

        /// add reply question
        if(isset($request['action']) && $request['action']=='save_reply_question'){
            $resulf = [];
            $data['object_id'] = $request['object_id'];
            $data['type'] = 'product';
            $data['parent_id'] = $request['parent_id'];
            $data['content'] = $request['content'];
            if($request['question'] != 'add_new'){
                Reviews::update_question( $request['question'], $data);
            }else{
                $data['user_id'] = Auth::id();
                Reviews::add_question($data);
            }
            $resulf['success'] = 'Successfully';
            return \GuzzleHttp\json_encode($resulf);
        }

        if(isset($request['action']) && $request['action'] =='display_top_product_sales'){
            return display_top_product_sales($request['time'],$request['type']);
        }

        //import_categories
        if(isset($request['action']) && $request['action'] =='import_categories'){
            $file = $request['import_categories'];
            $data = [];
            if($xlsx = SimpleXLSX::parse( $file )){
                foreach ( $xlsx->rows() as $r => $row ) {
                    if($r > 0){
                        foreach ( $row as $c => $cell ) {
                            $data[$r][] = format_text_cell($cell);
                        }
                    }

                }

                foreach ($data as $row){
                  if($row[0])$parent_id = Product::check_categories($row[0]);
                    if ( isset($parent_id) ) {
                        $child =  Product::check_categories($row[1]);
                        $update['parent_id'] = $parent_id;
                        DB::table('product_categories')->where('id',$child)->update($update);
                    }

                }
            }
            return \GuzzleHttp\json_encode($data);
        }



        //// apply category product
        if(isset($request['action']) && $request['action']=='apply_category_action'){
            $resulf = [];
            $action = $request['apply'];
            $reviews = $request['reviews'];
            foreach ($reviews as $review){
                $data['product_department'] = $action;
                Product::update_category($review,$data);
            }
            $resulf['reviews'] = $reviews;
            $resulf['success'] = 'Successfully';
            return \GuzzleHttp\json_encode($resulf);
        }

        if(isset($request['action']) && $request['action']=='apply_remove_trash_product'){
            Product::remove_products_trash();
        }

        //import_attribute
        if(isset($request['action']) && $request['action'] =='import_attributes'){
            $file = $request['import_attributes'];
            $data = [];
            $header = [];
            if($xlsx = SimpleXLSX::parse( $file )){
                foreach ( $xlsx->rows() as $r => $row ) {
                    if($r==0)$header = $row;
                    if($r > 0){
                        foreach ( $row as $c => $cell ) {
                            $data[$r][ $header[$c] ] = format_text_cell($cell);
                        }
                        $data[$r]['slug'] = check_field_table( $row[0], 'slug', 'product_attributes');
                        $data[$r]['parent_id'] = $request['parent_id'];
                        $data[$r]['type'] = $request['type'];
                    }

                }

                foreach ($data as $row){
                   if($row['name'])Product::check_attributes($row);

                }


            }
            return \GuzzleHttp\json_encode($data);
        }

        //insert attribute
        if(isset($request['action']) && $request['action'] =='insert_attributes'){
            $row = (array)\GuzzleHttp\json_decode($request['data']);
            if($row['name'])Product::check_attributes($row);
            return \GuzzleHttp\json_encode($row);
        }



        // apply_save_list_product
        if(isset($request['action']) && $request['action']=='apply_save_list_product'){
            $resulf = [];
            $products = $request['products'];
            $form['status'] = $request['form']['status'];
            $form['product_category'] = !empty($request['form']['product_category'])?$request['form']['product_category']:[];
            foreach ($products as $product){
                if($form['status']){
                    Product::update_product($product,array('status'=>$form['status']));
                }
                if( count($form['product_category']) ){
                    Relationships::delete_relationship($product,'product_category');
                    foreach ($form['product_category'] as $category){
                        Relationships::save_relationships($product, $category, 'product_category_');
                    }
                }
            }
            $resulf['products'] = $products;
            $resulf['form'] = $form;
            $resulf['success'] = 'Successfully';
            return \GuzzleHttp\json_encode($resulf);
        }



        //Search product ajax
        if(isset($request['action']) && $request['action']=='insert_filter_product_ajax'){
            $resulf = [];
            $query = $request['query'];
            $resulf['html']= display_filter_product_html($query);
           return json_encode($resulf);
        }








      /// end function action
    }
}
