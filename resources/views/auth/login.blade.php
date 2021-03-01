@extends('layouts.layout_main')
@section('content')
    <section class="login-page">
        <div class="container-fluid">
            <div class="row justify-content-center">
                <div class="col-lg-6 bg-white pt-5 mt-5 ">
                    <div class="section_tittle text-center">
                        <h2 class="teal">Log on to your account</h2>
                    </div>
                    <div class="section_description text-center">Please log in for access to convenient features and quick checkout.</div>
                </div>
            </div>
            <div class="row justify-content-center">
                <div class="col-lg-6 bg-white mb-5">
                    <div class="login-page-content">
                        <div class="login-form">
                            <div class="row justify-content-center">
                                <div class="col-md-6">
                                    <div class="col-md-12 col-centered">
                                        <div class="basic-login">
                                            <div class="alert-login"></div>
                                            @if( isset( $_GET['redirect_url'] ) )
                                            <input type="hidden" name="redirect_url" value="{{ $_GET['redirect_url'] }}">
                                            @endif
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text bg-white"><i class="fas fa-user"></i></span>
                                                </div>
                                                <input type="text" name="username" class="form-control rounded-right" placeholder="Enter Username" onkeyup="enter_key(event)" required>
                                            </div>
                                            <div class="input-group mt-3">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text bg-white"><i class="fa fa-lock"></i></span>
                                                </div>
                                                <input type="password" name="password" class="form-control rounded-right" placeholder="Enter Password" onkeyup="enter_key(event)" required>
                                            </div>

                                            <div class="input-group mt-3">
                                                <button type="button" onclick="javascript:sign_in();" class="btn btn-unipro btn-login-site form-control">Log on to proceed</button>
                                            </div>

                                            <div class="form-group text-center mt-3">
                                                <a href="{{ (isset($_GET['redirect_url']))?url('register?redirect_url='.$_GET['redirect_url']):url('register') }}" class="forgot-password d-inline-block ml-3 font-weight-bold" >Don't have account? Register. </a>
                                                <a href="#" class="forgot-password d-inline-block" >Forgot password?</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row justify-content-center d-none">
                <div class="col-lg-6 bg-unipro pt-5 pb-5 mt-5 mb-5">
                    <div class="section_tittle text-center">
                        <h2 class="text-white">New Customer</h2>
                    </div>
                    <div class="section_description text-center text-white">Create an account with UniPro to benefit from personalized services like:</div>
                    <div class="row justify-content-center">
                        <div class="col-md-8 col-lg-10 col-xl-8 pt-2">
                            <div class="content-register">
                                <ul>
                                    <li><span>Law enforcement & security</span></li>
                                    <li><span>Aviation & parking</span></li>
                                    <li><span>Fire rescue & Retardent</span></li>
                                    <li><span>Executive & career apparel</span></li>
                                    <li><span>Transportation</span></li>
                                    <li><span>Work & industrial</span></li>
                                </ul>
                            </div>
                        </div>
                        <div class="input-group col-md-6 mt-5">
                            <a href="{{ url('register') }}" class="btn btn-unipro form-control">Create Account</a>
                        </div>
                    </div>



                </div>
            </div>

            <div class="row justify-content-center">
                <div class="support text-center col-lg-6 mt-5 mb-5">
                <div class="title">Need Help?</div>
                <div class="description">If you have any question or need help with your account, you may contact us to assist you.</div>
                <div class="phone title">(888) 691-6200</div>
                </div>
            </div>
        </div>
    </section>
    <!-- /.login-box -->
@endsection