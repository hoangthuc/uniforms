@extends('admin.media.media_layout')
@section('content')
    <?php
    $type = (isset($_GET['type']))?$_GET['type']:'';
    $page = (isset($_GET['page']))?$_GET['page']:1;
    $search = (isset($_GET['search']))?$_GET['search']:'';
    $query = ['type'=>$type,'search'=>$search];
    $medias = App\Media::get_media($query,$page);
    ?>
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">
                        Media
                        <a class="btn btn-primary" data-toggle="collapse" href="#form-upload-media" role="button" aria-expanded="false" aria-controls="form-upload-media">
                            Upload
                        </a>
                    </h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin') }}">Home</a></li>
                        <li class="breadcrumb-item active">Media manage</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->
    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card card-primary">
                        <div class="card-header">
                            <div class="card-title">
                                List Media
                            </div>
                        </div>
                        <div class="card-body">
                            <div>
                                <div id="form-upload-media" class="collapse mb-5 mt-2">
                                    <form action="{{ url('admin/upload') }}" enctype="multipart/form-data" method="POST">
                                        {{ csrf_field() }}
                                        <input type="file" name="UploadMedia" data-type="list" multiple required="true">
                                        <br/>
                                        <button type="button" class="btn-button-upload">Upload Media</button>
                                    </form>
                                </div>

                                <div class="filter-media mb-5">
                                    <div class="text-left">
                                        <div class="btn-group">
                                            <a class="btn btn-default active" href="{{ route('admin.media_list') }}"> <i class="fas fa-th-list" style="font-size: 20px;"></i> </a>
                                            <a class="btn btn-default" href="{{ route('admin.media') }}" data-sortDesc> <i class="fas fa-th" style="font-size: 20px;"></i> </a>
                                        </div>
                                        <form class="d-inline-block form-inline">
                                            <select class="custom-select" name="type" style="width: auto;">
                                                @foreach(select_type_media() as $key=> $value)
                                                    <option value="{{ $key }}" {{ ($key == $type)?'selected':'' }}> {{ $value }} </option>
                                                @endforeach
                                            </select>
                                            <input type="text" name="search" class="form-control" value="{{ $search }}" placeholder="Search media...">
                                            <button type="submit" id="apply-filter" class="btn btn-primary">Apply</button>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            <div>
                                <table class="list-media table table-striped">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Media</th>
                                        <th>Name</th>
                                        <th>Author</th>
                                        <th>Type</th>
                                        <th>Created</th>
                                    </tr>
                                    </thead>
                                    <tbody id="list-medias">
                                    @if (count($medias) > 0)
                                        @foreach ($medias as $media)
                                    <tr>
                                        <td>{{ $media->id }}</td>
                                        <td> <div class="img_featured" style="background-image: url('{{ show_img_media($media->type, url($media->path) )}}');"></div></td>
                                        <td class="link">
                                                <a data-ID="{{ $media->id }}" onclick="setup_media_byjson('{{ json_encode($media) }}')" data-toggle="modal" data-target="#MediaModal" data-type="{{ get_type_media($media->type) }}" data-size="{{ get_size_media($media->path) }}" >{{ get_name_media( $media->path ) }}</a>
                                        </td>
                                        <td>{{  App\User::getUserByID($media->user_id)->name }}</td>
                                        <td>{{ $media->type }}</td>
                                        <td>{{ $media->created_at }}</td>
                                    </tr>
                                        @endforeach
                                    @endif
                                    </tbody>
                                </table>

                            </div>
                            <div class="pagition_page">
                                {{ $medias->appends($_GET)->links() }}
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
@endsection