@extends('admin.users.user_layout')
@section('content')
    <?php
    $level = Auth::user()->level;
    $users = App\User::get_all_users($level);
    ?>
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Manage Users</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Manage Users</li>
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
                    <h3 class="card-title">Users</h3>

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
                <div class="card-body table-responsive p-0" style="height: 300px;">
                    <table class="table table-head-fixed text-nowrap table-hover">
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th>User</th>
                            <th>Username</th>
                            <th>Email</th>
                            <th>Date</th>
                            <th>Role</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        @if($users) @foreach($users as $user )
                        <tr id="{{ 'item-user-'.$user->id}}">
                            <td>{{$user->id}}</td>
                            <td>{{$user->name}}</td>
                            <td>{{$user->username}}</td>
                            <td>{{$user->email}}</td>
                            <td>{{ display_date($user->created_at)}}</td>
                            <td><span class="tag tag-success">{{$roles[$user->level]}}</span></td>
                            <td class="project-actions ">
{{--                                <a class="btn btn-primary btn-sm" href="{{ route('admin.user_view',['id'=>$user->id] ) }}">--}}
{{--                                    <i class="fas fa-folder">--}}
{{--                                    </i>--}}
{{--                                    View--}}
{{--                                </a>--}}
                                <a class="btn btn-info btn-sm" href="{{ route('admin.user_edit',['id'=>$user->id] ) }}">
                                    <i class="fas fa-pencil-alt">
                                    </i>
                                    Edit
                                </a>
                                <a class="btn btn-danger btn-sm" href="javascript:delete_user({{$user->id}});">
                                    <i class="fas fa-trash">
                                    </i>
                                    Delete
                                </a>
                            </td>
                        </tr>
                        @endforeach
                        @endif
                        </tbody>
                    </table>
                </div>
                <!-- /.card-body -->
            </div>
            <!-- /.card -->
        </div>
    </div>
        </div>
    </section>
@endsection
