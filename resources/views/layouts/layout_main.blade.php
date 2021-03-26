<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no"/>
    <title>{{ get_field_option('site_title') }}</title>
    <link rel="icon" href="{{ asset('images/favicon.png') }}"/>
    <link href="https://fonts.googleapis.com/css2?family=Rajdhani:wght@300;400;500;600;700&display=swap"
          rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('plugins/fontawesome-5.14.0/css/all.css') }}"/>
    <link rel="stylesheet" href="{{ asset('plugins/bootstrap-4.5.3/css/bootstrap.min.css') }}"/>
    <link rel="stylesheet" href="{{ asset('plugins/sweetalert2/dist/sweetalert2.min.css') }}"/>
    <link rel="stylesheet" href="{{ asset('plugins/owlcarousel2-2.3.4/assets/owl.carousel.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/owlcarousel2-2.3.4/assets/owl.theme.default.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/slick/slick.css') }}"/>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}"/>
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}"/>
    <link rel="stylesheet" href="{{ asset('css/animate.min.css') }}"/>
    @yield('paypal')
</head>
<body>
<header>
<?php
//$product_categories = App\Product::get_product_categories_all();
//$product_categories = App\Product::get_sort_categories($product_categories);
$product_departments = \App\Product::product_departments();
$type = (isset($_GET['type'])) ? $_GET['type'] : '';
$search = isset($_GET['search']) ? $_GET['search'] : '';
?>
<!--Header Top-->
    <div class="header-top mdl-desktop">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <div class="headline">Uniforms for the professionals.</div>
                </div>
                <div class="col-md-6 text-right">
                    <ul class="head-contact">
                        <li><a href="tel:{{ get_field_option('site_phone') }}"><i class="fas fa-phone-alt"></i> Toll
                                free: {{ get_field_option('site_phone') }}</a></li>
                        <li class="border-left"><a href="{{ url('about') }}">About us</a></li>
                        <li class="border-left"><a href="{{ url('contact-us') }}">Contact</a></li>
                        @if(Auth::check())
                            <li class="border-left my-account-menu dropdown">
                                {!! display_menu_account() !!}
                            </li>
                        @else
                            <li class="border-left"><a href="{{ url('login') }}">Login</a></li>
                            <li class="border-left"><a href="{{ url('register') }}">Register</a></li>
                        @endif
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <!--Header main-->
    <div class="header-main mdl-desktop">
        <div class="container">
            <div class="row">
                <div class="col-md-2">
                    <div class="logo">
                        <a href="{{ url('/') }}"><img src="{{ url('images/unipro_stars_logo.png') }}"></a>
                    </div>
                </div>
                <div class="col-md-8">
                    <form class="search-product" action="{{ url('products') }}">
                        <div class="d-flex">
                            <div class="show_select">
                                <span>{{ ($type)?$product_departments[$type]:'All' }}</span>
                                <i class="fas fa-sort-down"></i>
                                <select name="type" onchange="change_cat(this,'form .show_select span')">
                                    <option value="">All</option>
                                    @if($product_departments)
                                        @foreach($product_departments as $cat_d => $de)
                                            <option value="{{ $cat_d }}" {{ $type == $cat_d ?'selected':'' }}>{{ $de }}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                            <input type="text" placeholder="Search with name or SKU of product" name="search"
                                   value="{{ $search }}" autocomplete="off"
                                   onkeyup="search_key_product(this)">
                        </div>
                        <button><i class="fas fa-search"></i></button>
                        <div class="resulf-search d-none" DataSearchProduct></div>
                    </form>
                </div>
                <div class="col-md-2 head-mini-cart text-right">
                    <div class="location_mark d-flex">
                        <i class="fas fa-map-marker-alt"></i>
                        <span class="font-weight-bold text-left ml-1">Location</span>
                    </div>
                    <div class="cart">
                        <a href="{{ route('cart') }}"><i class="fas fa-shopping-cart"></i>
                            <span id="mini-cart" class="mini-cart badge badge-dark badge-shopping-cart">0</span>
                        </a>
                    </div>
                </div>

            </div>
        </div>
        <div class="main-menu-desktop">
            <div class="container p-0">
                <div class="col-md-12 p-0">
                    <ul class="menu-main d-flex">
                        @if($product_departments)
                            @foreach($product_departments as $cat_d => $de)
                                <li class="nav-item-desktop display_submenu" data-cat="{{ $cat_d }}"
                                    onmouseover="display_department(this)">
                                    <a class="nav-link-desktop" href="#" target="_self">{{ $de }}</a>
                                </li>
                            @endforeach
                        @endif
                    </ul>
                    <div class="display_all_list_categories d-none">
                        @foreach($product_departments as $cat_d => $de)
                            <div class="row p-0" data-department="{{$cat_d}}">
                                <div class="heading_departments" >
                                    <a class="view_all_department" href="{{ url('products?type='.$cat_d) }}">View All
                                        <span>{{ $de }}</span></a>
                                </div>
                                <div class="parent_cattegories {{ $cat_d =='department'?'row':'d-flex'  }} m-0 p-3 pb-3">
                                    <?php
                                    $product_categories = list_menu_categories($cat_d);
                                    $product_categories_mobile[$cat_d] = $product_categories; ?>
                                    @foreach($product_categories as $category)
                                        <div class="col-category {{ $cat_d =='department'?'col-md-3':''  }} mb-1">
                                            <a class="category_main font-weight-bold"
                                               data-parent="{{ $category['id'] }}"
                                               href="{{ url('product_categories/'.$category['slug']) }}">{{ $category['name'] }}
                                                <i class="fas fa-angle-double-right" style="font-size: 15px;"></i></a>
                                            @if( isset($category['child']) )
                                                @foreach($category['child'] as $category_child)
                                                    <a class="show_child {{ $cat_d =='department'?'d-none':''  }}"
                                                       href="{{ url('product_categories/'.$category_child['slug']) }}">{{ $category_child['name'] }}
                                                    </a>
                                                @endforeach
                                            @endif
                                        </div>
                                    @endforeach
                                    <a class="category_main d-none font-weight-bold" data-department="ppe"
                                           data-parent=""
                                           href="#" style="color: #ed2d0d"><i class="fas fa-globe"
                                                                              style="font-size: 20px;"></i> Coming soon</a>
                                    <a class="category_main d-none font-weight-bold" data-department="specials"
                                       data-parent=""
                                       href="#" style="color: #ed2d0d"><i class="fas fa-globe"
                                                                          style="font-size: 20px;"></i> Coming soon</a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--Header main mobile-->
    <div class="header-main mdl-mobile">
        <div class="nav-top d-flex">
            <span class="hamburger material-icons" id="ham">menu</span>
            <div class="logo">
                <a href="{{ url('/') }}"><img src="{{ url('images/logo_uniforms.png') }}"></a>
            </div>
            <div class="mobile-mini-cart text-right d-flex">
                <button type="button" data-toggle="modal" data-target="#FormSearchMobile"><i class="fas fa-search"></i>
                </button>
                <div class="cart"><a href="{{ url('cart') }}"><i class="fas fa-shopping-cart"></i><span id="mini-cart"
                                                                                                        class="mini-cart badge badge-dark badge-shopping-cart">0</span></a>
                </div>
            </div>
        </div>
        <nav class="nav-main-mobile">
            <div class="mobile-top text-right">
                <ul class="head-contact">
                    <li class="border-left"><a href="{{ url('about') }}">About us</a></li>
                    <li class="border-left"><a href="{{ url('contact-us') }}">Contact</a></li>
                    @if(Auth::check())
                        <li class="border-left my-account-menu dropdown">
                            {!! display_menu_account() !!}
                        </li>
                    @else
                        <li class="border-left"><a href="{{ url('login') }}">Login</a></li>
                        <li class="border-left"><a href="{{ url('register') }}">Register</a></li>
                    @endif
                </ul>
            </div>
            <div class="main-menu-categories-mobile">
                @foreach($product_departments as $value_department => $department)
                    <div class="Parent-category">
                        <div class="category-header">
                            <a href="{{ url('products?type='.$value_department) }}" class="category-title">{{ $department }}</a>
                            <span class="btn-tool" data-card-widget="collapse" data-show="">
                                  <i class="fas fa-plus"></i>
                              </span>
                        </div>
                        <div class="category-body collapse" style="">
                            @foreach($product_categories_mobile[$value_department] as $category)
                                    <div class="category-child parent_cat">
                                        <a class="font-weight-bold" href="{{ url('product_categories/'.$category['slug']) }}">{{ $category['name'] }}</a>
                                    </div>

                                    @if( isset($category['child']) )
                                        @foreach($category['child'] as $category_child)
                                            <div class="category-child">
                                                <a href="{{ url('product_categories/'.$category_child['slug']) }}">{{ $category_child['name'] }}</a>
                                            </div>
                                        @endforeach
                                    @endif
                            @endforeach
                                @if($value_department =='ppe' || $value_department == 'specials')
                                    <div class="category-child parent_cat">
                                        <a class="category_main font-weight-bold" href="#" style="color: #ed2d0d"><i class="fas fa-globe" style="font-size: 20px;"></i> Coming soon</a>
                                    </div>
                                @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </nav>
        <div class="close-shadow-mobile" onclick="close_menu_mobile()">

        </div>
    </div>

</header>
@yield('content')
<footer class="footer-area pt-5 pl-3 pr-3 pb-3">
    <div class="container">
        <div class="row justify-content-between">
            <div class="col-lg-3 col-sm-4 mt-sm-0">
                <div class="single-footer-widget footer_1">
                    <div class="logo_footer">
                        <a href="{{ url('/') }}"><img src="{{ asset('images/logo_uniforms.png') }}" alt="logo"/> </a>
                    </div>
                    <label class="mt-5">Follows us on</label>
                    <ul class="social">
                        <li><a href="{{ get_field_option('facebook') }}" target="_blank" class="social-facebook"><i
                                        class="fab fa-facebook-f"></i></a></li>
                        <li><a href="{{ get_field_option('twitter') }}" target="_blank" class="social-twitter"><i
                                        class="fab fa-twitter"></i></a></li>
                        <li><a href="{{ get_field_option('instagram') }}" target="_blank" class="social-instagram"><i
                                        class="fab fa-instagram"></i></a></li>
                    </ul>
                </div>
            </div>
            <div class="col-lg-2 col-sm-4 col-6 mt-sm-0">
                <div class="footer_2">
                    <h4>Customer service</h4>
                    <ul class="service">
                        @foreach( display_menu_data('footer-1') as $item )
                            <li><a class="{{ $item->title }}" href="{{ $item->href }}">{{ $item->text }}</a></li>
                        @endforeach
                    </ul>
                </div>
            </div>
            <div class="col-lg-2 col-sm-4 col-6 mt-sm-0">
                <div class="footer_2">
                    <h4>About us</h4>
                    <ul class="pages">
                        @foreach( display_menu_data('footer-2') as $item )
                            <li><a class="{{ $item->title }}" href="{{ $item->href }}">{{ $item->text }}</a></li>
                        @endforeach
                    </ul>
                </div>
            </div>
            <div class="col-lg-3 mt-lg-0 mt-sm-5 col-sm-6">
                <div class="footer_2">
                    <h4>Contact us</h4>
                    <ul class="contacts">
                        <li><a><i class="fas fa-headphones-alt"></i>Online {{ get_field_option('site_work_time') }}</a>
                        </li>
                        <li class="mt-2"><a><i class="fas fa-map-marker-alt"></i>{{ get_field_option('site_address') }}
                            </a></li>
                        <li class="mt-2"><a><i class="far fa-envelope"></i>{{ get_field_option('site_email') }}</a></li>
                        <li class="mt-2"><a><i class="fas fa-phone-volume"></i>Toll
                                free: {{ get_field_option('site_phone') }}</a></li>
                    </ul>
                </div>
            </div>
            <div class="col-lg-2 col-sm-6">
                <div class="footer_2">
                    <div class="partner text-center">
                        <p class="mt-2"><a href="#"><img src="{{ url('images/authorize.png')  }}"></a></p>
                        <p class="mt-2"><a href="#"><img src="{{ url('images/wbenc.png')  }}"></a></p>
                    </div>
                </div>
            </div>
        </div>
        <div class="copy-right text-center">
            <p>2020 Unipro international. All rights reserved.</p>
        </div>
    </div>
</footer>
<!---Loading https://loading.io/css/ -->
<div id="loadingpage" class="d-none">
    <div class="lds-ripple">
        <div></div>
        <div></div>
    </div>
</div>
<!-- Modal -->
<div class="modal fade" id="MediaModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">
            <div class="card-audio card-primary card-outline card-outline-tabs">
                <div class="card-header p-0 border-bottom-0">
                    <ul class="nav nav-tabs" id="custom-tabs-four-tab" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="button-upload-media" data-toggle="pill"
                               href="#tabs-upload-media" role="tab" aria-controls="custom-tabs-four-home"
                               aria-selected="false">Upload file</a>
                        </li>
                    </ul>
                </div>
                <div class="card-body">
                    <div class="tab-content" id="custom-tabs-four-tabContent">
                        <div class="tab-pane fade active show" id="tabs-upload-media" role="tabpanel"
                             aria-labelledby="tabs-upload-media">
                            <div class="upload-file">
                                <input type="file" class="file-audio" name="UploadMedia" accept="image/*">
                                <button type="button">Upload Media</button>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="tabs-library" role="tabpanel" aria-labelledby="tabs-library">
                            <div id="grid-medias">

                            </div>
                            <div class="control-media text-right mt-3">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                <button type="button" id="insert_media_modal" class="btn btn-primary">Insert Media
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /.card -->
            </div>

        </div>
    </div>
</div>
<div class="modal fade" id="FormSearchMobile" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>

                <form class="search-product search-product-mobile" action="{{ url('products') }}">
                    <div class="d-flex">
                        <div class="show_select">
                            <span>All</span>
                            <i class="fas fa-sort-down"></i>
                            <select name="cat" onchange="change_cat(this,'.search-product-mobile .show_select span')">
                                <option value="">All</option>
                                @if($product_departments)
                                    @foreach($product_departments as $cat_d => $de)
                                        <option value="{{ $cat_d }}" {{ $type == $cat_d ?'selected':'' }}>{{ $de }}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                        <input type="text" placeholder="Search" name="search" value="{{ $search }}" autocomplete="off">
                    </div>
                    <button><i class="fas fa-search"></i></button>
                </form>
            </div>
        </div>
    </div>
</div>
<script src="{{ asset('plugins/jQuery/v2.2.4/jquery-2.2.4.min.js') }}"></script>
{{--<script src="{{ asset('plugins/jQuery-ui-1.11.4.custom/jquery-ui.min.js') }}"></script>--}}
<script src="{{ asset('plugins/bootstrap-4.5.3/js/bootstrap.min.js') }}"></script>
<script src="{{ asset('plugins/bootstrap-4.5.3/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('plugins/owlcarousel2-2.3.4/owl.carousel.min.js') }}"></script>
<script src="{{ asset('plugins/slick/slick.min.js') }}"></script>
<script src="{{ asset('plugins/sweetalert2/dist/sweetalert2.all.min.js') }}"></script>
<script src="{{ asset('js/custom.js') }}"></script>
<script src="{{ asset('js/main.js') }}"></script>
<script>
    var image_default = '{{ asset('images/image-coming-soon.jpg') }}';
        <?php
        $cart = Request::session()->get('cart');
        ?>
    var cart = {products: []};
    @if(isset($cart) && isset($cart['products']))
        cart =
            {!! json_encode($cart) !!}
            @endif
    var setting = {
            'logout': '{{ url('logout') }}',
            'login_ajax_url': '{{ url('loginsite') }}',
            'upload_ajax_url': '{{ url('nopriv_upload') }}',
            'ajax_url': '{{ url('nopriv_ajax') }}',
            'token': '{{ csrf_token() }}',
            'cart': '{{ url('cart') }}',
            'checkout': '{{ url('checkout') }}',
        };

            @if( Auth::check() && isset($slug) && $slug=='my-account')

    var profile_meta = [];
    $('.job-details-wrapper [name]').each(function () {
        var k = $(this).attr('name');
        var t = $(this).data('type');
        if (t == 'meta') profile_meta.push({name: k, value: ''});
    })
    $.ajax({
        url: setting.ajax_url,
        type: 'post',
        data: {data: profile_meta, action: 'get_all_user_meta', _token: setting.token},
        success: function (resulf) {
            if (resulf) {
                resulf = JSON.parse(resulf);
                resulf.forEach(function (meta, index) {
                    $('.job-details-wrapper [name="' + meta.name + '"]').val(meta.value);
                })
            }
        }
    })

    // upload file media
    $('[name="UploadMedia"]').on('change', function () {
        var id = $(this).attr('data-media');
        var media = $(this)[0].files[0];
        var formData = new FormData();
        formData.append('UploadMedia', media);
        formData.append('_token', setting.token);
        $.ajax({
            url: setting.upload_ajax_url,
            type: 'POST',
            data: formData,
            processData: false,  // tell jQuery not to process the data
            contentType: false,  // tell jQuery not to set contentType
            success: function (resulf) {
                if (resulf) {

                    resulf = JSON.parse(resulf);
                    console.log(resulf);
                    if (resulf['success']) {
                        $('[name="UploadMedia"]').val('');
                        $('#frmEditProfileImage img').attr('src', resulf.link);
                        $('#frmEditProfileImage [name="avata"]').val(resulf.id);
                        save_account('#frmEditProfileImage');
                        $('#MediaModal').modal('hide');

                    }

                }

            }
        })
    });

    @endif
    $('select[name="cat"]').change();
</script>
@yield('footer_layout');
</body>
</html>
