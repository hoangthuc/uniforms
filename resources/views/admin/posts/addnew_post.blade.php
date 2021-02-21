@extends('admin.posts.post_layout')
@section('content')
    <?php
    $user = Auth::user();
    $post_cateroty = App\Posts::post_category();
    $post_status = App\Posts::post_status();
    $post_types = App\Posts::post_types();
    $medias = App\Media::get_media();
    ?>
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Add new post</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin.posts') }}">Posts</a></li>
                        <li class="breadcrumb-item active">Add new post</li>
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
                        <form class="form-horizontal create_new_story" name="frmAdd" method="post" enctype="multipart/form-data" role="form">
                            <div class="card-body">
                                <!--Title-->
                                <div class="form-group">
                                    <label for="story_title" class="control-label">Post Title</label>
                                    <input type="text" class="form-control" id="post_title" name="post_title" placeholder="Post Title" value="" data-title="Post Title" data-required="true" />
                                    <span class="um-field-error d-none"></span>
                                </div>
                                <!--Content-->
                                <div class="form-group">
                                    <label for="story_content" class="control-label">Post Content</label>
                                    <textarea id="post_content" name="post_content" class="form-control summernote editor_summernote" rows="10" placeholder="Post content..." data-title="Content" data-required="false"></textarea>
                                    <span class="um-field-error d-none"></span>
                                </div>
                                <!--Category-->
                                <div class="form-group">
                                    <label for="story_category" class="control-label">Category</label>
                                    <select class="form-control" name="post_category" id="post_category" data-title="Category" data-required="true">

                                        @if (!empty($post_cateroty))
                                            @foreach ($post_cateroty as $key=> $value)


                                                <option value="{{ $key }}">{{ $value }}</option>

                                            @endforeach
                                        @endif

                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="status" class="control-label">Status</label>
                                    <select class="form-control" id="status" name="status" data-title="Status" data-required="true">

                                        @foreach ($post_status as $key => $val)
                                            <option value="{{ $key }}">{{ $val }}</option>
                                        @endforeach
                                    </select>
                                </div>


                                <div class="form-group">
                                    <label for="story_notes" class="control-label">Internal Notes</label>
                                    <textarea id="post_notes" name="post_notes" class="form-control" rows="3" placeholder="Internal notes..." data-title="Notes" data-required="false"></textarea>
                                </div>
                                <div class="form-group">
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}" data-title="Token" data-required="false">
                                    <input type="hidden" id="action" name="action" value="{{ url('admin/add_post/') }}" data-title="action" data-required="false" />
                                    <input type="hidden" id="user_id" name="user_id" value="<?php echo $user->id?>" data-title="User" data-required="false" />
                                </div>
                            </div>


                        </form>
                    </div>
                    <!-- /.card -->
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
                                            <option value="{{ $key }}">{{ $val }}</option>
                                        @endforeach
                                    </select>
                                    <span class="um-field-error d-none"></span>
                                </div>
                                <div id="show-media" class="form-group d-none">
                                    <div class="display-media">

                                    </div>
                                    <!-- Button trigger modal -->
                                    <div type="button" class="btn btn-primary button_upload_media" data-media="audio" data-ftype="audio" data-type="audio/mp3" data-toggle="modal" data-target="#MediaModal" data-required=true>
                                        Upload Audio
                                    </div>
                                </div>
                            </div>
                            <!-- /.card-body -->
                    </div>
                    {{--Story Image--}}
                    <div class="card card-primary">
                        <!-- /.card-header -->
                        <div class="card-header">
                            <h3 class="card-title">Post Image</h3>
                        </div>
                        <!-- /.card-body -->
                        <div class="card-body">
                            <div id="show-images" class="form-group">
                                <div class="display-media">

                                </div>
                                <!-- Button trigger modal -->
                                <div type="button" class="btn btn-primary button_upload_media" data-media="button_featured_image" data-ftype="image" data-type="image/*" data-toggle="modal" data-target="#MediaModal" data-required="false">
                                    Upload image
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
