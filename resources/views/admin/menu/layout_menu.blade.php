<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Dashboard | Dashboard</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset('admink/plugins/fontawesome-free/css/all.min.css') }}">
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('admink/dist/css/adminlte.min.css') }}">
    <!-- SweetAlert2 -->
    <link rel="stylesheet" href="{{ asset('admink/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css') }}">
    <!-- summernote -->
    <link rel="stylesheet" href="{{ asset('admink/plugins/summernote/summernote-bs4.css') }}">
    <!-- Theme style admin -->
    <link rel="stylesheet" href="{{ asset('css/style_admin.css') }}">
    <!-- Google Font: Source Sans Pro -->
    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
</head>

<body class="hold-transition sidebar-mini">
    <div class="wrapper">

        @include('admin.sidebar_admin')

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            @yield('content')
        </div>
        <!-- /.content-wrapper -->

        <!-- Modal -->
        <div class="modal fade" id="MediaModal" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-xl modal-dialog-centered">
                <div class="modal-content">
                    <div class="card-audio card-primary card-outline card-outline-tabs">
                        <div class="card-header p-0 border-bottom-0">
                            <ul class="nav nav-tabs" id="custom-tabs-four-tab" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" id="button-upload-media" data-toggle="pill"
                                        href="#tabs-upload-media" role="tab" aria-controls="custom-tabs-four-home"
                                        aria-selected="false">Upload file</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="button-library" data-toggle="pill" href="#tabs-library"
                                        role="tab" aria-controls="custom-tabs-four-profile"
                                        aria-selected="false">Library</a>
                                </li>
                            </ul>
                        </div>
                        <div class="card-body">
                            <div class="tab-content" id="custom-tabs-four-tabContent">
                                <div class="tab-pane fade active show" id="tabs-upload-media" role="tabpanel"
                                    aria-labelledby="tabs-upload-media">
                                    <div class="upload-file">
                                        <input type="file" class="file-audio" name="UploadMedia" accept="">
                                        <button type="button">Upload Media</button>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="tabs-library" role="tabpanel"
                                    aria-labelledby="tabs-library">
                                    <div id="grid-medias">

                                    </div>
                                    <div class="control-media text-right mt-3">
                                        <button type="button" class="btn btn-secondary"
                                            data-dismiss="modal">Close</button>
                                        <button type="button" id="insert_media_modal" class="btn btn-primary">Insert
                                            Media</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- /.card -->
                    </div>

                </div>
            </div>
        </div>

        <footer class="main-footer">
            <strong>Copyright &copy; 2019-2020.</strong>
            All rights reserved.
            <div class="float-right d-none d-sm-inline-block">
                <b>Version</b> 3.0.5
            </div>
        </footer>

        <!-- Control Sidebar -->
        <aside class="control-sidebar control-sidebar-dark">
            <!-- Control sidebar content goes here -->
        </aside>
        <!-- /.control-sidebar -->
    </div>
    <!-- ./wrapper -->
    <!-- jQuery -->
    <script src="{{ asset('admink/plugins/jquery/jquery.min.js') }}"></script>
    <!-- Bootstrap 4 -->
    <script src="{{ asset('admink/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <!-- AdminLTE App -->
    <script src="{{ asset('admink/dist/js/adminlte.min.js') }}"></script>
    <!-- Bootstrap menu-editor -->
    <script type="text/javascript"
        src="{{ asset('plugins/jQuery-Menu-Editor/bootstrap-iconpicker/js/iconset/fontawesome5-3-1.min.js') }}">
    </script>
    <script type="text/javascript"
        src="{{ asset('plugins/jQuery-Menu-Editor/bootstrap-iconpicker/js/bootstrap-iconpicker.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('plugins/jQuery-Menu-Editor/jquery-menu-editor.js') }}"></script>
    <!-- SweetAlert2 -->
    <script src="{{ asset('admink/plugins/sweetalert2/sweetalert2.min.js') }}"></script>
    <script>
    var setting = {
        'upload_ajax_url': '{{ url('admin/upload') }}',
        'ajax_url': '{{ url('admin/admin_ajax ') }}',
        'token': '{{ csrf_token() }}',
    };
    const add_new = document.querySelector('.btn-add-new');
    // add new
    if (add_new) add_new.addEventListener('click', function() {
        Swal.fire({
            title: 'Create new menu',
            input: 'text',
            inputAttributes: {
                autocapitalize: 'off'
            },
            showCancelButton: true,
            confirmButtonText: 'Submit',
            showLoaderOnConfirm: true,
            preConfirm: (value) => {
                return $.post(setting.ajax_url, {
                        data: {
                            name: value
                        },
                        action: 'add_menu',
                        _token: setting.token
                    })
                    .done(function(msg) {
                        return JSON.parse(msg);
                    })
                    .fail(function(xhr, status, error) {
                        // error handling
                        Swal.showValidationMessage(
                            `Request failed: ${error}`
                        )
                    });
            },
            allowOutsideClick: () => !Swal.isLoading()
        }).then((result) => {
            if (result) {
                location.reload();
            }
        })
    })
    // Delete
    document.querySelectorAll('.btn-delete').forEach(de => de.addEventListener('click', function() {
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.value) {
                let id = de.getAttribute('data-id');
                $.post(setting.ajax_url, {
                        id: id,
                        action: 'delete_menu',
                        _token: setting.token
                    })
                    .done(function(msg) {
                        let resulf = JSON.parse(msg);
                        if (resulf.success) {
                            location.href= de.getAttribute('data-return');
                        }
                    })
                    .fail(function(xhr, status, error) {
                        // error handling
                        Swal.showValidationMessage(
                            `Request failed: ${error}`
                        )
                    });
            }
        })


    }));


    // icon picker options
    var iconPickerOptions = {
        searchText: "Buscar...",
        labelHeader: "{0}/{1}"
    };
    // sortable list options
    var sortableListOptions = {
        placeholderCss: {
            'background-color': "#cccccc"
        }
    };
    var editor = new MenuEditor('myEditor', {
        listOptions: sortableListOptions,
        iconPicker: iconPickerOptions,
        maxLevel: 2 // (Optional) Default is -1 (no level limit)
        // Valid levels are from [0, 1, 2, 3,...N]
    });
    var save = document.querySelector('.btn-save');
    if (save) editor.setData(save.getAttribute('data-menu'));
    editor.setForm($('#frmEdit'));
    editor.setUpdateButton($('#btnUpdate'));
    //Calling the update method
    $("#btnUpdate").click(function() {
        editor.update();
    });
    // Calling the add method
    $('#btnAdd').click(function() {
        editor.add();
    });
    $('.btn-save').click(function() {
        let data = editor.getString();
        let id = $(this).data('id');
        let child = $(this).children('span');
        child.removeClass('d-none');
        let name = $('[name="name_menu"]').val();
        let location = $('[name="location_menu"]').val();
        $.post(setting.ajax_url, {
                data_menu: JSON.parse(data),
                id: id,
                name_menu: name,
                location_menu: location,
                action: 'save_menu',
                _token: setting.token
            })
            .done(function(msg) {
                console.log(JSON.parse(msg));
                child.addClass('d-none');
            })
            .fail(function(xhr, status, error) {
                // error handling
                Swal.showValidationMessage(
                    `Request failed: ${error}`
                )
            });
    })
    var complete_post = document.querySelector('.list-pages');
    if(complete_post){
        document.querySelector('#text').addEventListener('click', process_touchstart, false);
        window.addEventListener('click', process_touchend, false);
        complete_post.addEventListener('keyup', process_load, false);
    }
   
// touchstart handler
function process_touchstart(event) {
    process_load(event);
    document.querySelector('#page_menu').className='d-clock';
}
function process_touchend(event) {
    var $target = $(event.target);
  if(!$target.closest(complete_post).length && 
  $(complete_post).is(":visible")) {
    document.querySelector('#page_menu').className='d-none';
  }    
}
function process_load(event) {
    document.querySelector('#page_menu').className='d-clock';
   let keyword = event.target.value;
   let ul =  document.querySelectorAll(".list-pages li");
   ul.forEach ( (item) => {
    if( item.textContent.toLowerCase().includes( keyword.toLowerCase() ) ){
        item.className='d-clock';
    }else{
        item.className='d-none';  
    }
   }  )

}
function insert_url(event){
    let url = $(event).data('url');
    let text = $(event).data('text');
    $('#href').val(url);
    $('#text').val(text);
    document.querySelector('#page_menu').className='d-none';
}
    </script>
</body>

</html>