@if(isset($product))
    <section class="single-product-page pt-3 pb-5" data-id="{{ $product->id }}">
        <div class="container">
            <div class="row mt-3">
                <!---slick slider Vertical-->
                <div class="slider_slick_thumbnail col-md-2 {{ !isset($variantions)?'d-none':'' }}"
                     id="slider-thumbnail">
                    @if( isset($variantions['thumbnail_attribute']) )
                        @foreach($variantions['thumbnail_attribute'] as $color_id => $list_thumbnail)
                            @if($list_thumbnail)
                                @foreach($list_thumbnail as $thumbnail)
                                    <div class="slide" data-fiter-color="{{ $product->id.'_'.$color_id }}" onclick="javascript:selectImageThumbnail(this);"
                                         data-color="{{ $product->id.'_'.$color_id }}"
                                         src="{{ get_url_media($thumbnail) }}">
                                        <a class="mb-2"><img
                                                    data-color="{{ $product->id.'_'.$color_id }}"
                                                    src="{{ get_url_media($thumbnail) }}"/></a>
                                    </div>
                                @endforeach
                            @endif
                        @endforeach
                    @endif

                    @if($name_plates)
                        @foreach($name_plates as $img_color)
                            @if($img_color->img)
                                <div class="slide"  data-fiter-color="{{ $product->id.'_'.$img_color->color }}">
                                    <a class="mb-2"><img onclick="javascript:selectImageThumbnail(this);" data_key_name_plate="{{$img_color->key}}"
                                                         data-color="{{ $product->id.'_'.$img_color->color }}"
                                                         src="{{ get_url_media($img_color->img) }}"/></a>
                                </div>
                            @endif
                        @endforeach
                    @endif

                    @if(!isset($variantions['thumbnail_attribute']) && $galleries)
                        @foreach($galleries as $img_color)
                            <div class="slide"  data-fiter-color="{{ $product->id.'_'.$img_color}}">
                                <a class="mb-2"><img onclick="javascript:selectImageThumbnail(this);" data_key_name_plate="{{$img_color}}"
                                                     data-color="{{ $product->id.'_'.$img_color }}"
                                                     src="{{ get_url_media($img_color) }}"/></a>
                            </div>
                        @endforeach
                    @endif

                </div>
                <!--End Data show price-->
                <div class="{{ isset($variantions)?'col-md-6':'col-md-8' }}" id="display-images">
                    <div class="show-imgage"
                         style="background-image: url({{ (isset($product->featured_image))?get_url_media($product->featured_image):asset('images/image-coming-soon.jpg') }})">
                        @if(isset( $product->featured_image ))
                            <img src="{{ get_url_media($product->featured_image) }}"/>
                            <div class="action_view d-none">
                                <a class="zoom" data-image="{{ get_url_media($product->featured_image) }}"
                                   onclick="showZoomImage(this)"><i class="fas fa-search-plus"></i></a>
                                <a href="javascript:View360();" class="view360 ml-2 d-none"><i
                                            class="fab fa-unity"></i></a>
                            </div>
                        @else
                            <img src="{{ asset('images/image-coming-soon.jpg') }}"/>
                            <div class="action_view d-none">
                                <a class="zoom" data-image="{{ asset('images/image-coming-soon.jpg') }}"
                                   onclick="showZoomImage(this)"><i class="fas fa-search-plus"></i></a>
                                <a href="javascript:View360();" class="view360 ml-2 d-none"><i
                                            class="fab fa-unity"></i></a>
                            </div>
                        @endif
                    </div>
                </div>
                <div class="col-md-4" id="select-product">
                    <div class="order-product">
                        <div class="review_sku row">
                            <div class="col-md-8 pr-0">
                              <span class="rating">
                                  @for($i=1;$i<=5;$i++)
                                      <i class="{{ ($reviews['rating'] > ($i - 0.51) &&  $reviews['rating'] < $i )?'fas fa-star-half-alt':'' }} {{ ($reviews['rating'] >= $i)?'fas fa-star':'far fa-star'  }}"></i>
                                  @endfor
                              </span>
                                <a class="number_review font-weight-bold" href="javascript:scroll_to('#customer_reviews');">(View {{ $reviews['total'] }}
                                    reviews)</a>
                            </div>
                            <div class="col-md-12 mt-2"><span class="sku font-weight-bold">Item: {{ $sku }}</span>
                            </div>
                        </div>
                        <div class="title_product font-weight-bold mt-2">{{ $product->name }}</div>
                        <div class="price_amount mt-3 mb-3 row">
                            <div class="price col-md-6 font-weight-bold">{{ format_currency($price,2,'$') }}</div>
                            <div class="amount col-md-6 d-flex">
                                <span class="btn btn-minus" onclick="change_amount(-1)"><i class="fas fa-minus"></i></span>
                                <input type="number" class="form-control text-center" name="amount" min="1"
                                       value="1" readonly/>
                                <span class="btn btn-minus" onclick="change_amount(1)"><i
                                            class="fas fa-plus"></i></span>
                            </div>
                        </div>
                        <div class="alert-notification d-none">
                            <div class="alert alert-danger cat-itm-err-msg" style="display: block;">
                                <strong>Missing <span data-number-field>2</span> Fields!</strong>
                                Please make sure you have completed all required fields! (<em class="small">highlighted in red)</em>
                            </div>
                        </div>
                        <div class="select_variant attributes pt-1 border-top">
                            @if( isset($attributes) )
                                @foreach($attributes as $item_attribute)
                                    <div class="item-attribute select_variant" Attribute-P{{ $item_attribute['id'] }}>
                                        <label>{{ $item_attribute['name'] }}</label> <span class="ml-1">({{ $item_attribute['display'] }})</span>
                                        <div class="list">
                                            @if(isset($item_attribute['list']))
                                                @foreach ($item_attribute['list'] as $key=> $attribute)
                                                    <div class="item-attribute-list d-inline-block {{ ( isset($default_attribute[$attribute->id]) )  ?'active':'' }} mb-1"
                                                         Attribute-Type="{{ $item_attribute['type'] }}"
                                                         data-name-parent="{{ $item_attribute['name'] }}"
                                                         data-parent="{{ $item_attribute['id'] }}"
                                                         data-id="{{ $attribute->id }}"
                                                         data-title="{{ $attribute->name }}"
                                                         data-price="{{ isset($price_attribute[$attribute->id])?$price_attribute[$attribute->id]:0 }}"
                                                         onclick="select_attribute(this)" Data-Attribute-Product>
                                                        @if(isset($thumbnail_color[$attribute->id]))
                                                            <span class="badge badge-secondary color attr_thumbnail" style="{{ ($thumbnail_color[$attribute->id])?'background-image: url('.\App\Media::get_url_media($thumbnail_color[$attribute->id]).')':'background-color:'.$attribute->data_type }}; font-size: 24px; text-transform: uppercase">{{ $attribute->name }}</span>
                                                        @else
                                                            {!! DisplayAttributeType($attribute->type,$attribute->data_type) !!}
                                                        @endif

                                                    </div>
                                                @endforeach
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            @endif
                        </div>
                        <div class="select_name_plate">
                            @if($name_plates)
                                @include('layouts.view_ajax.view_product_name_plate')
                            @endif
                        </div>
                        <div id="form-add-cart" class="add-cart mt-3 d-flex">
                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                            <input type="hidden" name="thumbnail"
                                   value="{{ isset($featured_image)?$featured_image:asset('images/products/default.jpg') }}">
                            <input type="hidden" name="link" value="{{ url( 'product/'.$product->slug  ) }}">
                            <input type="hidden" name="title" value="{{ $product->name }}">
                            <input type="hidden" name="subtotal" value="{{ $price }}">
                            <input type="hidden" name="price_default" value="{{ $price }}">
                            <button type="button" class="btn btn-unipro form-control d-none" onclick="add_cart()">Add to
                                cart
                            </button>
                            <button type="button" class="btn btn-unipro form-control "
                                    data-href="{{ url('cart') }}" onclick="buy_now(this)">Add to cart
                            </button>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </section>
@endif

