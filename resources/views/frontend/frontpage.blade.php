@extends('layouts.layout_main')
@section('content')
    <?php
    $top_category = top_categories();
$top_products = getProductHomePage();
$partners = [
    ['name'=>'#','img'=>asset('images/partner/partner1.png'),'link'=>'#'],
    ['name'=>'#','img'=>asset('images/partner/urban_finel_logo.png'),'link'=>'#'],
    ['name'=>'#','img'=>asset('images/partner/partner3.png'),'link'=>'#'],
    ['name'=>'#','img'=>asset('images/partner/partner2.png'),'link'=>'#'],
    ['name'=>'#','img'=>asset('images/partner/partner4.png'),'link'=>'#'],
    ['name'=>'#','img'=>asset('images/partner/partner5.png'),'link'=>'#'],
    ['name'=>'#','img'=>asset('images/partner/partner6.png'),'link'=>'#'],
]
    ?>
    <section class="banner_part mt-3">
        <div id="CarouselHome" class="container carousel slide p-0" data-ride="carousel">
            <div class="carousel-inner">
                <div class="carousel-item active" style="background-image: url({{ url('images/slider1.jpg') }})">
                    <dic class="content-slider">
                        <div class="dataContent">
                            <h2>Dual east & west coast</h2>
                            <label>Warehouse & Distribution centers</label>
                            <ul>
                               <li><span>Law enforcement & security</span></li>
                               <li><span>Aviation & parking</span></li>
                               <li><span>Fire rescue & Retardent</span></li>
                               <li><span>Executive & career apparel</span></li>
                               <li><span>Transportation</span></li>
                               <li><span>Work & industrial</span></li>
                            </ul>
                            <a href="{{ url('products') }}" class="btn-button">Shop now</a>
                        </div>
                        <div class="border-content"></div>
                    </dic>
                    <img class="d-block w-100" src="{{ url('images/slider1.jpg') }}" alt="First slide">
                </div>
                <div class="carousel-item" style="background-image: url({{ url('images/Bar4.jpg') }})">
                    <dic class="content-slider">
                        <div class="dataContent">
                            <h2>Dual east & west coast</h2>
                            <label>Warehouse & Distribution centers</label>
                            <ul>
                                <li><span>Law enforcement & security</span></li>
                                <li><span>Aviation & parking</span></li>
                                <li><span>Fire rescue & Retardent</span></li>
                                <li><span>Executive & career apparel</span></li>
                                <li><span>Transportation</span></li>
                                <li><span>Work & industrial</span></li>
                            </ul>
                            <a href="{{ url('products') }}" class="btn-button">Shop now</a>
                        </div>
                        <div class="border-content"></div>
                    </dic>
                    <img class="d-block w-100" src="{{ url('images/Bar4.jpg') }}" alt="Second slide">
                </div>
            </div>
            <a class="carousel-control-prev" href="#CarouselHome" role="button" data-slide="prev">
                <span class="carousel-icon carousel-control-prev-icon" aria-hidden="true"></span>
            </a>
            <a class="carousel-control-next" href="#CarouselHome" role="button" data-slide="next">
                <span class="carousel-icon carousel-control-next-icon" aria-hidden="true"></span>
            </a>
        </div>

    </section>
    <section id="partners" class="mt-5 mb-5">
        <div class="container">
            <div class="d-flex text-center">
                @if($partners)
                    @foreach($partners as $item_partner)
                <div class="item-partner d-inline-block mt-md-0 mt-3"><a><img src="{{ $item_partner['img']  }}"/></a></div>
                    @endforeach
                @endif
            </div>
        </div>
    </section>
    <section class="Top-Category">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-xl-5">
                    <div class="section_tittle text-center">
                        <h2 class="">Top Category</h2>
                    </div>
                </div>
            </div>
            <div class="row">
                @if($top_category)
                    @foreach($top_category as $item)
                <div class="Item-Category col-md-3 col-6" style="background-image: url( {{ ($item['image'])?url($item['image']):asset('images/products/default.jpg') }} )">
                    <div class="ContentItem">
                        <div class="title">{{  $item['name'] }}</div>
                        <div class="numberItem">{{ $item['number_products']  }} items</div>
                        <div class="description">{{ $item['description']  }}</div>
                        <a href="{{ $item['url'] }}" class="btn-button">View more</a>
                    </div>
                </div>
                    @endforeach
                 @endif
            </div>
        </div>
    </section>
    <section class="Top-Product mt-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-xl-5">
                    <div class="section_tittle text-center">
                        <h2 class="">Top New Item</h2>
                    </div>
                </div>
            </div>
        </div>

        <div class="container mt-sm-0 mt-5">
            <div id="ItemProductSlider" class="owl-carousel owl-theme">
                @foreach($top_products as $key=>$item)
                @if($key%2)
                        <div class="item-product">{!! showItemProduct($item) !!}</div>
                        <?= '</div>' ?>
                        @else
                        <?= '<div class="ItemSlider">' ?>
                        <div class="item-product">{!! showItemProduct($item) !!}</div>
                    @endif
                @endforeach
            </div>
        </div>
    </section>

    <section class="Our-Newsletter mt-5 pt-5 pb-5">
        <div class="container-fluid">
            <div class="row justify-content-center">
                <div class="col-xl-5">
                    <div class="section_tittle text-center">
                        <h2 class="">Our newsletter</h2>
                    </div>
                   <div class="section_description text-center">Sign up for unipro news, sales and deals</div>
                    <form class="form-newsletter">
                        <div class="form-group text-center d-flex">
                            <input type="email" placeholder="Email address" data-user="{{ ( Auth::check() )?Auth::user()->name:'Guest'  }}" required>
                            <button type="button" onclick="send_newsletter()">Send</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>

@endsection
