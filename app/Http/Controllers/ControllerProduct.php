<?php

namespace App\Http\Controllers;

use App\Options;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Relationships;
use App\Product;

class ControllerProduct extends Controller
{
    public function add_product_category(Request $request)
    {
        if ($request['data']) {
            foreach ($request['data'] as $value) {
                $data[$value['name']] = $value['value'];
            }
            $data['slug'] = check_field_table($data['name'], 'slug', 'product_categories');
            $data['created_at'] = date('Y-m-d H:i:s');
            $data['updated_at'] = date('Y-m-d H:i:s');
            $feature_thumbnail = $data['feature_thumbnail'];
            unset($data['_token']);
            unset($data['feature_thumbnail']);
            $data['id'] = DB::table('product_categories')->insertGetId($data);
            if ($data['parent_id']) {
                $categories = (array)Product::get_product_categories_detail($data['parent_id']);
                DB::table('product_categories')->where('id', $data['id'])->update($data);
            }
            if($feature_thumbnail){
                $user_id = Auth::id();
                Options::update_option($user_id,'product_categories_thumbnail_'.$data['id'],$feature_thumbnail);
            }
            $data['success'] = 'successfully';
            echo \GuzzleHttp\json_encode($data);
        }

    }

    // add attributes
    public function add_product_attribute(Request $request)
    {
        if ($request['data']) {
            foreach ($request['data'] as $value) {
                $data[$value['name']] = $value['value'];
            }
            $data['slug'] = check_field_table($data['name'], 'slug', 'product_attributes');
            $data['created_at'] = date('Y-m-d H:i:s');
            $data['updated_at'] = date('Y-m-d H:i:s');
            unset($data['_token']);
            $data['id'] = DB::table('product_attributes')->insertGetId($data);
            $data['success'] = 'successfully';
            echo \GuzzleHttp\json_encode($data);
        }

    }

    // add attributes
    public function update_product_attribute(Request $request)
    {
        if ($request['data']) {
            foreach ($request['data'] as $value) {
                $data[$value['name']] = $value['value'];
            }
           /// $data['slug'] = check_field_table($data['name'], 'slug', 'product_attributes');
            $data['created_at'] = date('Y-m-d H:i:s');
            $data['updated_at'] = date('Y-m-d H:i:s');
            unset($data['_token']);
            Product::update_attribute($data);
            $data['success'] = 'successfully';
            echo \GuzzleHttp\json_encode($data);
        }

    }


    public function add_product(Request $request)
    {
        $resulf = [];
        if ($request['data']) {
            foreach ($request['data'] as $value) {
                if ($value['name'] == 'additional_information' && empty($value['value'])) $value['value'] = [];
                if ($value['name'] == 'product_category' && empty($value['value'])) $value['value'] = [];
                $data[$value['name']] = $value['value'];
            }
            $data['slug'] = check_field_table($data['name'], 'slug', 'products');
            // $data['status'] = 0;
            $data['created_at'] = date('Y-m-d H:i:s');
            $data['updated_at'] = date('Y-m-d H:i:s');
            $data['featured_image'] = (isset($data['button_featured_image'])) ? $data['button_featured_image'] : null;
            if (isset($data['product_category'])) {
                $category = $data['product_category'];
                unset($data['product_category']);
            }
            $price = (isset($data['price'])) ? $data['price'] : null;
            $sku = (isset($data['price'])) ? $data['sku'] : null;
            $gallery = (isset($data['button_gallery'])) ? \GuzzleHttp\json_encode($data['button_gallery']) : null;
            $additional_information = (isset($data['additional_information'])) ? \GuzzleHttp\json_encode($data['additional_information']) : null;
            unset($data['action']);
            unset($data['files']);
            unset($data['_token']);
            unset($data['button_featured_image']);
            unset($data['price']);
            unset($data['sku']);
            unset($data['button_gallery']);
            unset($data['additional_information']);
            unset($data['default_variant']);

            $product_id = DB::table('products')->insertGetId($data);
            // save category
            if (isset($category)) {
                foreach ($category as $item) {
                    Relationships::save_relationships($product_id, $item, 'product_category_');
                }
            }

            if ($price) Product::update_meta_product($product_id, 'price', $price);
            if ($sku) Product::update_meta_product($product_id, 'sku', $sku);
            if ($gallery) Product::update_meta_product($product_id, 'gallery', $gallery);
            if ($additional_information) Product::update_meta_product($product_id, 'additional_information', $additional_information);

            // save attributes
            if ($product_id) {
                $all_attribute = $request['all_attributes'];
                if (isset($all_attribute['attributes'])) {
                    Product::update_meta_product($product_id, 'all_attributes', \GuzzleHttp\json_encode($all_attribute['attributes']));
                    $attributes = [];
                    Relationships::delete_relationship($product_id, 'product_attribute');
                    foreach ($all_attribute['attributes'] as $key => $value) {
                        $attributes[] = $key;
                        if ($value['value']) {
                            foreach ($value['value'] as $item) {
                                Relationships::insert_relationships_attr($product_id, $item['value'], 'product_attribute_');
                            }
                        }
                    }
                    Product::update_meta_product($product_id, 'attributes', \GuzzleHttp\json_encode($attributes));
                }
                Product::update_meta_product($product_id, 'price_attribute', \GuzzleHttp\json_encode($request['price_attribute']));
                Product::update_meta_product($product_id, 'default_attribute', \GuzzleHttp\json_encode($request['default_attribute']));
                Product::update_meta_product($product_id, 'thumbnail_color', \GuzzleHttp\json_encode($request['thumbnail_color']));
                Product::update_meta_product($product_id, 'thumbnail_attribute', \GuzzleHttp\json_encode($request['thumbnail_attribute']));
            }
            // end save attributes

            $resulf['redirect'] = url('admin/product/' . $product_id . '/edit');
        }

        echo \GuzzleHttp\json_encode($resulf);
    }

    public function save_product(Request $request)
    {
        $resulf = [];
        if ($request['data']) {
            foreach ($request['data'] as $value) {
                if ($value['name'] == 'additional_information' && empty($value['value'])) $value['value'] = [];
                if ($value['name'] == 'product_category' && empty($value['value'])) $value['value'] = [];
                $data[$value['name']] = $value['value'];
            }
            //apply for new product
            $data['updated_at'] = date('Y-m-d H:i:s');
            $data['featured_image'] = (isset($data['button_featured_image'])) ? $data['button_featured_image'] : null;
            $gallery = (isset($data['button_gallery'])) ? \GuzzleHttp\json_encode($data['button_gallery']) : null;
            $price = (isset($data['price'])) ? $data['price'] : null;
            $sku = (isset($data['sku'])) ? $data['sku'] : null;
            $additional_information = (isset($data['additional_information'])) ? \GuzzleHttp\json_encode($data['additional_information']) : null;
            unset($data['action']);
            unset($data['files']);
            unset($data['_token']);
            unset($data['button_featured_image']);
            unset($data['price']);
            unset($data['sku']);
            unset($data['button_gallery']);
            unset($data['additional_information']);
            unset($data['default_variant']);
            // save category
            if (isset($data['product_category'])) {
                Relationships::delete_relationship($data['id'],'product_category');
                foreach ($data['product_category'] as $item) {
                    Relationships::save_relationships($data['id'], $item, 'product_category_');
                }

                unset($data['product_category']);
            }
            // save attribute
            if ($price) Product::update_meta_product($data['id'], 'price', $price);
            if ($sku) Product::update_meta_product($data['id'], 'sku', $sku);
            if ($gallery) Product::update_meta_product($data['id'], 'gallery', $gallery);
            if ($additional_information) Product::update_meta_product($data['id'], 'additional_information', $additional_information);

            DB::table('products')->where('id', $data['id'])->update($data);

            // save attributes
            if ($data['id']) {
                $product_id = $data['id'];
                $all_attribute = $request['all_attributes'];
                if (isset($all_attribute['attributes'])) {
                    Product::update_meta_product($product_id, 'all_attributes', \GuzzleHttp\json_encode($all_attribute['attributes']));
                    $attributes = [];
                    Relationships::delete_relationship($product_id, 'product_attribute');
                    foreach ($all_attribute['attributes'] as $key => $value) {
                        $attributes[] = $key;
                        if ($value['value']) {
                            foreach ($value['value'] as $item) {
                                Relationships::insert_relationships_attr($product_id, $item['value'], 'product_attribute_');
                            }
                        }
                    }
                    Product::update_meta_product($product_id, 'attributes', \GuzzleHttp\json_encode($attributes));
                }

                Product::update_meta_product($product_id, 'price_attribute', \GuzzleHttp\json_encode($request['price_attribute']));
                Product::update_meta_product($product_id, 'default_attribute', \GuzzleHttp\json_encode($request['default_attribute']));
                Product::update_meta_product($product_id, 'thumbnail_color', \GuzzleHttp\json_encode($request['thumbnail_color']));
                Product::update_meta_product($product_id, 'thumbnail_attribute', \GuzzleHttp\json_encode($request['thumbnail_attribute']));
            }
            // end save attributes

            $resulf['success'] = 'Successfully';
        }

        echo \GuzzleHttp\json_encode($resulf);
    }





}
