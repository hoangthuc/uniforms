@extends('layouts.layout_main')
@section('content')
    <?php
    $Category =  App\Posts::post_category();
    $post =  App\Posts::get_post_by_slug($slug);
    $comments = App\Posts::get_comment_post_publish($post->id);
    ?>
    <section class="products-page">
        <div class="container">
            <div class="row" style="background-image:url('{{ asset('images/about.jpg') }}');">
            </div>
        </div>
    </section>
    <section class="about-page">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-xl-5">
                    <div class="section_tittle_about text-center mt-3">
                        <h2 class="font-weight-bold">
                            Blogs
                        </h2>
                        <div class="description">{{ $post->post_title }}</div>
                    </div>
                </div>
            </div>
            <div class="content-about-page">
               {!! $post->post_content !!}
            </div>
        </div>
    </section>
@endsection
