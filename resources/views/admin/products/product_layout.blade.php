<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Dashboard | Products</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset('admink/plugins/fontawesome-free/css/all.min.css') }}">
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <!-- Tempusdominus Bbootstrap 4 -->
    <link rel="stylesheet" href="{{ asset('admink/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css') }}">
    <!-- Select2 -->
    <link rel="stylesheet" href="{{ asset('admink/plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('admink/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
    <!-- SweetAlert2 -->
    <link rel="stylesheet" href="{{ asset('admink/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css') }}">
    <!-- summernote -->
    <link rel="stylesheet" href="{{ asset('admink/plugins/summernote/summernote-bs4.css') }}">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('admink/dist/css/adminlte.min.css') }}">
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
<?php
$product_categories =  App\Product::get_product_categories();
?>
    <!-- Modal -->
    <div class="modal fade" id="CategoryModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content">
                <div class="card-audio card-primary card-outline card-outline-tabs">
                    <div class="card-body">
                        <form role="form" id="form-save-category">
                            <div class="card-body">
                                <div class="form-group">
                                    <label>Category title</label>
                                    <input type="text" class="form-control" name="name" placeholder="Enter name" data-title="Name" data-required="true">
                                    <span class="um-field-error d-none"></span>
                                </div>
                                <div class="form-group">
                                    <label>Description</label>
                                    <textarea class="form-control" name="description" placeholder="Enter description" data-title="Description" data-required="false"></textarea>
                                </div>
                                <div class="form-group">
                                    <label>Select parent</label>
                                    <select class="form-control" name="parent_id" data-title="Parent" data-required="false">
                                        <option value="">No parent</option>
                                        @if($product_categories)
                                            @foreach($product_categories as $category)
                                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                                <div class="form-group">
                                    <button type="button" onclick="save_category()" id="button-edit-category" class="btn btn-primary">Save <span class="d-none"><i class="fas fa-spinner fa-spin"></i></span></button>
                                </div>
                            </div>
                            <!-- /.card-body -->
                        </form>
                    </div>
                    <!-- /.card -->
                </div>

            </div>
        </div>
    </div>

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
                                <div class="filter_media_keyword text-right mb-2">
                                    <input type="text" name="search_media" class="d-inline-block" placeholder="Enter keyword" oninput="search_media(this)">
                                </div>
                                <div id="grid-medias">

                                </div>
                                <div class="load-more">
                                    <button type="button" class="btn btn-primary btn-sm" onclick="loading_more_medias()" data-page="1">Load more</button>
                                </div>
                                <div class="control-media text-right pt-3 mt-3 border-top">
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
<!---Loading https://loading.io/css/ -->
<div id="loadingpage" class="d-none">
    <div class="lds-ripple"><div></div><div></div></div>
</div>
<!-- jQuery -->
<script src="{{ asset('admink/plugins/jquery/jquery.min.js') }}"></script>
<!-- Bootstrap 4 -->
<script src="{{ asset('admink/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<!-- Select2 -->
<script src="{{ asset('admink/plugins/select2/js/select2.full.min.js') }}"></script>
<!-- SweetAlert2 -->
<script src="{{ asset('admink/plugins/sweetalert2/sweetalert2.min.js') }}"></script>
<!-- AdminLTE App -->
<script src="{{ asset('admink/dist/js/adminlte.min.js') }}"></script>
<!-- AdminLTE for demo purposes -->
<script src="{{ asset('admink/dist/js/demo.js')  }}"></script>
<script src="{{ asset('js/functions_admin.js')  }}"></script>
<script src="{{ asset('admink/plugins/summernote/summernote-bs4.min.js')  }}"></script>
<script>
    var products = [];
    var varitions = [];
    @if(isset($product_id))
        <?php
        $product_variations = App\Product::get_meta_product($product_id,'product_variations');
        if($product_variations){
           echo 'var varitions = '.$product_variations.';';
        }
        ?>
     @endif
    @if(isset($galleries))
    products['button_gallery'] = {name: 'button_gallery',value:[],label:'Gallery', required: false };
    @foreach($galleries as $value)
        @if( isset($value->id) )
        products['button_gallery'].value.push({ id: {{$value->id}}, link: '{{$value->link}}'});
        @endif
    @endforeach
    @endif
    var setting = {
        'upload_ajax_url':'{{ url('admin/upload') }}',
        'ajax_url':'{{ url('admin/admin_ajax') }}',
        'token':'{{ csrf_token() }}',
        'url':'{{ url('/').'/' }}',
        'upload_import':'{{ url('admin/upload_import') }}',
    };
var Categories = [];
    $('.editor_summernote').summernote(
        {
            placeholder: 'Story content...',
            tabsize: 2,
            height: 300
        }
    );
    $('[data-toggle="tooltip"]').tooltip();
    $('.select2').select2();
    //Initialize Select2 Elements
    $('.select2bs4').select2({
        theme: 'bootstrap4'
    })

    async function create_category(event){
        $('#form-create-category [name]').each( function(){
            var k = $(this).attr('name');
            var v = $(this).val();
            var t = $(this).data('title');
            var r = $(this).data('required');
            if(k != 'story_content'){
                Categories[k] = {name: k, value:v,label: t,required: r};
                $(this).next().addClass('d-none');
            }
        });
        if(Categories){
            var error = '';
            for (var i in Categories){
                if(Categories[i].required & !Categories[i].value){
                    error += Categories[i].label +' is required !';
                    var div = document.querySelector('#form-create-category [name="'+i+'"]');
                    $(div).next().html('<span class="um-field-arrow"><i class="um-faicon-caret-up"></i></span>' + Categories[i].label +' is required !').removeClass('d-none');
                }

            }
            if(products['button_featured_image']){
                Categories['media'] = products['button_featured_image'];
                Categories['media']['name'] = 'media';
            }
            if(products['button_feature_thumbnail']){
                Categories['feature_thumbnail'] = products['button_feature_thumbnail'];
                Categories['feature_thumbnail']['name'] = 'feature_thumbnail';
            }

            if( !error ){
                datas =  convert_array(Categories);
                $(event).children().removeClass('d-none');
                $.ajax({
                    url: '{{ url('admin/add_product_category') }}',
                    type: 'post',
                    data:{data:datas,_token:setting.token},
                    success: function(resulf){
                        console.log(resulf);
                        if(resulf){
                            $(event).children().addClass('d-none');
                            resulf = JSON.parse(resulf);
                            if(resulf['redirect']){
                                Swal.fire({
                                    position: 'top-end',
                                    icon: 'success',
                                    title: 'Your work has been saved',
                                    showConfirmButton: false,
                                    timer: 1500
                                })
                                setTimeout(function(){location.href= resulf['redirect'];},3000)

                            }

                            if(resulf['success']){
                                const Toast = Swal.mixin({
                                    toast: true,
                                    position: 'top-end',
                                    showConfirmButton: false,
                                    timer: 3000,
                                    timerProgressBar: true,
                                    onOpen: (toast) => {
                                        toast.addEventListener('mouseenter', Swal.stopTimer)
                                        toast.addEventListener('mouseleave', Swal.resumeTimer)
                                    }
                                })
                                Toast.fire({
                                    icon: 'success',
                                    title: 'Save successfully'
                                });
                                document.getElementById("form-create-category").reset();
                               location.reload();

                            }
                        }
                    }
                });

            }




        }
    }

    //delete category product by id
    async  function delete_category(id){
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
                var formData = new FormData();
                formData.append('action', 'delete_category');
                formData.append('id', id);
                formData.append('_token', setting.token);
                $.ajax({
                    url : setting.ajax_url,
                    type : 'POST',
                    data : formData,
                    processData: false,  // tell jQuery not to process the data
                    contentType: false,  // tell jQuery not to set contentType
                    success : function(resulf) {
                        if(resulf){
                            resulf = JSON.parse(resulf);
                            console.log(resulf);
                            if(resulf['success'] == true){
                                const Toast = Swal.mixin({
                                    toast: true,
                                    position: 'top-end',
                                    showConfirmButton: false,
                                    timer: 3000
                                });Toast.fire({
                                    icon: 'success',
                                    title: 'The category has been deleted successfully.'
                                })
                                document.querySelector('[data-category-id="'+id+'"]').remove();
                                location.reload();
                            }

                        }

                    }
                })
            }
        });
    }

    //edit category
    async  function edit_category(data){
        $('#form-create-category [name="name"]').val(data.name);
        $('#form-create-category [name="description"]').val(data.description);
        $('#form-create-category [name="parent_id"]').val(data.parent_id);
        $('#form-create-category [name="media"]').val(data.media);
        $('#form-create-category [name="feature_thumbnail"]').val(data.feature_thumbnail);
        $('#form-create-category [name="product_department"]').val(data.product_department);
        $('#form-create-category [name="loop"]').val(data.loop);
        $('#button-update-category').attr('onclick','save_category('+data.id+')').removeClass('d-none');
    }

    // save category
    async  function save_category(id){
        var Categories_modal = [
            {name:'id',value:id,label: 'ID',required: 'true'}
        ];
        $('#form-create-category [name]').each( function(){
            var k = $(this).attr('name');
            var v = $(this).val();
            var t = $(this).data('title');
            var r = $(this).data('required');
            if(k != 'story_content'){
                Categories_modal[k] = {name: k, value:v,label: t,required: r};
                $(this).next().addClass('d-none');
            }

        });

        if(Categories_modal){
            var error = '';
            for (var i in Categories_modal){
                if(Categories_modal[i].required & !Categories_modal[i].value){
                    error += Categories_modal[i].label +' is required !';
                    var div = document.querySelector('#form-create-category [name="'+i+'"]');
                    $(div).next().html('<span class="um-field-arrow"><i class="um-faicon-caret-up"></i></span>' + Categories_modal[i].label +' is required !').removeClass('d-none');
                }

            }
            if(products['button_featured_image']){
                Categories_modal['media'] = products['button_featured_image'];
                Categories_modal['media']['name'] = 'media';
            }
            if(products['button_feature_thumbnail']){
                Categories_modal['feature_thumbnail'] = products['button_feature_thumbnail'];
                Categories_modal['feature_thumbnail']['name'] = 'feature_thumbnail';
            }
            if( !error ){
                datas =  convert_array(Categories_modal);
                $('#button-update-category span').removeClass('d-none');
                $.ajax({
                    url: setting.ajax_url,
                    type: 'post',
                    data:{data:datas,_token:setting.token, action: 'update_product_category'},
                    success: function(resulf){
                        console.log(resulf);
                        if(resulf){
                            $('#button-update-category span').addClass('d-none');
                            resulf = JSON.parse(resulf);
                            if(resulf['success']){
                                const Toast = Swal.mixin({
                                    toast: true,
                                    position: 'top-end',
                                    showConfirmButton: false,
                                    timer: 3000,
                                    timerProgressBar: true,
                                    onOpen: (toast) => {
                                        toast.addEventListener('mouseenter', Swal.stopTimer)
                                        toast.addEventListener('mouseleave', Swal.resumeTimer)
                                    }
                                })
                                Toast.fire({
                                    icon: 'success',
                                    title: 'Save successfully'
                                });
                                location.reload();

                            }
                        }
                    }
                });

            }




        }
    }

    // show manage media to updaload
    $('.button_upload_media').on('click', function(){
        var id = $(this).data('media');
        var type = $(this).data('type');
        var ftype = $(this).data('ftype');
        $('#tabs-upload-media .upload-file input').attr('accept',type);
        $('#tabs-upload-media .upload-file input').attr('ftype',ftype);
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

    // show manage media to updaload
    $('.add_gallery_media').on('click', function(){
        var id = $(this).data('media');
        var type = $(this).data('type');
        var ftype = $(this).data('ftype');
        $('#tabs-upload-media .upload-file input').attr('accept',type);
        $('#tabs-upload-media .upload-file input').attr('ftype',ftype);
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

                        if(id == 'button_gallery'){
                            setup_media_gallery(resulf,'[data-gallery="'+id+'"]')
                        }else{
                            setup_media(resulf,'[data-media="'+id+'"]');
                        }

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
        if(id == 'button_gallery'){
            setup_media_gallery(data,'[data-gallery="'+id+'"]')
        }
        else if(id=='button_featured_image' || id=='button_feature_thumbnail'){
            setup_media(data,'[data-media="'+id+'"]');
        }
        else {
            setup_media_attribute(data,'[data-media="'+id+'"]');
        }



    });

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
            content.src = setting.url +  data.path;
        }else if(data.ftype == 'audio'){
            var content  = document.createElement('audio');
            var source  = document.createElement('source');
            source.src = setting.url +  data.path;
            source.type = data.type;
            content.controls = "controls";
            content.appendChild(source);

        }else if(data.ftype == 'video'){
            var content  = document.createElement('video');
            var source  = document.createElement('source');
            source.src = setting.url +  data.path;
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
        products[name] = {name:name,value: data.id, label:data.ftype, required: true};
        $('#MediaModal').modal('hide');
    }

    // edit media
    async function setup_media_gallery(data,id){
        var media = document.querySelector(id);
        media.className = "display-gallery mb-3";
        media.setAttribute('data-toggle','modals');
        var name = media.getAttribute('data-gallery');
        // set content media file
        var div  = document.createElement('div');
        div.className = 'item-gallery item_'+name+data.id;
        // check type media
        if(data.ftype == 'image'){
            var content  = document.createElement('img');
            content.src = setting.url + data.path;
        }else{
            var content  = document.createElement('img');
            content.src = '{{ url('uploads/use/office.png') }}';
        }

        var r = document.createElement('button');
        r.innerHTML = '<i class="far fa-trash-alt" style="font-size: 20px;"></i>';
        r.className ='btn btn-app';
        r.setAttribute('onClick',"remove_item_gallery('"+data.id+"','"+name+"')");
        div.appendChild(content);
        div.appendChild(r);
        media.appendChild(div);

        if(!products[name]){
            products[name] = {name:name,value: [{ id: data.id, link: setting.url + data.path }], label:data.ftype, required: false};
        }else{
            products[name].value.push({ id: data.id, link: data.link});
        }
        $('#MediaModal').modal('hide');
    }
    // remove media button
    function remove_media(id){
        var media = document.querySelector(id);
        var name = media.getAttribute('data-media');
        var required = media.getAttribute('data-required');
        media.innerHTML = "Upload";
        media.className = "btn btn-primary button_upload_media";
        media.setAttribute('data-toggle','modal');
        if(!products[name]){
            products[name] = {name:name,value:'', label:name, required: required};
        }
        products[name].value ='';
        products[name].required = Boolean(required);
    }

    //remove media item in gallery
    function remove_item_gallery(id,name){
        var data = [];
        products[name].value.forEach(function(element, index){
            if(element.id != id)data.push(element);
        });
        document.querySelector('.item_'+name+id).remove();
        products[name].value = data;
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
    remove_item = function(event){
        var t = $(event).parent().remove();
    }
</script>
</body>

</html>