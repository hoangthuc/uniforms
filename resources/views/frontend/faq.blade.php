@extends('layouts.layout_main')
@section('content')
    <?php
    $faq_title = $faq = App\Options::list_faq();
    $user_id = Auth::id();
    $faq_d = get_field_option('option_faq');
    if($faq_d)$faq = json_decode($faq_d);
    ?>
    <header class="main_menu single_page_menu">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-12">
                    <nav class="navbar navbar-expand-lg navbar-light">
                        <a class="navbar-brand" href="{{ url('/') }}"><img src="{{ asset('img/logos/kindward_logo.png') }}" alt="logo"></a>
                        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation"><span class="navbar-toggler-icon"></span></button>
                        <div class="collapse navbar-collapse main-menu-item justify-content-end" id="navbarSupportedContent">
                            {!! display_menu($slug) !!}
                        </div>
                    </nav>
                </div>
            </div>
        </div>
    </header>
    <section class="breadcrumb breadcrumb_bg" style="background-image:url('{{ asset('img/backgrounds/background_003.jpg') }}');">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="breadcrumb_iner text-center">
                        <div class="breadcrumb_iner_item">
                            <h2><i class="fa fa-question-circle teal"></i><br>Frequently Asked Questions</h2>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="push-top-35">
        <div class="container-fluid">
            <div class="row justify-content-center bg_white">
                <div class="col-xl-5">
                    <div class="section_tittle text-center">
                        <p>Put a Smile On Someone's Face</p>
                        <h2 class="teal">It's Easy!</h2>
                    </div>
                </div>
            </div>
            <div class="row member_counter">
                <div class="col-lg-3 col-sm-6">
                    <div class="single_member_counter">
                        <img src="{{ asset('img/image003_1.png') }}" class="img-fluid mx-auto rounded-circle" alt="image">
                        <h4 class="shadowed-text">Sign Up</h4>
                        <h5 class="white">Share a Story</h5>
                    </div>
                </div>
                <div class="col-lg-3 col-sm-6">
                    <div class="single_member_counter">
                        <img src="{{ asset('img/image005_2.png') }}" class="img-fluid mx-auto" alt="image">
                        <h4 class="shadowed-text">Keep Posting</h4>
                        <h5 class="white">Receive a K-note</h5>
                    </div>
                </div>
                <div class="col-lg-3 col-sm-6">
                    <div class="single_member_counter">
                        <img src="{{ asset('img/image007_3.png') }}" class="img-fluid mx-auto rounded-circle" alt="image">
                        <h4 class="shadowed-text">K-note = Free Gift</h4>
                        <h5 class="white">Send Gift To Friend</h5>
                    </div>
                </div>
                <div class="col-lg-3 col-sm-6">
                    <div class="single_member_counter">
                        <img src="{{ asset('img/image009_4.png') }}" class="img-fluid mx-auto rounded-circle" alt="image">
                        <h4 class="shadowed-text">Keep Posting</h4>
                        <h5 class="white">and sending gifts!</h5>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="testimonial_part section_padding">
        <div class="container-fluid">
            <div class="row justify-content-center">
                <div class="col-xl-5">
                    <div class="section_tittle text-center">
                        <p>Get The Answers You Need</p>
                        <h2 class="teal">Common Questions</h2>
                    </div>
                </div>
            </div>
            <div class="row justify-content-center">
                <div class="col-lg-12">
                    <div class="row">
                        <div class="col-lg-4">
                            <div class="nav nav-pills faq-nav" id="faq-tabs" role="tablist" aria-orientation="vertical">
                                <a href="#tab1" class="nav-link active" data-toggle="pill" role="tab" aria-controls="tab1" aria-selected="true"><i class="fa fa-question-circle teal"></i> Frequently Asked Questions</a>
                                <a href="#tab2" class="nav-link" data-toggle="pill" role="tab" aria-controls="tab2" aria-selected="false"><i class="fa fa-user teal"></i> Account</a>
                                <a href="#tab3" class="nav-link" data-toggle="pill" role="tab" aria-controls="tab3" aria-selected="false"><i class="fa fa-book teal"></i> Stories</a>
                                <a href="#tab4" class="nav-link" data-toggle="pill" role="tab" aria-controls="tab4" aria-selected="false"><i class="fa fa-headphones teal"></i> Media</a>
                                <a href="#tab5" class="nav-link" data-toggle="pill" role="tab" aria-controls="tab5" aria-selected="false"><i class="fa fa-shopping-cart teal"></i> Shopping</a>
                                <a href="#tab6" class="nav-link" data-toggle="pill" role="tab" aria-controls="tab6" aria-selected="false"><i class="fa fa-question teal"></i> General Help</a>
                            </div>
                        </div>
                        <div class="col-lg-8">
                            <div class="tab-content" id="faq-tab-content">
                                @if($faq)
                                    @foreach($faq as $key => $value)
                                <div class="tab-pane show {{ $key < 1?'active':'' }}" id="tab{{ $key + 1 }}" role="tabpanel" aria-labelledby="tab{{ $key + 1 }}">
                                    <div class="accordion" id="accordion-tab-{{ $key + 1 }}">
                                        @if(isset($value->value))
                                            @foreach($value->value as $key_c => $value_c)
                                        <div class="card">
                                            @if( isset($value_c->name) && $value_c->name == 'title')
                                            <div class="card-header" id="accordion-tab-{{ $key + 1 }}-heading-{{ $key_c + 1 }}">
                                                <h5><button class="btn btn-link {{ $key_c < 1?'':'collapsed' }}" type="button" data-toggle="collapse" data-target="#accordion-tab-{{ $key + 1 }}-content-{{ $key_c + 1 }}" aria-expanded="{{ $key_c < 1?'true':'false' }}" aria-controls="accordion-tab-{{ $key + 1 }}-content-{{ $key_c + 1 }}">{{ $value_c->value }}</button></h5>
                                            </div>
                                            @endif
                                            @if(isset($value_c->name) && $value_c->name == 'content')
                                            <div class="collapse {{ $key_c < 2?'show':'' }}" id="accordion-tab-{{ $key + 1 }}-content-{{ $key_c }}" aria-labelledby="accordion-tab-{{ $key + 1 }}-heading-{{ $key_c }}" data-parent="#accordion-tab-{{ $key + 1 }}">
                                                <div class="card-body">
                                                    <p>{{ $value_c->value }}</p>
                                                </div>
                                            </div>
                                            @endif
                                        </div>
                                            @endforeach
                                        @endif
                                    </div>
                                </div>
                                    @endforeach
                                @endif

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
