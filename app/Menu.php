<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App\Product;

class Menu extends Model
{
    public static $data_location = [
       'header'=> 'Header',
       'footer-1'=> 'Footer 1',
       'footer-2'=> 'Footer 2',
       'mobile_header'=> 'Mobile Header',
       'mobile_footer'=> 'Mobile Footer',
    ];

    public static function get_menus()
    {
        $menus = DB::table('menu')->get();
        return $menus;
    }

    // get menu by id
    public static function get_menu($id)
    {
        $menu = DB::table('menu')->where('id', $id)->first();
        return $menu;
    }

    // get menu by location
    public static function get_menu_by_location($location)
    {
        $menu = DB::table('menu')->where('location', $location)->first();
        return $menu;
    }


    // add menu
    public static function add_menu($data)
    {
        $id =  DB::table('menu')->insertGetId($data);
        return $id;
    }
    // Delete menu
    public static function DeleteMenu($id)
    {
        DB::table('menu')->where('id', $id)->delete();
    }

    // Update menu
    public static function UpdateMenu($data)
    {
        DB::table('menu')->where('id', $data['id'])->update($data);
    }

    public static function get_data_post_type(){
        $categories = Product::get_product_categories();
        $menu_url = [
            ['name'=>'Home','slug'=> url('/'), 'description'=>'Home'],
            ['name'=>'All Post','slug'=> url('posts'), 'description'=>'All Post'],
            ['name'=>'All Products','slug'=> url('products'), 'description'=>'All Product'],
            ['name'=>'About us','slug'=> url('about'), 'description'=>'About us'],
            ['name'=>'My account','slug'=> url('my-account'), 'description'=>'My account'],
            ['name'=>'Contact us','slug'=> url('contact-us'), 'description'=>'Contact us'],
        ];
        if($categories->items()){
            foreach ($categories->items() as $value){
                $menu_url[] = ['name'=>$value->name,'slug'=> url( 'product_categories/'.$value->slug ), 'description'=>$value->name.'(Product categories)' ];
            }
        }

        return $menu_url;
    }
}
