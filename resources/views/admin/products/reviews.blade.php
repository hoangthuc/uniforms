@extends('admin.products.product_layout')
@section('content')
    <?php
    $query = ['type'=>'product','rating'=>[],'status'=>''];
    if( isset($_GET['status']) )$query['status'] = $_GET['status'];
    if( isset($_GET['search']) )$query['search'] = $_GET['search'];
    $reviews =  App\Reviews::get_reviews($query);
    $reviews_status = App\Reviews::review_status();
    ?>
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-12">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active"><a href="{{  route('admin.reviews') }}">Reviews</a> ({{ $reviews->total() }})</li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <form id="form-filter">
                        <div class="card-header">
                            <div class="d-inline-block">
                                <select class="form-control" id="apply_reviews">
                                    <option value="delete">Delete</option>
                                    <option value="active">Change to active</option>
                                </select>
                            </div>
                            <div class="d-inline-block">
                                <button type="button" class="btn btn-primary mb-1" onclick="apply_posttype_action('#apply_reviews','#form_reviews_all','apply_reviews_action')">Apply</button>
                            </div>
                            <div class="card-tools d-flex">
                                <div class="input-group">
                                    <select name="status" class="form-control" onchange="form.submit()">
                                        <option value="">All</option>
                                        @foreach($reviews_status as $key => $status)
                                            <option value="{{ $key }}" {{ ($query['status'] == $key)?'selected':''  }}>{{ $status }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="input-group ml-1">
                                    <input type="text" name="search" class="form-control" placeholder="Search" value="{{ isset($_GET['search'])?$_GET['search']:'' }}">

                                    <div class="input-group-append">
                                        <button type="submit" class="btn btn-default"><i class="fas fa-search"></i></button>
                                    </div>
                                </div>
                            </div>

                        </div>
                        </form>
                        <!-- /.card-header -->
                        <div id="form_reviews_all" class="card-body table-responsive p-0">
                            <table class="table table-head-fixed text-nowrap table-hover">
                                <thead>
                                <tr>
                                    <th><input type="checkbox" value="off" onclick="checkbox_all(this,'#form_reviews_all')"></th>
                                    <th>Product</th>
                                    <th>Review</th>
                                    <th>Status</th>
                                    <th>Rating</th>
                                    <th>Customer</th>
                                    <th>Description</th>
                                    <th>Created</th>
                                </tr>
                                </thead>
                                <tbody>
                                @if (count($reviews) > 0)
                                    @foreach ($reviews as $review)
                                        <tr>
                                           <td><input type="checkbox" name="review_id" value="{{ $review->id }}"></td>
                                            <td class="product_name"><a href="{{ url('admin/product/'.$review->object_id.'/edit') }}">{{ $review->name }}</a></td>
                                            <td>{{ $review->title }}</td>
                                            <td>{{ $reviews_status[$review->status]  }}</td>
                                            <td><span class="font-weight-bold">{{ $review->rating  }}</span> <i class=" fas fa-star" style="color: #ffc120"></i></td>
                                            <td>{{ App\User::getUserByID($review->user_id)->name }}</td>
                                            <td style="max-width: 200px;    white-space: normal;">{!! $review->content  !!}</td>
                                            <td>{{ get_current_datetime($review->created_at) }}</td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="8" class="text-center">No reviews exist.</td>
                                    </tr>
                                @endif

                                </tbody>
                            </table>
                        </div>
                        <!-- /.card-body -->
                    </div>
                    <!-- /.card -->
                    {{ $reviews->links() }}
                </div>
            </div>
        </div>
    </section>
@endsection
