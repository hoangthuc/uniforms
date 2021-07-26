<div class="modal fade" id="editProductOrderModal" tabindex="-1" aria-labelledby="editProductOrderModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editProductOrderModalLabel">
                    Update Product Order
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div id="updateHtml" class="modal-body">
{{--                    <div class="row">--}}
{{--                        <div class="col-6">--}}
{{--                            <table class="table table-bordered ">--}}
{{--                                <tr>--}}
{{--                                    <td class="text-center" >--}}
{{--                                        <b>Product</b>--}}
{{--                                    </td>--}}
{{--                                    <td id="edit_product_name">--}}

{{--                                    </td>--}}
{{--                                </tr>--}}
{{--                                <tr>--}}
{{--                                    <td class="text-center" >--}}
{{--                                        <b>Qty</b>--}}
{{--                                    </td>--}}
{{--                                    <td>--}}
{{--                                        <input type="hidden" id="product_unit_price">--}}
{{--                                        <input type="hidden" id="update_product_unit_price">--}}
{{--                                        <input onchange="changeQuantity()" type="number" min="0" id="edit_product_qty" class="form-control">--}}
{{--                                    </td>--}}
{{--                                </tr>--}}
{{--                                <tr>--}}
{{--                                    <td class="text-center">--}}
{{--                                        <b>--}}
{{--                                            Unit--}}
{{--                                        </b>--}}
{{--                                    </td>--}}
{{--                                    <td id="edit_product_unit_price">--}}

{{--                                    </td>--}}
{{--                                </tr>--}}
{{--                                <tr>--}}
{{--                                    <td class="text-center">--}}
{{--                                        <b>--}}
{{--                                            Subtotal--}}
{{--                                        </b>--}}
{{--                                    </td>--}}
{{--                                    <td>--}}
{{--                                        <input type="number" min="0" class="form-control" id="edit_product_subtotal">--}}
{{--                                    </td>--}}
{{--                                </tr>--}}
{{--                            </table>--}}
{{--                        </div>--}}

{{--                        <div class="col-6">--}}
{{--                            <table class="table table-bordered ">--}}
{{--                                <tbody id="product_order_detail">--}}

{{--                                </tbody>--}}
{{--                            </table>--}}
{{--                        </div>--}}

{{--                    </div>--}}
            </div>
            <div class="modal-footer">
                <span class="out-of-stock text-danger font-weight-bold d-none">Out of stock</span>
                <button type="button" class="btn btn-primary btn-buy-now  " id="btnUpdateOrder"
                        onclick="updateOrder()">
                    Save
                </button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
@section('script_footer')
    <script>
        function select_attribute(event,checkSelect=false) {
            let parent = $(event).data('parent');
            let title = $(event).data('title');
            let attr_price = $(event).data('price');
            let key_var, key_thumbnail;
            key_var = key_thumbnail = $('[name="product_id"]').val();
            document.querySelectorAll('[data-parent="' + parent + '"]').forEach(el => {
                el.className = 'item-attribute-list d-inline-block mb-1';
            });

            title = '(' + title + ')';
            if (attr_price) title = title + ' <b>+' + format_currency(attr_price) + '<b>';
            document.querySelector('[attribute-p' + parent + '] > span').innerHTML = title;
            $(event).addClass('active');
            document.querySelectorAll('.select_variant .item-attribute-list.active').forEach(ac => {
                key_var += '_' + ac.getAttribute('data-id');
            });

            document.querySelectorAll('.select_variant .item-attribute-list.active[attribute-type="color"]').forEach(ac => {
                key_thumbnail += '_' + ac.getAttribute('data-id');
            });

            let get_img = document.querySelector('[data-color="' + key_thumbnail + '"]');
            /// total price
            if(checkSelect) {
                let price = document.querySelector('#form-add-cart [name="price_default"]');
                if (price) price = Number(price.value);
                document.querySelectorAll('.item-attribute-list.active').forEach(el => {
                    price += Number(el.getAttribute('data-price'));
                });
                $('.price_amount .price').text(format_currency(price));
                $('#form-add-cart [name="subtotal"]').val(price*$('#editOrderAmount').val());
                $('#editOrderSubtotal').val(price*$('#editOrderAmount').val());
            }

            /// Name plate
            $name_plate.display_name_plate(event,checkSelect);
            let divcart = document.querySelector('#form-add-cart');
            if(divcart){
                $product_stock.outstock = JSON.parse(divcart.getAttribute('data-outstock'));
                $product_stock.all_price = JSON.parse(divcart.getAttribute('data-all-price'));
            }
            let outstock = $product_stock.search();
            if( Number(outstock.QtyAvailable) > 0){
                document.querySelector('.out-of-stock').classList.add('d-none');
                document.querySelector('.btn-buy-now').classList.remove('d-none');
            }else{
                document.querySelector('.out-of-stock').classList.remove('d-none');
                document.querySelector('.btn-buy-now').classList.add('d-none');
            }
            if(checkSelect) {
                if ($product_stock.price) {
                    $('.price_amount .price').text(format_currency($product_stock.price));
                    $('#form-add-cart [name="subtotal"]').val($product_stock.price*$('#editOrderAmount').val());
                    $('#editOrderSubtotal').val($product_stock.price*$('#editOrderAmount').val());
                }
            }


        }
        var $name_plate = {
            label:'',
            render:function(){
                var sel = document.querySelector('[data-name-plate] [data-name="name_plate"]');
                if(sel){
                    sel.onchange();
                    var selected =  sel.options[sel.selectedIndex];
                }
                display_hemming();
            },
            check_required:function(){
                var error = 0;
                var a = document.querySelector('.alert-notification');
                document.querySelectorAll('[data-name-plate] input.active').forEach(nl=>{
                    if(!nl.value){
                        error++;
                        nl.classList.add('border-danger');
                    }else {
                        nl.classList.remove('border-danger');
                    }
                });

                if(error>0){
                    a.classList.remove('d-none');
                    a.querySelector('[data-number-field]').textContent = error;
                    return true;
                }else{
                    a.classList.add('d-none');
                }
                return false;

            },
            display_name_plate:function(event,checkSelect=false){
                var type= $(event).attr('attribute-type');
                if(type == 'color'){
                    var color_id = $(event).attr('data-id');
                    var plate = document.querySelector('[data-name-plate] [data-name="name_plate"]');
                    if(plate){
                        var plate_v = '';
                        var l_display = 0;
                        var check_selected = checkSelect;
                        var options = document.querySelectorAll('[data-name-plate] [data-name="name_plate"] option');
                        options.forEach(nl=>{
                            var color_n = nl.getAttribute('data-color');
                            l_display++;
                            if(color_n == color_id){
                                nl.removeAttribute('disabled');
                                nl.classList.remove('d-none');
                                plate_v = nl.getAttribute('value');
                                if(check_selected){
                                    plate.selectedIndex  = l_display-1;
                                    check_selected = false;
                                }
                            }else{
                                nl.setAttribute('disabled','');
                                nl.classList.add('d-none');
                            }
                        });
                        if(!check_selected ){
                            $name_plate.render();
                            document.querySelector('[data-name-plate]').classList.remove('d-none');
                        }else{
                            document.querySelector('[data-name-plate]').classList.add('d-none');
                        }

                    }

                }
            },

        }
        var $product_stock = {
            outstock: {},
            all_price: {},
            price: 0,
            choose:{},
            search: function(){
                var search = {};
                if(this.outstock.data){
                    var data = {};
                    document.querySelectorAll('.item-attribute.select_variant .item-attribute-list.active').forEach(attr=>{
                        let title = attr.getAttribute('data-title');
                        title = title.replace("|", "/");
                        data[ attr.getAttribute('data-name-parent') ] = title;
                    });
                    var outstock = this.outstock.data.item;
                    var count = Object.keys(data).length;
                    var sku_item = '';
                    for (var i = 0; i < outstock.length; i++) {
                        var s = 0;
                        for(t in data){
                            if(outstock[i][t] == data[t]){
                                s++;
                            }
                        }
                        if(s==count){
                            search = outstock[i];
                            sku_item =outstock[i].ProductID;
                        }
                    }
                }
                if(this.all_price[sku_item]){
                    this.price = this.all_price[sku_item];
                }else{
                    this.price = 0;
                }
                // console.log(search);
                // console.log(this.price);
                return search;
            }
        };
        document.querySelectorAll('.item-attribute.select_variant').forEach(att=>{
            var check =  att.querySelector('.item-attribute-list.active');
            var default_att =  att.querySelector('.item-attribute-list');
            if(check){
                check.click();
                // console.log('fsaf');
            }else{
                default_att.click();
            }
        })
        $name_plate.render();
        function display_hemming (event){
            var hemming = document.querySelector('[data-hemming] [data-name="hemming"]');
            if(hemming){
                var data_hemming = hemming.getAttribute('data-json');
                data_hemming = JSON.parse(data_hemming);
                let attr = data_hemming[hemming.value];
                document.querySelector('[data-hemming]').previousElementSibling.textContent = ' ('+format_currency(attr['Price'])+')';
                return data_hemming[hemming.value];
            }
            return false;
        }
        function change_amount(number) {
            let amount = Number($('[name="amount"]').val());
            amount = amount + number;
            if (amount < 1) amount = 1;
            $('[name="amount"]').val(amount);
            change_product_order_amount()
        }
        function display_name_plate(event) {
            var sel = document.querySelector('[data-name-plate] select[data-name]');
            var label = sel.options[sel.selectedIndex].text;
            var key = sel.options[sel.selectedIndex].getAttribute('data-key');
            sel.setAttribute('data-title', label);
            $name_plate.label = label;
            var thumbnail = document.querySelector('[data_key_name_plate="' + key + '"]');
            if (thumbnail) thumbnail.click();

            document.querySelectorAll('[data-name-plate] input[data-name]').forEach(el => {
                var name = el.getAttribute('data-name');
                el.classList.add('d-none');
                el.classList.remove('active');
                if (name == sel.value) {
                    el.classList.remove('d-none');
                    el.classList.add('active');
                }
            });
        }

        //convert key array
        async function convert_array(array) {
            var list_array = [];
            var j = 0;
            for (var i in array) {
                list_array[j] = array[i];
                j++;
            }
            return list_array;
        }

        async function updateOrder(orderId) {
            var check = $name_plate.check_required();
            if(check)return false;
            var data = {
                'quantily': $('.price_amount [name="amount"]').val(),
            };
            console.log(data);
            if (Number(data['quantily']) < 1) {
                Swal.fire({
                    icon: 'error',
                    title: 'Wrong amount!',
                });
                return false;
            }
            $('#form-add-cart [name]').each(function () {
                var k = $(this).attr('name');
                var v = $(this).val();
                data[k] = v;
            });
            var attribute = [];
            var Alteration = [];
            let key = data['product_id'];
            let attributes_title = '';
            document.querySelectorAll('.item-attribute-list.active').forEach(att => {
                var k = att.getAttribute('data-name-parent');
                var v = att.getAttribute('data-title');
                var i = att.getAttribute('data-id');
                data[k] = v;
                key += '_' + i;
                attributes_title += ' <b>' + k + '</b>: ' + v + ',';
                let attr = {};
                attr[k] = v;
                attribute.push(attr);
            })
            var name_plate = document.querySelector('[data-name-plate]');
            if (name_plate) {
                attributes_title += '<br/> <b>Line:</b> ' + $name_plate.label + ' ,';
                name_plate.querySelectorAll('input.active').forEach(inp => {
                    if (inp.value){
                        attributes_title += '<br/> <b>' + inp.getAttribute('placeholder') + '</b>: <em>' + inp.value + '</em> ,';
                        let attr = {};
                        attr[inp.getAttribute('placeholder')] = inp.value;
                        attribute.push(attr);
                    }
                });
                var plate = name_plate.querySelector('select');
                if (plate) key += '_' + plate.value;
            }
            var data_hemming = display_hemming();
            if(data_hemming){
                key += '_' + data_hemming['ProductID'];
                attributes_title += '<br/> <b>Hemming</b>: ' + data_hemming['ItemAlteration'] + ',';
                let attr = {};
                attr['Hemming'] = data_hemming['ItemAlteration'];
                Alteration.push(data_hemming);
            }
            data['key'] = key;
            data['attributes_title'] = attributes_title.slice(0, -1);
            let search =  $product_stock.search();
            if( Number(search.QtyAvailable) > 0 ){
                data['dbLineId'] = data['product_id'];
                data['attributes'] = attribute;
                data['alterations'] = Alteration;
                data['tax_ID'] = '10';
                data = Object.assign(data,search);
            }
            data['ListPrice']= $('#form-add-cart [name="subtotal"]').val()/$('.price_amount [name="amount"]').val()
            var settings = {
                "url": setting.ajax_url,
                "method": "POST",
                "headers": {},
                "data": {
                    action: 'admin_update_product_order',
                    _token: setting.token,
                    content: data,
                    order_id:orderId
                }
            };
            // console.log(data);
            $.ajax(settings).done(function (response) {
                location.reload();
            });
            //     // document.querySelector('[data-display-ajax-product]').innerHTML = response;
            //     // $('#editProductOrderModal').modal('hide');


            // let check = in_cart(data['key'], cart.products);
            // if (cart.products.length < 1 || check == 'insert') cart.products.push(data);
            // if (check != 'insert') cart.products[check]['quantily'] = Number(cart.products[check]['quantily']) + Number(data['quantily']);
            // if (Number(data['quantily']) > 0) {
            //     var key_product = (check != 'insert')?check:0;
            //     console.log(key_product);
            //     cart.products[key_product]['subtotal'] = Number(cart.products[key_product]['quantily']) * Number(cart.products[key_product]['ListPrice']);
            //     send_cart(cart.products);
            //     Swal.fire({
            //         position: 'top-end',
            //         icon: 'success',
            //         title: 'The Product has been add to cart!',
            //         showConfirmButton: false,
            //         timer: 1500
            //     });
            // }
        }
        function focusout_amount()
        {
            if($('#editOrderSubtotal').val()=='')
                $('#editOrderSubtotal').val(1)
            change_product_order_amount()
        }
        function change_product_order_amount()
        {
            if($('#editOrderAmount').val()<1)
                $('#editOrderAmount').val(1)
            if($('#editOrderSubtotal').val()<1 && $('#editOrderSubtotal').val()!='')
                    $('#editOrderSubtotal').val(1)
            var quality = $('#editOrderAmount').val();
            var subtotal = $('#editOrderSubtotal').val();
            $('#form-add-cart [name="subtotal"]').val(subtotal);
            $('.price').html(format_currency(subtotal/quality))
        }

        function in_cart(id, cart) {
            for (var i = 0; i < cart.length; i++) {
                if (id == cart[i]['key']) return i;
            }
            return 'insert';
        }

    </script>
@endsection