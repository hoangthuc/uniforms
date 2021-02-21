@extends('admin.widgets.widget_layout')
@section('content')
    <?php
    $faq = App\Options::list_faq();
    $user_id = Auth::id();
    $faq_d = App\Options::get_option($user_id,'option_faq');
    if($faq_d)$faq = json_decode($faq_d);
    ?>
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Widgets / FAQ</li>
                    </ol>
                </div><!-- /.col -->
                <div class="col-sm-6 text-right">
                    <button type="button" id ="button-save-faq" class="btn btn-primary btn-sm" onclick="save_faq()">
                        <i class="fas fa-save"></i> Save
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
                <!-- Left col -->
                <section id="setting-faq" class="col-lg-12 connectedSortable">
                    <!-- DIRECT CHAT -->
                    @if(isset($faq))
                        @foreach($faq as $key => $value)
                    <div class="card item-faq-{{ $key }} collapsed-card" data-faq="{{ $value->title }}">
                        <div class="card-header">
                            <h3 class="card-title"> {{ $value->title }}</h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                    <i class="fas fa-plus"></i>
                                </button>
                            </div>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            @if(isset($value->value))
                                @foreach($value->value as $item)
                                   <!---show title-->
                                    @if( $item->name == 'title')
                            <div class="form-group mb-4">
                                <button type="button" class="btn btn-tool" onclick="remove_item(this)">
                                    <i class="far fa-times-circle"></i> Delete
                                </button>
                                <input type="text" class="form-control mt-1 mb-3" value="{{ $item->value }}" name="title" data-faq="{{ $key }}" data-title="Title" data-required="true" placeholder="Title">
                                @endif
                                    <!--show content-->
                                @if($item->name == 'content')
                                <textarea class="form-control" name="content" data-faq="{{ $key }}" data-title="Content" data-required="true" placeholder="Content">{{ $item->value }}</textarea>
                            </div>
                                    @endif
                                @endforeach
                             @endif
                        </div>
                        <!-- /.card-body -->
                        <div class="card-footer">
                            <div class="form-group text-right">
                                <button type="button" class="btn btn-primary btn-sm" onclick="add_item_fqa({{$key }})">
                                    <i class="fas fa-plus-circle"></i> Add new
                                </button>
                            </div>
                        </div>
                    </div>
                    @endforeach
                @endif
                    <!--/.direct-chat -->
                </section>
                <!-- /.Left col -->
            </div>
            <!-- /.row (main row) -->
        </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
@endsection