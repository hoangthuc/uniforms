@extends('layouts.layout_main')
@section('content')
    <?php
    $user = Auth::user();
    $faq_d = App\Options::get_option($user->id,'option_faq');
    $myorders = \App\Orders::get_ordersBy_user($user->id);
    $myprocessing = \App\Orders::get_ordersBy_user_processing($user->id);
    if($faq_d)$faq = json_decode($faq_d);
    $avata_id = get_user_meta($user->id,'avata');
    if( isset($avata_id) )$avata =  App\Media::get_media_detail($avata_id);
    $order_status = App\Orders::order_status();
    ?>
    <section class="account-page push-top-25 pb-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-xl-5">
                    <div class="section_tittle text-center mt-5">
                        <h2 class="teal">My Account</h2>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <div class="card card-primary card-outline">
                        <div class="card-body box-profile">
                            <div class="text-center">
                                <form id="frmEditProfileImage" method="post" class="form-light push-top-25" role="form">
                                    <input id="avata" name="avata" type="hidden" value="" data-type="meta">
                                    <div class="avatar text-center">
                                        @if( isset($avata) )
                                            <img src="{{ $avata->link }}" class="profile-user-img img-fluid img-circle" alt="profile image">
                                        @else
                                            <img src="{{ asset('uploads/users/user.png') }}" class="profile-user-img img-fluid img-circle" alt="profile image">
                                        @endif
                                        <span class="post-action-link action-edit-avatar" data-toggle="modal" data-target="#MediaModal" ><i class="fas fa-plus"></i></span>

                                    </div>
                                </form>
                            </div>

                            <h3 class="profile-username text-center mt-3">{{ $user->name }}</h3>

                            <ul class="list-group list-group-unbordered mb-3">
                                <li class="list-group-item">
                                    <b>Orders</b> <a class="float-right">{{ count($myorders) }}</a>
                                </li>
                                <li class="list-group-item">
                                    <b>Cart</b> <a class="float-right mini-cart">0</a>
                                </li>
                                <li class="list-group-item">
                                    <b>Processing</b> <a class="float-right">{{ $myprocessing->total() }}</a>
                                </li>
                            </ul>

                            <div class="action_profile">
                                @if( Illuminate\Support\Facades\Auth::check())
                                    <aside class="single_sidebar_widget search_widget">
                                        <ul class="text-left sidebar-menu list-unstyled push-top-15">
                                            @if($user->level =='administrator')
                                            <li><a href="{{ url('admin') }}" class="btn btn-unipro btn-block mb-3"><b>Dashboard</b></a></li>
                                            @endif
                                            <li><a href="#" class="btn btn-unipro btn-block" data-toggle="modal" data-target="#ListMyOrder"><b>View my order</b></a></li>
                                            <li><a href="{{ url('cart') }}" class="btn btn-unipro btn-block mt-3"><b>View cart</b></a></li>
                                            <li><a href="javascript:logout();" class="btn btn-unipro btn-block mt-3"><b>Log Out</b></a></li>
                                        </ul>
                                    </aside>
                                @endif
                            </div>
                        </div>
                        <!-- /.card-body -->
                    </div>

                </div>
                <div class="col-md-8">
                    <div class="card ">
                        <div class="card-header p-2 bg-white">
                            <ul class="nav nav-pills">
                                <li class="nav-item"><a class="nav-link font-weight-bold active" href="#frmEditAccount" data-toggle="tab">Account</a></li>
                                <li class="nav-item"><a class="nav-link font-weight-bold" href="#frmEditSocialMedia" data-toggle="tab">Social</a></li>
                            </ul>
                        </div>
                        <div class="card-body Information-profile">
                         <div class="job-details-wrapper tab-content">
                            <form id="frmEditAccount"   method="post" class="tab-pane active form-light mt-20" role="form">
                                <h3 class="teal semibold">Edit Account Information</h3>
                                <p class="push-bottom-25">Update your account by using the form below. When you are done, click "<strong>Save</strong>" to return to your account.</p>
                                <fieldset class="bordered-fieldset">
                                    <legend class="teal"><i class="fa fa-info-circle"></i> Account Information</legend>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Username</label>
                                                <input id="username" type="text" class="form-control" data-type="main" value="{{ $user->username }}" readonly>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Name</label>
                                                <input id="name" name="name" type="text" data-type="main" class="form-control" value="{{ $user->name }}">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Email</label>
                                                <input id="email" name="email" type="email" data-type="main" class="form-control" value="{{ $user->email }}">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Password</label>
                                                <input id="password" name="password" type="password" data-type="main" class="form-control" placeholder="Unchanged" value="">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Address</label>
                                                <input id="address" name="address" type="text" data-type="meta" class="form-control" value="">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>City</label>
                                                <input id="city" name="city" type="text" data-type="meta" class="form-control" value="">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>State</label>
                                                <select id="state" name="state" class="form-control" data-type="meta">
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
                                                <label>Zip Code</label>
                                                <input id="zipcode" name="zipcode" type="text" class="form-control" value="" data-type="meta">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Phone</label>
                                                <input id="phone" name="phone" type="text" class="form-control" value="(800) 555-1212" data-type="meta">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Gender</label>
                                                <select id="gender" name="gender" class="form-control" data-type="meta">
                                                    <option value="1">Male</option>
                                                    <option value="2">Female</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label>Bio</label>
                                                <textarea id="bio" name="bio" class="form-control" rows="4" data-type="meta"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label>Internal Notes</label>
                                                <textarea id="notes" name="notes" class="form-control" rows="4" data-type="meta"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <button type="button" onclick="save_account('#frmEditAccount')" class="btn btn-unipro float-right"><i class="fa fa-save"></i> Save</button>
                                        </div>
                                    </div>
                                </fieldset>
                            </form>
                            <form id="frmEditSocialMedia" method="post" class="tab-pane form-light mt-20 push-top-25 push-bottom-25" role="form">
                                <fieldset class="bordered-fieldset">
                                    <legend class="teal"><i class="fas fa-users-cog"></i> Social Media Information</legend>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label><i class="fa fa-globe teal"></i> Website</label>
                                                <input id="website" name="website" type="text" class="form-control" value=""  data-type="meta">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label><i class="fab fa-linkedin-in"></i> LinkedIn</label>
                                                <input id="linkedin" name="linkedin" type="text" class="form-control" value=""  data-type="meta">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label><i class="fab fa-facebook-square"></i> Facebook</label>
                                                <input id="facebook" name="facebook" type="text" class="form-control" value=""  data-type="meta">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label><i class="fab fa-twitter"></i> Twitter</label>
                                                <input id="twitter" name="twitter" type="text" class="form-control" value=""  data-type="meta">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label><i class="fab fa-instagram"></i> Instagram</label>
                                                <input id="instagram" name="instagram" type="text" class="form-control" value=""  data-type="meta">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label><i class="fab fa-skype"></i> Skype</label>
                                                <input id="skype" name="skype" type="text" class="form-control" value=""  data-type="meta">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label><i class="fab fa-snapchat"></i> Snapchat</label>
                                                <input id="snapchat" name="snapchat" type="text" class="form-control" value=""  data-type="meta">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label><i class="fab fa-youtube"></i> YouTube</label>
                                                <input id="youtube" name="youtube" type="text" class="form-control" value=""  data-type="meta">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <button type="button" id="btnEditProfile" onclick="save_account('#frmEditSocialMedia')" class="btn btn-md btn-unipro float-right" ><i class="fa fa-save"></i> Save</button>
                                        </div>
                                    </div>
                                </fieldset>
                            </form>
                        </div>
                        </div>
                    </div>
                </div>
        </div>
    </section>

    <div id="ListMyOrder" class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-body table-responsive">
                    <table class="table table-striped">
                        <thead class="thead-dark">
                        <tr>
                            <th scope="col">Date</th>
                            <th scope="col">Order ID</th>
                            <th scope="col">Status</th>
                            <th scope="col">Total</th>
                        </tr>
                        </thead>
                        <tbody id="resulfMyOrder">
                        @if($myorders)
                            @foreach($myorders as $order)
                        <tr>
                            <th scope="row">{!! date("m/d/Y H:i A",strtotime( $order->created_at ) )  !!}</th>
                            <td><a href="{{ url('order/'.$order->id.'?order_key='.md5($order->id)) }}">Order #{{ $order->id }}</a></td>
                            <td><span class="status badge badge-{{ $order->status == 3?'success':'dark' }}">{{ $order_status[$order->status] }}</span></td>
                            <td>{{ format_currency($order->total,2,'$') }}</td>
                        </tr>
                            @endforeach
                        @endif
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
@endsection
