<section class="slider_section slider_section_five mb-70 mt-30">
    <div class="container">
        <div class="row">
            <!-- Slider -->
            <div class="col-lg-9 col-md-8">
                <div class="slider_area owl-carousel">
                    @foreach(\App\Helpers\WebsiteSettings::homeSlider() as $data)
                    <div class="single_slider d-flex align-items-center" data-bgimg="{{App\Models\Media::fileLocation($data->featured_image)}}">
                        <div class="slider_content slider_c_four">
                            <?php echo $data->description; ?>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div><!-- End Slider -->
            <!-- Right Side Banner -->
            <div class="col-lg-3 col-md-4">
                <div class="sidebar_banner5">
                    <div class="single_banner">
                        <div class="banner_thumb">
                            <a href="shop.html"><img src="assets/img/bg/banner22.jpg" alt=""></a>
                        </div>
                    </div>
                </div>
            </div><!-- End Right Side Bannere-->
        </div>
    </div>
</section>