<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Dashboard | Posts</title>
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
                                <a class="nav-link active" id="button-upload-media" data-toggle="pill" href="#tabs-upload-media" role="tab" aria-controls="custom-tabs-four-home" aria-selected="false">Upload file</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="button-library" data-toggle="pill" href="#tabs-library" role="tab" aria-controls="custom-tabs-four-profile" aria-selected="false">Library</a>
                            </li>
                        </ul>
                    </div>
                    <div class="card-body">
                        <div class="tab-content" id="custom-tabs-four-tabContent">
                            <div class="tab-pane fade active show" id="tabs-upload-media" role="tabpanel" aria-labelledby="tabs-upload-media">
                                <div class="upload-file">
                                    <input type="file" class="file-audio" name="UploadMedia" accept="">
                                    <button type="button">Upload Media</button>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="tabs-library" role="tabpanel" aria-labelledby="tabs-library">
                                <div id="grid-medias">

                                </div>
                                <div class="load-more">
                                    <button type="button" class="btn btn-primary btn-sm" onclick="loading_more_medias()" data-page="1">Load more</button>
                                </div>
                               <div class="control-media text-right mt-3">
                                   <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                   <button type="button" id="insert_media_modal" class="btn btn-primary">Insert Media</button>
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
<!-- SweetAlert2 -->
<script src="{{ asset('admink/plugins/sweetalert2/sweetalert2.min.js') }}"></script>
<!-- AdminLTE App -->
<script src="{{ asset('admink/dist/js/adminlte.min.js') }}"></script>
<!-- AdminLTE for demo purposes -->
<script src="{{ asset('admink/dist/js/demo.js')  }}"></script>
<script src="{{ asset('js/functions_admin.js')  }}"></script>
<script src="{{ asset('admink/plugins/summernote/summernote-bs4.min.js')  }}"></script>
<script>
    var setting = {
        'upload_ajax_url':'{{ url('admin/upload') }}',
        'ajax_url':'{{ url('admin/admin_ajax') }}',
        'token':'{{ csrf_token() }}',
    };
    var posts = [];

    $(function () {
        // Summernote
        $('.editor_summernote').summernote(
            {
                placeholder: 'Post content...',
                tabsize: 2,
                height: 300,
                toolbar: [
                    ['style', ['bold', 'italic', 'underline', 'clear']],
                    ['font', ['strikethrough', 'superscript', 'subscript']],
                    ['fontname', ['fontname']],
                    ['fontsize', ['fontsize']],
                    ['color', ['color']],
                    ['para', ['ul', 'ol', 'paragraph']],
                    ['table', ['table']],
                    ['height', ['height']],
                    ['view', ['fullscreen', 'codeview', 'help']],
                ]
            }
        );
        // show manage media to updaload
        $('.button_upload_media').on('click', function(){
            var id = $(this).data('media');
            var type = $(this).data('type');
            var ftype = $(this).data('ftype');
            $('#tabs-upload-media .upload-file input').attr('accept',type);
            $.ajax({
                url: setting.ajax_url,
                type: "POST",
                data: {action:'get_medias',_token: setting.token,type:ftype},
                success: function(resulf){
                    if(resulf){
                        document.querySelector('#grid-medias').innerHTML = resulf;
                    }
                }
            });
            $('[name="UploadMedia"]').attr('data-media',id);
        });
        // upload file media
        $('[name="UploadMedia"]').on('change',function(){
            var id = $(this).attr('data-media');
            var media = $(this)[0].files[0];
            var formData = new FormData();
            formData.append('UploadMedia', media);
            formData.append('_token', setting.token);
            $.ajax({
                url : setting.upload_ajax_url,
                type : 'POST',
                data : formData,
                processData: false,  // tell jQuery not to process the data
                contentType: false,  // tell jQuery not to set contentType
                success : function(resulf) {
                    if(resulf){

                        resulf = JSON.parse(resulf);
                        console.log(resulf);
                        if(resulf['success']){
                            $('[name="UploadMedia"]').val('');
                            const Toast = Swal.mixin({
                                toast: true,
                                position: 'top-end',
                                showConfirmButton: false,
                                timer: 3000
                            });
                            Toast.fire({
                                icon: 'success',
                                title: 'Upload media successfully.'
                            });
                            setup_media(resulf,'[data-media="'+id+'"]');

                        }

                    }

                }
            })
        });

        // choose media to upload
        $('#insert_media_modal').on('click',function(){
            var data =  $(this).attr('data-json');
            var id =  $('[name="UploadMedia"]').attr('data-media');
            $('#grid-medias .link').removeClass('active');
            data = JSON.parse(data);
            setup_media(data,'[data-media="'+id+'"]');
        });

        // choose type story
        $('#post_type').on('change', function(){
            var v = $(this).val();
            if(v == 1){
                posts['audio'] = {name:'audio',value: '', label:'Audio', required: true};
                $('#show-media').removeClass('d-none');
            }else{
                remove_media('[data-media="audio"]');
                $('#show-media').addClass('d-none');
                delete posts['audio'];

            }

        })

    })

    // edit media
    async function setup_media(data,id){
        var media = document.querySelector(id);
        media.innerHTML = "";
        media.className = "btn button_upload_media_i";
        media.setAttribute('data-toggle','modals');
        var name = media.getAttribute('data-media');
        // set content media file
        var div  = document.createElement('div');
        // check type media
        if(data.ftype == 'image'){
            var content  = document.createElement('img');
            content.src = data.link;
        }else if(data.ftype == 'audio'){
            var content  = document.createElement('audio');
            var source  = document.createElement('source');
            source.src = data.link;
            source.type = data.type;
            content.controls = "controls";
            content.appendChild(source);

        }else if(data.ftype == 'video'){
            var content  = document.createElement('video');
            var source  = document.createElement('source');
            source.src = data.link;
            source.type = data.type;
            content.controls = "controls";
            content.width = "320";
            content.height = "240";
            content.appendChild(source);

        }else{
            var content  = document.createElement('img');
            content.src = '{{ url('uploads/use/office.png') }}';
        }

        var r = document.createElement('button');
        r.innerHTML = '<i class="far fa-trash-alt" style="font-size: 20px;"></i> Remove';
        r.className ='btn btn-app mt-3';
        r.setAttribute('onClick',"remove_media('"+id+"')");
        div.appendChild(content);
        media.appendChild(div);
        media.appendChild(r);
        posts[name] = {name:name,value: data.id, label:data.ftype, required: true};
        $('#MediaModal').modal('hide');
    }

    function remove_media(id){
        var media = document.querySelector(id);
        var name = media.getAttribute('data-media');
        var required = media.getAttribute('data-required');
        media.innerHTML = "Upload";
        media.className = "btn btn-primary button_upload_media";
        media.setAttribute('data-toggle','modal');
        if(!posts[name]){
            posts[name] = {name:name,value:'', label:name, required: required};
        }
        posts[name].value ='';
        posts[name].required = Boolean(required);
    }

$.fn.select_media = function(data){
        var data = JSON.parse(data);
        $('#grid-medias .link').removeClass('active');
        $(this).addClass('active');
        $('#insert_media_modal').attr('data-json',JSON.stringify(data));
    }

    var extensionLists = {}; //Create an object for all extension lists
    extensionLists.video = ['m4v', 'avi','mpg','mp4', 'webm','wmv'];
    extensionLists.image = ['jpg', 'gif', 'bmp', 'png'];
    extensionLists.audio = ['mp3'];
    // One validation function for all file types
    function isValidFileType(fName, fType) {
        return extensionLists[fType].indexOf(fName.split('.').pop()) > -1;
    }
</script>
</body>

</html>