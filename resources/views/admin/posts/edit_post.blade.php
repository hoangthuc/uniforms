@extends('admin.posts.post_layout')
@section('content')
    <?php
    $post = App\Posts::get_post($post_id??'');
    $user = Auth::user();
    $post_cateroty = App\Posts::post_category();
    $post_status = App\Posts::post_status();
    $comments = App\Posts::get_comment_post($post_id??'');
    $comment_status = App\Posts::comment_status();

    $post_types = App\Posts::post_types();
    $medias = App\Media::get_media();

    $audio_id  = get_meta_post($post->id,'audio');
    if($audio_id){
        $audio = App\Media::get_url_media($audio_id);
    }
    if($post->post_image){
        $featured_image = App\Media::get_url_media($post->post_image);
    }
    ?>
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1><a href="{{ route('admin.add-post') }}" class="btn btn-outline-info">Add new</a></h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin.posts') }}">Posts</a></li>
                        <li class="breadcrumb-item active">Edit Post</li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                {{--colume left--}}
                <div class="col-md-9">
                    <!-- general form elements -->
                    {{--update info--}}
                    <div class="card card-info">
                        <div class="card-header">
                            <h3 class="card-title">Post Information</h3>
                        </div>
                        <!-- /.card-header -->
                        <!-- form start -->
                        <form class="form-horizontal create_new_story" name="frmAdd" method="post" action="{{ url('admin/save_post/') }}" enctype="multipart/form-data" role="form">
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="status" class="control-label">Status</label>
                                    <select class="form-control" id="status" name="status" data-title="Status" data-required="true">

                                        @foreach ($post_status as $key => $val)
                                            <option value="{{ $key }}" {{ ($key == $post->status)?'selected':'' }}>{{ $val }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="post_category" class="control-label">Category</label>
                                    <select class="form-control" name="post_category" id="post_category" data-title="Category" data-required="true">

                                        @if (!empty($post_cateroty))
                                            @foreach ($post_cateroty as $key=> $value)


                                                <option value="{{ $key }}" {{ ($key == $post->post_category)?'selected':'' }}>{{ $value }}</option>

                                            @endforeach
                                        @endif

                                    </select>
                                </div>
                                <div class="form-group">
                                    <label class="control-label">Post Title</label>
                                    <input type="text" class="form-control" id="post_title" name="post_title" placeholder="Post Title" value="{{$post->post_title}}" data-title="Post Title" data-required="true" />
                                    <span class="um-field-error d-none"></span>
                                </div>
                                <div class="form-group">
                                    <label class="control-label">Post Content</label>
                                    <textarea id="post_content" name="post_content" class="form-control summernote editor_summernote" rows="10" placeholder="Post content..." data-title="Content" data-required="false">{{$post->post_content}}</textarea>
                                    <span class="um-field-error d-none"></span>
                                </div>
                                <div class="form-group">
                                    <label class="control-label">Internal Notes</label>
                                    <textarea id="post_notes" name="post_notes" class="form-control" rows="3" placeholder="Internal notes..." data-title="Notes" data-required="false">{{$post->post_notes}}</textarea>
                                </div>
                                <div class="form-group">
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}" data-title="Token" data-required="false">
                                    <input type="hidden" id="action" name="action" value="{{ url('admin/save_post/') }}" data-title="action" data-required="false" />
                                    <input type="hidden" id="post" name="id" value="{{ $post->id }}" data-title="ID" data-required="false" />
                                </div>
                            </div>

                        </form>
                    </div>
                    <!-- /.card -->

                    <!--Stories comment-->
                    <div class="card card-success">
                        <div class="card-header">
                            <h3 class="card-title">Post Comments</h3>
                        </div>
                        <div class="card-body">
                            <table class="table table-head-fixed text-nowrap table-hover">
                                <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Author</th>
                                    <th>Content</th>
                                    <th>Date</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                                </thead>
                                <tbody>
                                @if (count($comments) > 0)
                                    @foreach ($comments as $comment)
                                        <tr>
                                            <td>{{ $comment->id }}</td>
                                            <td>{{ App\User::getUserByID($comment->user_id)->name }}</td>
                                            <td>{{ $comment->content }}</td>
                                            <td>{{$post->created_at}}</td>
                                            <td>
                                                <select class="form-control" id="comment_status" name="comment_status" onchange="jQuery(this).change_status_comment('{{ $comment->id }}','status_comment')">

                                                    @foreach ($comment_status as $key => $val)
                                                        <option value="{{ $key }}" {{ ($key ==$comment->status)?'selected':'' }}>{{ $val }}</option>
                                                    @endforeach
                                                </select>

                                            </td>

                                            <td>
                                                <a class="btn btn-danger btn-sm" href="javascript:delete_post('{{$comment->id}}','delete_comment');">
                                                    <i class="fas fa-trash">
                                                    </i>
                                                    Delete
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="8" class="text-center">No comment exist.</td>
                                    </tr>
                                @endif

                                </tbody>
                            </table>
                        </div>
                        <!-- /.card-body -->
                    </div>


                </div>
                {{--colume right--}}
                <div class="col-md-3">
                    <!-- general form controls -->
                    <div class="card card-info">
                        <div class="card-header">
                            <h3 class="card-title">Control</h3>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <a href="{{ route('admin.posts') }}" class="btn btn-outline-info btn-flat"><i class="fas fa-chevron-circle-left"></i> Cancel</a>
                            <a href="javascript:save_post()" id="button-save-story"  class="btn btn-outline-info btn-flat float-right"><i class="fa fa-save fa-right-5"></i> Save <span class="d-none">
                                <i class="fas fa-spinner fa-spin"></i>
                            </span></a>
                        </div>
                        <!-- /.card-body -->
                    </div>
                    <!-- /.card -->
                    <!--Story type-->
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">Post type</h3>
                        </div>
                        <!-- /.card-header -->
                        <!-- form start -->
                        <div class="card-body">
                            <div class="form-group create_new_story">
                                <label for="status" class="control-label">Type</label>
                                <select class="form-control" id="post_type" name="post_type" data-title="Type" data-required="false">
                                    @foreach ($post_types as $key => $val)
                                        <option value="{{ $key }}" {{ ($key == $post->post_type)? 'selected':'' }}>{{ $val }}</option>
                                    @endforeach
                                </select>
                                <span class="um-field-error d-none"></span>
                            </div>
                            <div id="show-media" class="form-group {{ (isset($audio))?'':'d-none' }}">
                                <div class="display-media">

                                </div>
                                <!-- Button trigger modal -->
                                <div type="button" class="btn {{ (isset($audio))?'':'btn-primary' }} button_upload_media" data-media="audio" data-ftype="audio" data-type="audio/mp3" data-toggle="{{ (isset($audio))?'modal2':'modal' }}" data-target="#MediaModal" data-required=true>
                                    @if( isset($audio))
                                        <div>
                                            <audio controls=""><source src="{{ $audio }}" type="audio/mpeg"></audio>
                                        </div>
                                        <button class="btn btn-app" onclick='remove_media(`[data-media="audio"]`)'><i class="far fa-trash-alt" style="font-size: 20px;"></i> Remove</button>
                                    @else
                                        Upload Audio
                                    @endif
                                </div>
                            </div>
                        </div>
                        <!-- /.card-body -->
                    </div>
                    {{--Featured image--}}
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">Post Image</h3>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <div id="show-image" class="form-group create_new_story">
                                <div class="display-media">

                                </div>
                                <!-- Button trigger modal -->
                                <div type="button" class="btn {{ (isset($featured_image))?'':'btn-primary' }} button_upload_media" data-media="button_featured_image" data-ftype="image" data-type="image/*" data-toggle="{{ (isset($featured_image))?'modal2':'modal' }}" data-target="#MediaModal" data-required=false>
                                    @if( isset($featured_image))
                                        <div><img src="{{ $featured_image }}"></div>
                                        <button class="btn btn-app mt-3" onclick='remove_media(`[data-media="button_featured_image"]`)'><i class="far fa-trash-alt" style="font-size: 20px;"></i> Remove</button>
                                        <input type="hidden" name="button_featured_image" value="{{ $post->post_image }}" data-title="Featured image" data-required="false">
                                    @else
                                        Upload Image
                                    @endif
                                </div>
                            </div>
                        </div>
                        <!-- form start -->

                    </div>
                    <!-- /.card -->
                </div>
            </div>
            <!-- /.row -->
        </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
@endsection
