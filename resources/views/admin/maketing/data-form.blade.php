@extends('admin.widgets.widget_layout')
@section('content')
    <?php
    $query = [
        'search' => isset($_GET['search'])?$_GET['search']:false,
        'type' => isset($_GET['type'])?$_GET['type']: false,
    ];
    $user_id = Auth::id();
    $Data_forms = App\DataForm::get_data($query);
    $list_form = (array) App\Options::display_ctf($user_id,'option_list_ctf');
    ?>
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <form method="get" action="" class="filter-data-form form-inline mt-3">
                        <div class="form-group">
                            <input type="text" name="search" class="form-control" placeholder="Search..." value="{{ $query['search'] }}">
                        </div>
                        <div class="form-group mx-sm-3">
                            <select class="form-control" name="type" data-title="Type" data-required="false">
                                @foreach($list_form as $key => $value)
                                    <option value="{{ $key }}" {{ $key == $query['type'] ? 'selected': '' }}>{{ $value }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-outline-info">Apply</button>
                        </div>
                        <div class="form-group mx-sm-3">
                            <a href="{{ route('admin.add-data-form') }}" class="btn btn-outline-info">Add new</a>
                        </div>

                    </form>

                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Database Form</li>
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
                <!-- Content -->
                <div class="card col-md-12">
                    <!-- /.card-header -->
                    <div class="card-body p-0">
                        <table class="table table-hover">
                            <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Newsletter</th>
                                <th>Subscribed On</th>
                                <th>Last Modified</th>
                                <th>Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            @if( isset($Data_forms) && count($Data_forms) > 0 )
                                @foreach( $Data_forms as $key => $value )
                                    <tr>
                                        <td>{{ $value->id }}</td>
                                        <td><a href="{{ url('admin/data-form/'.$value->id.'/edit') }}">{{ $value->name }}</a></td>
                                        <td>{{ $value->email }}</td>
                                        <td>{{ $value->phone }}</td>
                                        <td><a href="{{ url('admin/contact-form/'.$value->type) }}">{{ $list_form[$value->type] }}</a></td>
                                        <td>{{ get_current_datetime( $value->created_at ) }}</td>
                                        <td>{{ $value->updated_at }}</td>
                                        <td>
                                            <a class="btn btn-info btn-sm" href="{{ url('admin/data-form/'.$value->id.'/edit') }}">
                                                <i class="fas fa-pencil-alt">
                                                </i>
                                                Edit
                                            </a>
                                            <a class="btn btn-danger btn-sm" href="javascript:delete_post('{{$value->id}}','detele_data_form');">
                                                <i class="fas fa-trash">
                                                </i>
                                                Delete
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                             @else
                                <tr>
                                    <td colspan="8" class="text-center">No products exist.</td>
                                </tr>
                            @endif
                            </tbody>
                        </table>
                    </div>
                    <!-- /.card-body -->
                </div>

               {{ $Data_forms->links() }}
                <!-- /.Content -->
            </div>
            <!-- /.row (main row) -->
        </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
@endsection