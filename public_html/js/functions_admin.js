var stringToBoolean = function(string){
    switch(string.toLowerCase().trim()){
        case "true": case "yes": case "1": return true;
        case "false": case "no": case "0": case null: return false;
        default: return Boolean(string);
    }
}
// check username & email to register
async function save_post(){
    jQuery('.create_new_story [name]').each( function(){
        var k = jQuery(this).attr('name');
        var v = jQuery(this).val();
        var t = jQuery(this).data('title');
        var r = jQuery(this).data('required');
        if(k != 'post_content'){
            posts[k] = {name: k, value:v,label: t,required: r};
            jQuery(this).next().addClass('d-none');
        }
        if(k == 'post_content'){
            posts[k] = {name: k, value:v,label: t,required: r};
            jQuery(this).next().next('span').addClass('d-none');
        }

    });

    if(posts){
        var error = '';
        for (var i in posts){
            if(posts[i].required & !posts[i].value){
                error += posts[i].label +' is required !';
                var div = document.querySelector('.create_new_story [name="'+i+'"]');
                jQuery(div).next().html('<span class="um-field-arrow"><i class="um-faicon-caret-up"></i></span>' + posts[i].label +' is required !').removeClass('d-none');
            }

            if(i =='post_content' & !posts[i].value){
                error += posts[i].label +' is required !';
                var div = document.querySelector('.create_new_story [name="'+i+'"]');
                jQuery(div).next().next('span').html('<span class="um-field-arrow"><i class="um-faicon-caret-up"></i></span>' + posts[i].label +' is required !').removeClass('d-none');
            }

            if(i =='audio' & posts[i].required & !posts[i].value){
                error += posts[i].name +' is required !';
                var div = document.querySelector('.create_new_story [name="story_type"]');
                jQuery(div).next().html('<span class="um-field-arrow"><i class="um-faicon-caret-up"></i></span>' + posts[i].name +' is required !').removeClass('d-none');
            }
        }

        if( !error ){
            datas =  convert_array(posts);
            jQuery('#button-save-story span').removeClass('d-none');
            jQuery.ajax({
                url: posts['action']['value'],
                type: 'post',
                data:{data:datas,_token:posts['_token']['value']},
            success: function(resulf){
                console.log(resulf);
                if(resulf){
                    jQuery('#button-save-story span').addClass('d-none');
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
                        })
                    }
                }
            }
            });

        }




    }

}

//convert key array
function convert_array(array) {
    var list_array = [];
    var j = 0;
    for (var i in array){
        list_array[j] = array[i];
        j++;
    }
    return list_array;
}
// delete story
async function delete_post(id,action){
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
            jQuery.ajax({
                url:setting.ajax_url,
                type: 'post',
                data:{action:action,_token:setting.token, id:id},
            success: function(resulf){
                if(resulf){
                    Swal.fire(
                        'Deleted!',
                        'Your work has been deleted.',
                        'success'
                    );
                    resulf = JSON.parse(resulf);
                    if(resulf['redirect']){
                        setTimeout(function(){location.href= resulf['redirect'];},2000);
                    }

                    if(resulf['reload']){
                        setTimeout(function(){location.href= location;},2000);
                    }

                }
            }
        });

        }
    })
}

jQuery.fn.change_status_comment = function(id,action) {
    var v = jQuery(this).val();
    jQuery.ajax({
        url:setting.ajax_url,
        type: 'post',
        data:{action:action,_token:setting.token, id:id,value:v},
        success: function(resulf){
            if(resulf){
                resulf = JSON.parse(resulf);
                if(resulf['success']){
                    const Toast = Swal.mixin({
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 1000,
                        timerProgressBar: true,
                        onOpen: (toast) => {
                            toast.addEventListener('mouseenter', Swal.stopTimer)
                            toast.addEventListener('mouseleave', Swal.resumeTimer)
                        }
                    })

                    Toast.fire({
                        icon: 'success',
                        title: 'Save successfully'
                    })
                }
            }
        }
    });
}


// save product
async function save_product(){
    var error = '';
    products['additional_information'] = {name:'additional_information',value:[],required:false};
    jQuery('.create_new_product [name]').each( function(){
        var k = jQuery(this).attr('name');
        var v = jQuery(this).val();
        var t = jQuery(this).data('title');
        var r = jQuery(this).data('required');

        if(k != 'description' && k != 'size' && k != 'color' && k != 'title' && k != 'content' && k!='additional-information'){
            products[k] = {name: k, value:v,label: t,required: r};
            if(products[k].required)jQuery(this).next().addClass('d-none');
        }
        if(k == 'description' || k == 'size' || k == 'color'){
            products[k] = {name: k, value:v,label: t,required: r};
            jQuery(this).next().next('span').addClass('d-none');
        }

        if(k == 'title' || k == 'content'){
            products['additional_information'].value.push({name: k, value:v,label: t,required: r});
            jQuery(this).removeClass('d-error');
            if(r & !v){
                error += t +' is required !';
                $(this).addClass('d-error');
            }
        }

    });
    $Attribute.update();

    if(products){
        for (var i in products){
            if(products[i].required & !products[i].value){
                error += products[i].label +' is required !';
                var div = document.querySelector('.create_new_product [name="'+i+'"]');
                jQuery(div).next().html('<span class="um-field-arrow"><i class="um-faicon-caret-up"></i></span>' + products[i].label +' is required !').removeClass('d-none');
            }

            if(i =='description' & !products[i].value){
                error += products[i].label +' is required !';
                var div = document.querySelector('.create_new_product [name="'+i+'"]');
                jQuery(div).next().next('span').html('<span class="um-field-arrow"><i class="um-faicon-caret-up"></i></span>' + products[i].label +' is required !').removeClass('d-none');
            }

            if( (i =='size' || i == 'color') & !products[i].value.length > 0){
                error += products[i].label +' is required !';
                var div = document.querySelector('.create_new_product [name="'+i+'"]');
                jQuery(div).next().next('span').html('<span class="um-field-arrow"><i class="um-faicon-caret-up"></i></span>' + products[i].label +' is required !').removeClass('d-none');
            }

        }

        document.querySelectorAll('.item-attribute.selected select').forEach(at=>{
            at.nextElementSibling.nextElementSibling.className = 'um-field-error d-none';
            if(!$(at).val().length){
                error += at.getAttribute('data-title') +' is required !';
                at.nextElementSibling.nextElementSibling.className = 'um-field-error';
                at.nextElementSibling.nextElementSibling.textContent = at.getAttribute('data-title')+' is required.';
            }
        });


        if( !error ){
            datas =  convert_array(products);
            console.log(datas);
            jQuery('#button-save-product span').removeClass('d-none');
            jQuery.ajax({
                url: products['action']['value'],
                type: 'post',
                data:{data:datas,_token:products['_token']['value'],all_attributes: get_data_attribute(),price_attribute:$Attribute.price_attr,default_attribute:$Attribute.default_attr,thumbnail_attribute:$Attribute.thumbnail_attr, thumbnail_color:$Attribute.thumbnail_color, name_plate:$Attribute.name_plate},
                success: function(resulf){
                    if(resulf){
                        jQuery('#button-save-product span').addClass('d-none');
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
                            })
                        }
                    }
                }
            });

        }




    }

}


// save order
async function save_order(event){
    orders['products'] = {name:'products',value: jQuery('#button-save-order').data('products'), label:'Product', required: true};
    console.log(orders);
    jQuery('.save_order [name]').each( function(){
        var k = jQuery(this).attr('name');
        var v = jQuery(this).val();
        var t = jQuery(this).data('title');
        var r = jQuery(this).data('required');
        if(k != 'description' && k != 'size' && k != 'color'){
            orders[k] = {name: k, value:v,label: t,required: r};
            if(orders[k].required)jQuery(this).next().addClass('d-none');
        }
        if(k == 'description' || k != 'size' || k != 'color'){
            orders[k] = {name: k, value:v,label: t,required: r};
            jQuery(this).next().next('span').addClass('d-none');
        }

    });

    if(orders){
        var error = '';
        for (var i in orders){
            if(orders[i].required & !orders[i].value){
                error += orders[i].label +' is required !';
                var div = document.querySelector('.save_order [name="'+i+'"]');
                jQuery(div).next().html('<span class="um-field-arrow"><i class="um-faicon-caret-up"></i></span>' + orders[i].label +' is required !').removeClass('d-none');
            }

        }

        if( !error ){
            datas =  convert_array(orders);
            jQuery('#button-save-order span').removeClass('d-none');
            jQuery.ajax({
                url: orders['action']['value'],
                type: 'post',
                data:{data:datas,_token:orders['_token']['value']},
                success: function(resulf){
                    console.log(resulf);
                    if(resulf){
                        jQuery('#button-save-order span').addClass('d-none');
                        resulf = JSON.parse(resulf);
                        if(resulf['redirect']){
                            Swal.fire({
                                position: 'top-end',
                                icon: 'success',
                                title: 'Your work has been saved',
                                showConfirmButton: false,
                                timer: 1500
                            })
                           setTimeout(function(){location.href= resulf['redirect'];},1000)

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
                            })
                        }
                    }
                }
            });

        }




    }

}

// add item in FAQ in each array
async function add_item_fqa(dom){
var div =  document.createElement('div');
div.className = 'form-group mb-4';
var button =  document.createElement('button');
button.className = 'btn btn-tool';
button.type = 'button';
button.innerHTML = '<i class="far fa-times-circle"></i> Delete';
button.setAttribute('onclick','remove_item(this)');
div.appendChild(button);

var input = document.createElement('input');
input.type = 'text';
input.name = 'title';
input.className = 'form-control mt-1 mb-1';
input.setAttribute('data-faq',dom);
input.setAttribute('data-title','Title');
input.setAttribute('data-required','true');
input.setAttribute('placeholder','Title');
    div.appendChild(input);
var span = document.createElement('span');
span.className = 'um-field-error d-none';
div.appendChild(span);



var textarea = document.createElement('textarea');
    textarea.name = 'content';
    textarea.className = 'form-control mt-3 mb-1';
    textarea.setAttribute('data-faq',dom);
    textarea.setAttribute('data-title','Content');
    textarea.setAttribute('data-required','true');
    textarea.setAttribute('placeholder','Content');
    div.appendChild(textarea);
var span = document.createElement('span');
    span.className = 'um-field-error d-none';
    div.appendChild(span);

document.querySelector('.item-faq-'+dom+' .card-body').appendChild(div);
}
// save setting FAQ
async  function save_faq(){
    var error = '';
    clear_faq();
    jQuery('#setting-faq [name]').each( function(index){
        var f = jQuery(this).data('faq');
        var k = jQuery(this).attr('name');
        var v = jQuery(this).val();
        var t = jQuery(this).data('title');
        var r = jQuery(this).data('required');
        faq[f].value.push({name: k, value:v,label: t,required: r});
        $(this).removeClass('d-error');
        if(r & !v){
            error += t +' is required !';
            $(this).addClass('d-error');
        }
    });
    if(!error){
        jQuery('#button-save-faq span').removeClass('d-none');
        jQuery.ajax({
            url: setting.save_faq_ajax_url,
            type: 'post',
            data:{data:faq,_token:setting.token},
            success: function(resulf){
                console.log(resulf);
                if(resulf){
                    resulf = JSON.parse(resulf);
                    if(resulf['success']){
                        jQuery('#button-save-product span').addClass('d-none');
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
                        })
                    }
                }

                }
        });
    }

}

// save form data  ctf
async function save_ctf(){
    var error = '';
    var ctf = [];
    jQuery('#setting-ctf [name]').each( function(index){
        var k = jQuery(this).attr('name');
        var v = jQuery(this).val();
        var t = jQuery(this).data('title');
        var r = jQuery(this).data('required');
        ctf[k] = {name: k, value:v,label: t,required: r};
        if(k != 'message'){
            $(this).removeClass('d-error');
            $(this).next().addClass('d-none');
        }
        if(k == 'message'){
            $(this).next().next().addClass('d-none');
        }

        if(r & !v){
            error += t +' is required !';
            $(this).addClass('d-error');
            if(k == 'message'){
                $(this).next().next().removeClass('d-none').text(t +' is required !');
            }else{
                $(this).next().removeClass('d-none').text(t +' is required !');
            }
        }

        if(k =='email' & r & !ValidateEmail(v)){
            error += t +' is unvalid !';
            $(this).next().removeClass('d-none').text(t +' is unvalid !');
        }
    });
    console.log(error);

    if(!error){
        datas =  convert_array(ctf);
        console.log(datas);
        jQuery('#button-save-ctf span').removeClass('d-none');
        jQuery.ajax({
            url: setting.ajax_url,
            type: 'post',
            data:{data:datas,_token:setting.token, action:ctf['action'].value},
            success: function(resulf){
                console.log(resulf);
                if(resulf){
                    resulf = JSON.parse(resulf);
                    if(resulf['success']){
                        jQuery('#button-save-ctf span').addClass('d-none');
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
                        })
                    }
                }

            }
        });
    }
}

// check valicate email
function ValidateEmail(inputText)
{
    var mailformat = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;
    if(inputText.match(mailformat))
    {
        return true;
    }
    else
    {
        return false;
    }
}

// check valicate URL
function isValidUrl(string) {
    try {
        new URL(string);
    } catch (_) {
        return false;
    }

    return true;
}

// add customer data form ctf
async  function save_data_row_ctf(){
    var error = '';
    var data = [];
    jQuery('#display-data-ctf [name]').each( function(index){
        var k = jQuery(this).attr('name');
        var v = jQuery(this).val();
        var t = jQuery(this).data('title');
        var r = jQuery(this).data('required');
        data[k]= {name: k, value:v,label: t,required: r};
        $(this).removeClass('d-error');
        if(r & !v){
            error += t +' is required !';
            $(this).addClass('d-error');
        }

        if(k =='email' && v && !ValidateEmail(v)){
            error += t +' is unvalid !';
            $(this).addClass('d-error');
        }


    });
    if(!error){
        console.log(data);
        datas =  convert_array(data);
        jQuery('#button-data-form-customer span').removeClass('d-none');
        jQuery.ajax({
            url: setting.ajax_url,
            type: 'post',
            data:{data:datas,_token:setting.token, action:data['action'].value},
            success: function(resulf){
                console.log(resulf);
                if(resulf){
                    resulf = JSON.parse(resulf);
                    if(resulf['success']){
                        jQuery('#button-data-form-customer span').addClass('d-none');
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
                        })


                    }

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
                }

            }
        });
    }

}


async function save_system_settings(){
    var error = '';
    var data = [];
    jQuery('.display_system_settings [name]').each( function(index){
        var k = jQuery(this).attr('name');
        var v = jQuery(this).val();
        var t = jQuery(this).data('title');
        var r = jQuery(this).data('required');
        data[k]= {name: k, value:v,label: t,required: r};
        $(this).removeClass('d-error');
        if(r & !v){
            error += t +' is required !';
            $(this).addClass('d-error');
        }

        if(k =='site_email' && v && !ValidateEmail(v)){
            error += t +' is unvalid !';
            $(this).addClass('d-error');
        }

        if(k =='site_url' && v && !isValidUrl(v)){
            error += t +' is unvalid !';
            $(this).addClass('d-error');
        }

    });

    if(!error){
        console.log(data);
        datas =  convert_array(data);
        jQuery('#button-display_system_settings span').removeClass('d-none');
        jQuery.ajax({
            url: setting.ajax_url,
            type: 'post',
            data:{data:datas,_token:setting.token, action:data['action'].value},
            success: function(resulf){
                console.log(resulf);
                if(resulf){
                    resulf = JSON.parse(resulf);
                    if(resulf['success']){
                        jQuery('#button-display_system_settings span').addClass('d-none');
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
                        })


                    }

                }

            }
        });
    }
}


async function create_attribute(event){
    var Categories = [];
    var post_url = document.querySelector('#form-create-attribute').getAttribute('action');
    $('#form-create-attribute [name]').each( function(){
        var type = $(this).attr('type');
        var k = $(this).attr('name');
        var v = $(this).val();
        var t = $(this).data('title');
        var r = $(this).data('required');
        Categories[k] = {name: k, value:v,label: t,required: r};
        $(this).next().addClass('d-none');
        if(type=='hidden' && k=='data_type' && products['button_featured_image'] ){
            if(products['button_featured_image'].value)Categories[k].value = products['button_featured_image'].value;
        }
    });
    console.log(Categories);
    if(Categories){
        var error = '';
        for (var i in Categories){
            if(Categories[i].required & !Categories[i].value){
                console.log(i);
                error += Categories[i].label +' is required !';
                var div = document.querySelector('#form-create-attribute [name="'+i+'"]');
                $(div).next().html('<span class="um-field-arrow"><i class="um-faicon-caret-up"></i></span>' + Categories[i].label +' is required !').removeClass('d-none');
            }

        }

        if( !error ){
            datas =  convert_array(Categories);
            $(event).removeClass('d-none');
            $.ajax({
                url: post_url,
                type: 'post',
                data:{data:datas,_token:setting.token},
            success: function(resulf){
                console.log(resulf);
                if(resulf){
                    $(event).addClass('d-none');
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
                        location.reload();

                    }
                }
            }
        });

        }




    }
}
// function in attribute product management
async  function delete_attribute(id){
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
            formData.append('action', 'delete_attribute');
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
                                title: 'The attribute has been deleted successfully.'
                            })
                            document.querySelector('[data-category-id="'+id+'"]').remove();
                        }

                    }

                }
            })
        }
    });
}
async function edit_attribute(data){
    if(!data)return false;
    for( let el in data ){
        let div = document.querySelector('#form-create-attribute [name="'+el+'"]');
        if(div)div.value =data[el];
    }
    document.querySelector('#button-add-category').className = 'btn btn-primary d-none';
    let update  = document.querySelector('#button-update-category');
    update.className = 'btn btn-primary';
    update.setAttribute('data-id',data.id);
}
async function update_attribute(event) {
    var Categories = [{name: 'id', value: $(event).data('id'),label: 'ID',required: true}];
    var post_url = $(event).data('action');
    $('#form-create-attribute [name]').each( function(){
        var type = $(this).attr('type');
        var k = $(this).attr('name');
        var v = $(this).val();
        var t = $(this).data('title');
        var r = $(this).data('required');
        Categories[k] = {name: k, value:v,label: t,required: r};
        $(this).next().addClass('d-none');
        if(type=='hidden' && k=='data_type' && products['button_featured_image'] ){
            if(products['button_featured_image'].value)Categories[k].value = products['button_featured_image'].value;
        }
    });
    console.log(Categories);
    if(Categories){
        var error = '';
        for (var i in Categories){
            if(Categories[i].required & !Categories[i].value){
                console.log(i);
                error += Categories[i].label +' is required !';
                var div = document.querySelector('#form-create-attribute [name="'+i+'"]');
                $(div).next().html('<span class="um-field-arrow"><i class="um-faicon-caret-up"></i></span>' + Categories[i].label +' is required !').removeClass('d-none');
            }

        }

        if( !error ){
            datas =  convert_array(Categories);
            $(event).removeClass('d-none');
            $.ajax({
                url: post_url,
                type: 'post',
                data:{data:datas,_token:setting.token},
                success: function(resulf){
                    console.log(resulf);
                    if(resulf){
                        $(event).addClass('d-none');
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
                            location.reload();

                        }
                    }
                }
            });

        }




    }
}
// in edit product
async function change_attribute(event,name=''){
    let attributes = $(event).val();
    if(name){
        document.querySelectorAll('.item-attribute').forEach(el=>{
            el.className= 'form-group item-attribute d-none';
        });
        for (let el of attributes){
            document.querySelector( '[data-attribute="'+el+'"]' ).className='form-group item-attribute selected';
        }
    }
    // $('[name="product_type"]').change();
    change_product_type(null);
}
async function change_product_type(event){
    let attribute = $('[name="attributes"]');
    let json = attribute.data('json');
    let data = {};
    let check  =[];
    document.querySelectorAll('.item-attribute.selected select').forEach(el=>{
    let name = $(el).attr('name');
     data[name] = {value:[],display: true};
    $(el).val().forEach( item =>{
        data[name]['value'].push( {title:json[name].value[item],value: item } );
    });
    if(!data[name].value.length )check.push(name);
    })
    if(!attribute.val().length)check.push('empty');
    let show  = $(event).val();
    $Attribute.data = data;
    $Attribute.render();
}
// add variations
async function add_variation(event){
let data = $(event).attr('data-json');
data = JSON.parse(data);
varitions.push(data);
let id = varitions.length;
    setup_variations(data,id);
    add_product_varition_default(null);
}

async function setup_variations(data,id){
    let def  =  document.createElement('div');
    def.className = 'none';
    def.setAttribute('data-check-default','');
    def.setAttribute('onclick','add_product_varition_default(this)');
    def.setAttribute('data-select',id);
    def.innerHTML = '<i class="fas fa-star"></i><i class="far fa-star"></i>';
    let div  =  document.createElement('div');
    div.className = 'item-product-varition mb-3 pb-3 border-bottom'
    let inc = document.createElement('div');
    inc.className = 'form-inline mb-3';
    inc.appendChild(def);
    for(var va in data){
        if(data[va].display){
        let se =  document.createElement('select');
        se.className = 'form-control mr-2';
        se.setAttribute('name',va);
        for(var option of data[va].value){
            let op =  document.createElement('option');
            op.setAttribute('value',option['value']);
            op.text = option.title;
            se.appendChild(op);
        }
        inc.appendChild(se);
        }
    }
    div.appendChild(inc);
    let del_attr = document.createElement('span');
    del_attr.className = 'delete_attribute fas fa-trash-alt';
    del_attr.setAttribute('onclick','delete_variation(this,'+id+')');
    inc.appendChild(del_attr);
    let row =  document.createElement('div');
    row.className = 'row';
    let left = document.createElement('div');
    left.className = 'col-md-3';
    row.appendChild(left);
    // set images
    let display =  document.createElement('div');
    display.className = 'display-media';
    left.appendChild(display);
    let button =  document.createElement('div');
    button.className ='btn btn-primary button_upload_media';
    button.textContent ='Upload image';
    button.setAttribute('data-media','varition_featured_image'+id);
    button.setAttribute('data-ftype','image');
    button.setAttribute('data-type','image/*');
    button.setAttribute('data-toggle','modal');
    button.setAttribute('data-target','#MediaModal');
    button.setAttribute('data-required','false');
    button.setAttribute('onclick','loading_medias(this)');
    left.appendChild(button);
    let right = document.createElement('div');
    right.className = 'col-md-9';
    row.appendChild(right);
    let price = document.createElement('input');
    price.className = 'form-control';
    price.setAttribute('type','number');
    price.setAttribute('name','price');
    price.setAttribute('placeholder','Price');
    right.appendChild(price);
    let description = document.createElement('textarea');
    description.className = 'form-control mt-3';
    description.setAttribute('name','description');
    description.setAttribute('placeholder','Description');
    right.appendChild(description);
    div.appendChild(row);
    document.querySelector('[data-Product-Variations]').appendChild(div);
}
// for attribute in product with button onlu image
async function loading_medias(event){
    var id = $(event).data('media');
    var type = $(event).data('type');
    var ftype = $(event).data('ftype');
    $('#tabs-upload-media .upload-file input').attr('accept',type);
    $('#tabs-upload-media .upload-file input').attr('ftype',ftype);
    $('#tabs-upload-media .upload-file input').attr('data-insert','single_image');
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
}
// loading media in modal popup perpage 12 in page
async function loading_more_medias(){
 let medias = document.querySelectorAll('#grid-medias .item-media');
    let number  = Math.round(medias.length/12 ) + 1;
 let pause = medias.length%12;
if(!pause){
    let ftype = $('#tabs-upload-media .upload-file input').attr('ftype');
    $.ajax({
        url: setting.ajax_url,
        type: "POST",
        data: {action:'get_medias',_token: setting.token,type:ftype ,page: number},
        success: function(resulf){
            if(resulf){
                let data =  document.createElement('div');
                data.innerHTML = resulf;
                document.querySelector('#grid-medias').appendChild(data);
                // console.log(data.innerHTML);
            }
        }
    });
}

}

function get_data_attribute() {
    let all_attribute = {product_type:0,attributes:{},optional:[] };
    let attributes_optional = [];
    all_attribute.attributes = $Attribute.data;
    all_attribute.optional = attributes_optional;
    return all_attribute;
}

// setup media attribute
async function setup_media_attribute(data,id){
    var media = document.querySelector(id);
    media.innerHTML = "";
    media.className = "btn button_upload_media_i";
    media.setAttribute('data-toggle','modals');
    var name = media.getAttribute('data-media');
    // set content media file
    var div  = document.createElement('div');
    // check type media
    var content  = document.createElement('img');
    content.src = setting.url +  data.path;
    content.setAttribute('data-id',data.id);

    var r = document.createElement('button');
    r.innerHTML = '<i class="far fa-trash-alt" style="font-size: 20px;"></i> Remove';
    r.className ='btn btn-app mt-3';
    r.setAttribute('onClick',"remove_media_attribute('"+id+"')");
    div.appendChild(content);
    media.appendChild(div);
    media.appendChild(r);
    $('#MediaModal').modal('hide');
}
async function delete_variation(event,id){
delete varitions[id];
$(event).parent().parent().remove();
}
// save all attributes
async function save_variation() {
    let id = $('[data-save-variation]').data('id');
    let error = [];
    document.querySelectorAll('.item-attribute.selected select').forEach(at=>{
        at.nextElementSibling.nextElementSibling.className = 'um-field-error d-none';
    if(!$(at).val().length){
        error.push(at.getAttribute('data-title'));
        at.nextElementSibling.nextElementSibling.className = 'um-field-error';
        at.nextElementSibling.nextElementSibling.textContent = at.getAttribute('data-title')+' is required.';
    }
    });
if(!error.length){
    $.ajax({
        url: setting.ajax_url,
        type: "POST",
        data: {action:'save_product_attributes',_token: setting.token,id:id ,data: get_data_attribute() },
        success: function(resulf){
            if(resulf){
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
                })
            }
        }
    });
}

}

// remove media button
function remove_media_attribute(id){
    var media = document.querySelector(id);
    if(media){
    var name = media.getAttribute('data-media');
    var required = media.getAttribute('data-required');
    var html = media.getAttribute('data-html-button');
    if(html){
        media.innerHTML = html;
    }else{
        media.innerHTML = "Upload image";
    }
    media.className = "btn btn-primary button_upload_media";
    media.setAttribute('data-toggle','modal');
    media.setAttribute('data-insert','single_image');
    }
}
// Format Currency
function format_currency(money){
    money =  Number(money);
    // Create our number formatter.
    var formatter = new Intl.NumberFormat('en-US', {
        style: 'currency',
        currency: 'USD',
        minimumFractionDigits: 2,

        // These options are needed to round to whole numbers if that's what you want.
        //minimumFractionDigits: 0, // (this suffices for whole numbers, but will print 2500.10 as $2,500.1)
        //maximumFractionDigits: 0, // (causes 2500.99 to be printed as $2,501)
    });

   return formatter.format(money); /* $2,500.00 */
}
function load_data_money(event){
    let price = $(event).val();
    if(price < 0)price = 0;
    if(price > 999999)price = 999999;
    $(event).val(price);
    price = format_currency(price);
    $(event).prev().text(price);
    $Attribute.update();
}

function upload_import_product(){
    var formData = new FormData();
    $('#import_file_products [type="file"]').each(function(){
        let k  = $(this).attr('name');
        let v  = $(this)[0].files[0];
        formData.append(k, v);
    })
    formData.append('_token', setting.token);
    document.querySelector('#show_info').className = 'form-group d-none';
    document.querySelector('#show_file_import').className = 'form-group';
    document.querySelector('#import_file_products .list_fail_product').innerHTML = '';
    document.querySelector('#loadingpage').className = '';
    $.ajax({
        url : setting.upload_import,
        type : 'POST',
        data : formData,
        processData: false,  // tell jQuery not to process the data
        contentType: false,  // tell jQuery not to set contentType
        success : function(resulf) {
            if(resulf){
                 resulf = JSON.parse(resulf);
                console.log(resulf);
                if(resulf['success']){
                  document.querySelectorAll('#show_info span').forEach(el=>{
                      let c =  el.getAttribute('data-info');
                      el.textContent = resulf[c];
                  });
                  document.querySelector('#show_info').className = 'form-group';
                  document.querySelector('#show_file_import').className = 'form-group d-none';
                  document.querySelector('#loadingpage').className = 'd-none';
                    document.querySelector('#show_info .table_product ').innerHTML = resulf['html'];
                    let img_miss = document.querySelectorAll('.table_product img').length;
                    document.querySelector('#show_info [data-info="images_miss"]').textContent = (resulf['images'] - img_miss);
                }

            }

        }
    })
}
function show_form_upload() {
    document.querySelector('#show_info').className = 'form-group d-none';
    document.querySelector('#show_file_import').className = 'form-group';
    $('.custom-file-input').val('');
}
function insert_process(num){
document.querySelector('.insert-product > .progress').className = 'progress mb-2';
    let process =    document.querySelector('.insert-product .progress-bar');
    process.textContent = num+'%';
    process.setAttribute('style','width: '+num+'%');
    process.setAttribute('aria-valuenow',num);
}
// load data import excel
function load_insert_product(){
    let el = document.querySelector('#show_info tr[dataproductjson]');
    if(el){
        let data =  el.getAttribute('dataproductjson');
        let path =  el.getAttribute('data-path');
        let attr =  el.getAttribute('data-attribute');
        let variations =  el.getAttribute('data-product_variations');
        if(!data)return false;
        data =  JSON.parse(data);
        data['path'] =  path;
        data['attr'] =  JSON.parse(attr);
        if(variations)data['variations'] =  JSON.parse(variations);
        let count = document.querySelectorAll('#show_info tr[dataproductjson]').length;
        let total = document.querySelector('[data-info="number_product"]').textContent;
        insert_process( Math.round((1 - count/total)*100 ) );
        if(data)insert_product(data);
    }else{
        document.querySelector('.insert-product > .progress').className = 'progress mb-2 d-none';
        show_form_upload();
    }
}
function insert_product(product){
    console.log(product);
    $.ajax({
        url : setting.ajax_url,
        type : 'POST',
        data : {data: product,action:'insert_product_excel','_token':setting.token},
        success : function(resulf) {
            if(resulf){
                resulf = JSON.parse(resulf);
                console.log(resulf);
                if(resulf['success']){
                    document.querySelector('#show_info tr[DataProductSKU="'+resulf.sku+'"]').remove();
                }
                load_insert_product();
            }
        },
        error: function (xhr, ajaxOptions, thrownError) {
            console.log(xhr.status);
            console.log(ajaxOptions);
            console.log(thrownError);
            var div = document.querySelector('#import_file_products .list_fail_product');
            var error_p = document.createElement('div');
            error_p.innerHTML = '<span>SKU: '+product.sku+' - Product import fail.<span>';
            div.appendChild(error_p);
            document.querySelector('#show_info tr[DataProductSKU="'+product.sku+'"]').remove();
            load_insert_product();
        }
    })
}

// check box all
function checkbox_all(event,dom){
    let v = $(event).is(':checked');
    document.querySelectorAll(dom+' [type="checkbox"]').forEach(el=>{
        el.checked = v;
    })
}
/// apply action review
function apply_posttype_action(form,dom,action='apply_reviews_action'){
 let a = $(form).val();
 var r = [];
    document.querySelectorAll(dom+' tbody [type="checkbox"]:checked').forEach(el=>{
        r.push(el.value);
    });
    console.log(r);
if(r.length){
    $.ajax({
        url : setting.ajax_url,
        type : 'POST',
        data : {apply: a,reviews:r,action:action,'_token':setting.token},
        success : function(resulf) {
            if(resulf){
                resulf = JSON.parse(resulf);
                console.log(resulf);
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
                        title: 'Update successfully'
                    });
                    location.reload();

                }

            }

        }
    })
}

}

/// apply action clear trash product
function apply_clear_trash(){
    Swal.fire({
        title: 'Are you sure?',
        text: "You won't be able to revert this!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, clear it!'
    }).then((result) => {
        if (result.value) {
            $.ajax({
                url : setting.ajax_url,
                type : 'POST',
                data : {action:'apply_remove_trash_product','_token':setting.token},
                success : function(resulf) {
                    Swal.fire(
                        'Deleted!',
                        'Product has been deleted.',
                        'success'
                    )
                    location.reload();
                }
            })
        }
    })


}

// get data reply
function get_reply_question(id,event){
    let product = $(event).attr('data-product');
    let title = $(event).attr('data-title');
    $.ajax({
        url : setting.ajax_url,
        type : 'POST',
        data : {parent_id: id,action:'get_reply_question','_token':setting.token},
        success : function(resulf) {
            if(resulf){
                resulf = JSON.parse(resulf);
                console.log(resulf);
               document.querySelector('#ReplyModal .modal-title').textContent = title;
               if(resulf['content']){
                   $('#save_reply_question').attr('data-type',resulf['id']);
               }else {
                    $('#save_reply_question').attr('data-type','add_new');
               }
                $('.item_reply_question').val(resulf['content']);
                $('#save_reply_question').attr('data-parent',id);
                $('#save_reply_question').attr('data-product',product);
                $('#ReplyModal').modal('show');
            }

        }
    })
}


/// save reply question
function save_reply_question(event){
 let type = $(event).attr('data-type');
 let content = $('.item_reply_question ').val();
 let parent = $(event).attr('data-parent');
 let product = $(event).attr('data-product');
    $.ajax({
        url : setting.ajax_url,
        type : 'POST',
        data : {question: type,content:content,parent_id:parent, object_id:product, action:'save_reply_question','_token':setting.token},
        success : function(resulf) {
            if(resulf){
                resulf = JSON.parse(resulf);
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
                    title: 'Update successfully'
                });
                $('#ReplyModal').modal('hide');
            }

        }
    })
}

function click_collapse(turn_on,turn_off) {
  $(turn_on).removeClass('d-none');
  $(turn_off).addClass('d-none');
}
function loadingpage(t){
    if(t=='on'){
        $('#loadingpage').removeClass('d-none');
    }else {
        $('#loadingpage').addClass('d-none');
    }
}

function import_product_categories(dom){
    var file = $(dom)[0].files[0];
    if(file){
        loadingpage('on');
        var formData = new FormData();
        formData.append('_token', setting.token);
        formData.append('action', 'import_categories');
        formData.append('import_categories', file);
        var post = {
            url : setting.ajax_url,
            type : 'POST',
            data : formData,
            processData: false,  // tell jQuery not to process the data
            contentType: false,  // tell jQuery not to set contentType
        }
        $.ajax(post).done(function(response){
            loadingpage('off');
            location.href = location;
        })
    }else{
     $(dom).parent().addClass('border-danger border');
    }
}

function import_product_attribute(event){
    let input  = $(event).data('input');
    let type  = $(event).data('type');
    let id  = $(event).data('id');
    var file = $(input)[0].files[0];
    if(file){
        $(input).parent().removeClass('border-danger border error-input');
        loadingpage('on');
        var formData = new FormData();
        formData.append('_token', setting.token);
        formData.append('action', 'import_attributes');
        formData.append('import_attributes', file);
        formData.append('type', type);
        formData.append('parent_id', id);
        var post = {
            url : setting.ajax_url,
            type : 'POST',
            data : formData,
            processData: false,  // tell jQuery not to process the data
            contentType: false,  // tell jQuery not to set contentType
        }
        $.ajax(post).done(function(response){
            loadingpage('off');
           console.log(JSON.parse(response));
           location.reload();
        })
    }else{
        $(input).parent().addClass('border-danger border error-input');
    }

}


function alert_all_check(form,dom,action){
    Swal.fire({
        title: 'Are you sure?',
        text: "You won't be able to revert this!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, do it!'
    }).then((result) => {
        if (result.value) {
            apply_posttype_action(form,dom,action);
        }
    })
}

function open_edit_action(form,dom,action){
 var v = $(form).val();
 console.log(v);
 if(v=='edit'){
    document.querySelector(dom+' .apply-edit-product').classList.remove('d-none');
 }else{
     apply_posttype_action('#apply_product','#form_products_all','apply_product_action');
 }
}

function close_object(dom){
    document.querySelector(dom).classList.add('d-none');
}


// save product list in dashboard
function save_list_product(form,dom,action){
    var f = {};
    $(form).each(function(){
        f[$(this).attr('name')] = $(this).val();
    })
    var r = [];
    document.querySelectorAll(dom+' tbody [type="checkbox"]:checked').forEach(el=>{
        r.push(el.value);
    });
    console.log(f);
    console.log(r);
    if(r.length){
        $.ajax({
            url : setting.ajax_url,
            type : 'POST',
            data : {form: f,products:r,action:action,'_token':setting.token},
            success : function(resulf) {
                if(resulf){
                    resulf = JSON.parse(resulf);
                    console.log(resulf);
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
                            title: 'Update successfully'
                        });
                        location.reload();

                    }

                }

            }
        })
    }
}

async function add_product_varition_default(event){
    var id  = $(event).attr('data-check-default');
    console.log(id);
   document.querySelectorAll('[data-check-default="'+id+'"]').forEach(el=>{
        el.className=  'none';
   });
    $(event).attr('class','active');
}
function search_media(event){
    var ftype = $('#tabs-upload-media .upload-file input').attr('ftype');
    var keyword = $(event).val();
    var loading = '<div class="text-center">\n' +
        '  <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status">\n' +
        '    <span class="visually-hidden"></span>\n' +
        '  </div>\n' +
        '</div>';
    document.querySelector('#grid-medias').innerHTML = loading;
    $.ajax({
        url: setting.ajax_url,
        type: "POST",
        data: {action:'get_medias',_token: setting.token,type:ftype,search:keyword},
        success: function(resulf){
            if(resulf){
                document.querySelector('#grid-medias').innerHTML = resulf;
            }
        }
    });
}

// show manage media to updaload
function add_gallery_media(event){
    var id = $(event).data('media');
    var type = $(event).data('type');
    var ftype = $(event).data('ftype');
    $('#tabs-upload-media .upload-file input').attr('accept',type);
    $('#tabs-upload-media .upload-file input').attr('ftype',ftype);
    $('#tabs-upload-media .upload-file input').attr('data-insert','gallery');
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
}
// show manage media to updaload
function single_upload_media(event){
    var id = $(event).data('media');
    var type = $(event).data('type');
    var ftype = $(event).data('ftype');
    $('#tabs-upload-media .upload-file input').attr('accept',type);
    $('#tabs-upload-media .upload-file input').attr('ftype',ftype);
    $('#tabs-upload-media .upload-file input').attr('data-insert','single_image');
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
}
// setup media gallery product
async function setup_media_gallery(data,id){
    var media = document.querySelector(id);
    media.className = "d-inline-block mb-3";
    media.setAttribute('data-toggle','modals');
    var name = media.getAttribute('data-gallery');
    var color_id = media.getAttribute('data-attribute-id');
    // set content media file
    var div  = document.createElement('div');
    div.className = 'd-inline-block item-gallery item_'+name+data.id;
        var content  = document.createElement('img');
        content.src = setting.url + data.path;
        content.setAttribute('data_thumbnail_product','');
        content.setAttribute('data-attribute-id',color_id);
        content.setAttribute('data-id',data.id);

    var r = document.createElement('button');
    r.innerHTML = '<i class="far fa-trash-alt" style="font-size: 20px;"></i>';
    r.className ='btn-item-gallery';
    r.setAttribute('onClick',"remove_media_gallery('"+color_id+"','"+data.id+"','"+name+"')");
    div.appendChild(content);
    div.appendChild(r);
    media.appendChild(div);
    if($Attribute.thumbnail_attr[color_id]){
        $Attribute.thumbnail_attr[color_id][data.id]=data.id;
    }else{
        let img = {};
        img[data.id] = data.id;
        $Attribute.thumbnail_attr[color_id]= img;
    }
    $('#MediaModal').modal('hide');
}

// remove media gallery button
function remove_media_gallery(color_id,img_id,name){
    var media = document.querySelector('.item_'+name+img_id);
    delete $Attribute.thumbnail_attr[color_id][img_id];
    media.remove();
    console.log($Attribute.thumbnail_attr);
}
