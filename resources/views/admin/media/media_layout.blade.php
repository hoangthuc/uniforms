<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Dashboard | Media</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset('admink/plugins/fontawesome-free/css/all.min.css') }}">
    <!-- Ekko Lightbox -->
    <link rel="stylesheet" href="{{ asset('admink/plugins/ekko-lightbox/ekko-lightbox.css') }}">
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <!-- SweetAlert2 -->
    <link rel="stylesheet" href="{{ asset('admink/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css') }}">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('admink/dist/css/adminlte.min.css') }}">
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
                <div class="modal-header">
                    <h5 class="modal-title" id="MediaModalLabel">Edit Media</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                   <div class="row">
                       <div class="content col-sm-8">
                           <!-- Input addon -->
                           <div class="card card-info">
                               <div class="card-header">
                                   <h3 class="card-title">Content Media</h3>
                               </div>
                               <div class="card-body">

                               </div>
                               <!-- /.card-body -->
                           </div>
                           <!-- /.card -->

                       </div>
                       <div class="control-form col-sm-4">
                       <!-- general form elements -->
                       <div class="card card-primary">
                           <div class="card-header">
                               <h3 class="card-title">Info media</h3>
                           </div>
                           <!-- /.card-header -->
                           <!-- form start -->
                           <form role="form" id="edit-media">
                               <div class="card-body">
                                   <div class="callout callout-info">
                                       <h5>Type: <div data-display="type" class="d-inline">Image</div></h5>
                                       <p>Delete</p>
                                       <p>Create: 2020/07/14</p>
                                       <p>Size: 38.04 MB</p>
                                   </div>
                                   <div class="form-group">
                                       <label>Title</label>
                                       <input type="text" class="form-control" name="title" placeholder="Enter title">
                                   </div>
                                   <div class="form-group">
                                       <label for="exampleInputPassword1">Description</label>
                                       <textarea  class="form-control" name="description" rows="5" placeholder="Enter description"></textarea>
                                   </div>
                               </div>
                               <!-- /.card-body -->
                           </form>
                       </div>
                       <!-- /.card -->

                       </div>
                   </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <a type="button" id="save_media_modal" class="btn btn-primary">Save changes</a>
                </div>
            </div>
        </div>
    </div>

    <footer class="main-footer">
        <div class="float-right d-none d-sm-block">
            <b>Version</b> 3.0.5
        </div>
        <strong>Copyright &copy; 2020.</strong> All rights
        reserved.
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
<!-- Bootstrap -->
<script src="{{ asset('admink/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<!-- SweetAlert2 -->
<script src="{{ asset('admink/plugins/sweetalert2/sweetalert2.min.js') }}"></script>
<!-- jQuery UI -->
<script src="{{ asset('admink/plugins/jquery-ui/jquery-ui.min.js') }}"></script>
<!-- Ekko Lightbox -->
<script src="{{ asset('admink/plugins/ekko-lightbox/ekko-lightbox.min.js') }}"></script>
<!-- AdminLTE App -->
<script src="{{ asset('admink/dist/js/adminlte.min.js') }}"></script>
<!-- AdminLTE for demo purposes -->
<script src="{{ asset('admink/dist/js/demo.js') }}"></script>

<!-- Page specific script -->
<script>
    var setting = {
        'ajax_url':'{{ url('admin/upload') }}',
        'admin_ajax_url':'{{ url('admin/admin_ajax') }}',
        'token':'{{ csrf_token() }}',
        'url':'{{ url('/').'/' }}',
    };
    $(function () {
        $(document).on('click', '[data-toggle="lightbox"]', function(event) {
            event.preventDefault();
            $(this).ekkoLightbox({
                alwaysShowClose: true
            });
        });
        $('[name="UploadMedia"]').on('change',function(){
            var type = $(this).data('type');
            var medias = $(this)[0].files;
            if(medias){
                for (media of medias){
                    var formData = new FormData();
                    formData.append('UploadMedia', media);
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
                                if(resulf['success']){
                                    if(type=='list'){
                                        var div = create_media_list(resulf);
                                        var parent =  document.getElementById('list-medias');
                                    }else{
                                        var div = create_media(resulf);
                                        var parent =  document.getElementById('grid-medias');
                                    }

                                    parent.insertBefore(div, parent.childNodes[0]);
                                    $('[name="UploadMedia"]').val('');
                                    const Toast = Swal.mixin({
                                        toast: true,
                                        position: 'top-end',
                                        showConfirmButton: false,
                                        timer: 3000
                                    });Toast.fire({
                                        icon: 'success',
                                        title: 'Upload media successfully.'
                                    })
                                }

                            }

                        }
                    })
                }
            }

        });




        $("#MediaModal").on('hide.bs.modal', function () {
            console.log('close');
            document.querySelector('#MediaModal .content .card-body').innerHTML = "";
            document.querySelector('#edit-media .callout-info').innerHTML = ""
        });



    })
// create with grid
    function create_media(resulf){
        var div = document.createElement('div');
        var link = document.createElement('div');
        var img = document.createElement('img');
        var span = document.createElement('span');
        div.className = 'item-media filtr-item col-sm-2';
        link.className = 'link';
        img.className = 'responsive';
        span.className = 'name-media d-block';
        if (isValidFileType(resulf['end_file'], 'image')) {
            link.style.backgroundImage = "url('" + resulf['link'] + "')";
        }
        else if (isValidFileType(resulf['end_file'], 'video')) {
            link.style.backgroundImage = "url(' {{ url('uploads/use/video.png') }}')";

        }
        else if (isValidFileType(resulf['end_file'], 'audio')) {
            link.style.backgroundImage = "url('{{ url('uploads/use/audio.png') }}')";

        }
        else{
            link.style.backgroundImage = "url('{{ url('uploads/use/document.png') }}')";
        }
        img.setAttribute('data-json',JSON.stringify(resulf));
        img.setAttribute('onClick',"setup_media_byjson('"+JSON.stringify(resulf)+"')");
        img.setAttribute('data-id',resulf['id']);
        img.setAttribute('data-size',resulf['size']);
        img.setAttribute('data-type',resulf['ftype']);
        img.setAttribute('data-toggle','modal');
        img.setAttribute('data-target','#MediaModal');
        img.src= '{{ url('uploads/use/plus2.png') }}';
        span.innerText = resulf['name_media'];
        link.appendChild(img);
        link.appendChild(span);
        div.appendChild(link);
        return div;
    }
// create with list
    function create_media_list(resulf){
        var tr = document.createElement('tr');
        // ID
        var td1 = document.createElement('td');
        td1.innerText = resulf.id;
        tr.appendChild(td1);
        // Image
        var td2 = document.createElement('td');
        var div2 = document.createElement('div');
        div2.className = 'img_featured';
        if (isValidFileType(resulf['end_file'], 'image')) {
            div2.style.backgroundImage = "url('" + resulf['link'] + "')";
        }
        else if (isValidFileType(resulf['end_file'], 'video')) {
            div2.style.backgroundImage = "url(' {{ url('uploads/use/video.png') }}')";

        }
        else if (isValidFileType(resulf['end_file'], 'audio')) {
            div2.style.backgroundImage = "url('{{ url('uploads/use/audio.png') }}')";

        }
        else{
            div2.style.backgroundImage = "url('{{ url('uploads/use/document.png') }}')";
        }
        td2.appendChild(div2);
        tr.appendChild(td2);
        // Name
        var td3 = document.createElement('td');
        var a3 = document.createElement('a');
        td3.className = 'link';
        a3.innerText = resulf.name_media;
        a3.setAttribute('onClick',"setup_media_byjson('"+JSON.stringify(resulf)+"')");
        a3.setAttribute('data-id',resulf['id']);
        a3.setAttribute('data-size',resulf['size']);
        a3.setAttribute('data-type',resulf['ftype']);
        a3.setAttribute('data-toggle','modal');
        a3.setAttribute('data-target','#MediaModal');
        td3.appendChild(a3);
        tr.appendChild(td3);

        // Author
        var td4 = document.createElement('td');
        td4.innerText = resulf.author;
        tr.appendChild(td4);

        // Type
        var td5 = document.createElement('td');
        td5.innerText = resulf.type;
        tr.appendChild(td5);

        // Created
        var td6 = document.createElement('td');
        td6.innerText = resulf.created_at;
        tr.appendChild(td6);

        return tr;
    }

    var extensionLists = {}; //Create an object for all extension lists
    extensionLists.video = ['m4v', 'avi','mpg','mp4', 'webm','wmv'];
    extensionLists.image = ['jpg', 'gif', 'bmp', 'png'];
    extensionLists.audio = ['mp3'];
    // One validation function for all file types
    function isValidFileType(fName, fType) {
        return extensionLists[fType].indexOf(fName.split('.').pop()) > -1;
    }
// edit media
    async function setup_media(data){
        // set content media file
        var media = document.querySelector('#MediaModal .content .card-body');
        media.innerHTML = '';
       var div  = document.createElement('div');
       // check type media
       if(data.ftype == 'image'){
           var content  = document.createElement('img');
           content.src = setting.url + data.path;
       }else if(data.ftype == 'audio'){
           var content  = document.createElement('audio');
           var source  = document.createElement('source');
           source.src = setting.url + data.path;
           source.type = data.type;
           content.controls = "controls";
           content.appendChild(source);

       }else if(data.ftype == 'video'){
           var content  = document.createElement('video');
           var source  = document.createElement('source');
           source.src = setting.url + data.path;
           source.type = data.type;
           content.controls = "controls";
           content.width = "320";
           content.height = "240";
           content.appendChild(source);

       }else{
           var content  = document.createElement('img');
           content.src = setting.url + 'uploads/use/office.png';
       }
       div.appendChild(content);
        media.appendChild(div);

        // display title
       document.querySelector('#edit-media [name="title"]').value = data.title;
       // display description
        document.querySelector('#edit-media [name="description"]').value = data.description;
        //display media info
        var media_info = document.querySelector('#edit-media .callout-info');
        media_info.innerHTML = "";
        // display type
        var type_i = document.createElement('p');
        type_i.innerText = 'Type: '+ data.ftype;
        media_info.appendChild(type_i);

        // display date create
        var type_dc = document.createElement('p');
        let today = new Date(data.created_at);
        let dd = today.getDate();
        let mm = today.getMonth()+1;
        const yyyy = today.getFullYear();
        if(dd<10)dd=`0${dd}`;
        if(mm<10)mm=`0${mm}`;
        today = `${mm}/${dd}/${yyyy}`;
        type_dc.innerText = 'Create: '+ today;
        media_info.appendChild(type_dc);

        //display data size media
        var type_s = document.createElement('p');
        type_s.innerText = 'Size: '+ data.size;
        media_info.appendChild(type_s);

        // display link delete
        var type_d = document.createElement('div');
        var type_da = document.createElement('a');
        type_da.className = 'btn btn-app';
        type_da.href = 'javascript:delete_media('+ JSON.stringify(data) +')';
        type_da.innerHTML = '<i class="far fa-trash-alt"></i> Delete';
        type_d.appendChild(type_da);
        media_info.appendChild(type_d);

        // insert id
        var button  = document.querySelector('#save_media_modal');
        button.setAttribute('href','javascript:save_media('+ data.id +',`'+JSON.stringify(data)+'`)');
    }
    // edit media form json
    async function setup_media_byjson(data){
        data =  JSON.parse(data);
        if(!data.ftype)data.ftype = document.querySelector('.link [data-id="'+data.id+'"]').getAttribute('data-type');
        if(!data.size)data.size = document.querySelector('.link [data-id="'+data.id+'"]').getAttribute('data-size');
        setup_media(data);
    }
    // save media
    async function save_media(id,data){
        var title = document.querySelector('#edit-media [name="title"]').value;
        var description =  document.querySelector('#edit-media [name="description"]').value;
        var formData = new FormData();
        formData.append('action', 'update_media');
        formData.append('title', title);
        formData.append('description', description);
        formData.append('id', id);
        formData.append('_token', setting.token);
        $.ajax({
            url : setting.admin_ajax_url,
            type : 'POST',
            data : formData,
            processData: false,  // tell jQuery not to process the data
            contentType: false,  // tell jQuery not to set contentType
            success : function(resulf) {
                if(resulf){
                    resulf = JSON.parse(resulf);
                    console.log(resulf);
                    if(resulf['success']){
                        data =  JSON.parse(data);
                        data.title = title;
                        data.description = description;
                        document.querySelector('.link [data-id="'+id+'"]').setAttribute('onClick',"setup_media_byjson('"+JSON.stringify(data)+"')");
                        $('#MediaModal').modal('hide');

                    }

                }

            }
        })
    }

    async function delete_media(data){
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
                formData.append('action', 'delete_media');
                formData.append('path', data.path);
                formData.append('id', data.id);
                formData.append('_token', setting.token);
                $.ajax({
                    url : setting.admin_ajax_url,
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
                                    title: 'The '+data.title+' has been deleted successfully.'
                                })
                                document.querySelector('.link [data-ID="'+data.id+'"]').parentElement.parentElement.remove();
                                $('#MediaModal').modal('hide');
                            }

                        }

                    }
                })
            }
        });



    }
</script>
</body>
</html>
