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
    <section class="about-page mb-5">
        <div class="container">
            <div class="row">
                <div class="col-md-6 border-right">
                    <div class="section_tittle_about text-center mt-5">
                        <h2 class="font-weight-bold">
                            CALL US TOLL-FREE
                        </h2>
                    </div>

                    <div class="content-about-page">
                        <p class="font-weight-bold"><span>Phone:</span> <a href="tel:(973) 577-1300">(973) 577-1300</a></p>
                        <p class="font-weight-bold"><span>Toll Free:</span> <a href="tel:(888) 691-6200">(888) 691-6200</a></p>
                        <p class="font-weight-bold"><span>Fax:</span> <a href="tel:(973) 622-1446">(973) 622-1446</a></p>
                        <p class="office-hours"><span  class="font-weight-bold">Office Hours:</span>
                            <br>Monday - Thursday, 8:30 AM to 5:00 PM EST;
                            <br>Friday, 8:30 AM to 4:00 PM.
                        </p>
                        <div class="leave-massage font-italic font-weight-light">We suggest that you leave a message during non-business hours, so that a representative can get back to you as soon as possible.</div>
                        <div class="address mt-3">
                            <span class="font-weight-bold">UniPro International</span> 390 Nye Avenue Irvington, NJ 07111
                            <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3023.828266644876!2d-74.22507688459474!3d40.72179687933078!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x89c3accdd1ff39ef%3A0x1a6066a6bb1c206!2s390+Nye+Ave%2C+Irvington%2C+NJ+07111!5e0!3m2!1sen!2sus!4v1473358803976" width="100%" height="200" frameborder="0" style="border:0" allowfullscreen=""></iframe>

                        </div>
                        <p>
                            Send an email to <a class="font-weight-bold" href="mailto:support@uniprointl.com" style="text-decoration:none;"> Customer Service</a> or use the contact form.
                            <br>All replies will be handled in a prompt manner, usually within 24 hours.
                        </p>
                        <table class="email-Department">
                            <tbody>
                            <tr>
                                <th >Department</th>
                                <th>Email</th>
                            </tr>
                            <tr>
                                <td>Billing inquiry</td>
                                <td><a href="mailto:ar@uniprointl.com">ar@uniprointl.com</a></td>
                            </tr>
                            <tr>
                                <td>Returns/Exchanges</td><td><a href="mailto:returns@uniprointl.com?subject=Returns" >returns@uniprointl.com</a></td>
                            </tr>
                            <tr>
                                <td>Sales Inquiry</td>
                                <td><a href="mailto:sales@uniprointl.com">sales@uniprointl.com</a></td>
                            </tr>
                            <tr>
                                <td>Customer Support</td><td><a href="mailto:support@uniprointl.com">support@uniprointl.com</a></td>
                            </tr>
                            </tbody>
                        </table>

                    </div>
                </div>
                <div class="col-md-6">
                    <div class="section_tittle_about text-center mt-5">
                        <h2 class="font-weight-bold">
                            SEND US AN EMAIL
                        </h2>
                    </div>
                    <div class="alert alert-success up-tanks-alert d-none">
                        Thank you for your interest!<br>Your email was successfully delivered <br> One of our representatives will contact you shortly.
                    </div>
                    <form id="contact-form" method="post" action="/up/web/html/general/email.php">
                        <div class="form-group row">
                            <div class="col-sm-4 col-md-3"><label>Full Name:</label></div>
                            <div class="col-sm-8 col-md-9">
                                <input type="text" name="name" class="form-control" placeholder="Full name" data-title="Full name">
                                <div class="error"></div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-4 col-md-3"><label>Phone #:</label></div>
                            <div class="col-sm-8 col-md-9">
                                <input type="text" name="phone" class="form-control" placeholder="(123) 456-7890 or 123-456-7890" data-title="Phone number">
                                <div class="error"></div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-4 col-md-3"><label>Email Address:</label></div>
                            <div class="col-sm-8 col-md-9">
                                <input name="email" class="form-control" placeholder="Email" data-title="Email">
                                <div class="error"></div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-4 col-md-3"><label>Subject:</label></div>
                            <div class="col-sm-8 col-md-9">
                                <select id="serviceSubject" class="form-control">
                                    <option value="Forgot Password">Forgot Password</option>
                                    <option value="Trouble Logging-in">Trouble Logging-in</option>
                                    <option value="Size change to recent order">Size change to recent order</option>
                                    <option value="Order not received">Order not received</option>
                                    <option value="Return/Exchange">Return/Exchange</option>
                                    <option value="Billing inquiry">Billing inquiry</option>
                                    <option value="Sales Inquiry">Sales Inquiry</option>
                                    <option value="Customer Support">Customer Support</option>
                                    <option value="Other">Other</option>
                                </select>
                                <div class="error"></div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-4 col-md-3"><label>Comments:</label></div>
                            <div class="col-sm-8 col-md-9">
                                <textarea id="message" class="form-control" placeholder="Your Message" rows="6" cols="25"></textarea>
                            </div>

                        </div>
                        <button type="button" onclick="send_contact_us()" value="submit" class="btn btn-unipro">Submit</button>
                        <div class="clearfix"></div>
                    </form>
                </div>
            </div>

        </div>
    </section>
@endsection
