@extends('layouts.layout_main')
@section('content')
    <section class="account-page push-top-25 pb-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-xl-5">
                    <div class="section_tittle text-center mt-5">
                        <h2 class="teal">My Order</h2>
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
                                            <li><a href="{{ url('my-account') }}" class="btn btn-unipro btn-block"><b>View my account</b></a></li>
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
                        <div class="card-body Information-profile">
                            <div class="list-orders">
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
                                <div class="pagition">
                                    {{ $myorders->links() }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    </section>
@endsection
