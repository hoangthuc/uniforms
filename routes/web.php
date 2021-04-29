<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('frontend.frontpage',['slug'=>'/']);
});
Auth::routes();
Route::get('login', function(){
    if(Auth::check())return redirect('/');
    return view('auth.login',['slug'=>'login']);
})->name('login');
Route::get('register', function(){
    if(Auth::check())return redirect('/');
    return view('auth.register',['slug'=>'register']);
})->name('register');
Route::get('/admin', function(){
    return view('admin.dashboard');
})->name('admin')->middleware('auth')->middleware(['role:administrator']);
Route::get('/home', function(){
    return view('home');
});
Route::get('/forgot-password', function () {
    return view('auth.passwords.email');
})->middleware('guest')->name('password.request');
//Login
Route::post('loginsite','HomeController@check');

//Register
Route::post('register','HomeController@register');

//Group admin
Route::group(['prefix' => 'admin','as'=>'admin.','middleware' => ['auth','role:administrator']], function () {

//    Display users
    Route::get('users', function () {
        $roles = App\User::get_roles();
        return view('admin.users.users',['roles'=>$roles]);
    })->name('users');

//    Display profile user
    Route::get('profile', function () {
        $roles = App\User::get_roles();
        return view('admin.users.profile',['roles'=>$roles]);
    })->name('profile');

    //    Display profile user other
    Route::get('user/{id}/view', function ($id) {
        $roles = App\User::get_roles();
        $user = App\User::getUserByID($id);
        return view('admin.users.user_view',['user'=>$user,'roles'=>$roles]);
    })->name('user_view');

    //    Edit profile user other
    Route::get('user/{id}/edit', function ($id) {
        return view('admin.users.user_edit',['user_id'=>$id]);
    })->name('user_edit');
// change passord
    Route::post('change_password', 'auth\UpdateUser@ChangePassword');
    // update field main user
    Route::post('update_userinfo', 'auth\UpdateUser@UpdateUserInfo');
    // ajax delete user
    Route::post('detele_user', 'auth\UpdateUser@DeleteUser');

    // Posts
    Route::get('posts', function () {
        return view('admin.posts.posts');
    })->name('posts');
    // add new story
    Route::get('add-post', function () {
        return view('admin.posts.addnew_post');
    })->name('add-post');

    // edit story
    Route::get('post/{id}/edit', function ($id) {
        return view('admin.posts.edit_post',['post_id'=>$id]);
    })->name('edit_post');
    // add story
    Route::post('add_post', 'ControllerPosts@add_post');
    //save story
    Route::post('save_post', 'ControllerPosts@save_post');

    // ajax story & all
    Route::post('admin_ajax', 'ControllerAjax@admin_ajax');

    // Media
    Route::get('media', function () {
        return view('admin.media.media');
    })->name('media');

    Route::get('media/list', function () {
        return view('admin.media.media_list');
    })->name('media_list');

    // upload file
    Route::post('upload','ControllerMedia@doUpload');

    // upload file import
    Route::post('upload_import','ControllerMedia@UploadFileImport');

    //products
    Route::get('products', function () {
        return view('admin.products.products');
    })->name('products');

    // product categories
    Route::get('product_categories', function () {
        return view('admin.products.product_categories');
    })->name('product_categories');
    // add product categories
    Route::POST('add_product_category', 'ControllerProduct@add_product_category');


    // product attributes
    Route::get('product_attributes', function () {
        return view('admin.products.product_attributes');
    })->name('product_attributes');
    // product attributes detail
    Route::get('product_attribute/{attribute_id}', function ($attribute_id) {
        return view('admin.products.detail_attribute', ['attribute_id'=>$attribute_id]);
    })->name('detail_attribute');
    // add product attributes
    Route::POST('add_product_attribute', 'ControllerProduct@add_product_attribute');
    Route::POST('update_product_attribute', 'ControllerProduct@update_product_attribute');

    // add new product
    Route::get('add-product', function () {
        return view('admin.products.add-product');
    })->name('add-product');

    Route::post('add_product','ControllerProduct@add_product');

    // edit product
    Route::get('product/{id}/edit', function ($id) {
        return view('admin.products.edit-product',['product_id'=>$id]);
    })->name('edit-product');

    Route::post('edit_product','ControllerProduct@save_product');

    // Import Products
    Route::get('import_products', function () {
        return view('admin.products.import_products');
    })->name('import_products');

    // Import Products
    Route::get('reviews', function () {
        return view('admin.products.reviews');
    })->name('reviews');

    // Import Products
    Route::get('questions', function () {
        return view('admin.products.questions');
    })->name('questions');

    //Orders
    Route::get('orders', function () {
        return view('admin.order.orders');
    })->name('orders');

    // view order
    Route::get('order/{id}/view', function ($id) {
        return view('admin.order.view-order',['order_id'=>$id]);
    })->name('view-order');

    // view order template
    Route::get('order/{id}/view-template', 'ControllerOrders@email_template');

    // edit new orders
    Route::get('order/{id}/edit', function ($id) {
        return view('admin.order.edit-order',['order_id'=>$id]);
    })->name('edit-order');
    // edit order
    Route::post('edit_order','ControllerOrders@save_order');

    // FAQ setting
    Route::get('faq', function () {
        return view('admin.widgets.faq');
    })->name('faq');
    // update FAQ
    Route::post('save_faq','ControllerOptions@save_faq');

    // Contact Form
    Route::get('contact-form', function () {
        return view('admin.widgets.contact-form');
    })->name('contact-form');

    Route::get('contact-form/{slug}', function ($slug) {
        return view('admin.widgets.ctf-detail',['slug'=>$slug]);
    })->name('contact-form-detail');


    // Data Form
    Route::get('data-form', function () {
        return view('admin.maketing.data-form');
    })->name('data-form');

    // add data form
    Route::get('add-data-form', function () {
        return view('admin.maketing.add_data-form');
    })->name('add-data-form');

    // edit data form
    Route::get('data-form/{id}/edit', function ($id) {
        return view('admin.maketing.edit_data-form',['id'=>$id]);
    })->name('edit-data-form');



    // System Settings
    Route::get('settings', function () {
        return view('admin.system-settings');
    })->name('settings');

    // menu
    Route::get('menu', function () {
        return view('admin.menu.list-menu');
    })->name('menu');

    // menu
    Route::get('menu/{id}', function ($id) {
        return view('admin.menu.detail-menu',['id'=>$id]);
    })->name('menu_detail');


    // Analytics
    Route::get('analytics', function () {
        return view('admin.maketing.analytics');
    })->name('analytics');


});


// main pages
// Stories page
Route::get('/posts', function(){
    return view('frontend.posts',['slug'=>'posts']);
});

// Story detail
Route::get('/post/{slug}', function($slug){
    return view('frontend.single-post',['slug'=>$slug]);
});

//Add story comments
Route::post('/post/{slug}','ControllerStories@add_post_comment');

// Media page
Route::get('/media', function(){
    return view('frontend.media',['slug'=>'media']);
});

// FAQ page
Route::get('/faq', function(){
    return view('frontend.faq',['slug'=>'faq']);
});

// About page
Route::get('/about', function(){
    return view('frontend.about',['slug'=>'about']);
});

// Contact us page
Route::get('/contact-us', function(){
    return view('frontend.contact_us',['slug'=>'contact-us']);
})->name('contact-us');

// Shop page
Route::get('/products', function(){
    return view('frontend.shop',['slug'=>'products']);
})->name('shops');

// Story detail
Route::get('/product/{slug}',['uses'=> 'ControllerProduct@single_product']);

// Categories detail
Route::get('/product_categories/{slug}', function($slug){
    return view('frontend.product_categories',['slug'=>$slug]);
});

// Brandname detail
Route::get('/attributes/{brand}/{slug}',['uses'=> 'ControllerProduct@single_brand']);


// Get quick view in list product
Route::post('/get_quick_view',['uses'=> 'ControllerProduct@quick_view_product']);


// My account
Route::get('/my-account', function(){
    return view('frontend.account',['slug'=>'my-account']);
})->name('my-account')->middleware('auth');

// ajax not admin all
Route::post('nopriv_ajax', 'ControllerAjax@admin_ajax')->name('nopriv_ajax');
Route::post('nopriv_upload','ControllerMedia@doUpload')->name('nopriv_ajax')->middleware('auth');


// My cart
Route::get('/cart', function(){
    return view('frontend.cart',['slug'=>'cart']);
})->name('cart');

// Checkout
Route::get('/checkout', function(){
    $cart = Request::session()->get('cart');
    if(!$cart)return redirect('/products');
    return view('frontend.checkout',['slug'=>'checkout']);
})->name('checkout');

// Order
Route::get('/order/{id}', function($id){
    $check  = md5($id);
    $order_key  = isset($_GET['order_key'])?$_GET['order_key']:md5('00');
    if($check == $order_key)return view('frontend.order_detail',['slug'=>'order','order_id'=>$id]);
    return redirect('/products');
})->name('order_detail');
