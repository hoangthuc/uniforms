@extends('admin.widgets.widget_layout')
@section('content')
    <?php
    $user_id = Auth::id();
    $list_form = App\Options::display_ctf($user_id,'option_list_ctf');
    $data_form = App\Options::get_option($user_id,'option_ctf_'.$slug);
    if(isset($data_form))$data_form = json_decode($data_form);
    ?>
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.contact-form') }}">List Form</a></li>
                        <li class="breadcrumb-item active">Detail Contact Form</li>
                    </ol>
                </div><!-- /.col -->
                <div class="col-sm-6 text-right">
                    <button type="button" id ="button-save-ctf" class="btn btn-primary btn-sm" onclick="save_ctf()">
                        <i class="fas fa-save"></i> Save <span class="d-none">
                                <i class="fas fa-spinner fa-spin"></i>
                            </span>
                    </button>
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
                <div class=" col-md-12">
                    <!-- Content -->
                    @if( isset( $list_form->$slug ))
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">{{ $list_form->$slug }}</h3>
                        </div>
                        <!-- /.card-header -->
                        <!-- form start -->
                        <form role="form" id="setting-ctf">
                            <div class="card-body">
                                <div class="form-group">
                                    <label>Email To</label>
                                    <input type="email" class="form-control" name="email" placeholder="Enter email" data-title="Email" data-required="true" value="{{ $data_form?$data_form->email:''  }}">
                                    <span class="um-field-error d-none">Message is required.</span>
                                </div>
                                <div class="form-group">
                                    <label>Subject</label>
                                    <input type="text" class="form-control" name="subject" placeholder="Enter Subject" data-title="Subject" data-required="true" value="{{ $data_form?$data_form->subject:''  }}">
                                    <span class="um-field-error d-none">Message is required.</span>
                                </div>

                                <div class="form-group">
                                    <label>Message</label>
                                    <textarea type="text" class="form-control editor_summernote" name="message" placeholder="Enter Message" data-title="Message" data-required="true">{{ $data_form?$data_form->message:''  }}</textarea>
                                    <span class="um-field-error d-none">Message is required.</span>
                                </div>
                                <div class="form-group">
                                    <input type="hidden" name="action" data-title="Action" data-required="false" value="update_data_ctf">
                                    <input type="hidden" name="option_ctf" data-title="Option" data-required="false" value="{{ $slug }}">
                                </div>

                            </div>
                            <!-- /.card-body -->
                        </form>
                    </div>
                    <!-- /.Content -->
                     @else
                        <h1 class="text-center">404 Error Page</h1>
                     @endif
                </div>

            </div>
            <!-- /.row (main row) -->
        </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
@endsection