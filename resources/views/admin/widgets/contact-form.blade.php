@extends('admin.maketing.maketing_layout')
@section('content')
    <?php
    $user_id = Auth::id();
    $list_form = App\Options::display_ctf($user_id,'option_list_ctf');
    ?>
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Widgets / Contact Form</li>
                    </ol>
                </div><!-- /.col -->
                <div class="col-sm-6 text-right">

                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <!-- Main row -->
            <div class="row">
                <!-- Content -->
                <div class="card col-md-12">
                    <div class="card-header">
                        <h3 class="card-title">List Form</h3>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body p-0">
                        <table class="table table-striped">
                            <thead>
                            <tr>
                                <th>Form</th>
                                <th>Slug</th>
                                <th>Number</th>
                            </tr>
                            </thead>
                            <tbody>
                            @if( isset($list_form) )
                                @foreach( $list_form as $key => $value )
                            <tr>
                                <td><a href="{{ url('admin/contact-form/'.$key) }}">{{ $value }}</a></td>
                                <td>{{ $key }}</td>
                                <td><span class="badge bg-danger">55%</span></td>
                            </tr>
                                @endforeach
                            @endif
                            </tbody>
                        </table>
                    </div>
                    <!-- /.card-body -->
                </div>
                <!-- /.Content -->
            </div>
            <!-- /.row (main row) -->
        </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
@endsection