 <!--footer area start-->
    <footer class="footer_widgets" style="background: #ffffff;">
        <!-- Newsletter Section -->
        <div class="newsletter-section row justify-content-center" style="background: #004c91; color: white; padding: 40px 0;">
            <div class="col-lg-10">
                <div class="row align-items-center">
                    <div class="col-lg-8 mb-3 mb-lg-0">
                        <h2 style="margin: 0; font-size: 24px; font-weight: 600;">Stay in the loop</h2>
                        <p style="margin: 8px 0 0 0; font-size: 16px; opacity: 0.9;">Get email updates on new items, sales, and more</p>
                    </div>
                    <div class="col-lg-4">
                        <form class="d-flex" style="gap: 0;">
                            <input type="email" class="form-control" placeholder="Enter your email" style="border-radius: 4px 0 0 4px; border: 2px solid #fff; padding: 12px 16px; font-size: 16px;">
                            <button type="submit" class="btn" style="background: #ffc220; color: #004c91; border: 2px solid #ffc220; border-radius: 0 4px 4px 0; padding: 12px 24px; font-weight: 600; font-size: 16px;">Sign up</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Footer -->
        <div class="footer_top row justify-content-center" style="background: #ffffff; padding: 40px 0 30px 0;">
            <div class="col-lg-10">
                <div class="row">
                    <!-- Company Info -->
                    <div class="col-lg-2 col-md-6 mb-1">
                        <div class="footer-section">
                            <div class="footer_logo mb-4">
                                <a href="{{url('/')}}"><img src="{{ \App\Helpers\websiteSettings::siteLogo() }}" alt="" style="max-height:50px"></a>
                            </div>
                            <div class="footer_contact" style="color: #333; line-height: 1.6; font-size: 14px; margin-bottom: 20px;">
                                {{\App\Helpers\WebsiteSettings::settings('footer_content')}}
                            </div>
                            <div class="contact-info">
                                <div style="display: flex; align-items: center; margin-bottom: 10px; color: #333; font-size: 14px;">
                                    <i class="fa fa-phone" style="color: #004c91; margin-right: 10px; width: 16px;"></i>
                                    <span>{{\App\Helpers\WebsiteSettings::settings('company_phone')}}</span>
                                </div>
                            </div>
                            <div class="footer_social_link mt-4 mb-0">
                                <h6 style="color: #333; font-weight: 600; margin-bottom: 15px; font-size: 14px;">Follow us</h6>
                                <ul class="list-inline" style="margin:0;">
                                    <li class="list-inline-item" style="margin-right: 15px;">
                                        <a href="{{\App\Helpers\WebsiteSettings::settings('fb_url')}}" title="Facebook" style="color: #1877F2; font-size: 20px; text-decoration: none;">
                                            <i class="fa fa-facebook"></i>
                                        </a>
                                    </li>
                                    <li class="list-inline-item" style="margin-right: 15px;">
                                        <a href="{{\App\Helpers\WebsiteSettings::settings('twitter_url')}}" title="Twitter" style="color: #1DA1F2; font-size: 20px; text-decoration: none;">
                                            <i class="fa fa-twitter"></i>
                                        </a>
                                    </li>
                                    <li class="list-inline-item">
                                        <a href="{{\App\Helpers\WebsiteSettings::settings('instagram_url')}}" title="Instagram" style="color: #E4405F; font-size: 20px; text-decoration: none;">
                                            <i class="fa fa-instagram"></i>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <?php $footerOne = Menu::getByName('footer-1'); $footerTwo = Menu::getByName('footer-2'); ?>
                    <!-- Information Links -->
                    <div class="col-lg-2 col-md-6 mb-1">
                        <div class="footer-section">
                            <h6 style="color: #333; font-size: 16px; font-weight: 600; margin-bottom: 20px; text-transform: uppercase; letter-spacing: 0.5px;">Information</h6>
                            <div class="footer_menu">
                                <ul style="list-style: none; padding: 0; margin: 0;">
                                    @foreach($footerOne as $menu)
                                        <li style="margin-bottom: 12px;">
                                            <a href="{{$menu['link']}}" style="color: #666; text-decoration: none; font-size: 14px; transition: color 0.2s ease; display: block;" 
                                               onmouseover="this.style.color='#004c91'" onmouseout="this.style.color='#666'">
                                                {{$menu['label']}}
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- My Account Links -->
                    <div class="col-lg-2 col-md-6 mb-1">
                        <div class="footer-section">
                            <h6 style="color: #333; font-size: 16px; font-weight: 600; margin-bottom: 20px; text-transform: uppercase; letter-spacing: 0.5px;">My Account</h6>
                            <div class="footer_menu">
                                <ul style="list-style: none; padding: 0; margin: 0;">
                                    @foreach($footerTwo as $menu)
                                        <li style="margin-bottom: 12px;">
                                            <a href="{{$menu['link']}}" style="color: #666; text-decoration: none; font-size: 14px; transition: color 0.2s ease; display: block;" 
                                               onmouseover="this.style.color='#004c91'" onmouseout="this.style.color='#666'">
                                                {{$menu['label']}}
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- Customer Service -->
                    <div class="col-lg-2 col-md-6 mb-1">
                        <div class="footer-section">
                            <h6 style="color: #333; font-size: 16px; font-weight: 600; margin-bottom: 20px; text-transform: uppercase; letter-spacing: 0.5px;">Customer Service</h6>
                            <div class="footer_menu">
                                <ul style="list-style: none; padding: 0; margin: 0;">
                                    <li style="margin-bottom: 12px;"><a href="#" style="color: #666; text-decoration: none; font-size: 14px;">Help Center</a></li>
                                    <li style="margin-bottom: 12px;"><a href="#" style="color: #666; text-decoration: none; font-size: 14px;">Return Policy</a></li>
                                    <li style="margin-bottom: 12px;"><a href="#" style="color: #666; text-decoration: none; font-size: 14px;">Shipping Info</a></li>
                                    <li style="margin-bottom: 12px;"><a href="#" style="color: #666; text-decoration: none; font-size: 14px;">Track Order</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>

            

                    <!-- Payment & Trust Section -->
                    <div class="col-lg-4 col-md-6 mb-1">
                        <div class="footer-section">
                            <h6 style="color: #333; font-size: 16px; font-weight: 600; margin-bottom: 20px; text-transform: uppercase; letter-spacing: 0.5px;">We Accept</h6>
                            <div class="payment-methods" style="margin-bottom: 30px;">
                                <div class="d-flex align-items-center" style="gap: 12px; flex-wrap: wrap;">
                                    <div style="background: #f8f9fa; padding: 10px 14px; border-radius: 4px; border: 1px solid #e9ecef; display: flex; align-items: center;">
                                        <i class="fa fa-cc-visa" style="font-size: 28px; color: #1A1F71;"></i>
                                    </div>
                                    <div style="background: #f8f9fa; padding: 10px 14px; border-radius: 4px; border: 1px solid #e9ecef; display: flex; align-items: center;">
                                        <i class="fa fa-cc-mastercard" style="font-size: 28px; color: #EB001B;"></i>
                                    </div>
                                    <div style="background: #f8f9fa; padding: 10px 14px; border-radius: 4px; border: 1px solid #e9ecef; display: flex; align-items: center;">
                                        <i class="fa fa-cc-amex" style="font-size: 28px; color: #006FCF;"></i>
                                    </div>
                                    <div style="background: #f8f9fa; padding: 10px 14px; border-radius: 4px; border: 1px solid #e9ecef; display: flex; align-items: center;">
                                        <i class="fa fa-cc-paypal" style="font-size: 28px; color: #0070BA;"></i>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Trust Badges -->
                            <div class="trust-badges">
                                <h6 style="color: #333; font-size: 16px; font-weight: 600; margin-bottom: 15px; text-transform: uppercase; letter-spacing: 0.5px;">Why Shop With Us</h6>
                                <div class="d-flex flex-wrap" style="gap: 10px;">
                                    <div style="background: #e8f5e8; color: #2e7d32; padding: 8px 16px; border-radius: 20px; font-size: 12px; font-weight: 600; display: flex; align-items: center;">
                                        <i class="fa fa-shield" style="margin-right: 6px;"></i>
                                        Secure Checkout
                                    </div>
                                    <div style="background: #e3f2fd; color: #1976d2; padding: 8px 16px; border-radius: 20px; font-size: 12px; font-weight: 600; display: flex; align-items: center;">
                                        <i class="fa fa-undo" style="margin-right: 6px;"></i>
                                        Free Returns
                                    </div>
                                    <div style="background: #fff3e0; color: #f57c00; padding: 8px 16px; border-radius: 20px; font-size: 12px; font-weight: 600; display: flex; align-items: center;">
                                        <i class="fa fa-headphones" style="margin-right: 6px;"></i>
                                        24/7 Support
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bottom -->
        <div class="footer_bottom row justify-content-center" style="background: #f8f9fa; color: #6c757d; border-top: 1px solid #e9ecef; padding: 10px;">
            <div class="col-lg-10">
                <div class="row align-items-center py-0">
                    <div class="col-md-6">
                        <div class="copyright_area">
                            <p style="margin:0; font-size: 16px;">&copy; {{date('Y')}} {{ config('app.name','LaravelCommerce') }}. All rights reserved.</p>
                        </div>
                    </div>
                    <div class="col-md-6 text-md-right mt-2 mt-md-0">
                        <div style="color: #6c757d; font-size: 15px;">
                            <a href="#" style="color: #6c757d; text-decoration: none; margin-right: 18px;" onmouseover="this.style.color='#0063d1'" onmouseout="this.style.color='#6c757d'">Privacy Policy</a>
                            <a href="#" style="color: #6c757d; text-decoration: none; margin-right: 18px;" onmouseover="this.style.color='#0063d1'" onmouseout="this.style.color='#6c757d'">Terms</a>
                            <a href="#" style="color: #6c757d; text-decoration: none;" onmouseover="this.style.color='#0063d1'" onmouseout="this.style.color='#6c757d'">Cookies</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </footer>
    <!--footer area end-->
   
    



