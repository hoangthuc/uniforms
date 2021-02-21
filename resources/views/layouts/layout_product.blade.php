<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>{{ get_field_option('site_title') }} | Product Details</title>
    <link rel="icon" href="{{ asset('img/favicon.png') }}">
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/font-awesome/font-awesome-4.7.0/css/font-awesome.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/animate.css') }}">
    <link rel="stylesheet" href="{{ asset('css/owl.carousel.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/flaticon.css') }}">
    <link rel="stylesheet" href="{{ asset('css/magnific-popup.css') }}">
    <link rel="stylesheet" href="{{ asset('css/slick.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/jquery-timepicker/v1.11.14/jquery.timepicker.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/datatables/v1.10.18/datatables.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/summernote/v0.8.12/summernote-bs4.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/image-picker/image-picker.css') }}">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">
</head>
<body>
@yield('content')
<footer class="footer-area">
    <div class="container">
        <div class="row justify-content-between">
            <div class="col-sm-6 col-md-4 col-xl-4">
                <div class="single-footer-widget footer_1">
                    <a href="{{ url('/') }}"><img src="{{ asset('img/logos/kindward_logo.png') }}" alt="logo" /> </a>
                    <p>{{ get_field_option('site_description') }}</p>
                </div>
            </div>
            <div class="col-sm-6 col-md-4 col-xl-4">
                <div class="single-footer-widget footer_2">
                    <h4><i class="fa fa-envelope-o"></i> Newsletter</h4>
                    <p>{{ get_field_option('site_welcome') }}</p>
                    <form action="#">
                        <div class="form-group">
                            <div class="input-group mb-3">
                                <input type="text" id="email" name="email" class="form-control" placeholder="Email Address" />
                                <div class="input-group-append">
                                    <button type="button" class="btn btn_1"><i class="fa fa-send"></i></button>
                                </div>
                            </div>
                        </div>
                    </form>
                    <div class="social_icon social-network">
                        <a href="{{ get_field_option('facebook') }}" target="_blank"> <i class="fa fa-facebook"></i></a>
                        <a href="{{ get_field_option('twitter') }}" target="_blank"> <i class="fa fa-twitter"></i></a>
                        <a href="{{ get_field_option('instagram') }}" target="_blank"> <i class="fa fa-instagram"></i></a>
                        <a href="{{ get_field_option('youtube') }}" target="_blank"> <i class="fa fa-youtube"></i></a>
                    </div>
                </div>
            </div>
            <div class="col-xl-4 col-sm-6 col-md-4">
                <div class="single-footer-widget footer_2">
                    <h4><i class="fa fa-bullhorn"></i> Contact Us</h4>
                    <div class="contact_info">
                        <p class="py-0 my-0"><span class="py-0 my-0"><i class="fa fa-phone teal"></i></span> <a href="tel:{{ get_field_option('site_phone') }}" class="teal">{{ get_field_option('site_phone') }}</a></p>
                        <p class="py-0 my-0"><span class="py-0 my-0"><i class="fa fa-comment teal"></i></span> <a href="#" class="teal">Send Us a Message!</a></p>
                        <p class="py-0 my-0"><span class="py-0 my-0"><i class="fa fa-envelope teal"></i></span> <a href="mailto:{{ get_field_option('site_email') }}" class="teal">{{ get_field_option('site_email') }}</a></p>
                        <p class="py-0 my-0"><span class="py-0 my-0"><i class="fa fa-exclamation-triangle teal"></i></span> <a href="#" class="teal">Disclaimer</a></p>
                        <p class="py-0 my-0"><span class="py-0 my-0"><i class="fa fa-shield teal"></i></span> <a href="#" class="teal">Terms of Use</a></p>
                        <p class="py-0 my-0"><span class="py-0 my-0"><i class="fa fa-lock teal"></i></span> <a href="#" class="teal">Privacy Policy</a></p>
                        <p class="py-0 my-0"><span class="py-0 my-0"><i class="fa fa-question-circle teal"></i></span> <a href="#" class="teal">Product Issues</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="copyright_part_text text-center">
                    <div class="row">
                        <div class="col-lg-12">
                            <p class="footer-text m-0">Copyright &copy; 2020 <a href="#" class="teal">kindward.com</a>. All rights reserved | Developed by <a href="#" class="teal" >Kenny</a></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</footer>
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" arialabelledby="modalTitle" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTitle">Modal title</h5>
                <button type="button" class="close red" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <h2>Modal body heading</h2>
                <p>Modal body text description</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary">Save changes</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="modal-edit-profile-data" tabindex="-1" role="dialog" arialabelledby="modalTitle" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form id="frmEditSocialMedia" name="frmEditSocialMedia" action="#" method="post" role="form">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitle"><i class="fa fa-users"></i> Update Your Social Media Information</h5>
                    <button type="button" class="close red" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                    <p class="red semibold">Only enter your USERNAME for each service.</p>
                    <input type="hidden" id="action" name="action" value="save_user_profile_data" />
                    <input type="hidden" id="user_id" name="user_id" value="0" />
                    <input type="hidden" id="id" name="id" value="0" />
                    <div class="row">
                        <div class="col-md-6 col-sm-12">
                            <div class="form-group">
                                <label><i class="fa fa-globe"></i> Website</label>
                                <input type="text" class="form-control" id="website" name="website" value="" />
                            </div>
                        </div>
                        <div class="col-md-6 col-sm-12">
                            <div class="form-group">
                                <label><i class="fa fa-linkedin"></i> LinkedIn</label>
                                <input type="text" class="form-control" id="linkedin" name="linkedin" value="" />
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 col-sm-12">
                            <div class="form-group">
                                <label><i class="fa fa-facebook"></i> Facebook</label>
                                <input type="text" class="form-control" id="facebook" name="facebook" value="" />
                            </div>
                        </div>
                        <div class="col-md-6 col-sm-12">
                            <div class="form-group">
                                <label><i class="fa fa-twitter"></i> Twitter</label>
                                <input type="text" class="form-control" id="twitter" name="twitter" value="" />
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 col-sm-12">
                            <div class="form-group">
                                <label><i class="fa fa-instagram"></i> Instagram</label>
                                <input type="text" class="form-control" id="instagram" name="instagram" value="" />
                            </div>
                        </div>
                        <div class="col-md-6 col-sm-12">
                            <div class="form-group">
                                <label><i class="fa fa-skype"></i> Skype</label>
                                <input type="text" class="form-control" id="skype" name="skype" value="" />
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 col-sm-12">
                            <div class="form-group">
                                <label><i class="fa fa-snapchat"></i> SnapChat</label>
                                <input type="text" class="form-control" id="snapchat" name="snapchat" value="" />
                            </div>
                        </div>
                        <div class="col-md-6 col-sm-12">
                            <div class="form-group">
                                <label><i class="fa fa-youtube"></i> YouTube</label>
                                <input type="text" class="form-control" id="youtube" name="youtube" value="" />
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger float-left" data-dismiss="modal"><i class="fa fa-close fa-right-5"></i> Cancel</button>
                    <button type="submit" id="btnSubmit" name="btnSubmit" class="btn btn-primary float-right"><i class="fa fa-save fa-right-5"></i> Save</button>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="modal fade" id="modal-edit-avatar" tabindex="-1" role="dialog" arialabelledby="modalTitle" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form id="frmEditAvatar" name="frmEditAvatar" method="post" action="#" enctype="multipart/form-data" role="form">
            <input type="hidden" id="action" name="action" value="do_update_avatar" />
            <input type="hidden" id="user_id" name="user_id" value="0" />
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitle"><i class="fa fa-image"></i> Edit Profile Image</h5>
                    <button type="button" class="close red" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                    <h4 style="font-weight:bold; padding-left:10px;"><i class="fa fa-image"></i> Image</h4>
                    <div class="form-group">
                        <div class="col-md-12 push-bottom-25">
                            <input type="file" id="image_main" name="image_main[]" class="form-control-file" accept="image/*" />
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" id="btnAvatarCancel" name="btnAvatarCancel" class="btn btn-danger float-left" data-dismiss="modal"><i class="fa fa-close fa-right-5"></i> Cancel</button>
                    <button type="button" id="btnUpdateAvatar" name="btnUpdateAvatar" class="btn btn-primary float-right"><i class="fa fa-save fa-right-5"></i> Update</button>
                </div>
            </div>
        </form>
    </div>
</div>
<script>
        <?php
        $cart = Request::session()->get('cart');
        ?>
    var cart = {products:[]};
    @if(isset($cart) && isset($cart['products']))
      cart = {!! json_encode($cart) !!}
    @endif
    @if(Auth::check())
       cart['user'] = {!! json_encode(Auth::user()) !!};
    @endif
    var setting = {
            'upload_ajax_url':'{{ url('nopriv_upload') }}',
            'ajax_url':'{{ url('nopriv_ajax') }}',
            'token':'{{ csrf_token() }}',
        };

</script>
<script src="{{ asset('plugins/jQuery/v2.2.4/jquery-2.2.4.min.js') }}"></script>
<script src="{{ asset('plugins/jQuery-ui-1.11.4.custom/jquery-ui.min.js') }}"></script>
<script src="{{ asset('js/popper.min.js') }}"></script>
<script src="{{ asset('js/bootstrap.min.js') }}"></script>
<script src="{{ asset('js/jquery.magnific-popup.js') }}"></script>
<script src="{{ asset('js/swiper.min.js') }}"></script>
<script src="{{ asset('js/masonry.pkgd.js') }}"></script>
<script src="{{ asset('js/owl.carousel.min.js') }}"></script>
<script src="{{ asset('js/jquery.nice-select.min.js') }}"></script>
<script src="{{ asset('js/slick.min.js') }}"></script>
<script src="{{ asset('js/jquery.counterup.min.js') }}"></script>
<script src="{{ asset('js/waypoints.min.js') }}"></script>
<script src="{{ asset('js/xzoom.min.js') }}"></script>
<script src="{{ asset('js/hammer.min.js') }}"></script>
<script src="{{ asset('js/foundation.min.js') }}"></script>
<script src="{{ asset('js/local_xzoom.js') }}"></script>
<script src="{{ asset('plugins/jQuery-timepicker/v1.11.14/jquery.timepicker.js') }}"></script>
<script src="{{ asset('plugins/datepicker/bootstrap-datepicker.js') }}"></script>
<script src="{{ asset('plugins/datatables/v1.10.18/datatables.js') }}"></script>
<script src="{{ asset('plugins/summernote/v0.8.12/summernote-bs4.min.js') }}"></script>
<script src="{{ asset('plugins/image-picker/image-picker.min.js') }}"></script>
<script src="{{ asset('framework/sweetalert2/dist/sweetalert2.all.min.js') }}"></script>
<script src="{{ asset('js/custom.js') }}"></script>
<script src="{{ asset('js/main.js') }}"></script>
</body>
</html>