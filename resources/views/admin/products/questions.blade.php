@extends('admin.products.product_layout')
@section('content')
    <?php
    $query = ['type'=>'product','rating'=>[],'status'=>''];
    if( isset($_GET['status']) )$query['status'] = $_GET['status'];
    if( isset($_GET['search']) )$query['search'] = $_GET['search'];
    $questions =  App\Reviews::get_questions($query);
    $reviews_status = App\Reviews::review_status();
    ?>
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-12">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active"><a href="{{  route('admin.questions') }}">Questions</a> ({{ $questions->total() }})</li>
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
                                    <button type="button" class="btn btn-primary mb-1" onclick="apply_posttype_action('#apply_reviews','#form_reviews_all','apply_questions_action')">Apply</button>
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
                                    <th>Question</th>
                                    <th>Status</th>
                                    <th>Customer</th>
                                    <th>Created</th>
                                    <th>Product</th>
                                </tr>
                                </thead>
                                <tbody>
                                @if (count($questions) > 0)
                                    @foreach ($questions as $question)
                                        <tr>
                                            <td><input type="checkbox" name="question_id" value="{{ $question->id }}"></td>
                                            <td style="max-width: 200px;    white-space: normal;" class="item-attibute">
                                                <div class="title"> {!! $question->content  !!}</div>
                                                <div class="action">
                                                    <a class="mt-2" href="#" data-product="{{ $question->object_id }}" data-title="{{ $question->content }}" onclick="get_reply_question( '{{ $question->id }}',this)">
                                                        Reply
                                                    </a>
                                                </div>
                                            </td>
                                            <td>{{ $reviews_status[$question->status]  }}</td>
                                            <td>{{ App\User::getUserByID($question->user_id)->name }}</td>
                                            <td>{{ get_current_datetime($question->created_at) }}</td>
                                            <td class="product_name"><a href="{{ url('admin/product/'.$question->object_id.'/edit') }}">{{ $question->name }}</a></td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="8" class="text-center">No question exist.</td>
                                    </tr>
                                @endif

                                </tbody>
                            </table>
                        </div>
                        <!-- /.card-body -->
                    </div>
                    <!-- /.card -->
                    {{ $questions->links() }}
                </div>
            </div>
        </div>
    </section>
    <div class="modal fade" id="ReplyModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" >Modal title</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                   <textarea class="item_reply_question form-control-plaintext" placeholder="Please enter reply question..."></textarea>
                </div>
                <div class="modal-footer">
                    <button type="button" id="save_reply_question" class="btn btn-primary" data-type="add_new" onclick="save_reply_question(this)">Save changes</button>
                </div>
            </div>
        </div>
    </div>

@endsection
