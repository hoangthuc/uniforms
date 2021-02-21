<?php
$args = [
    array('name'=>'post_type','operator'=>'=','value'=>0),
];
$posts_recent =  App\Posts::query_stories($args,'created_at','desc',false);
$post_category = App\Posts::post_category();
$categories =  App\Posts::get_categories_post();
?>
<div class="blog_right_sidebar">
    @if( Illuminate\Support\Facades\Auth::check())
    <aside class="single_sidebar_widget search_widget">
        <ul class="text-left sidebar-menu list-unstyled push-top-15">
            <li><i class="fa fa-pencil black"></i> <a href="#">My profile</a></li>
            <li><i class="fa fa-pencil black"></i> <a href="#">Your order</a></li>
            <li><i class="fa fa-lock black"></i> <a href="#">Change Your Password</a></li>
            <li><i class="fa fa-sign-out black"></i> <a href="javascript:logout();" class="">Log Out</a></li>
        </ul>
    </aside>
    @endif
</div>