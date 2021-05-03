<?php

use App\Menu;
use App\Options;
use App\Product;
use Illuminate\Support\Facades\Storage;
/******************************************
 * Generate SEO URL...
 ******************************************/
if (!function_exists('generateSEOURL'))
{
    function generateSEOURL($strURL='')
    {
        $arrFind = array(' ', ',', '.', '"', "'", '?', '!');
        $arrReplace = array('-', '', '', '', '', '', '');
        $strURL = strtolower(trim($strURL));
        $strURL = str_replace($arrFind, $arrReplace, $strURL);
        return $strURL;
    }
}

// Illuminate/Support/helpers.php

if (! function_exists('str_slug')) {
    /**
     * Generate a URL friendly "slug" from a given string.
     *
     * @param  string  $title
     * @param  string  $separator
     * @return string
     */
    function str_slug($title, $separator = '-')
    {
        return Str::slug($title, $separator);
    }
}

//Check exist intable unique and create random number
if (! function_exists('check_field_table')) {
    /**
     * Generate a URL friendly "slug" from a given string.
     *
     * @param  string  $title
     * @param  string  $separator
     * @return string
     */
    function check_field_table($title, $colume, $table)
    {
        $slug_r = $slug = str_slug($title,  '-');
        for($i=1;$i<20;$i++){
            $select =  DB::table($table)->where($colume ,$slug_r)->get()->count();
            if($select){
                $slug_r = $slug.'-'.$i;
            }else{
                return $slug_r;
            }
        }


    }
}

//Get data row by slug table
if (! function_exists('get_data_by_slug')) {
    /**
     * Generate a URL friendly "slug" from a given string.
     *
     * @param  string  $title
     * @param  string  $separator
     * @return string
     */
    function get_data_by_slug($slug, $colume, $table)
    {
        $select =  DB::table($table)->where($colume ,$slug)->get();
        return $select;


    }
}

//Get type file media
if (! function_exists('get_name_media')) {
    function get_name_media($path)
    {
        $array = explode('/',$path);
        return end($array);
    }
}

//Get type file media
if (! function_exists('get_type_media')) {
    function get_type_media($type)
    {
        $array = explode('/',$type);
        return $array[0];
    }
}

//Get type file media
if (! function_exists('show_img_media')) {
    function show_img_media($type,$url)
    {
        $type = get_type_media($type);
        if($type == 'image'){
            return $url;
        }
        return url( 'uploads/use/'.$type.'.png');
    }
}

//Select type media
if (! function_exists('select_type_media')) {
    function select_type_media()
    {
        $type_array = array(
            ''=>'All',
            'image'=>'Images',
            'video'=>'Video',
            'audio'=>'Audio',
            'application'=>'Document',
        );
        return $type_array;
    }
}

//get size by path
if (! function_exists('get_size_media')) {
    function get_size_media($file_path)
    {
        if(file_exists($file_path)){
            $file_size =  File::size(public_path($file_path));
            return number_format($file_size / 1048576,2).' MB';
        }
        return false;

    }
}


//delete file media by path
if (! function_exists('delete_file_media')) {
    function delete_file_media($file_path)
    {
        if(file_exists($file_path)){
           File::delete(public_path($file_path));
            return true;
        }
        return false;

    }
}


//Get meta story by meta_key
if (! function_exists('get_meta_post')) {
    function get_meta_post($post_id,$meta_key)
    {
        $meta = App\Posts::get_meta_post($post_id,$meta_key);
        if($meta)return $meta->meta_value;
        return false;

    }
}

//Update meta story by meta_key
if (! function_exists('update_meta_post')) {
    function update_meta_post($post_id,$meta_key,$meta_value)
    {
        $meta = App\Posts::update_meta_post($post_id,$meta_key,$meta_value);
        return $meta;

    }
}

//ADD meta story by meta_key
if (! function_exists('add_meta_post')) {
    function add_meta_post($post_id,$meta_key,$meta_value)
    {
        $meta = App\Posts::add_meta_post($post_id,$meta_key,$meta_value);
        return $meta;

    }
}

// echo content function
if (! function_exists('_e')) {
    function _e($content){echo $content;}
}


//show media ajax then get
if (! function_exists('display_media_modal')) {
    function display_media_modal($medias)
    {
        ob_start();
        ?>
        <!--Show media-->
        <div class="grid-medias filter-container p-0 row">
            <?php if (count($medias) > 0): ?>
            <?php foreach ($medias as $media): $media->ftype = get_type_media($media->type); ?>
            <div class="item-media filtr-item mb-5 col-sm-2">
                <div class="link"  style="background-image: url('<?php _e(show_img_media($media->type,url($media->path))) ?>');"  onClick='<?php _e( "$(this).select_media(`".json_encode($media)."`)" ) ?>' data-ID="<?php _e($media->id) ?>">
                    <?php if( get_type_media($media->type) != 'image'): ?>
                    <span class="name-media d-block"><?php _e(get_name_media( $media->path )) ?></span>
                    <?php endif; ?>
                </div>
            </div>
            <?php endforeach; ?>
            <?php endif; ?>
        </div>
<?php
        $resulf = ob_get_contents();
        ob_end_clean();
        return $resulf;

    }
}

// display category product
if (! function_exists('display_category_product')) {
    function display_category_product($id,$slug='name'){
        $category = App\Relationships::get_relationships($id,'product_category_');
        if($category)return $category->$slug;
        return '';
    }

    function display_list_category_product($id,$slug='name'){
        $categories = App\Relationships::get_relationships($id,'product_category_',2);
        $html = [];
        if($categories){
           foreach ($categories as $category){
               $html[]= '<a href="'.url( 'product_categories/'.$category->slug ).'" target="_blank">'.$category->name.'</a>';
           }

        }
        return implode(', ',$html);

    }
}


// display attribute product
if (! function_exists('display_attribute_product')) {
    function display_attribute_product($id,$name,$re=''){
        $meta_value = App\Product::get_meta_product($id,$name);
        if( isset($meta_value) )return json_decode($meta_value);
        return $re;

    }
}

// display item attribute product
if (! function_exists('display_item_attribute_product')) {
    function display_item_attribute_product($id,$name,$re=''){
        $resulf = [];
        $meta_value = App\Product::get_meta_product($id,'all_attributes');
        if($meta_value)$meta_value =  json_decode($meta_value);
        $meta_value =  (array)$meta_value;
        if(isset($meta_value[$name]->value)){
            foreach ((array)$meta_value[$name]->value as $item){
                $resulf[] = $item->value;
            }
        }
        return $resulf;

    }
}

if (! function_exists('check_item_variation_product')) {
    function check_item_variation_product($id,$name,$re=''){
        $meta_value = App\Product::get_meta_product($id,'all_attributes');
        if($meta_value)$meta_value =  json_decode($meta_value);
        $meta_value =  (array)$meta_value;
        if(isset($meta_value[$name])){
           return $meta_value[$name]->display == 'true'?'checked':'';
        }
        return false;

    }
}

// checl attribute select
// display attribute product
if (! function_exists('check_search_array')) {
    function check_search_array($val,$array,$re=''){
        if(!$array)$array=[];
        $key = array_search($val,$array);
        if(is_numeric($key))return true;
        return false;
    }
}

// format currency
if (! function_exists('format_currency')) {
    function format_currency($input, $decimal=0, $cur=''){
        if($input < 0 || !$input ){
            $input=0;
        }
        $currency = number_format($input, $decimal, '.', ',');
        return $cur.$currency;
    }
}

// display product in orders
if (! function_exists('display_product_in_order')) {
    function display_product_in_order($data){
        $resulf = [];
       ob_start();
        if(isset($data)){
            $total = 0;
            foreach($data as $value): $total += $value->subtotal * $value->quantily;
             $detail_product  =    \App\Product::get_product($value->product_id);
             $title = (isset($detail_product))?'<a href="'.url('product/'.$detail_product->slug).'" target="_blank">'.$detail_product->name.'</a>':$value->title;
            ?>
                <tr>
                    <td style="padding: .75rem;vertical-align: top;border-bottom: 1px solid #dee2e6;"><?php _e($value->quantily) ?></td>
                    <td style="padding: .75rem;vertical-align: top;border-bottom: 1px solid #dee2e6;"><?php _e($title) ?></td>
                    <td style="padding: .75rem;vertical-align: top;border-bottom: 1px solid #dee2e6;"><?php _e($value->attributes) ?></td>
                    <td style="padding: .75rem;vertical-align: top;border-bottom: 1px solid #dee2e6;"><?php _e( format_currency( $value->subtotal,2,'$') ) ?></td>
                    <td style="padding: .75rem;vertical-align: top;border-bottom: 1px solid #dee2e6;"><?php _e( format_currency( $value->subtotal * $value->quantily,2,'$') ) ?></td>
                </tr>
          <?php  endforeach;
            $resulf['subtotal'] =  $total;
        }
       $resulf['html'] = ob_get_contents();
       ob_end_clean();
       return $resulf;
    }
}



/// get curren datetime
if( !function_exists('get_current_datetime')){
    function get_current_datetime($date){
        $time = round(time()/60,0);
        $minutes = round(strtotime($date) / 60,0);
        $left = $time - $minutes;
        if($left == 0)$left=1;
        switch ($left){
            case $left < 60:
                return ( round($left).' minutes ago');
            break;

            case $left < 24*60:
                return (round($left/60).' hours ago');
            break;

            case $left < 24*60*7:
                return ( round($left/(24*60)).' days ago');
            break;

            case $left < 24*60*30:
                return (round($left/(24*60*7) ).' weeks ago');
            break;

            case $left < 24*60*180:
                return (round($left/(24*60*30) ).' months ago');
            break;

            default:
                return date('m/d/Y', strtotime($date));
            break;
        }
    }
}

/// get optiond
if( !function_exists('get_option')){
    function get_option($user_id,$key){
     return App\Options::get_option($user_id,$key);
    }
}

/// get field optiond
if( !function_exists('get_field_option')){
    function get_field_option($key){
        return App\Options::get_field_option($key);
    }
}



/// display menu
if( !function_exists('display_menu')){
    function display_menu($slug=''){
       ob_start();
       $menu = App\Menu::get_menu_by_location('header');
       $menu = ($menu)?json_decode($menu->data_menu):App\Options::get_menu();
        $slug = Request::fullUrl();
        ?>
        <ul class="menu-main">
            <?php foreach ($menu as $key => $value): ?>
            <li class="nav-item-desktop <?= $value->title ?> <?php $value->href == $slug ?_e('active'):'' ?>">
                <a class="nav-link-desktop" href="<?php _e( url($value->href)) ?>" target="<?= $value->target ?>">
                    <i class="<?= $value->icon ?>"></i>
                    <?php _e($value->text) ?>
                </a>
            <?php if( isset($value->children) ): ?>
                <ul class="sub-menu">
                    <?php foreach ($value->children as $key2 => $value2): ?>
                        <li class="sub-nav-item <?php $value2->href == $slug ?_e('active'):'' ?>"><a class="nav-link" href="<?php _e( $value2->href ) ?>"><?php _e( $value2->text ) ?></a></li>
                    <?php endforeach; ?>
                </ul>
            <?php endif ?>
            </li>
            <?php endforeach; ?>
        </ul>
        <?php
       $resulf = ob_get_contents();
       ob_end_clean();
       return $resulf;
    }
}


/// display menu
if( !function_exists('display_menu_mobile')){
    function display_menu_mobile($slug=''){
        ob_start();
        $menu = App\Menu::get_menu_by_location('mobile_header');
        $menu = ($menu)?json_decode($menu->data_menu):App\Options::get_menu();
        ?>
        <ul class="menu-main-mobile nav-items nav-level-1">
            <?php foreach ($menu as $key => $value): ?>
                <li class="nav-content nav-item  <?php $value->href == $slug ?_e('active'):'' ?>">
                    <a class="nav-link" href="<?php _e( $value->href ) ?>"><?php _e($value->text) ?></a>
                    <div class="<?= isset($value->children)?'nav-expand':'' ?>">
                    <?php if( isset($value->children) ): ?>
                        <a class="nav-link <?= ($value->children)?'nav-expand-link':'' ?>"></a>
                        <ul class="nav-items nav-expand-content">
                            <?php foreach ($value->children as $key2 => $value2): ?>
                                <li class="nav-item <?php $value2->href == $slug ?_e('active'):'' ?>">
                                    <a class="nav-link" href="<?php _e( $value2->href ) ?>"><?php _e( $value2->text ) ?></a>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>
                    </div>
                </li>
            <?php endforeach; ?>
            <?php if(Auth::check()): ?>
            <li class="nav-content nav-item"><a class="nav-link" href="<?= url('my-account') ?>">My account</a></li>
            <?php else: ?>
            <li class="nav-content nav-item"><a class="nav-link" href="<?= url('login') ?>">Login</a></li>
            <li class="nav-content nav-item"><a class="nav-link" href="<?= url('register') ?>">Register</a></li>
            <?php endif; ?>
            <li class="nav-item">
                <div class="headline">Uniforms for the professionals.</div>
                <div class="hotline"><a href="#"><i class="fas fa-phone-alt"></i> (888) 691-6200</a></div>
            </li>
        </ul>
        <?php
        $resulf = ob_get_contents();
        ob_end_clean();
        return $resulf;
    }
}


// limit text
    if( !function_exists('limit_text') ){
        function limit_text($text, $limit) {
            if (str_word_count($text, 0) > $limit) {
                $words = str_word_count($text, 2);
                $pos = array_keys($words);
                $text = substr($text, 0, $pos[$limit]) . '...';
            }
            return $text;
        }
    }

// get user meta
if( !function_exists('get_user_meta') ){
    function get_user_meta($user_id,$meta_key){
        $user_meta =  App\User::get_user_meta($user_id,$meta_key);
        return $user_meta;
    }

}
// upadte user meta
if( !function_exists('update_user_meta') ){
    function update_user_meta($user_id,$meta_key,$meta_value){
        $user_meta =  App\User::update_user_meta($user_id,$meta_key,$meta_value);
        return $user_meta;
    }

}

/// cart_total
if( !function_exists('cart_total') ){
    function cart_total($cart,$tax=0.08,$shipping=30){
        if(!$cart)return false;
        $subtotal = 0;
        $total = [
            'tax'=>0,
            'shipping'=>0,
            'subtotal'=>0,
            'total'=>0,
        ];
        foreach ($cart['products'] as $key => $item){
            $total['subtotal'] += $item['subtotal']*$item['quantily'];
        }
        $total['tax'] = $tax*100;
        $total['shipping'] = $shipping;
        $total['total'] = $shipping + ( $total['subtotal']*$tax ) + $total['subtotal'];
        return $total;

    }
}

// show item product
if( !function_exists('showItemProduct') ){
    function showItemProduct($product){
        ob_start();
        if(!$product['price'])$product['price']= 0;
        $colors = show_color_list_product($product['product_id']);
        $data = [
                'attributes'=>null,
                'key'=>$product['product_id'],
                'link'=>$product['url'],
                'product_id'=>$product['product_id'],
                'quantily'=>1,
                'subtotal'=>$product['price'],
                'thumbnail'=>null,
        ];
        $colume_color = '';
        $default = end($colors);
        if( isset( $colors[$default['data_default']] ) )$default = $colors[$default['data_default']];
        if( isset($default['img']) )$product['image'] = $data['thumbnail'] = $default['img'];
        if( isset($default['attributes']) ){
            $data['attributes'] = 'Color: '.$default['name'].', '.$default['attributes'];
            $data['data_default'] = $default['attributes'];
        }
        if( isset($default['data_key']) )$data['key'] = $default['data_key'];
        if(count($colors)> 9)$colume_color = 'show-colume-2';
        if(count($colors)> 18)$colume_color = 'show-colume-3';
        ?>
        <div class="thumbnail-product" data-color='<?= json_encode($colors) ?>' >
            <span class="SKU">SKU: <?= $product['sku']  ?></span>
            <a href="<?= $product['url'] ?>" style="background-image: url(<?= $product['image']?$product['image']:asset('images/products/default.jpg')  ?>)"><img src="<?= $product['image']  ?>"></a>
            <div class="<?php echo $colume_color; ?> list-color-product ">
            <?php foreach($colors as $color): ?>
            <span class="item_color" data-bs-toggle="tooltip" data-placement="right"  title="<?= $color['name'] ?>" data-key="<?= $color['data_key'] ?>" data-color_id="<?= $color['id'] ?>" data-color="<?= $color['name'] ?>" data-product_id="<?= $product['product_id'] ?>" onclick="change_color_img_product(this)" onmouseover="change_color_img_product(this)" data-img="<?= (isset($color['img']))?$color['img']:asset('images/products/default.jpg'); ?>" style=" <?= (isset($color['thumbnail']))?'background-image: url('.$color['thumbnail'].')':'background-color:'.$color['data_type'] ?>"></span>
            <?php endforeach;  ?>
            </div>
        </div>
        <div class="title-product"><a href="<?= $product['url'] ?>"><?= $product['title']  ?></a></div>
        <div class="price-product pb-2 d-flex">
         <span>$<?= format_currency($product['price'],2); ?></span>
            <a class="add-to-cart btn btn-unipro d-inline-block text-center" data-product="<?= $product['product_id'] ?>" data-title="<?= $product['title'] ?>" data-json='<?= json_encode($data) ?>' data-toggle="modal" data-target="#quickviewproduct" onclick="quick_view_product(this)">Quick view <i class="fas fa-spinner fa-spin fa-1x fa-fw d-none"></i></a>
        </div>
    <?php
        $resulf = ob_get_contents();
        ob_end_clean();
        return $resulf;
    }

}




// get product home page
if( !function_exists('getProductHomePage') ){
    function  getProductHomePage(){
        $products =  App\Product::get_products_publish()->items();
        $top_products = [];
        if($products){
            foreach ($products as $item){

                $top_products[] = [
                    'sku'=> App\Product::get_meta_product($item->id,'sku'),
                    'title'=> $item->name,
                    'category'=> '',
                    'price'=>App\Product::get_meta_product($item->id,'price'),
                    'image'=> ($item->featured_image)?App\Media::get_url_media($item->featured_image):asset('images/products/default.jpg'),
                    'url'=> url('product/'.$item->slug),
                    'product_id'=> $item->id,
                ];
            }
        }
        return $top_products;
    }

}


// get product home page
if( !function_exists('DisplayCategories') ){
    function DisplayCategories($category){
    ob_start();
        $product_departments = \App\Product::product_departments();
    if(isset($category['id'])){
        $category['feature_thumbnail'] = get_field_option('product_categories_thumbnail_'.$category['id']);
        ?>
        <tr class="item-attibute <?= ($category['parent_id'])?'category_child':'category_parent' ?>" data-category-id="<?= $category['id'] ?>">
            <td><input type="checkbox" name="category_id" value="<?= $category['id'] ?>"></td>
            <td>
                <?php
                if($category['feature_thumbnail']){
                    $feature_thumbnail = App\Media::get_url_media($category['feature_thumbnail']);
                    echo '<img style="max-width: 60px;" src="'.$feature_thumbnail.'"/>';
                }
                ?>
            </td>
            <td>
                <?= ($category['parent_id'])?'-- '.$category['name']:$category['name'] ?>
                <div class="action">
                    <a class="mt-2" href='<?= url('product_categories/'.$category['slug'] ) ?>' target="_blank">View</a>
                    <a class="mt-2 ml-2" href='javascript:edit_category(<?= json_encode($category) ?>)'>Edit</a>
                    <a class="mt-2 ml-2" href='javascript:delete_category(<?= $category['id'] ?>)'>Delete</a>
                </div>
            </td>
            <td><?= $category['product_department']?$product_departments[$category['product_department']]:'Unknown'; ?></td>
            <td><?= $category['slug'] ?></td>
            <td><?= $category['loop'] ?></td>
            <td class="text-center font-weight-bold"><?= getNumberProductbyTax($category['id'],'product_category') ?></td>
            <td class="text-right">
             <?php
             if($category['media']){
                 $featured_image = App\Media::get_url_media($category['media']);
                 echo '<img style="max-height: 60px;" src="'.$featured_image.'"/>';
             }
             ?>
            </td>
        </tr>
        <?php
        if(isset($category['child'])){
            foreach ($category['child'] as $item){
               echo DisplayCategories($item);
            }
        }
    }
        $resulf = ob_get_contents();
        ob_end_clean();
        return $resulf;
    }
}

// get product home page
if( !function_exists('DisplayAttributeType') ){
    function DisplayAttributeType($type,$data_type){
        ob_start();
        switch ($type){
            case 1:
                $featured_image = App\Media::get_media_detail($data_type);
                echo '<img style="max-width: 60px;" src="'.url($featured_image->path).'"/>';
                break;
            case 2:
                echo '<span class="badge badge-secondary text" style="font-size: 24px; text-transform: uppercase">'.$data_type.'</span>';
                break;
            case 3:
                echo '<span class="badge badge-secondary number" style="font-size: 24px; text-transform: uppercase">'.$data_type.'</span>';
                break;
            default:
                echo '<span class="badge badge-secondary color" style="background-color: '.$data_type.';font-size: 24px; text-transform: uppercase">'.$data_type.'</span>';

                break;
        }
        $resulf = ob_get_contents();
        ob_end_clean();
        return $resulf;
    }
}

if( !function_exists('get_filter_product') ){
    function get_filter_product($query=[]){
        $filter = [];
        $data_count_all = \App\Product::get_product_byTax_attribute_array($query);
        $product_categories =  App\Product::get_product_categories_all($query);
        $list_cat = list_ob_to_array($product_categories);
        if($product_categories){
            $query['cat'] = $list_cat;
//            $data_count =  App\Product::get_product_byTax_array($query);
            $product_categories =  App\Product::get_sort_categories($product_categories);
            $data = [];
            $count = 0;
            foreach ($product_categories as $key=>$item){
                $count = ( isset($data_count_all['product_category_'.$item['id']]) )?$data_count_all['product_category_'.$item['id']]:0;
                if($count)$data[] = [
                        'title'=>$item['name'],
                        'value'=>$item['id'],
                        'slug'=>$item['slug'],
                        'type'=>'product_category',
                        'number'=>$count,
                        'parent_id'=>$item['parent_id']?'has-parent category-parent-'.$item['parent_id']:'',
                        'product_department'=>$item['product_department'],
                ];
                if(isset($item['child'])){
                    foreach ($item['child'] as $item){
                        $count = ( isset($data_count_all['product_category_'.$item['id']]) )?$data_count_all['product_category_'.$item['id']]:0;
                        if($count)$data[] = [
                            'title'=>$item['name'],
                            'value'=>$item['id'],
                            'slug'=>$item['slug'],
                            'type'=>'product_category',
                            'number'=>$count,
                            'parent_id'=>$item['parent_id']?'has-parent category-parent-'.$item['parent_id']:'',
                            'product_department'=>$item['product_department'],
                        ];
                    }
                }
            }
            $filter[] = [
                'title'=>'Categories',
                'slug'=>'product_categories',
                'data'=>$data,
                'display'=>true,
                'class'=>'filter_product_categories',
                'number'=>$count,
            ];
        }

        $product_attributes =  App\Product::get_product_attributes();

        if($product_attributes){
            foreach ($product_attributes as $attribute){
                $count = 0;
                $attribute_detail =  App\Product::get_product_attributes_detail_filter($attribute->id);
                if( isset( $attribute_detail->child ) && $attribute->loop){
                    $attribute_detail =   $attribute_detail->child;
                    $data = [];
                    $list_attr =  list_ob_to_array($attribute_detail);
                    $query['attr'] = $list_attr;
                  //  $data_count_att =   App\Product::get_product_byTax_cat_array($query);
                    foreach ($attribute_detail as $item_attribute){
                        $count_att =  (isset($data_count_all['product_attribute_'.$item_attribute->id]))?$data_count_all['product_attribute_'.$item_attribute->id]:0;
                      //  $count_att =   get_product_byTax_cat($item_attribute->id,$list_cat);
                        if($count_att)$data[] = [
                            'title'=>$item_attribute->name,
                            'value'=>$item_attribute->id,
                            'slug'=>$item_attribute->slug,
                            'type'=>'product_attribute',
                            'number'=>$count_att,
                            'parent_id'=>'not-child',
                        ];
                        $count += $count_att;
                    }
                    if($count)$filter[] = [
                        'title'=>$attribute->name,
                        'slug'=>$attribute->slug,
                        'data'=>$data,
                        'display'=>false,
                        'class'=>'filter_product_attribute',
                        'number'=>$count,
                    ];
                }

            }

        }

        return $filter;

    }
}


// get filter product page
if( !function_exists('getProductFilterPage') ){
    function getProductFilterPage($query=array()){
        $products = App\Product::get_filter_products($query);
        $filter_products = ['data'=>[],
            'pagition'=>[
                    'total'=> $products->total(),
                    'perPage'=> $products->perPage(),
                    'currentPage'=> $products->currentPage(),
            ]
        ];
        if($products){
            foreach ($products as $item){
                $filter_products['data'][] = [
                    'sku'=> App\Product::get_meta_product($item->id,'sku'),
                    'title'=> $item->name,
                    'category'=> '',
                    'price'=>App\Product::get_meta_product($item->id,'price'),
                    'image'=> App\Media::get_url_media($item->featured_image),
                    'url'=> url('product/'.$item->slug),
                    'product_id'=> $item->id,
                    'order'=> $item->loop_cat,
                ];
            }
        }
        return $filter_products;
    }

}

// show pagition
if( !function_exists('DisplayPagition') ){
    function DisplayPagition($data){
        ob_start();
        if($data['total']>$data['perPage']):
        $list = round($data['total']/$data['perPage'],0);
        echo '<ul class="pagination">';
         if($data['currentPage']>2){
                echo '<li class="page-item"><a class="page-link" href="javascript:start_filter_product(1);" data-page="'.($data['currentPage']-1).'">First</a></li>';
         }
        if($data['currentPage']>1){
            echo '<li class="page-item"><a class="page-link" href="javascript:start_filter_product('.($data['currentPage']-1).');" data-page="'.($data['currentPage']-1).'"><i class="fas fa-angle-double-left"></i></a></li>';
        }
        if($data['currentPage']>3){
            echo '<li class="page-item"><span class="page-link">...</span></li>';
        }
        if($data['currentPage']>2){
                echo '<li class="page-item d-none d-md-block"><a class="page-link" href="javascript:start_filter_product('.($data['currentPage']-2).');" data-page="'.($data['currentPage']-2).'">'.($data['currentPage']-2).'</a></li>';
        }
        if($data['currentPage']>1){
            echo '<li class="page-item"><a class="page-link" href="javascript:start_filter_product('.($data['currentPage']-1).');" data-page="'.($data['currentPage']-1).'">'.($data['currentPage']-1).'</a></li>';
        }
        echo '<li class="page-item active"><span class="page-link">'.$data['currentPage'].'</span></li>';
        if($data['currentPage']< $list){
            echo '<li class="page-item"><a class="page-link" href="javascript:start_filter_product('.($data['currentPage']+1).');" data-page="'.($data['currentPage']+1).'">'.($data['currentPage']+1).'</a></li>';
        }
            if($data['currentPage']< ($list-1) ){
                echo '<li class="page-item d-none d-md-block"><a class="page-link" href="javascript:start_filter_product('.($data['currentPage']+2).');" data-page="'.($data['currentPage']+2).'">'.($data['currentPage']+2).'</a></li>';
            }
        if($data['currentPage']< ($list-2) ){
            echo '<li class="page-item"><span class="page-link">...</span></li>';
        }
        if($data['currentPage']*$data['perPage'] < $data['total'] ){
            echo '<li class="page-item"><a class="page-link" href="javascript:start_filter_product('.($data['currentPage']+1).');" data-page="'.($data['currentPage']+1).'"><i class="fas fa-angle-double-right"></i></a></li>';
        }
        if($data['currentPage']*$data['perPage'] < $data['total'] ){
                echo '<li class="page-item"><a class="page-link" href="javascript:start_filter_product('.($list).');" data-page="'.($data['currentPage']+1).'">Last</a></li>';
        }
        echo '</ul>';
        endif;
        $resulf = ob_get_contents();
        ob_end_clean();
        return $resulf;
    }

}

// display attribute single product
if( !function_exists('DisplayAttributeProductSimple') )    {
    function DisplayAttributeProductSimple($product_id){
        $data = [];
        $select_variations = (array)display_attribute_product($product_id,'all_attributes');
        $attribute_type =  App\Product::attribute_type();
        if( $select_variations ){
            foreach ($select_variations as $key=>$item){
                $attributes = App\Product::get_product_attributes_detail($key);
                $child = [];
                if( isset($item->value) && $attributes ){
                    foreach($item->value as $item_child){
                        $child[] = App\Product::get_product_attributes_detail($item_child->value);
                    }
                    array_multisort(array_column($child, 'name'), SORT_ASC, $child);
                    $data[] = ['name'=>$attributes->name,'id'=>$attributes->id,'select_variant'=>$item->display,'list'=>$child, 'display'=>$item->value[0]->title, 'type'=>$attribute_type[$attributes->type]['type'] ];
                }

            }

        }
        return $data;
    }
}

// get product home page
if( !function_exists('getProductRelation') ){
    function getProductRelation($product_id,$term_id){
        $products =  \App\Product::get_product_relation($product_id,$term_id)->items();
        $relation_products = [];
        if($products){
            foreach ($products as $item){
                $colors = show_color_list_product($item->id);
                $product =  [
                    'sku'=> App\Product::get_meta_product($item->id,'sku'),
                    'title'=> $item->name,
                    'category'=> display_category_product($item->id),
                    'price'=>App\Product::get_meta_product($item->id,'price'),
                    'image'=>($item->featured_image)?get_url_media($item->featured_image):'',
                    'url'=> url('product/'.$item->slug),
                ];
                $default = end($colors);
                if( isset($default['img']) )$product['image'] =  $default['img'];
                if( isset($default['data_default']) )$product['image'] =  $colors[$default['data_default']]['img'];
                if(!$product['image']) $product['image'] = url('images/products/default.jpg');
                $relation_products[] = $product;
            }
        }
        return $relation_products;
    }

}

// get quantily product by tax
if( !function_exists('getProductbyTax') ){
   function getNumberProductbyTax($term_id,$type='product_category'){
       $products = \App\Product::get_product_byTax($term_id,$type);
       return $products?count($products):0;
   }
}

// get quantily product by tax and cat
if( !function_exists('get_product_byTax_cat') ){
    function get_product_byTax_cat($term_id,$cat){
        $products = \App\Product::get_product_byTax_cat($term_id,$cat);
        return $products?count($products):0;
    }
}

/// display media by id
if( !function_exists('get_url_media') ){
    function get_url_media($id){
       return  \App\Media::get_url_media($id);
    }
}
if(!function_exists('display_menu_data') ){
    function display_menu_data($location){
        $menus = App\Menu::get_menu_by_location($location);
        $menus = ($menus)?json_decode($menus->data_menu):[];
        return $menus;
    }
}

if(!function_exists('display_resulf_ajax_search') ){
    function display_resulf_ajax_search($products=[]){
        ob_start();
        if( count($products) ):
        foreach($products as $item_product):
            $thumbnail_attribute = \App\Product::get_meta_product($item_product->id,'thumbnail_attribute');
            if($thumbnail_attribute){
                $thumbnail_attribute = ($thumbnail_attribute)?(array)\GuzzleHttp\json_decode($thumbnail_attribute):[];
                $thumbnail_attribute = (array)end($thumbnail_attribute);
                $thumbnail_attribute = array_shift($thumbnail_attribute);
            }

            ?>
            <div class="item-product-aj mb-2 border-bottom" data-product="<?= $item_product->id ?>">
                <div class="d-flex">
                    <div class="thumbnail_product pl-0 pr-0">
                        <a href="<?= url( 'product/'.$item_product->slug ) ?>"><img src="<?= ($item_product->featured_image)?\App\Media::get_url_media($item_product->featured_image):\App\Media::get_url_media($thumbnail_attribute); ?>"></a>
                    </div>
                    <div class="title_product pl-2 pr-2">
                        <div class="title font-weight-bold"><a href="<?= url( 'product/'.$item_product->slug ) ?>"><?= $item_product->name ?></a></div>
                        <div class="price">
                            <label>SKU: </label> <strong><?= $item_product->sku ?></strong>
                            <label>Price: </label> <strong><?= format_currency( App\Product::get_meta_product($item_product->id,'price'),2,'$') ?></strong>
                        </div>
                    </div>
                </div>

            </div>
        <?php
        endforeach;
        else: echo '<div class="item-product-aj">Please contact our customer support for this item email.</div>';
        endif;
        $resulf = ob_get_contents();
        ob_end_clean();
        return $resulf;
    }
}
/// show date
if(!function_exists('display_date')){
    function display_date($date){
       return  $date?date('m/d/Y', strtotime($date)):$date;
    }
}

if(!function_exists('folder_Size')){
    function format_Size($set_bytes)
    {
        $set_kb = 1024;
        $set_mb = $set_kb * 1024;
        $set_gb = $set_mb * 1024;
        $set_tb = $set_gb * 1024;
        if (($set_bytes >= 0) && ($set_bytes < $set_kb))
        {
            return $set_bytes . ' B';
        }
        elseif (($set_bytes >= $set_kb) && ($set_bytes < $set_mb))
        {
            return ceil($set_bytes / $set_kb) . ' KB';
        }
        elseif (($set_bytes >= $set_mb) && ($set_bytes < $set_gb))
        {
            return ceil($set_bytes / $set_mb) . ' MB';
        }
        elseif (($set_bytes >= $set_gb) && ($set_bytes < $set_tb))
        {
            return ceil($set_bytes / $set_gb) . ' GB';
        }
        elseif ($set_bytes >= $set_tb)
        {
            return ceil($set_bytes / $set_tb) . ' TB';
        } else {
            return $set_bytes . ' Bytes';
        }
    }
    function folder_Size($set_dir)
    {
        $file_size = 0;

        foreach( File::allFiles($set_dir) as $file)
        {
            $file_size += $file->getSize();
        }
        return format_Size($file_size);
    }

    function get_images_import($string){
        $images = explode(';', $string);
        return $images;
    }
    function show_color_import($data){
        $resulf = [];
        $colors = explode(',', $data);
        foreach ($colors as $key=> $color){
            $resulf[] = $color;
        }
        return $resulf;
    }

    function show_size_import($data){
        $resulf = [];
        $sizes = explode(',', $data);
        foreach ($sizes as $key=> $size){
            $resulf[] = $size;
        }
        return $resulf;
    }

  function show_product_import($data){
ob_start();
?>
      <table class="table">
          <thead class="thead-dark table-hover">
          <tr>
              <?php
              $head_attr = $data['head_attr'];
              unset($head_attr[0]);
              $head = array_merge($data['head'],$head_attr);
              foreach ($head as $td): ?>
              <th scope="col"><?= $td ?></th>
          <?php endforeach; ?>
          </tr>
          </thead>
          <tbody>

      <?php foreach ($data['data'] as $r=> $row): ?>
      <tr DataProductSKU="<?= $row['sku'] ?>" DataProductJson='<?= json_encode($row) ?>'
          data-attribute='<?= json_encode($data['data_attr'][$row['sku']]) ?>'
          data-product_variations='<?= (isset($data['product_variations'][$row['sku']] ))?json_encode($data['product_variations'][$row['sku']]):'' ?>'
          data-path="<?= $data['path'] ?>">
          <?php
          $data_attr = $data['data_attr'][$row['sku']];
          $row = array_merge($row,$data_attr);
          foreach ($row as $key=> $td): ?>
              <td><?php
                 switch ($key) {
                     case 'price':
                         echo format_currency( $td ,2,'$');
                         break;
                     case 'featured_image':
                     case 'gallery':
                         $images = get_images_import($td);
                         foreach ($images as $url){
                             if($url&&file_exists($data['path'].'/'.$url)){
                                 echo '<img class="mb-2" src="'.url($data['path'].'/'.$url).'" />';
                             }
                         }
                         break;
                     case 'color':
                         $colors = show_color_import($td);
                         foreach ($colors as $color){
                             echo '<span class="badge badge-secondary color mr-1" style="background-color: '.$color.';font-size: 14px; text-transform: uppercase">'.$color.'</span>';
                         }
                     break;
                     case 'size':
                     case 'number-size':
                     case 'weight':
                         $sizes = show_size_import($td);
                         foreach ($sizes as $size){
                             echo '<span class="badge badge-secondary mr-1" style="font-size: 14px; text-transform: uppercase">'.$size.'</span>';
                         }
                         break;
                     default;
                     _e($td);
                     break;
                 }
                  ?></td>
          <?php endforeach; ?>
      </tr>
      <?php endforeach; ?>



          </tbody>
      </table>
<?php
$resulf= ob_get_contents();
ob_end_clean();
      return $resulf;
  }
}
// Top category
if(!function_exists('top_categories')){
    function top_categories(){
        $top_category = [];
        $categories =  \App\Product::get_product_categories_top();
        if($categories){
            foreach ($categories as $key => $category){
                $thumbnail = get_field_option('product_categories_thumbnail_'.$category->term_id);
                $top_category[$key] = (array)$category;
                $top_category[$key]['image'] = \App\Media::get_url_media($thumbnail);
                $top_category[$key]['url'] = url('product_categories/'.$category->slug);
            }
        }

        return $top_category;
    }
}

// Menu account
if(!function_exists('display_menu_account')){
   function display_menu_account(){
       ob_start();
       $user = Auth::user();
       $avata = get_user_meta($user->id,'avata');
       $avata = ($avata)?\App\Media::get_url_media($avata):asset('uploads/users/user.png');
        ?>
       <span><?= $user->name ?></span> <a href="<?=  url('my-account') ?>"><span class="avatar" style="background-image: url(<?= ($avata)?$avata: url('uploads/users/user.png')   ?>)"></span></a>
       <div class="control-account text-left">
           <div class="item-account"><a href="<?=  url('my-account') ?>"><i class="fas fa-user-cog"></i> My account</a></div>
           <div class="item-account"><a href="javascript:logout();"><i class="fas fa-sign-out-alt"></i> Sign out</a></div>
       </div>
        <?php
       $resulf = ob_get_contents();
       ob_end_clean();
       return $resulf;
   }
}

/// Type checkout
if(!function_exists('type_checkout')){
    function type_checkout(){
        $checkout = [
            'Cash on delivery',
            'Direct bank transfer',
            'PayPal',
        ];

        return $checkout;
    }
}

/// Type checkout
if(!function_exists('display_url_product')){
    function display_url_product($product_id){
       $detail = \App\Product::get_product($product_id);
       if($detail) return url('product/'.$detail->slug);
        return false;
    }
}

/// remove_empty array
if(!function_exists('remove_empty_array')){
    function remove_empty_array($array){
        $resulf = [];
        foreach($array as $key=> $link)
        {
            if($link)$resulf[] = $link;
        }
        return $resulf;
    }
}

if(!function_exists('get_attribute_from_inmport')){
 function get_attribute_from_inmport($array){
     $resulf = [];
     foreach($array as $key=> $link)
     {
         $sku = $link['sku'];
         unset($link['sku']);
         $resulf[$sku][] = $link;
     }
     return $resulf;
 }
}

// get product variantions
if(!function_exists('get_product_variantions')){
    function get_product_variantions($product_id){
        $variantions= [];
        $thumbnail_attribute = \App\Product::get_meta_product($product_id,'thumbnail_attribute');
        $price_attribute = \App\Product::get_meta_product($product_id,'price_attribute');
        $default_attribute =    App\Product::get_meta_product($product_id,'default_attribute');
        $variantions['thumbnail_attribute']  = ($thumbnail_attribute)?json_decode($thumbnail_attribute):[];
        $variantions['price_attribute'] = ($price_attribute)?json_decode($price_attribute):[];
        $variantions['default_attribute']  = ($default_attribute)?json_decode($default_attribute):[];
     return $variantions;
    }
}


/// format text excel
if(!function_exists('format_text_cell')){
    function format_text_cell($cell){
        $cell = strip_tags($cell);
        $cell = str_replace("'s",' of',$cell);
        $cell = str_replace("/",'|',$cell);
        $cell = str_replace("'",'',$cell);
        $cell = str_replace('"','',$cell);
        return $cell;
    }
}
/// display rating review analytic
if(!function_exists('get_rating_analytic')){
   function get_rating_analytic($product_id){
       $list_reviews = get_list_reviews($product_id);
       $rating = 0;
       $rating_percent = [
               1=>0,
               2=>0,
               3=>0,
               4=>0,
               5=>0,
       ];
       if($list_reviews){
           foreach ($list_reviews as $rate){
               $rating += $rate->rating;
               if( isset($rating_percent[$rate->rating]) )$rating_percent[$rate->rating]++;
           }
       }
       $total  = $list_reviews->total();
       $reviews['total'] = $total;
       if(!$total)$total = 1;
       $reviews['rating'] = round($rating/$total,1);
       $reviews['analytic'] = [
           ['star'=>5, 'subtotal'=>$rating_percent[5], 'rating'=> round($rating_percent[5]/$total*100,0).'%'],
           ['star'=>4, 'subtotal'=>$rating_percent[4], 'rating'=>round($rating_percent[4]/$total*100,0).'%'],
           ['star'=>3, 'subtotal'=>$rating_percent[3], 'rating'=>round($rating_percent[3]/$total*100,0).'%'],
           ['star'=>2, 'subtotal'=>$rating_percent[2], 'rating'=>round($rating_percent[2]/$total*100,0).'%'],
           ['star'=>1, 'subtotal'=>$rating_percent[1], 'rating'=>round($rating_percent[1]/$total*100,0).'%'],
       ];
    return $reviews;
   }
}

/// display user review analytic
if(!function_exists('get_user_rating_analytic')){
    function get_user_rating_analytic($product_id){
        $reviews = [
            ['rating'=>4.5, 'user'=>1, 'create_at'=> date(), 'title'=>'Good', 'description'=>'Lorem Ipsum is simply dummy text of the printing and typesetting industry.'],
        ];
        return $reviews;
    }
}

/// Data review with rating
if(!function_exists('get_data_review_rating')){
    function get_data_review_rating(){
        $reviews = [
                ['rating'=>1,'title'=>'Very dissatisfied', 'description'=>'Please share why this product is not good.'],
                ['rating'=>2,'title'=>'Unsatisfied', 'description'=>'Please share why dont you like this product.'],
                ['rating'=>3,'title'=>'Normal', 'description'=>'Please share why dont you really like it.'],
                ['rating'=>4,'title'=>'Satisfied', 'description'=>'Please share why do you like this product.'],
                ['rating'=>5,'title'=>'Very Good', 'description'=>'Please share things you like about this product.'],
        ];
        return $reviews;
    }
}

// Get List Reviews
function get_list_reviews($product_id){
    $reviews = \App\Reviews::get_review_publish($product_id);
    return  $reviews;
}

// get avata user
function get_url_avata_user($id){
    $avata_id = get_user_meta($id,'avata');
    $avata = asset('images/default-avatar.png');
    if( isset($avata_id) )$avata =  App\Media::get_url_media($avata_id);
    return $avata;
}
function getUserByID($id){
    return \App\User::getUserByID($id);
}
/// Display Item review Front End
function display_item_review($item_review){
    ob_start();
?>
    <div class="item-review border-top pt-3">
        <div class="user d-flex">
            <div class="avata-review"
                 style="background-image: url(<?= get_url_avata_user($item_review->user_id) ?>)"></div>
            <div class="review-account-info">
                <span class="name font-weight-bold"><?= getUserByID($item_review->user_id)->name ?></span>
                <span class="date font-italic"><?= get_current_datetime($item_review->created_at) ?></span>
            </div>
        </div>
        <div class="content-review p-3">
            <div class="rating">
                <?php for($i=1;$i<=5;$i++): ?>
                <i class="<?= ($item_review->rating > ($i - 0.51) &&  $item_review->rating < $i )?'fas fa-star-half-alt':'' ?> <?= ($item_review->rating >= $i)?'fas fa-star':'far fa-star' ?>"></i>
                <?php endfor; ?>
            </div>
            <div class="title-review font-weight-bold"><?= $item_review->title ?></div>
            <div class="description-review"><?= $item_review->content ?></div>
        </div>

    </div>
<?php
    $resulf = ob_get_contents();
    ob_end_clean();
    return $resulf;
}

function DisplayPagitionReview($data){
    ob_start();
    $list = round($data['total']/$data['perPage'],0);
    if($list<2)return "";
    echo '<ul class="pagination">';
    if($data['currentPage']>1){
        echo '<li class="page-item"><a class="page-link" href="javascript:get_data_reviews('.($data['currentPage']-1).');" data-page="'.($data['currentPage']-1).'">Previous</a></li>';
    }
    if($data['currentPage']>2){
        echo '<li class="page-item"><span class="page-link">...</span></li>';
    }
    if($data['currentPage']>1){
        echo '<li class="page-item"><a class="page-link" href="javascript:get_data_reviews('.($data['currentPage']-1).');" data-page="'.($data['currentPage']-1).'">'.($data['currentPage']-1).'</a></li>';
    }
    echo '<li class="page-item active"><span class="page-link">'.$data['currentPage'].'</span></li>';
    if($data['currentPage']< $list){
        echo '<li class="page-item"><a class="page-link" href="javascript:get_data_reviews('.($data['currentPage']+1).');" data-page="'.($data['currentPage']+1).'">'.($data['currentPage']+1).'</a></li>';
    }
    if($data['currentPage']< ($list-1 ) ){
        echo '<li class="page-item"><span class="page-link">...</span></li>';
    }
    if($data['currentPage']*$data['perPage'] < $data['total'] ){
        echo '<li class="page-item"><a class="page-link" href="javascript:get_data_reviews('.($data['currentPage']+1).');" data-page="'.($data['currentPage']+1).'">Next</a></li>';
    }
    echo '</ul>';
    $resulf = ob_get_contents();
    ob_end_clean();
    return $resulf;
}
function get_check_review($product_id){
    return \App\Reviews::get_check_review($product_id);
}

function get_title_product($product_id){
   $product =  \App\Product::get_product($product_id);
   if($product)return $product->name;
}

function get_url_product($product_id){
    $product =  \App\Product::get_product($product_id);
    if($product)return url('product/'.$product->slug);
}
// get thumbanil product
function get_url_thumbnail_product($product_id){
    $product =  \App\Product::get_product($product_id);
    if($product)return \App\Media::get_url_media($product->featured_image);
}

function check_question($product_id){
    return \App\Reviews::check_question($product_id);
}
function get_question_publish($product_id){
   return  \App\Reviews::get_question_publish($product_id);
}
function get_question_reply($id){
    $data = \App\Reviews::get_question_reply($id);
    return ($data)?(array)$data:[];
}
/// data json in analytics dashboard
function get_data_json_analytics($data){
    if($data['type'] == 'year'){
        $start = strtotime("01/01 midnight");
        $start_last = strtotime("01/01 midnight",strtotime("last year"));
        foreach ($data['labels'] as $key=>$item){
            $next = strtotime("+{$key} month",$start);
            $month = \App\Orders::get_data_order($data['type'],$next);
            $data['data_now'][] = $month;

            $next = strtotime("+{$key} month",$start_last);
            $month = \App\Orders::get_data_order($data['type'],$next);
            $data['data_last'][] = $month;
        }
        $data['total'] = array_sum($data['data_now']);
        $growth = array_sum($data['data_now'])/array_sum($data['data_last'])*100 - 100;

        $data['growth'] = round( $growth ,1 );
    }

    if($data['type'] == 'week'){
        $start = strtotime("last Mon");
        $start_last = strtotime("last Mon",strtotime("-1 week"));
        foreach ($data['labels'] as $key=>$item){
            $next = strtotime("+{$key} day",$start);
            $month = \App\Orders::get_data_order($data['type'],$next);
            $data['data_now'][] = $month;

            $next = strtotime("+{$key} day",$start_last);
            $month = \App\Orders::get_data_order($data['type'],$next);
            $data['data_last'][] = $month;
        }
        $data['total'] = array_sum($data['data_now']);
        $growth = ( array_sum($data['data_last']) )?array_sum($data['data_now'])/array_sum($data['data_last'])*100 - 100:100;

        $data['growth'] = round( $growth ,1 );
    }

return $data;
}

/// get_query order
function get_query_order($query){
   return  \App\Orders::get_query_order($query);
}
function online_store_overview(){
    $resulf = ['complete_current'=>0,'total_current'=>0,'complete_last_month'=>0,'total_last_month'=>0];
    $query = ['month'=> strtotime("01/01 midnight"),'select'=>'status, count(*) as total'];
    $data = get_query_order($query);
     foreach ($data as $item){
        if($item->status == 2)$resulf['complete_current'] = $item->total;
        $resulf['total_current'] += $item->total;
     }
    $query = ['month'=> strtotime("01/01 midnight -1 month"),'select'=>'status, count(*) as total'];
    $data = get_query_order($query);
    foreach ($data as $item){
        if($item->status == 2)$resulf['complete_last_month'] = $item->total;
        $resulf['total_last_month'] += $item->total;
    }
    $resulf['conversion_rate'] = ($resulf['total_current'])?round($resulf['complete_current']/$resulf['total_current']*100,2):100;
    $resulf['conversion_rate_last_month'] = ($resulf['total_last_month'])?round($resulf['complete_last_month']/$resulf['total_last_month']*100,2):100;
    $resulf['sales_rate'] = ($resulf['total_last_month'])?round($resulf['total_current']/$resulf['total_last_month']*100 - 100,2):100;

return $resulf;
}

function get_top_product_sales($query){
    $slide = (isset($query['slide']))?$query['slide']:5;
    $products_q = \App\Orders::get_top_product_sales($query);

    $products = [];

    foreach($products_q as  $item){
        $quanlity = json_decode($item->quantily);
        $count = 1;
        $array_products = json_decode($item->products);
        foreach( $array_products as $k=> $p){
            if(!isset($products[$p]))$products[$p] = 0;
            $products[$p] += (int)$quanlity[$k];
        }
    }
    arsort($products);
    return $products;
}


function compare_rate($number1,$number2){
    $resulf = ['key'=>'fa-arrow-up','rate'=>''];
    if(!isset($number2))return $resulf;
    $resulf['rate'] = $number1/$number2*100 - 100;
    if( $resulf['rate'] <= 0){
        $resulf['key'] = 'fa-arrow-down text-danger';
        $resulf['rate'] = '<span class="text-danger">'.abs($resulf['rate']).'%<span>';
    }else{
        $resulf['rate'] = '<span class="text-success">'.abs($resulf['rate']).'%<span>';
    }
    return $resulf;
}

function display_top_product_sales($select='month',$type='sales',&$func='get_top_product_sales'){
     ob_start();
     $label = ['title'=>'Sales','value'=>'sold'];
     if($type =='reviews'){
         $func='get_top_product_reviews';
         $label = ['title'=>'Reviews','value'=>'reviews'];
     }
    if($type =='questions'){
        $func='get_top_product_questions';
        $label = ['title'=>'Questions','value'=>'questions'];
    }
     if($select=='week'){
         $tops_now = $func([$select=> strtotime('last Mon')]);
         $tops_last = $func([$select=> strtotime('last Mon -1 week')]);
     }else if($select=='year'){
         $tops_now = $func([$select=> strtotime('first day of this year')]);
         $tops_last = $func([$select=> strtotime('first day of this year -1 year')]);
    }else{
         $tops_now = $func([$select=> strtotime('first day of this month')]);
         $tops_last = $func([$select=> strtotime('first day of this month -1 month')]);
     }

        ?>
    <table class="table table-striped table-valign-middle">
                                <thead>
                                <tr>
                                    <th>Product</th>
                                    <th>Price</th>
                                    <th><?= $label['title']  ?></th>
                                </tr>
                                </thead>
                                <tbody>
    <?php if($tops_now):
        foreach($tops_now as $product=>$quanlity):
            if($type=='sales')\App\Product::update_meta_product($product,'top_sell_'.$select,$quanlity);
    ?>
     <tr>
                                    <td>
                                        <img src="<?= get_url_thumbnail_product($product); ?>" class="img-circle img-size-32 mr-2">
                                        <a href="<?= get_url_product($product) ?>" target="_blank" class="text-muted"> <?= get_title_product($product) ?> </a>
                                    </td>
                                    <td><?= format_currency(\App\Product::get_meta_product($product,'price'),2,'$') ?> USD</td>
                                    <td>
                                        <small class="text-success mr-1">
                                            <i class="fas <?= (isset($tops_last[$product]))?compare_rate($quanlity,$tops_last[$product])['key']:'fa-arrow-up' ?>"></i>
                                            <?= (isset($tops_last[$product]))?compare_rate($quanlity,$tops_last[$product])['rate']:'100%' ?>
                                        </small>
                                        <?= format_currency($quanlity,0) ?> <?= $label['value']  ?>
                                    </td>
    </tr>
    <?php endforeach;
                                endif;
    ?>
                                </tbody>
                            </table>
    <?php
    $resulf = ob_get_contents();
    ob_end_clean();
    return $resulf;
}
function get_top_product_reviews($query){
    $products_q = \App\Reviews::get_top_product_review($query);
    $products = [];
    foreach($products_q as  $item){
        $quanlity = $item->quantily;
        $product_id = $item->product_id;
        if(!isset($products[$product_id]))$products[$product_id] = 0;
        $products[$product_id] +=$quanlity;
    }
    arsort($products);
    return $products;
}

function get_top_product_questions($query){
    $products_q = \App\Reviews::get_top_product_question($query);
    $products = [];
    foreach($products_q as  $item){
        $quanlity = $item->quantily;
        $product_id = $item->product_id;
        if(!isset($products[$product_id]))$products[$product_id] = 0;
        $products[$product_id] +=$quanlity;
    }
    arsort($products);
    return $products;
}

function get_categories_bytype($type=''){
    $cat = \App\Product::get_product_categories_byType($type);
    if(count($cat)<1)$cat[]=(object)['id'=>9999];
    return $cat;
}


function list_menu_categories($type){
    $product_categories = App\Product::get_product_categories_byType($type);
    $product_categories = App\Product::get_sort_categories($product_categories);
    return $product_categories;
}


function get_id_child_attribute($name='color',$query){
    $parent_id = \App\Product::get_product_attributes_bylug($name);
    return \App\Product::get_id_child_attributes_byparent($parent_id,$query);
}

function get_relationships($object_id,$type=""){
   $result =  \App\Relationships::get_relationships($object_id,$type);
   if(!$result)return false;
    return $result->id;
}

function check_array_search($value,$array){
    if(false !== array_search($value,$array)) return true;
    return false;
}

function list_ob_to_array($data){
    $data_array = [];
    foreach ($data as $key=> $item){
        if(isset($item->id))$data_array[$item->id]= $item->id;
    }
    return $data_array;
}


function display_filter_product_html($query=[]){
    ob_start();
    $filters = get_filter_product( $query );
    if(isset($filters)):
        foreach($filters as $item):
            ?>
                    <div class="item-filter" data-type="<?= $item['slug'] ?>">
                        <div class="item-header">
                            <div class="item-title"><?= $item['title'] ?></div>
                            <span class="btn-tool" data-card-widget="collapse" data-show="<?= $item['display'] ?>" onclick="collapse_attribute_filter(this)">
                                  <i class="<?= $item['display']?'fas fa-minus':'fas fa-plus' ?>"></i>
                              </span>
                        </div>
                    <!---count($cat) && $item['slug'] == 'product_categories' && !check_array_search($check['value'],$cat) ?'d-none':''--->
                        <div class="item-body <?= $item['display']?'show':'collapse' ?> ">
  <?php  foreach($item['data'] as $check): ?>
                            <div class="form-group form-check <?= $check['parent_id'] ?>">
                                <input type="checkbox" onchange="start_filter_product()" value="<?= $check['value'] ?>" name="<?= $item['slug'] ?>" class="form-check-input <?= $item['class'] ?>" data-child="<?= $check['slug'] ?>" id="<?= $item['slug'].$check['value'] ?>">
                                <label class="form-check-label font-weight-bold" for="<?= $item['slug'].$check['value'] ?>"><?= $check['title'] ?></label>
    (<?= $check['number'] ?>)
                            </div>
   <?php endforeach; ?>
                        </div>
                    </div>
   <?php endforeach;
                        endif;
    $resulf = ob_get_contents();
    ob_end_clean();
    return $resulf;
}


function show_color_list_product($product_id){
    $thumbnail_attribute = App\Product::get_meta_product($product_id, 'thumbnail_attribute');
    $thumbnail_attribute = ($thumbnail_attribute)?(array)json_decode($thumbnail_attribute):[];

    $thumbnail_color = App\Product::get_meta_product($product_id, 'thumbnail_color');
    $thumbnail_color = ($thumbnail_color)?(array)json_decode($thumbnail_color):[];

    $price_attribute = App\Product::get_meta_product($product_id, 'price_attribute');
    $price_attribute = ($price_attribute)?(array)json_decode($price_attribute):[];

    $default_attribute = App\Product::get_meta_product($product_id, 'default_attribute');
    $default_attribute = ($default_attribute)?(array)json_decode($default_attribute):[];

    $color=[];
    $default_color = '';
    $default = [];
    $data_key= [];

    foreach ($default_attribute as $id => $item){
        $data_color =  Product::get_product_attributes_detail_single($id);
        if( $data_color->type){
            $data_key[] = $id;
            $default[] =  $item->title.': '.$item->value;
        }else{
            $default_color = $id;
        }
    }

    if($thumbnail_color)foreach ($thumbnail_color as $id => $item){
        if(!$default_color)$default_color = $id;
        $data_color =  Product::get_product_attributes_detail_single($id);
        $img = '';
        if(isset($thumbnail_attribute[$id]) && count(  (array)$thumbnail_attribute[$id] ) )$img = end( $thumbnail_attribute[$id] );
        $color[$id]['img'] = \App\Media::get_url_media($img);
        if($item)$color[$id]['thumbnail'] = \App\Media::get_url_media($item);
        $color[$id]['name'] = $data_color->name;
        $color[$id]['data_type'] = $data_color->data_type;
        $color[$id]['data_key'] = $id.'_'.implode('_',$data_key);
        $color[$id]['attributes'] = implode(',',$default);
        $color[$id]['data_default'] = $default_color;
        $color[$id]['id'] = $id;
    }
    return $color;
}

function compare_vocabulary($text,$c_text){
    $text = strtolower($text);
    $c_text = strtolower($c_text);
    $c_text = explode(' ',$c_text);
    $i=0;
    $count = 0;
    while ( $i < count($c_text)){
        $catname =  str_replace('|','',$c_text[$i]);
        $catname =  str_replace(',','',$catname);
        $catname =  str_replace('"','',$catname);
        $catname =  (substr($catname,-1,1)=='s')?substr($catname,0,-1):$catname;
        if ($catname && strstr( $text, $catname ) ) {
            $count++;
        }
        $i++;
    }

   return  array('p'=>round( $count/$i,2 )*100, 'c'=>$count,'t'=>$i);
}
// save auto category to product by name
function list_compare_cat(){
    $categories_array =  \App\Product::get_product_categories_all();
    $products = Product::get_products(['limit'=>400]);
    $table = [];
    foreach ($products as $product){
        $productname =  $product->name;
        $categories_old = App\Relationships::get_relationships($product->id,'product_category_',2);
      //  \App\Relationships::delete_relationship($product->id,'product_category_');
        if($categories_old)foreach ($categories_old as $cat){
            $table[$product->id]['categories'][$cat->id] = $cat->name;
      //      \App\Relationships::save_relationships($product->id, $cat->id, 'product_category_');
        }
        foreach ($categories_array as $cat){
            if(isset($cat->name) && !isset($table[$product->id]['categories'][$cat->id])){
                $data_cat = str_replace('&',' ',$cat->name);
               // $data_cat = str_replace('-',' ',$data_cat);
                $data_cat = str_replace('/',' ',$data_cat);
                $data_cat = str_replace('"','',$data_cat);
                $data_cat = trim($data_cat);
                $resulf = compare_vocabulary($productname,$data_cat);
                if($resulf['p'] > 50 || $resulf['c'] > 1){
                   // $table[$product->id]['categories'][$cat->id] = $cat->name;
          //          \App\Relationships::save_relationships($product->id, $cat->id, 'product_category_');
                }

            }
        }
        if( !isset($table[$product->id]['categories']) )$table[$product->id]['categories'] = [];
            $table[$product->id]['product'] = (array)$product;
    }
return $table;
}

function http_build_query_not_enc_type($array,$numeric_prefix = '', $arg_separator = ''){
    if(empty($array))return '';
    $resulf = [];
    foreach ($array as $key => $value){
        $resulf[]=$key.$numeric_prefix.$value;
    }
    return implode($arg_separator,$resulf);
}
function get_title_media_array($array){
    $media = [];
    foreach ($array as $key => $id){
       $images  = \App\Media::get_media_detail($id);
       $media[$key] = ($images)?$images->title:'';
    }
    return $media;
}


function getUrlContent($url){
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; .NET CLR 1.1.4322)');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
    curl_setopt($ch, CURLOPT_TIMEOUT, 5);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER , false);
    $data = curl_exec($ch);
    $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    return ($httpcode>=200 && $httpcode<300) ? $data : '{}';
}

 function get_data_brands($slug){
    $brand = Product::get_product_brand_bylug($slug);
     $list = [];
    if($brand){
        $list = Product::get_product_attributes_detail($brand->id);
    }
    return $list;
 }