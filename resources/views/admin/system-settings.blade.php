@extends('admin.widgets.widget_layout')
@section('content')
    <?php
    $user_id = Auth::id();
    ?>
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">System Settings</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Settings</li>
                    </ol>
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
                <!-- Left col -->
                <section class="col-lg-8">
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">Website Details</h3>
                        </div>
                        <!-- /.card-header -->
                        <!-- form start -->
                        <form role="form" class="display_system_settings">
                            <div class="card-body">
                                <div class="form-group">
                                    <label>Site Title</label>
                                    <input type="text" name="site_title" class="form-control" placeholder="Title" data-title="Title" data-required="true" value="{{ get_option($user_id,'site_title') }}" >
                                </div>
                                <div class="form-group">
                                    <label>Site URL</label>
                                    <input type="text" name="site_url" class="form-control" placeholder="Site URL" data-title="Site URL" data-required="true" value="{{ get_option($user_id,'site_url') }}">
                                </div>

                                <div class="form-group">
                                    <label>Admin Email</label>
                                    <input type="email" name="site_email" class="form-control" placeholder="Email" data-title="Email" data-required="true" value="{{ get_option($user_id,'site_email') }}" >
                                </div>

                                <div class="form-group">
                                    <label>Site Phone</label>
                                    <input type="text" name="site_phone" class="form-control" placeholder="Site Phone" data-title="Site Phone" data-required="true" value="{{ get_option($user_id,'site_phone') }}" >
                                </div>

                                <div class="form-group">
                                    <label>Address</label>
                                    <input type="text" name="site_address" class="form-control" placeholder="Address" data-title="Address" data-required="true" value="{{ get_option($user_id,'site_address') }}" >
                                </div>

                                <div class="form-group">
                                    <label>Work time</label>
                                    <input type="text" name="site_work_time" class="form-control" placeholder="Work time" data-title="Work time" data-required="true" value="{{ get_option($user_id,'site_work_time') }}" >
                                </div>

                                <div class="form-group">
                                    <label>META Description (SEO)</label>
                                    <textarea name="global_description" class="form-control" placeholder="META Description" data-title="META Description" data-required="true">{{ get_option($user_id,'global_description') }}</textarea>
                                </div>

                                <div class="form-group">
                                    <label>META Keywords (SEO)</label>
                                    <textarea name="global_keywords" class="form-control" placeholder="META Keywords" data-title="META Keywords" data-required="true">{{ get_option($user_id,'global_keywords') }}</textarea>
                                </div>

                                <div class="form-group">
                                    <label>Site Descrption</label>
                                    <textarea name="site_description" class="form-control" placeholder="Site Descrption" data-title="Site Descrption" data-required="true">{{ get_option($user_id,'site_description') }}</textarea>
                                </div>

                                <div class="form-group">
                                    <label>Site Welcome</label>
                                    <textarea name="site_welcome" class="form-control" placeholder="Site Welcome" data-title="Site Welcome" data-required="true">{{ get_option($user_id,'site_welcome') }}</textarea>
                                </div>



                            </div>
                            <!-- /.card-body -->
                        </form>
                    </div>

                </section>
                <!-- /.Left col -->
                <!-- right col (We are only adding the ID to make the widgets sortable)-->
                <section class="col-lg-4 ">
                    <!---Controls-->
                    <div class="card card-info">
                        <div class="card-header">
                            <h3 class="card-title">Controls</h3>
                        </div>
                        <!-- /.card-header -->
                        <!-- form start -->
                            <div class="card-body display_system_settings">
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fab fa-facebook-f"></i></span>
                                    </div>
                                    <input type="url" class="form-control" name="facebook" data-title="Facebook" data-required="false" value="{{ get_option($user_id,'facebook') }}">
                                </div>

                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fab fa-twitter"></i></span>
                                    </div>
                                    <input type="url" class="form-control" name="twitter" data-title="Twitter" data-required="false"  value="{{ get_option($user_id,'twitter') }}">
                                </div>

                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fab fa-instagram"></i></span>
                                    </div>
                                    <input type="url" class="form-control" name="instagram" data-title="Instagram" data-required="false"  value="{{ get_option($user_id,'instagram') }}">
                                </div>

                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fab fa-youtube"></i></span>
                                    </div>
                                    <input type="url" class="form-control" name="youtube" data-title="Youtube" data-required="false"  value="{{ get_option($user_id,'youtube') }}">
                                </div>

                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fab fa-linkedin-in"></i></span>
                                    </div>
                                    <input type="url" class="form-control" name="linkedin-in" data-title="Linkedin In" data-required="false"  value="{{ get_option($user_id,'linkedin-in') }}">
                                </div>
                                <div class="input-group">
                                   <input type="hidden" name="action" value="update_system_settings" data-title="Action" data-required="false" >
                                </div>



                            </div>
                            <!-- /.card-body -->
                            <div class="card-footer">
                                <button type="button" id="button-display_system_settings" class="btn btn-info" onclick="save_system_settings()" >Save <span class="d-none">
                                <i class="fas fa-spinner fa-spin"></i>
                            </span></button>
                                <a href="{{ route('admin') }}" class="btn btn-default float-right">Cancel</a>
                            </div>
                            <!-- /.card-footer -->
                    </div>



                </section>
                <!-- right col -->
            </div>
            <!-- /.row (main row) -->
        </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
@endsection