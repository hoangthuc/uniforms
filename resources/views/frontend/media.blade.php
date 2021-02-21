@extends('layouts.layout_main')
@section('content')
    <?php
    $args = [
        array('name'=>'post_type','operator'=>'=','value'=>1),
    ];
    $medias =  App\Posts::query_stories($args,'updated_at','asc',20);
    ?>
    <header class="main_menu single_page_menu">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-12">
                    <nav class="navbar navbar-expand-lg navbar-light">
                        <a class="navbar-brand" href="https://www.kindward.com/"><img src="https://www.kindward.com/img/logos/kindward_logo.png" alt="logo"></a>
                        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation"><span class="navbar-toggler-icon"></span></button>
                        <div class="collapse navbar-collapse main-menu-item justify-content-end" id="navbarSupportedContent">
                            {!! display_menu($slug) !!}
                        </div>
                    </nav>
                </div>
            </div>
        </div>
    </header>
    <section class="breadcrumb breadcrumb_bg">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="breadcrumb_iner text-center">
                        <div class="breadcrumb_iner_item">
                            <h2><i class="fa fa-book teal"></i><br>Our Media</h2>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="blog_area section_padding">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 mb-5 mb-lg-0">
                    <div class="blog_left_sidebar list_media">
                        <div class="row">
                            @if (count($medias) > 0)
                                @foreach ($medias as $media)
                                    <div class="col-lg-6 col-sm-12 float-left">
                                        <article class="blog_item">
                                            <div class="blog_item_img" style="background-image: url({{ App\Media::get_media_detail($media->post_image)->link }})">
                                                @if( isset($media->post_image) )
                                                    <img class="card-img img-fluid rounded-lg" src="{{ App\Media::get_media_detail($media->post_image)->link }}" alt="image">
                                                @endif
                                            </div>
                                            <div class="blog_details">
                                                <a class="d-inline-block" href="{{ url('/post/'.$media->slug) }}"><h2>{{ $media->post_title }}</h2></a>
                                                {!! limit_text($media->post_content,15) !!}
                                                <ul class="blog-info-link">
                                                    <li><a href="" onclick="return false;"><i class="fa fa-user"></i> {{ App\User::getUserByID($media->user_id)->name }}</a></li>
                                                    <li><a href="" onclick="return false;"><i class="fa fa-comments"></i> {{ App\Posts::get_comment_post($media->id)->total() }} Comments</a></li>
                                                </ul>
                                                <div class="text-center pad-top-5">
                                                    @if( get_meta_post ($media->id,'audio') )
                                                            <audio controls=""><source src="{{  App\Media::get_media_detail(get_meta_post ($media->id,'audio'))->link }}" type="audio/mpeg"></audio>
                                                    @endif
                                                </div>
                                            </div>
                                        </article>
                                    </div>
                                @endforeach
                            @endif
                        </div>
                        @if($medias->hasMorePages())
                            <nav class="blog-pagination justify-content-center d-flex">
                                <ul class="pagination">
                                    @if( $medias->previousPageUrl())
                                        <li class="page-item"><a href="{{ $medias->previousPageUrl() }}" class="page-link" aria-label="Previous"><i class="fa fa-arrow-left"></i></a></li>
                                    @endif
                                    <li class="page-item {{ $medias->currentPage()?'active':'' }}"><a class="page-link">{{ $medias->currentPage() }}</a></li>
                                    @if( $medias->nextPageUrl())
                                        <li class="page-item"><a href="{{ $medias->nextPageUrl() }}" class="page-link" aria-label="Next"><i class="fa fa-arrow-right"></i></a></li>
                                    @endif
                                </ul>
                            </nav>
                        @endif
                    </div>
                </div>
                <div class="col-lg-4">
                    @include('frontend.sidebar_right');
                </div>
            </div>
        </div>
    </section>
@endsection
