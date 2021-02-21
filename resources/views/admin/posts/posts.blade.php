@extends('admin.posts.post_layout')
@section('content')
    <?php
    $posts =  App\Posts::get_posts();
    $Status =  App\Posts::post_status();
    $Category =  App\Posts::post_category();
    $post_types = App\Posts::post_types();
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
                        <li class="breadcrumb-item"><a href="{{ route('admin') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Manage Posts</li>
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
                        <div class="card-header">
                            <h3 class="card-title">Posts ({{ $posts->total() }})</h3>

                            <div class="card-tools">
                                <div class="input-group input-group-sm" style="width: 150px;">
                                    <input type="text" name="table_search" class="form-control float-right" placeholder="Search">

                                    <div class="input-group-append">
                                        <button type="submit" class="btn btn-default"><i class="fas fa-search"></i></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body table-responsive p-0">
                            <table class="table table-head-fixed text-nowrap table-hover">
                                <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Title</th>
                                    <th>Category</th>
                                    <th>User</th>
                                    <th>Type</th>
                                    <th>Status</th>
                                    <th>Last Modified</th>
                                    <th>Actions</th>
                                </tr>
                                </thead>
                                <tbody>
                                @if (count($posts) > 0)
                                @foreach ($posts as $post)
                                <tr>
                                    <td><?php echo $post->id ?></td>
                                    <td><?php echo $post->post_title?></td>
                                    <td>{{ $Category[$post->post_category]}}</td>
                                    <td>{{ App\User::getUserByID($post->user_id)->name }}</td>
                                    <td>{{ $post_types[$post->post_type] }}</td>
                                    <td>{{ $Status[$post->status]}}</td>
                                    <td>{{$post->updated_at}}</td>
                                    <td>
                                        <a class="btn btn-primary btn-sm" href="{{ url('post/'.$post->slug) }}" target="_blank">
                                            <i class="fas fa-folder">
                                            </i>
                                            View
                                        </a>
                                        <a class="btn btn-info btn-sm" href="{{ route('admin.edit_post',['id'=>$post->id]) }}">
                                            <i class="fas fa-pencil-alt">
                                            </i>
                                            Edit
                                        </a>
                                        <a class="btn btn-danger btn-sm" href="javascript:delete_post('{{$post->id}}','detele_story');">
                                            <i class="fas fa-trash">
                                            </i>
                                            Delete
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                                @else
                                <tr>
                                    <td colspan="8" class="text-center">No stores exist.</td>
                                </tr>
                                @endif

                                </tbody>
                            </table>
                        </div>
                        <!-- /.card-body -->
                    </div>
                    <!-- /.card -->
                    <nav aria-label="Page navigation example">
                        <ul class="pagination">
                            <li class="page-item"><a class="page-link" href="{{ $posts->previousPageUrl() }}">Previous</a></li>
                            <li class="page-item"><span class="page-link">{{ $posts->currentPage() }}</span></li>
                            <li class="page-item"><a class="page-link" href="{{ $posts->nextPageUrl() }}">Next</a></li>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </section>
@endsection
