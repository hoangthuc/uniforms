@extends('layouts.layout_main')
@section('content')
    <section class="sign_in push-top-25">
        <div class="container-fluid">
            <div class="row justify-content-center">
                <div class="col-md-6 mt-5 pt-5 bg-white">
                    <div class="section_tittle text-center">
                        <h2 class="teal">Register for a new account</h2>
                    </div>
                </div>
            </div>
            <div class="row justify-content-center">
                <div class="col-md-6 pb-5 bg-white">
                    <div class="login-page-content">
                        <div class="login-form">
                            <div class="row justify-content-center">
                                <div class="col-lg-8 col-centered">
                                    <div class="basic-login">
                                        <form class="form-light" id="form_register_account">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label>Name</label>
                                                        <input type="text" class="form-control" name="name" value="{{ old('name') }}" data-title="Name" data-required="true" autocomplete="off">
                                                        <span class="error red mt-1 d-none">Name is required</span>
                                                    </div>
                                                </div>
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label>Username</label>
                                                        <input type="text" class="form-control" name="username" value="{{ old('username') }}" data-title="Username" data-required="true">
                                                        <span class="error red mt-1 d-none">Username is required</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row hide">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Address</label>
                                                        <input name="address" type="text" class="form-control" value="" data-title="Address" data-required="false">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>City</label>
                                                        <input name="city" type="text" class="form-control" value="" data-title="City" data-required="false">
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>State</label>
                                                        <select  name="state" class="form-control"  data-title="State" data-required="false">
                                                            <option value="0">-- Select --</option>
                                                            <option value="AL">Alabama</option>
                                                            <option value="AK">Alaska</option>
                                                            <option value="AZ">Arizona</option>
                                                            <option value="AR">Arkansas</option>
                                                            <option value="CA">California</option>
                                                            <option value="CO">Colorado</option>
                                                            <option value="CT">Connecticut</option>
                                                            <option value="DC">Washington D.C.</option>
                                                            <option value="DE">Delaware</option>
                                                            <option value="FL">Florida</option>
                                                            <option value="GA">Georgia</option>
                                                            <option value="HI">Hawaii</option>
                                                            <option value="ID">Idaho</option>
                                                            <option value="IL">Illinois</option>
                                                            <option value="IN">Indiana</option>
                                                            <option value="IA">Iowa</option>
                                                            <option value="KS">Kansas</option>
                                                            <option value="KY">Kentucky</option>
                                                            <option value="LA">Louisiana</option>
                                                            <option value="ME">Maine</option>
                                                            <option value="MD">Maryland</option>
                                                            <option value="MA">Massachusetts</option>
                                                            <option value="MI">Michigan</option>
                                                            <option value="MN">Minnesota</option>
                                                            <option value="MS">Mississippi</option>
                                                            <option value="MO">Missouri</option>
                                                            <option value="MT">Montana</option>
                                                            <option value="NE">Nebraska</option>
                                                            <option value="NV">Nevada</option>
                                                            <option value="NH">New Hampshire</option>
                                                            <option value="NJ">New Jersey</option>
                                                            <option value="NM">New Mexico</option>
                                                            <option value="NY">New York</option>
                                                            <option value="NC">North Carolina</option>
                                                            <option value="ND">North Dakota</option>
                                                            <option value="OH">Ohio</option>
                                                            <option value="OK">Oklahoma</option>
                                                            <option value="OR">Oregon</option>
                                                            <option value="PA">Pennsylvania</option>
                                                            <option value="RI">Rhode Island</option>
                                                            <option value="SC">South Carolina</option>
                                                            <option value="SD">South Dakota</option>
                                                            <option value="TN">Tennessee</option>
                                                            <option value="TX" selected="selected">Texas</option>
                                                            <option value="UT">Utah</option>
                                                            <option value="VT">Vermont</option>
                                                            <option value="VA">Virginia</option>
                                                            <option value="WA">Washington</option>
                                                            <option value="WV">West Virginia</option>
                                                            <option value="WI">Wisconsin</option>
                                                            <option value="WY">Wyoming</option>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Zip code</label>
                                                        <input name="zipcode" type="text" class="form-control" value="" data-title="Zip code" data-required="false">
                                                    </div>
                                                </div>


                                            </div>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Email</label>
                                                        <input type="email" class="form-control" name="email" data-title="Email" data-required="true">
                                                        <span class="error red mt-1 d-none">Email is required</span>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Phone</label>
                                                        <input name="phone" type="text" class="form-control" value="" data-title="Phone" data-required="false">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Password</label>
                                                        <input type="password" class="form-control" name="password" data-title="Password" data-required="true">
                                                        <span class="error red mt-1 d-none">Password is required</span>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Password (confirm)</label>
                                                        <input type="password" class="form-control" name="password_confirmation"  data-title="Password confirm" data-required="true">
                                                        <span class="error red mt-1 d-none">Password confirm is invalid</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <button type="button" onclick="form_register(this)" data-url="{{ url('register') }}" class="btn btn-unipro form-control">Submit registration</button>
                                            <input type="hidden" name="action" value="{{ url('/register') }}" data-title="Action" data-required="false">
                                            <input type="hidden" name="_token" value="{{ csrf_token() }}" data-title="Token" data-required="false">
                                            <a href="{{ url('login') }}" class="btn btn-purple mt-3 "><i class="fa fa-lock"></i> Already have an account? Log in</a>
                                        </form>
                                    </div>
                                </div>
                            </div>
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

        </div></section>
@endsection