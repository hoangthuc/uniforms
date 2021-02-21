@extends('admin.widgets.widget_layout')
@section('content')
    <?php
    $user_id = Auth::id();
    $list_form = (array) App\Options::display_ctf($user_id,'option_list_ctf');
    ?>
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <ol class="breadcrumb float-left">
                        <li class="breadcrumb-item"><a href="{{ route('admin.data-form') }}">List Data</a></li>
                        <li class="breadcrumb-item active">Add new</li>
                    </ol>
                </div><!-- /.col -->
                <div class="col-sm-6">

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
                <div class="col-md-12">
                    <!-- /.card-header -->
                    <div class="card card-primary">
                        <!-- /.card-header -->
                        <!-- form start -->
                        <form role="form" id="display-data-ctf">
                            <div class="card-body">
                                <div class="form-group">
                                    <label>Name</label>
                                    <input type="text" class="form-control" name="name" placeholder="Enter Name" data-title="Name" data-required="true">
                                    <span class="um-field-error d-none"></span>
                                </div>

                                <div class="form-group">
                                    <label>Email</label>
                                    <input type="email" class="form-control" name="email" placeholder="Enter email" data-title="Email" data-required="true">
                                    <span class="um-field-error d-none"></span>
                                </div>
                                <div class="form-group">
                                    <label>Phone</label>
                                    <input type="text" class="form-control" name="phone" placeholder="Enter Phone" data-title="Phone" data-required="false">
                                    <span class="um-field-error d-none"></span>
                                </div>

                                <div class="form-group">
                                    <label>Type</label>
                                    <select class="form-control" name="type" data-title="Type" data-required="false">
                                        @foreach($list_form as $key => $value)
                                            <option value="{{ $key }}" >{{ $value }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <input type="hidden" name="action" data-title="Action" data-required="false" value="add_data_form_ctf">
                                </div>

                            </div>
                            <!-- /.card-body -->
                            <div class="card-footer">
                                <button type="button" id="button-data-form-customer" onclick="save_data_row_ctf()" class="btn btn-info">Save <span class="d-none">
                                <i class="fas fa-spinner fa-spin"></i>
                            </span></button>
                                <a href="{{ url('admin/data-form') }}" class="btn btn-default float-right">Cancel</a>
                            </div>
                        </form>
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