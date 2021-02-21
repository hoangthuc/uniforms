@extends('layouts.layout_main')
@section('content')
    <?php
    $faq_title = $faq = App\Options::list_faq();
    $user_id = Auth::id();
    $faq_d = App\Options::get_option($user_id,'option_faq');
    if($faq_d)$faq = json_decode($faq_d);
    ?>
    <section class="products-page">
        <div class="container">
            <div class="row" style="background-image:url('{{ asset('images/about.jpg') }}');">
            </div>
        </div>
    </section>
    <section class="about-page">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-xl-5">
                    <div class="section_tittle_about text-center mt-5">
                        <h2 class="font-weight-bold">
                            About us
                        </h2>
                        <div class="description">Welcome to UniPro International LLC</div>
                    </div>
                </div>
            </div>
            <div class="content-about-page">
                    <p><strong>UniPro International Uniforms</strong> was founded in 2009 as a distributor of Career Apparel servicing the Police and Security uniform markets. <strong>UniPro</strong> maintains manufacturing facilities in Mexico and China. Additionally, <strong>UniPro</strong> owns three state-of-the-art distribution centers with a total of 300,000 square feet of warehouse space in Hillside, New Jersey,  Irvington, New Jersey and Reno, Nevada.  Headquartered in Irvington, NJ. we are conveniently located a few miles from Newark/Liberty Airport and 15 miles from New York City, making it accessible for us to service the entire East Coast of the United States. Our Nevada facility allows us to efficiently cover the Midwest and West Coast.</p>
                    <p>From its inception, <strong>UniPro Uniforms</strong> has secured numerous established accounts including  federal contracts, contracts with the TSA, NYPD, NYC Housing Authority and multiple departments within the City of Newark, New Jersey. These are substantial contracts which demand high levels of service and attention to detail.</p>
                    <p><strong>UniPro</strong> applies this commitment to the police and security uniform markets as well and has  established working relationships with several national wholesale distribution companies.</p>
                    <p><strong>Unipro</strong> also services larger, medium and smaller sized end-user companies in the private contract law enforcement/security market. Our goal is to solidify relationships with  companies by optimizing our unique and superior combination of both production and service capabilities – thereby satisfying the total uniform program needs of our customers. To date, we have successfully entered into uniform supply programs with many prominent law enforcement/security companies and numerous government agencies.</p>
                    <p><strong>UniPro’s</strong> extensive inventory is manufactured using a high-tech, just-in-time revolving system and our turn-around time for shipping orders is 24-48 hours from receipt of purchase order. We also maintain an in-house, state-of-the-art embroidery department allowing alterations, patches, embroidery and customization of garments to have the same 24-48 hour turn-around time for shipping. Agreed upon minimum inventory levels for specific programs are strictly adhered to as well.</p>
                    <p><strong>UniPro</strong> offers its corporate customers a state-of-the-art comprehensive online management system, from customized order forms, complete sub-branches and client ordering, to budget and invoice management. Each corporate account can have its own online web/pages customized to ensure that the specific needs of our customers are fulfilled.</p>
                    <p><strong>UniPro</strong> is a New Jersey corporation, privately held and woman-owned and certified by Sari Frei who operates as our Chief Executive Officer.
                        </p>
            </div>
        </div>
    </section>
@endsection
