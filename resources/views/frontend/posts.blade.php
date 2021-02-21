@extends('layouts.layout_main')
@section('content')
    <?php
    $args = [
        array('name'=>'post_type','operator'=>'=','value'=>0),
    ];
    $posts =  App\Posts::query_stories($args,'updated_at','asc',20,6);
    ?>
    <section class="products-page">
        <div class="container">
            <div class="row ml-0 mr-0" style="background-image:url('{{ asset('images/products/categories.png') }}');">
                <div class="col-md-6">
                    <div class="breadcrumb_iner">
                        Blogs
                    </div>
                </div>
                <div class="col-md-6"></div>
            </div>
        </div>
    </section>
    <section class="about-page">
        <div class="container">
            <div class="list-posts row">
                @if($posts)
                    @foreach($posts as $post)
                <div class="post col-md-4">
                    @if($post->post_image)
                        <div class="thumbnail text-center">
                            <a href="{{ url('post/'.$post->slug) }}">
                            <img class="rounded" src="{{ get_url_media($post->post_image) }}" alt="Post Image">
                            </a>
                        </div>
                    @endif
                    <div class="title-post">
                            <a href="{{ url('post/'.$post->slug) }}" class="btn-tool mt-3">{{ $post->post_title }}</a>
                            <span class="description font-italic">{{ get_current_datetime( $post->created_at ) }}</span>
                    </div>
                    <div class="content-post">
                        {!! limit_text($post->post_content,19) !!}
                    </div>
                </div>
                    @endforeach
                @endif
            </div>
            {{ $posts->links() }}
        </div>
    </section>
@endsection
