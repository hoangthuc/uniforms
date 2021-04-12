$(function () {
    if( document.querySelector('.owl-carousel') ) {
        $(".owl-carousel").owlCarousel({
            nav: true,
            dots: false,
            loop: false,
            rewind: true,
            // autoWidth: true,
            stagePadding: 0,
            margin: 0,
            checkVisibility: true,
            navElement: 'div',
            responsive : {
                0 : {
                    items: 1,
                    slideBy: 1
                },
                768 : {
                    items: 2,
                    slideBy: 2
                },
                1024 : {
                    items: 3,
                    slideBy: 3
                },
                1280 : {
                    items: 4,
                    slideBy: 4
                },
                1440 : {
                    items: 4,
                    slideBy: 1
                }
            }
        });
    }
  $('.mini-cart').text( cart.products ? cart.products.length: 0 );
  cart_total();
  $('.btn_commentForm').click(function(){
    var comment = {};
    var error = '';
    $('#frmAddStoryComment [name]').each(function(index){
      $(this).removeClass('error');
      var name = $(this).attr('name');
      var val = $(this).val();
      comment[name] = val;
      if(!val){
        $(this).addClass('error');
        error = 'Field is required';
      }
    });
    if(!error){
      console.log(comment);
      $('.btn_commentForm > span').removeClass('d-none');
      jQuery.ajax({
        url: setting.ajax_url,
        type: 'post',
        data:{data:comment,_token:setting.token,action:comment['action']},
        success: function(resulf){
          console.log(resulf);
          if(resulf){
            resulf = JSON.parse(resulf);
            if(resulf['success']){
              jQuery('.btn_commentForm > span').addClass('d-none');

              Swal.fire(
                  'Success!',
                  '',
                  'success'
              );
              location.href = location;


            }

          }

        }
      });

    }
  })

    $('#same_as_billing').change(function(){
        var same_bill = document.getElementById("same_as_billing").checked;
        $('[data-show="same_as_billing"]').show();
        if(same_bill)$('[data-show="same_as_billing"]').hide();
    })



});

async function save_account(id){
  var profile = [];
  $(id+' [name]').each(function(){
    var k = $(this).attr('name');
    var t = $(this).data('type');
    var v = $(this).val();
    if(v)profile.push({name:k,value:v,type:t});
  });
  $.ajax({
    url:setting.ajax_url,
    type:'post',
    data:{data: profile ,action:'update_account',_token:setting.token},
    success: function(resulf){
      if(resulf){
        resulf = JSON.parse(resulf);
        console.log(resulf);
        if( resulf['success'] ){
          Swal.fire(
              'Success!',
              '',
              'success'
          );
        }
      }
    }
  })

}

//convert key array
async function convert_array(array) {
  var list_array = [];
  var j = 0;
  for (var i in array){
    list_array[j] = array[i];
    j++;
  }
  return list_array;
}

async function add_cart(){
  var data = {
      'quantily': $('.price_amount [name="amount"]').val()
  };
    if( Number( data['quantily'] ) < 1 ){
        Swal.fire({
            icon: 'error',
            title: 'Wrong amount!',
        });
        return false;
    }
$('#form-add-cart [name]').each(function(){
  var k =$(this).attr('name');
  var v= $(this).val();
  data[k] = v;
});
    console.log(data);
let key = data['product_id'];
let attributes = '';
document.querySelectorAll('.item-attribute-list.active').forEach(att=>{
    var k =att.getAttribute('data-name-parent');
    var v =att.getAttribute('data-title');
    var i =att.getAttribute('data-id');
    data[k] = v;
    key += '_'+i;
    attributes+= ' <b>'+k+'</b>: '+v+',';
})
var name_plate =  document.querySelector('[data-name-plate]');
if(name_plate){
    attributes+= '<br/> <b>Line:</b> '+$name_plate.label+' ,';
    name_plate.querySelectorAll('input.active').forEach(inp=>{
        if(inp.value)attributes+= '<br/> <b>'+inp.getAttribute('placeholder')+'</b>: <em>'+inp.value+'</em> ,';
    });
    var plate = name_plate.querySelector('select');
    if(plate)key+='_'+plate.value;
}
data['key']=key;
data['attributes']= attributes.slice(0,-1);
let check = in_cart(data['key'],cart.products);
if(cart.products.length < 1 || check =='insert' )cart.products.push(data);
if(check != 'insert')cart.products[check]['quantily'] = Number( cart.products[check]['quantily'] ) + Number( data['quantily'] );
if( Number( data['quantily'] ) > 0 ){
    send_cart(cart.products);
  Swal.fire({
      position: 'top-end',
      icon: 'success',
      title: 'The Product has been add to cart!',
      showConfirmButton: false,
      timer: 1500
  });
}
}
async function buy_now(event){
    await add_cart();
    let amount = $('[name="amount"]').val();
    let link = $(event).data('href');
    if( Number( amount ) > 0 ){
        setTimeout(function(){
            location.href = link;
     },1000)
    }


}

 function in_cart(id,cart){
  for(var i= 0; i < cart.length ; i++){
    if(id == cart[i]['key'])return i;
  }
  return 'insert';
}

function send_cart(data){
  $.ajax({
    url:setting.ajax_url,
    type:'post',
    data:{data: data ,action:'add_to_cart',_token:setting.token},
    success: function(resulf){
      if(resulf){
        resulf = JSON.parse(resulf);
        console.log(resulf);
        if( resulf['success'] ){
          $('.mini-cart').text( cart.products ? cart.products.length: 0 );
        }
      }
    }
  })
}
// save send_cart butno1 reload
function update_cart(data){
    $.ajax({
        url:setting.ajax_url,
        type:'post',
        data:{data: data ,action:'add_to_cart',_token:setting.token},
        success: function(resulf){
            if(resulf){
                resulf = JSON.parse(resulf);
                console.log(resulf);
                if( resulf['success'] ){
                    $('.mini-cart').text( cart.products ? cart.products.length: 0 );
                    location.href = setting.cart;
                }
            }
        }
    })
}

function remove_product(button){
  var key = $(button).data('product-id');
  let check = in_cart(key,cart.products);
  let products = [];
  cart.products.forEach(item=>{
    if(item.key != key)products.push(item);
  })
  cart.products = products;
  $('#shopping-cart-items .shopping-cart-item-id-'+key).remove();
  send_cart(cart.products);
  cart_total();
}

function change_quantily(button){
  var key = $(button).data('product-id');
  let check = in_cart(key,cart.products);
  let quantily = $(button).val();
  quantily = parseInt( quantily );
  if(!quantily || Number(quantily) < 1){
      quantily=1;
      $(button).val(quantily);
  }
    $(button).val(quantily);
  document.querySelector('.cart-single-item[data-item-id="'+key+'"] .total-price').textContent = format_currency(cart.products[check]['subtotal'] * quantily,'$');
  console.log(cart.products[check]['subtotal'] * quantily);
  cart.products[check]['quantily'] = Number( quantily );
  send_cart(cart.products);
  cart_total();
}

function cart_total(){
  let Subtotal = 0;
  let Tax = $('#cart_tax_rate').val();
  let Shipping = $('#cart_shipping').val();
  let Total = 0;
  if(cart.products){
    cart.products.forEach(item=>{
      Subtotal += Number( item.subtotal ) * item.quantily ;
    });
    $('#shopping-cart-subtotal').text( format_currency(Subtotal,'$') );
    $('#shopping-cart-tax').text( format_currency(Tax*Subtotal,'$') );
    $('#shopping-cart-shipping').text( format_currency(Shipping,'$') );
    Total = Number(Shipping) + Subtotal + Tax*Subtotal;
    $('#shopping-cart-total').text( format_currency(Total,'$') );
  }
    if(!cart.products.length && document.querySelector('#frmShoppingCart .cart_extra')){
        document.querySelector('#frmShoppingCart .cart_extra').className = 'd-none';
    }

}
function enter_key(e){
    if(e.keyCode == '13'){
        sign_in();
    }
}
function sign_in() {
  var data = [];
  var login = document.querySelector('.alert-login');
  var alert = document.createElement('div');
  var close_alert = document.createElement('button');
  login.innerHTML = '';
  alert.className = 'alert alert-warning alert-dismissible fade show';
  close_alert.className = 'close';
  close_alert.setAttribute('data-dismiss','alert');
  close_alert.setAttribute('aria-label','Close');
  close_alert.innerHTML = ' <span aria-hidden="true">&times;</span>';
  document.querySelectorAll('.basic-login input').forEach( item =>{
      let v = $(item).val();
      let n = $(item).attr('name');
      data.push({name: n, value: v});
  } );
  document.querySelector('#loadingpage').className = 'loading';
    $.ajax({
        url:setting.login_ajax_url,
        type:'post',
        data:{data: data ,_token:setting.token},
        success: function(resulf){
            resulf = JSON.parse(resulf);
            setTimeout(function(){document.querySelector('#loadingpage').className = 'd-none';},500);
            if(resulf['error']){
                alert.innerHTML = resulf['error'];
                alert.appendChild(close_alert);
                login.appendChild(alert);
            }
            if(resulf['redirect']){
                location.href = resulf['redirect'];
            }
        }
    })
}
function logout(){
    document.querySelector('#loadingpage').className = 'loading';
    $.ajax({
        url:setting.logout,
        type:'post',
        data:{_token:setting.token},
        success: function(resulf){
           location.reload();
        }
    });
}

document.querySelectorAll('[data-card-widget="collapse"]').forEach(event=>{
    $(event).click(function(){
        let show = $(event).data('show');
        $(event).parent().next().collapse('toggle');
        $(event).children().attr('class','fas fa-minus');
        $(event).data('show',!show);
        if(show){
            $(event).children().attr('class','fas fa-plus');

        }
    })
})

async function hide_filter_product(event) {
    let display = $(event).data('display');
    if(!display){
        document.querySelector('[data-filter-product]').className = 'col-md-3';
        document.querySelector('[data-products-resulf]').className = 'col-md-9';
        document.querySelectorAll('[data-item-product]').forEach(item=>{
            item.className = 'item-product col-md-4';
        });
        $('.display-control span').text('Hide Filter');
    }else{
        document.querySelector('[data-filter-product]').className = 'd-none';
        document.querySelector('[data-products-resulf]').className = 'col-md-12';
        document.querySelectorAll('[data-item-product]').forEach(item=>{
            item.className = 'item-product col-md-3';
        });
        $('.display-control span').text('Show Filter');
    }
    $(event).data('display',!display);
}
// get data product by filter
async function start_filter_product(page=1){
    var data = [];
    let categories = [];
    let attributes = {};
    if(query_filter.search)data.push( {name:'search',value:query_filter.search});
    document.querySelector('#loadingpage').className = 'loading';
    document.querySelectorAll('.filter_product_categories').forEach(el=>{
        let id = el.value;
        if(el.checked){
            categories.push(el.value);
        }
    })
    if(!categories.length)categories = cat_default;
    console.log(categories);
    document.querySelectorAll('.filter_product_attribute').forEach(el=>{
        if(el.checked){
            let name = el.getAttribute('name');
            let child = el.getAttribute('data-child');
            if(!attributes[name])attributes[name] = {data:[]}
            attributes[name].data.push(el.value);
        }
    });
    let sort = document.querySelector('.sort-filter').value;
    let slug = document.querySelector('[Data-Filter-Product] [name="slug"]').value;
    data.push( {name:'page',value:page});
    let col  = document.querySelector('[data-item-product]')?document.querySelector('[data-item-product]').getAttribute('class'):'item-product col-md-4';
    $.ajax({
        url:setting.ajax_url,
        type:'post',
        data:{_token:setting.token, action:'filter_product_page', data:data,colume:col,sort:sort,product:categories, attribute:attributes, slug:slug},
        success: function(resulf){
            if(resulf){
                setTimeout(function(){document.querySelector('#loadingpage').className = 'd-none';},100);
                resulf= JSON.parse(resulf);
                console.log(resulf);
                document.querySelector('[data-products-resulf] .row').innerHTML = resulf.data;
                document.querySelectorAll('.pagition-product').forEach(el=>{
                    el.innerHTML = resulf.pagition;
                })
                document.querySelector('[Data-Resulfs-Count]').textContent = String(resulf.total);
                toggle_tooltip();
                scroll_to('header');
            }
        }
    })
}

async function selectImageThumbnail(event){
    let link = $(event).attr('src');
    document.querySelector('#display-images .show-imgage img').setAttribute('src',link);
    document.querySelector('#display-images .action_view .zoom').setAttribute('data-image',link);
    document.querySelector('#display-images .show-imgage').setAttribute('style','background-image: url('+link+')');
}
async function showZoomImage(event){
    let link = $(event).attr('data-image');
    let img = document.createElement('img');
    img.src = link;
    let div = document.querySelector('#ModalZoom .ImageZoom');
    div.innerHTML = '';
    div.appendChild(img);
    $('#ModalZoom').modal();
}
async function View360() {
    Swal.fire({
        title: 'This is the 360 product viewer experience of the product.',
        html: "Click <strong>Yes</strong> if you want to see",
        icon: 'info',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, do it!'
    }).then((result) => {
        if (result.isConfirmed) {
            document.querySelector('[DataView360Display]').className = 'View360Display';
            $('#View360Display').j360();
        }
    })

}
async  function closeView360() {
    document.querySelector('[DataView360Display]').className = 'View360Display d-none';
}

async function change_amount(number){
    let amount = Number( $('[name="amount"]').val());
    amount = amount + number;
    if(amount < 1)amount = 1;
    $('[name="amount"]').val(amount);
}
async function select_attribute(event){
  let parent = $(event).data('parent');
  let title = $(event).data('title');
  let attr_price = $(event).data('price');
  let key_var,key_thumbnail;
    key_var = key_thumbnail = $('[name="product_id"]').val();
  document.querySelectorAll('[data-parent="'+parent+'"]').forEach(el=>{
      el.className = 'item-attribute-list d-inline-block mb-1';
  });

    title = '('+title+')';
    if(attr_price)title = title+' <b>+'+format_currency(attr_price)+'<b>';
    document.querySelector('[attribute-p'+parent+'] > span').innerHTML = title;
    $(event).addClass('active');
 document.querySelectorAll('.select_variant .item-attribute-list.active').forEach(ac=>{
     key_var +='_'+ac.getAttribute('data-id');
 });

    document.querySelectorAll('.select_variant .item-attribute-list.active[attribute-type="color"]').forEach(ac=>{
        key_thumbnail +='_'+ac.getAttribute('data-id');
    });

 $('.slider_slick_thumbnail').slick('slickUnfilter');
 $('.slider_slick_thumbnail').slick('slickFilter', '[data-fiter-color="'+key_thumbnail+'"]');

 let get_img = document.querySelector('[data-color="'+key_thumbnail+'"]');
 /// total price
 let price = document.querySelector('#form-add-cart [name="price_default"]');
 if(price)price = Number(price.value);
 document.querySelectorAll('.item-attribute-list.active').forEach(el=>{
     price += Number( el.getAttribute('data-price') );
    });
$('.price_amount .price').text( format_currency(price) );
$('#form-add-cart [name="subtotal"]').val(price);
 if(get_img){
     /// set image by color product
     get_img.click();
     let img = get_img.getAttribute('src');
     $('#form-add-cart [name="thumbnail"]').val(img);
 }else{
     document.querySelector('#display-images .show-imgage img').setAttribute('src',image_default);
     document.querySelector('#display-images .action_view .zoom').setAttribute('data-image',image_default);
     document.querySelector('#display-images .show-imgage').setAttribute('style','background-image: url('+image_default+')');
 }

 /// Name plate
    $name_plate.display_name_plate(event);

}


function payment_order(){
    var data = [];
    var error = '';
    var same_bill = document.getElementById("same_as_billing").checked;
    var notes = document.getElementById("notes").value;
    data.push({name:'notes',value:notes});
    $('#billing-form [name]').each(function(){
        var k = $(this).attr('name');
        var v = $(this).val();
        v = v.trim();
        if(!v){
            $(this).addClass('error');
            error += k+' is invalid';
        }
        if(v){
            $(this).removeClass('error');
            data.push({name:k,value:v});
        }
        if(k == 'email' && v && !ValidateEmail(v)){
            error += k+' is invalid';
            $(this).addClass('error');
        }
    })
    if(!same_bill){
        $('#shipping-form [name]').each(function(){
            var k = $(this).attr('name');
            var v = $(this).val();
            if(!v){
                $(this).addClass('error');
                error += k+' is invalid';
            }
            if(v){
                $(this).removeClass('error');
                data.push({name:k,value:v});
            }
            if(k == 'shipping_email' && v && !ValidateEmail(v)){
                error += k+' is invalid';
                $(this).addClass('error');
            }
        })

    }
    data.push({name:'same_bill',value:same_bill});

    if(error){
        $('#payment-gate').addClass('d-none');
        return false;
    }
    $('#payment-gate').removeClass('d-none');
    console.log(data);
    return data;

}
function send_order(data){
    document.querySelector('#loadingpage').className = 'loading';
    $.ajax({
        url:setting.ajax_url,
        type:'post',
        data:{data: data ,action:'add_to_order',_token:setting.token},
        success: function(resulf){
            if(resulf){
                resulf = JSON.parse(resulf);
                console.log(resulf);
                if( resulf['success'] ){
                    location.href =  resulf['success'];

                }
            }
            document.querySelector('#loadingpage').className = 'd-none';
        }
    })
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

// check valicate phone us
function ValidatePhone(inputText)
{
    var phoneformat = /^(\()?\d{3}(\))?(-|\s)?\d{3}(-|\s)\d{4}$/;
    if(inputText.match(phoneformat))
    {
        return true;
    }
    else
    {
        return false;
    }
}
var request;
function search_key_product(event) {
    let cat = $('.show_select [name="type"]').val();
    let key =  $(event).val();
    let resulf_search = document.querySelector('[DataSearchProduct]');
    resulf_search.className = 'resulf-search';
    resulf_search.innerHTML = '<div class="lds-ellipsis"><div></div><div></div><div></div><div></div></div>';
    if(request && !request.status){
        request.abort();
    }
    request =  $.ajax({
            url: setting.ajax_url,
            type: 'post',
            data: {cat:cat,search: key, action: 'search_product_ajax', _token: setting.token},
            success: function (resulf) {
                if (resulf) {
                    resulf_search.innerHTML =  resulf;
                }
            }
        });


}

function form_register(event){
    var register = [];
    var error = [];

$('#form_register_account [name]').each(function(){
    let k = $(this).attr('name');
    let v = $(this).val();
    let t = $(this).data('title');
    let r = $(this).data('required');
    $(this).next().addClass('d-none').text('');
    register.push( {name:k,value:v,title:t,required:r});
    v= v.trim();
    if( r && !v){
        $(this).next().removeClass('d-none').text(t+' is required.');
        error.push(k);
    }
    if(v && k=='email' && !ValidateEmail(v)){
        $(this).next().removeClass('d-none').text(t+' is invalid.');
        error.push(k);
    }
    if(k=='password_confirmation' && v && (v != $('[name="password"]').val() ) ){
        $(this).next().removeClass('d-none').text(t+' doesn\'t match Password');
        error.push(k);
    }
});

if(error.length < 1){
    document.querySelector('#loadingpage').className = 'loading';
    $.ajax({
        url: $(event).data('url'),
        type: 'post',
        data: {data: register, _token: setting.token},
        success: function (resulf) {
            resulf = JSON.parse(resulf);
            setTimeout(function(){document.querySelector('#loadingpage').className = 'd-none';},500);
            if (resulf['error']) {
               for(var f in resulf['error']){
                   $('[name="'+f+'"]').next().removeClass('d-none').text(resulf['error'][f]);
               }
            }
            if(resulf['success']){
                location.href = resulf['redirect'];
            }
        }
    });
}

}

/// add to cart ajax
function add_to_cart(event){
    let title = $(event).data('title');
    let data = $(event).attr('data-json');
    data = JSON.parse(data);
    data['title'] = title;
    $(event).children("i").attr('class','fas fa-spinner fa-spin fa-1x fa-fw');
    setTimeout(function () {
        $(event).children("i").attr('class','fas fa-check fa-1x');
        let check = in_cart(data['key'],cart.products);
        if(cart.products.length < 1 || check =='insert' )cart.products.push(data);
        if(check != 'insert')cart.products[check]['quantily'] = Number( cart.products[check]['quantily'] ) + Number( data['quantily'] );
        if( Number( data['quantily'] ) > 0 ){
            update_cart(cart.products);
        }
    },2000)

}

function send_contact_us(){
    var contact_form = [];
    var error = [];
    $('#contact-form [name]').each(function(){
        let k= $(this).attr('name');
        let v= $(this).val();
        let l = $(this).data('title');
        contact_form.push({name: k, value: v, label: l});
        $(this).next().html('');
        if(!v){
            error.push(l+' is required.');
            $(this).next().html(l+' is required.');
        }

        if(k == 'email' && v && !ValidateEmail(v)){
            error.push(l+' is invalid.');
            $(this).next().html(l+' is invalid.');
        }

        if(k == 'phone' && v && !ValidatePhone(v)){
            error.push(l+' is invalid.');
            $(this).next().html(l+' is invalid.');
        }

    });
    let serviceSubject = 'Subject: '+$('#serviceSubject').val() + '\r '+ $('#message').val();
    contact_form.push({name: 'data',value:serviceSubject, Label:'' });
    contact_form.push({name: 'type',value:'contact-us', Label:'Type' });
    console.log(contact_form);
    if(!error.length){
        document.querySelector('#loadingpage').className = 'd-block';
        $('.up-tanks-alert').addClass('d-none');
        $.ajax({
            url: setting.ajax_url,
            type: 'post',
            data: {data: contact_form, _token: setting.token, action: 'add_data_form_ctf'},
            success: function (resulf) {
                resulf = JSON.parse(resulf);
                setTimeout(function(){document.querySelector('#loadingpage').className = 'd-none';},500);
                if(resulf['success']){
                    $('.up-tanks-alert').removeClass('d-none');
                    $('#contact-form').addClass('d-none');
                }
            }
        });
    }
}
/// send subscribers email
function send_newsletter(){
    var form_newsletter = []
   var email  = $('.form-newsletter [type="email"]').val();
   var name  = $('.form-newsletter [type="email"]').data('user');
    $('.form-newsletter [type="email"]').removeClass('error');
   if( !ValidateEmail(email) ){
       $('.form-newsletter [type="email"]').addClass('error');
   }else{
       form_newsletter.push({name: 'name',value:name, Label:'' });
       form_newsletter.push({name: 'email',value:email, Label:'' });
       form_newsletter.push({name: 'type',value:'subscribers', Label:'Type' });
       document.querySelector('#loadingpage').className = 'd-block';
       $.ajax({
           url: setting.ajax_url,
           type: 'post',
           data: {data: form_newsletter, _token: setting.token, action: 'add_data_form_ctf'},
           success: function (resulf) {
               resulf = JSON.parse(resulf);
               setTimeout(function(){document.querySelector('#loadingpage').className = 'd-none';},500);
               if(resulf['success']){
                   $('.form-newsletter [type="email"]').val('');
                   Swal.fire({
                       position: 'center',
                       icon: 'success',
                       title: 'Your email has been saved',
                       showConfirmButton: false,
                       timer: 1500
                   })
               }
           }
       });
   }
}
// Display review for user
function display_review_hover(event){
    let r = $(event).data('review');
    document.querySelectorAll('[dataTitleReview] [data-review]').forEach(el=>{
        el.className = 'far fa-star';
       if(r >= el.getAttribute('data-review')){
           el.className = 'fas fa-star';
       }
       if(r == el.getAttribute('data-review')){
           $('[datatitlereview] .DisplayTitle').text(el.getAttribute('data-title'));
           $('[datadescriptionreview]').attr('placeholder',el.getAttribute('data-description'));
       }

    })
}
function display_review_leave(event){
    let r= $('[datatitlereview]').attr('data-review');
    let t= $('[datatitlereview]').attr('data-title');
    let d= $('[datatitlereview]').attr('data-description');
    $('[datatitlereview] .DisplayTitle').text(t);
    $('[datadescriptionreview]').attr('placeholder',d);
    document.querySelectorAll('[dataTitleReview] [data-review]').forEach(el=>{
        el.className = 'far fa-star';
        if(r >= el.getAttribute('data-review')){
            el.className = 'fas fa-star';
        }
    })
}
function display_review_choose(event){
    let r = $(event).data('review');
    document.querySelectorAll('[dataTitleReview] [data-review]').forEach(el=>{
        el.className = 'far fa-star';
        if(r >= el.getAttribute('data-review')){
            el.className = 'fas fa-star';
        }
        if(r == el.getAttribute('data-review')){
            $('[datatitlereview]').attr('data-review',el.getAttribute('data-review'));
            $('[datatitlereview]').attr('data-title',el.getAttribute('data-title'));
        }

    })
}

// send review
function send_review_product(event){
    let r= $('[datatitlereview]').attr('data-review');
    let t= $('[datatitlereview]').attr('data-title');
    let d= $('[datadescriptionreview]').val();
    let p= $(event).attr('data-product');
    if(r && Number(r) >0){
        document.querySelector('#loadingpage').className = 'd-block';
        $.ajax({
            url: setting.ajax_url,
            type: 'post',
            data: {product:p,review:r,title:t,description:d, _token: setting.token, action: 'add_data_review'},
            success: function (resulf) {
                resulf = JSON.parse(resulf);
                setTimeout(function(){document.querySelector('#loadingpage').className = 'd-none';},500);
                if(resulf['success']){
                    Swal.fire({
                        position: 'center',
                        icon: 'success',
                        title: 'Your review has been saved',
                        showConfirmButton: false,
                        timer: 1500
                    });
                    $('#form-review-product').modal('hide');
                    $('#write_review').addClass('d-none');
                    location.reload();
                }
            }
        });
    }else{
        Swal.fire({
            icon: 'error',
            title: 'Please choose review'
        })
    }
}
// payment with cash
function payment_with_cash(event){
    let data = [];
    let total = $(event).data('total');
    console.log(total);
    data[1] = {name:'payment',value: payment_order()};
    data[2] = {name:'products',value: cart.products};
    data[3] = {name:'total',value: total  };
    data[4] = {name:'payment_type',value: 0 };
    send_order(data);
}

// Filter Review
function filter_review(event){
let filter = $(event).data('filter');
filter = !filter;
$(event).data('filter',filter);
if(filter){
    $(event).addClass('active');
}else {
    $(event).removeClass('active');
}
    get_data_reviews();
}
function get_data_reviews(page=1){
    let rating = [];
    let product_id = $('[name="product_id"]').val();
    document.querySelectorAll('.filter-reviews  .rating.active').forEach(el=>{
        rating.push( el.getAttribute('datafilterreview') );
    })
    document.querySelector('#loadingpage').className = 'd-block';
    $.ajax({
        url: setting.ajax_url,
        type: 'post',
        data: {type:'product',rating:rating,object_id:product_id,status:2,page:page, _token: setting.token, action: 'get_data_reviews_product'},
        success: function (resulf) {
            resulf = JSON.parse(resulf);
            setTimeout(function(){document.querySelector('#loadingpage').className = 'd-none';},500);
            $('.list-reviews').html(resulf['html_reviews']);
            $('.filter-reviews .pagition_reviews').html(resulf['html_pagition']);
        }
    });
}


/// scrollto
function scroll_to(dom=''){
    let scrollDiv = (dom)?document.querySelector(dom).offsetTop:0;
    window.scrollTo({ top: scrollDiv, behavior: 'smooth'});
}

// send data question
function send_question(dom){
    let q = $(dom).val();
    let p = $('[name="product_id"]').val();
    if(q){
        document.querySelector('#loadingpage').className = 'd-block';
        $.ajax({
            url: setting.ajax_url,
            type: 'post',
            data: {type:'product',content:q,object_id:p,status:1, _token: setting.token, action: 'send_data_question'},
            success: function (resulf) {
                resulf = JSON.parse(resulf);
                console.log(resulf);
                setTimeout(function(){document.querySelector('#loadingpage').className = 'd-none';},500);
                if(resulf['success']){
                    Swal.fire({
                        position: 'center',
                        icon: 'success',
                        title: 'Your question has been saved',
                        showConfirmButton: false,
                        timer: 2000
                    });
                }
                if(resulf['error']){
                    Swal.fire({
                        position: 'center',
                        icon: 'error',
                        title: resulf['error'],
                        showConfirmButton: false,
                        timer: 2000
                    })
                }
                $(dom).val('');
            }
        });
    }

}

function change_cat(event,dom){
   // let v = $(event).val();
    let v =  event.options[event.selectedIndex].textContent;
    $(dom).text(v);
    if( $(event).attr('name') == 'type' )document.querySelector('.search-product [name="search"]').onkeyup();
}
//http://www.geoplugin.net/json.gp
$.getJSON('https://api.ipregistry.co/?key=yqm8trxv0rv0qn&pretty=true', function(data) {
    console.log(data);
    var address = data.location.city+', '+data.location.country.name+' '+data.location.country.code;
    $('.location_mark span').text(address);
});
function display_department(event){
    $('.menu-main .nav-item-desktop').removeClass('active');
    $(event).addClass('active');
    let depart = $(event).data('cat');
    $('.display_all_list_categories').removeClass('d-none');
    $('.display_all_list_categories [data-department]').addClass('d-none');
    $('.display_all_list_categories [data-department="'+depart+'"]').removeClass('d-none');
}
document.querySelector('.display_all_list_categories').addEventListener('mouseleave',el=>{
    $('.display_all_list_categories').addClass('d-none');
})

document.querySelector('.header-main > .container').addEventListener('mouseover',el=>{
    $('.display_all_list_categories').addClass('d-none');
});
document.querySelector('body > section').addEventListener('mouseover',el=>{
    $('.display_all_list_categories').addClass('d-none');
});

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

function display_filter_product(){
    var div = document.querySelector('[data-filter-product] .show_content_filter');
    $.ajax({
        url: setting.ajax_url,
        type: 'post',
        data: {query:query_filter, _token: setting.token, action: 'insert_filter_product_ajax'},
        success: function (resulf) {
            resulf = JSON.parse(resulf);
            if(resulf.html){
                div.innerHTML = resulf.html;
            }

        }
    });
}
function collapse_attribute_filter(event){
    var c = event.querySelector('i').getAttribute('class');
    if(c=='fas fa-minus'){
        event.parentElement.nextElementSibling.setAttribute('class','item-body collapse');
        event.querySelector('i').setAttribute('class','fas fa-plus');
    }else{
        event.parentElement.nextElementSibling.setAttribute('class','item-body show');
        event.querySelector('i').setAttribute('class','fas fa-minus');
    }
}

// click change image color product
function change_color_img_product(event){
    var img = $(event).data('img');
    var product_id = $(event).data('product_id');
    var color_id = $(event).data('color_id');
    var color = $(event).data('color');
    var data_key = $(event).attr('data-key');
    var button = document.querySelector('.price-product [data-product="'+product_id+'"]');
    var json = JSON.parse(button.getAttribute('data-json'));
    json.thumbnail = img;
    json.attributes = 'Color: '+color+', '+json.data_default;
    json.key = data_key;
    json['Color'] = color;
    button.setAttribute('data-json',JSON.stringify(json));
    $(event).parent().prev().attr('style','background-image: url('+img+')');
}
document.querySelectorAll('.coming_soon').forEach(el=>{
    el.addEventListener('click',function(event){
        event.preventDefault;
        let timerInterval
        Swal.fire({
            title: 'Comming Soon!',
            html: 'I will close in <b></b> milliseconds.',
            timer: 2000,
            timerProgressBar: true,
            onBeforeOpen: () => {
                Swal.showLoading()
                timerInterval = setInterval(() => {
                    const content = Swal.getContent()
                    if (content) {
                        const b = content.querySelector('b')
                        if (b) {
                            b.textContent = Swal.getTimerLeft()
                        }
                    }
                }, 100)
            },
            onClose: () => {
                clearInterval(timerInterval)
            }
        }).then((result) => {
            /* Read more about handling dismissals below */
            if (result.dismiss === Swal.DismissReason.timer) {
                console.log('I was closed by the timer')
            }
        })
    })
})

function toggle_tooltip(event){
    $('[data-bs-toggle="tooltip"]').tooltip();
}
toggle_tooltip();

function display_name_plate(event){
    var sel = document.querySelector('[data-name-plate] select[data-name]');
     var label =    sel.options[sel.selectedIndex].text;
     var key =    sel.options[sel.selectedIndex].getAttribute('data-key');
    sel.setAttribute('data-title',label);
    $name_plate.label = label;
    console.log(key);
    var thumbnail = document.querySelector('[data_key_name_plate="'+key+'"]');
    if(thumbnail)thumbnail.click();

  document.querySelectorAll('[data-name-plate] input[data-name]').forEach(el=>{
      var name = el.getAttribute('data-name');
      el.classList.add('d-none');
      el.classList.remove('active');
      if(name == sel.value){
          el.classList.remove('d-none');
          el.classList.add('active');
      }
  });
}
window.clickOutSide = (element, clickOutside, clickInside) => {
    document.addEventListener('click', (event) => {
        if (!element.contains(event.target)) {
            if (typeof clickInside === 'function') {
                clickOutside();
            }
        } else {
            if (typeof clickInside === 'function') {
                clickInside();
            }
        }
    });
};
window.clickOutSide(document.querySelector('.search-product'), () => document.querySelector('[DataSearchProduct]').className ='d-none', () => document.querySelector('[DataSearchProduct]').className ='resulf-search' );