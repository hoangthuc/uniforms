@extends('layouts.layout_main')
@section('content')
    @if(isset($product))
        <section class="single-product-page pt-3 pb-5" data-id="{{ $product->id }}">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <div class="breadcrumb-page">
                            <a href="{{ url('products') }}">Products</a>
                            @if( $category )
                                /<a href="{{ url('product_categories/'.$category->slug) }}">{{ $category->name }}</a>
                            @endif
                            /<span>{{ $sku }}</span>
                        </div>
                    </div>
                </div>
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
                                <div class="action_view">
                                    <a class="zoom" data-image="{{ get_url_media($product->featured_image) }}"
                                       onclick="showZoomImage(this)"><i class="fas fa-search-plus"></i></a>
                                    <a href="javascript:View360();" class="view360 ml-2 d-none"><i
                                                class="fab fa-unity"></i></a>
                                </div>
                            @else
                                <img src="{{ asset('images/image-coming-soon.jpg') }}"/>
                                <div class="action_view">
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
                                    	@if( $item_attribute['name'] != 'Weight')
                                        <div class="item-attribute{{ ($item_attribute['type']=='hidden')?'-hidden':'' }} select_variant" Attribute-P{{ $item_attribute['id'] }}>
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
                                         @endif
                                         <!--end end weight--->
                                    @endforeach
                                @endif
                            </div>

                                @if($name_plates)
                                <div class="select_name_plate">
                                    @include('layouts.view_ajax.view_product_name_plate')
                                </div>
                                @endif

                                @if($attr_hemming)
                                <div class="select_hemming item-attribute-hidden">
                                    <label>Hemming</label> <span class="ml-1"></span>
                                    <div class="form-group" data-hemming>
                                        <select class="form-control mb-2" data-name="hemming" onchange="display_hemming(this)" data-json="{{ \GuzzleHttp\json_encode($attr_hemming) }}" >
                                            @foreach($attr_hemming as $hemming)
                                            <option value="{{ $hemming->hemming }}">{{ $hemming->hemming.' ('.format_currency($hemming->hemming_price,2,'$').')' }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                @endif  
                                @if( isset($outstock_hemming->data->Alterations) && count($outstock_hemming->data->Alterations) > 1 )
                                <div class="select_hemming item-attribute-hidden">
                                    <label>Hemming</label> <span class="ml-1"></span>
                                    <div class="form-group" data-hemming>
                                        <select class="form-control mb-2" data-name="hemming" onchange="display_hemming(this)" data-json="{{ json_encode($outstock_hemming->data->Alterations) }}" >
                                            @foreach($outstock_hemming->data->Alterations as $key => $hemming)
                                            @if($key==1)
                                            <optgroup label="{{ $hemming->AlterationGroup }}">{{ $hemming->AlterationGroup }}</optgroup>
                                            @endif
                                            @if($key>1)
                                            <option value="{{ $key }}">{{ $hemming->ItemAlteration.' ('.format_currency($hemming->Price,2,'$').')' }}</option>
                                            @endif
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                @endif

                            <div id="form-add-cart" class="add-cart mt-3 d-flex" data-outstock="{{ $outstock }}" data-all-price="{{ $all_price }}">
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
                                <button type="button" class="btn btn-unipro btn-buy-now form-control "
                                        data-href="{{ url('cart') }}" onclick="buy_now(this)">Add to cart
                                </button>
                                <div class="out-of-stock text-danger font-weight-bold d-none">Out of stock</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!--Product detail-->
                <div class="product_description mt-5">
                    <div class="title font-weight-bold">Product Detail</div>
                    <div class="content bg-white">
                        {!! $product->description !!}
                    </div>
                </div>
                <!--Product related-->

                <div class="product_description mt-5">
                    @if( isset($relation_product) && count($relation_product) )
                        <div class="title font-weight-bold">Usually goes with/ Related Items</div>
                        <div class="Product_relation bg-white">
                            <div class="product_relation_slider owl-carousel owl-theme">
                                @foreach($relation_product as $product_relation)
                                    <div class="item-product text-center">
                                        <div class="thumbnail-product text-center">
                                            <a href="{{ $product_relation['url'] }}"
                                               style="background-image: url({{ $product_relation['image']   }})"><img
                                                        src="{{  $product_relation['image'] }}">
                                            </a>
                                        </div>
                                        <div class="price-product">{{ format_currency($product_relation['price'], 2,'$') }}</div>
                                        <div class="title-product"><a
                                                    href="{{ $product_relation['url'] }}">{{ $product_relation['title'] }}</a></div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>

                <!--Product review--->
                <div id="customer_reviews" class="product_reviews mt-5">
                    <div class="title font-weight-bold">Product Review</div>
                    <div class="content bg-white">
                        <!--Analytics-->
                        <div class="row p-3">
                            <div class="col-md-3 border-right text-center">
                                <span class="number_rating">{{ $reviews['rating'] }}</span>
                                <span class="rating_reviews rating">
                                  @for($i=1;$i<=5;$i++)
                                        <i class="{{ ($reviews['rating'] > ($i - 0.51) &&  $reviews['rating'] < $i )?'fas fa-star-half-alt':'' }} {{ ($reviews['rating'] >= $i)?'fas fa-star':'far fa-star'  }}"></i>
                                    @endfor
                                </span>
                                <span class="count_reviews">{{ $reviews['total'] }} reviews</span>
                            </div>
                            <div class="col-md-4">
                                @foreach($reviews['analytic'] as $analytic)
                                    <div class="rating-5 d-flex">
                                        <span class="rating_reviews_analytic mr-2 mb-2 rating">
                                  @for($i=1;$i<=5;$i++)
                                                <i class="{{ ($analytic['star'] > ($i - 0.51) &&  $analytic['star'] < $i )?'fas fa-star-half-alt':'' }} {{ ($analytic['star'] >= $i)?'fas fa-star':'far fa-star'  }}"></i>
                                            @endfor
                                          </span>
                                        <span class="rating_process d-none d-sm-block">
                                         <span class="progress-bar" style="width: {{ $analytic['rating'] }}"></span>
                                    </span>
                                        <span class="count_review_type font-weight-bold">{{ $analytic['subtotal'] }}</span>
                                    </div>
                                @endforeach
                            </div>
                            <div class="col-md-5 text-center">
                                @if(get_check_review($product->id))
                                <button type="button" id="write_review" class="btn btn-unipro" data-toggle="modal" data-target="#form-review-product">Write Review</button>
                                @endif
                            </div>
                        </div>

                        <!--Analytics filter-->
                        <div class="filter-reviews pb-5 p-3">
                            <span class="label-review font-weight-bold">Filter: </span>
                            <span class="rating" DataFilterReview="5" data-type="rating" data-filter="false" onclick="filter_review(this)"><i class="fas fa-check" ></i> 5 <i class=" fas fa-star"></i><i class=" far fa-star"></i></span>
                            <span class="rating" DataFilterReview="4" data-type="rating" data-filter="false" onclick="filter_review(this)"><i class="fas fa-check" ></i> 4 <i class=" fas fa-star"></i><i class=" far fa-star"></i></span>
                            <span class="rating" DataFilterReview="3" data-type="rating" data-filter="false" onclick="filter_review(this)"><i class="fas fa-check" ></i> 3 <i class=" fas fa-star"></i><i class=" far fa-star"></i></span>
                            <span class="rating" DataFilterReview="2" data-type="rating" data-filter="false" onclick="filter_review(this)"><i class="fas fa-check" ></i> 2 <i class=" fas fa-star"></i><i class=" far fa-star"></i></span>
                            <span class="rating" DataFilterReview="1" data-type="rating" data-filter="false" onclick="filter_review(this)"><i class="fas fa-check" ></i> 1 <i class=" fas fa-star"></i><i class=" far fa-star"></i></span>
                            <div class="pagition_reviews d-inline-block float-right">
                                @if($list_reviews['html_pagition'])
                                    {!! $list_reviews['html_pagition'] !!}
                                @endif
                            </div>
                        </div>

                        <!--Display review-->
                        <div class="list-reviews">
                            @if($list_reviews['html_reviews'])
                                {!! $list_reviews['html_reviews'] !!}
                            @endif
                        </div>
                        <div class="filter-reviews pb-5 p-3">
                           <div class="pagition_reviews d-inline-block float-right">
                                @if($list_reviews['html_pagition'])
                                    {!! $list_reviews['html_pagition'] !!}
                                @endif
                            </div>
                        </div>
                    </div>
                </div>


                <!---Question && Answer-->
                <div id="customer_question_answer" class="product_qa product_reviews mt-5">
                    <div class="title font-weight-bold">Question & Answer</div>
                    <div class="content bg-white">
                    <?php
                      $questions =  get_question_publish($product->id);
                        ?>
                    @if( count($questions) )
                        @foreach($questions as $question)
                     <div class="item_question mb-4">
                        <div class="title_question font-weight-bold mb-2">{{ $question->content }}</div>
                        <div class="content_question">
                            <?php  $item = get_question_reply($question->id); ?>
                                @if($item)
                                {{ $item['content'] }}
                                <div class="time_question">{{ get_current_datetime( $item['created_at'] ) }}</div>
                                 @endif
                        </div>
                     </div>
                        @endforeach
                    @endif
                        @if(check_question($product->id))
                    <form class="form_question_answer">
                        <div class="form-group d-flex mb-2">
                            <input type="text" class="form-control mr-2" id="data_question" placeholder="Ask questions related to the product ..."/>
                            <button type="button" class="btn btn-unipro" onclick="send_question('#data_question')">Send question</button>
                        </div>
                    </form>
                        @endif
                    </div>
                </div>

            </div>
        </section>

        <div id="ModalZoom" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="ModalZoomImage"
             aria-hidden="true">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <a class="closeZoom" data-dismiss="modal" aria-label="Close">
                        <i class="fas fa-search-minus"></i>
                    </a>
                    <div class="ImageZoom text-center"></div>
                </div>
            </div>
        </div>
        <div class="View360Display d-none" DataView360Display>
            <a class="closeView360" href="javascript:closeView360();">
                <i class="far fa-times-circle"></i>
            </a>
            <center style="cursor: move">
                <div id="View360Display" data-loading="{{ asset('images/products/loader.gif') }}"
                     style="display: inline-block; height: 600px; width: 600px;"
                     data-image="{{ asset('images/products/T-shirt-21600.jpg') }}"></div>
            </center>
        </div>
        @if(get_check_review($product->id))
        <div id="form-review-product" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="ModalReview"
             aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" >{{ $product->name }}</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                          <form>
                              <div class="text-center" dataTitleReview data-review="0" data-title="Please Review" data-description="Please share your comments and reviews about the product.">
                                  <span class="DisplayTitle font-weight-bold">Please Review</span>
                                  <div class="rating">
                                      @foreach(get_data_review_rating() as $item)
                                          <i class="far fa-star" onmouseover="display_review_hover(this)" onmouseleave="display_review_leave(this)" onclick="display_review_choose(this)" data-review="{{ $item['rating'] }}" data-title="{{ $item['title'] }}" data-description="{{ $item['description'] }}"></i>
                                      @endforeach
                                  </div>
                              </div>
                              <div class="form-group mt-2">
                                  <textarea dataDescriptionReview placeholder="Please share your comments and reviews about the product."></textarea>
                              </div>
                          </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-unipro form-control" onclick="send_review_product(this)" data-product="{{ $product->id }}">Send Review</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif
    @endif
@endsection
@section('footer_layout')


@endsection

