@extends('admin.users.user_layout')
@section('content')
    <?php
    $roles = App\User::get_roles();
    $user = App\User::getUserByID($user_id ?? '');
    ?>
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Edit Profile: {{ ($user)?$user->name:'' }}</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin.users') }}">Users</a></li>
                        <li class="breadcrumb-item active">Edit Profile</li>
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
                <div class="col-md-6">
                    <!-- general form elements -->
                    {{--update info--}}
                    <div class="card card-info">
                        <div class="card-header">
                            <h3 class="card-title">Info</h3>
                        </div>
                        <!-- /.card-header -->
                        <!-- form start -->
                        <form role="form" method="post" action="{{url('admin/update_userinfo')}}">
                            {{ csrf_field()}}
                            <div class="card-body">
                                <div class="form-group">
                                    @if($errors -> all() && isset($request->action)&& $request->action == 'info')
                                        @foreach($errors->all() as $error)
                                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                                {{$error}}
                                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                        @endforeach
                                    @endif

                                    @if (Session::has('success_info'))
                                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                                            {{Session::get('success_info')}}
                                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                    @endif
                                </div>
                                <div class="form-group">
                                    <label>Name</label>
                                    <input type="text" class="form-control" name="name" placeholder="Name" value="{{ (isset($request->name))?$request->name:$user->name  }}">
                                </div>
                                <div class="form-group">
                                    <label>Email</label>
                                    <input type="email" class="form-control" name="email" placeholder="Email" value="{{ (isset($request->email))?$request->email:$user->email  }}">
                                </div>
                                <div class="form-group">
                                    <label>Role</label>
                                    <select class="form-control" name="level">
                                        @if( isset($roles))
                                            @foreach($roles as $key => $value)
                                                <option value="{{ $key }}" {{ $key == $user->level ?'selected':'' }}>{{ $value }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                            <!-- /.card-body -->

                            <div class="card-footer">
                                <input type="hidden" name="user_id" value="{{ ($user)?$user->id:''}}">
                                <input type="hidden" name="action" value="info">
                                <button type="submit" class="btn btn-primary">Submit</button>
                            </div>
                        </form>
                    </div>
                    <!-- /.card -->
                </div>
                {{--colume right--}}
                <div class="col-md-6">
                    {{--Update password--}}
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">Change password</h3>
                        </div>
                        <!-- /.card-header -->
                        <!-- form start -->
                        <form role="form" method="post" action="{{url('admin/change_password')}}">
                            {{ csrf_field()}}
                            <div class="card-body">
                                <div class="form-group">
                                    @if($errors -> all() && isset($request->action) && $request->action == 'change_password')
                                        @foreach($errors->all() as $error)
                                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                                {{$error}}
                                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                        @endforeach
                                    @endif

                                    @if (Session::has('success_password'))
                                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                                            {{Session::get('success_password')}}
                                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                    @endif
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputPassword1">Password</label>
                                    <input type="password" class="form-control" name="password" placeholder="Password" value="{{ (isset($request))?$request->password:''  }}">
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputPassword1">Password Confirm</label>
                                    <input type="password" class="form-control" name="password_comfirm" placeholder="Password Comfirm" value="{{ (isset($request))?$request->password_comfirm:''  }}">
                                </div>
                            </div>
                            <!-- /.card-body -->

                            <div class="card-footer">
                                <input type="hidden" name="user_id" value="{{ ($user)?$user->id:''}}">
                                <input type="hidden" name="action" value="change_password">
                                <button type="submit" class="btn btn-primary">Submit</button>
                            </div>
                        </form>
                    </div>
                    <!-- /.card -->
                </div>
            </div>
            <!-- /.row -->
        </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
@endsection
