<?php
    require_once "../global.php";

    render_template("base", [
        "title" => "Home",
        "navigation_bar" => true,
        "footer" => true,
        "content" => <<<CONTENT
            <!-- Swiper-->
            <section class="section swiper-container swiper-slider swiper-slider-corporate swiper-pagination-style-2"
                data-loop="true" data-autoplay="5000" data-simulate-touch="true" data-nav="false" data-direction="vertical">
                <div class="swiper-wrapper text-left">
                    <div class="swiper-slide context-dark" data-slide-bg="images/slider-4-slide-1-1920x678.jpg">
                        <div class="swiper-slide-caption section-md">
                            <div class="container">
                                <div class="row">
                                    <div class="col-md-10">
                                        <h6 class="text-uppercase" data-caption-animate="fadeInRight"
                                            data-caption-delay="0">Enjoy the Best
                                            Destinations with Our Travel Agency</h6>
                                        <h2 class="oh font-weight-light" data-caption-animate="slideInUp"
                                            data-caption-delay="100">
                                            <span>Explore</span><span class="font-weight-bold"> The World</span>
                                        </h2><a class="button button-default-outline button-ujarak" href="#footer"
                                            data-caption-animate="fadeInLeft" data-caption-delay="0">Get in touch</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="swiper-slide context-dark" data-slide-bg="images/slider-4-slide-2-1920x678.jpg">
                        <div class="swiper-slide-caption section-md">
                            <div class="container">
                                <div class="row">
                                    <div class="col-md-10">
                                        <h6 class="text-uppercase" data-caption-animate="fadeInRight"
                                            data-caption-delay="0">A team of
                                            professional Travel Experts</h6>
                                        <h2 class="oh font-weight-light" data-caption-animate="slideInUp"
                                            data-caption-delay="100">
                                            <span>Trust</span><span class="font-weight-bold"> Our Experience</span>
                                        </h2><a class="button button-default-outline button-ujarak" href="#footer"
                                            data-caption-animate="fadeInLeft" data-caption-delay="0">Get in touch</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="swiper-slide context-dark" data-slide-bg="images/slider-4-slide-3-1920x678.jpg">
                        <div class="swiper-slide-caption section-md">
                            <div class="container">
                                <div class="row">
                                    <div class="col-md-10">
                                        <h6 class="text-uppercase" data-caption-animate="fadeInRight"
                                            data-caption-delay="0">Build your Next
                                            Holiday Trip with Us</h6>
                                        <h2 class="oh font-weight-light" data-caption-animate="slideInUp"
                                            data-caption-delay="100">
                                            <span>Create</span><span class="font-weight-bold"> Your Tour</span>
                                        </h2><a class="button button-default-outline button-ujarak" href="#footer"
                                            data-caption-animate="fadeInLeft" data-caption-delay="0">Get in touch</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Swiper Pagination-->
                <div class="swiper-pagination"></div>
            </section>
            <!-- Section Box Categories-->
            <section class="section section-lg section-top-1 bg-gray-4">
                <div class="container offset-negative-1">
                    <div class="box-categories cta-box-wrap">
                        <div class="box-categories-content">
                            <div class="row justify-content-center">
                                <div class="col-md-4 wow fadeInDown col-9" data-wow-delay=".2s">
                                    <ul class="list-marked-2 box-categories-list">
                                        <li><a href="#"><img src="images/cta-1-368x420.jpg" alt="" width="368"
                                                    height="420" /></a>
                                            <h5 class="box-categories-title">Balloon Flights</h5>
                                        </li>
                                    </ul>
                                </div>
                                <div class="col-md-4 wow fadeInDown col-9" data-wow-delay=".2s">
                                    <ul class="list-marked-2 box-categories-list">
                                        <li><a href="#"><img src="images/cta-2-368x420.jpg" alt="" width="368"
                                                    height="420" /></a>
                                            <h5 class="box-categories-title">Mountain Holiday</h5>
                                        </li>
                                    </ul>
                                </div>
                                <div class="col-md-4 wow fadeInDown col-9" data-wow-delay=".2s">
                                    <ul class="list-marked-2 box-categories-list">
                                        <li><a href="#"><img src="images/cta-3-368x420.jpg" alt="" width="368"
                                                    height="420" /></a>
                                            <h5 class="box-categories-title">Beach Holidays</h5>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div><a class="link-classic wow fadeInUp" href="/tours.php">Other Tours<span></span></a>
                    <!-- Owl Carousel-->
                </div>
            </section>
            <!-- Discover New Horizons-->
            <section class="section section-sm section-first bg-default text-md-left">
                <div class="container">
                    <div class="row row-50 align-items-center justify-content-center justify-content-xl-between">
                        <div class="col-lg-6 text-center wow fadeInUp"><img src="images/index-3-556x382.jpg" alt=""
                                width="556" height="382" />
                        </div>
                        <div class="col-lg-6 wow fadeInRight" data-wow-delay=".1s">
                            <div class="box-width-lg-470">
                                <h3>Discover New Horizons</h3>
                                <!-- Bootstrap tabs-->
                                <div class="tabs-custom tabs-horizontal tabs-line tabs-line-big tabs-line-style-2 text-center text-md-left"
                                    id="tabs-7">
                                    <!-- Nav tabs-->
                                    <ul class="nav nav-tabs">
                                        <li class="nav-item" role="presentation"><a class="nav-link active" href="#tabs-7-1"
                                                data-toggle="tab">About us</a></li>
                                        <li class="nav-item" role="presentation"><a class="nav-link" href="#tabs-7-2"
                                                data-toggle="tab">Why
                                                choose us</a></li>
                                        <li class="nav-item" role="presentation"><a class="nav-link" href="#tabs-7-3"
                                                data-toggle="tab">Our
                                                mission</a></li>
                                    </ul>
                                    <!-- Tab panes-->
                                    <div class="tab-content">
                                        <div class="tab-pane fade show active" id="tabs-7-1">
                                            <p>Wonder Tour is committed to bringing our clients the best in value and
                                                quality travel
                                                arrangements. We are passionate about travel and sharing the world's wonders
                                                with you.</p>
                                            <div class="group-md group-middle"><a
                                                    class="button button-secondary button-pipaluk"
                                                    href="#footer">Get in Touch</a><a
                                                    class="button button-black-outline button-md" href="/about.php">Read
                                                    More</a></div>
                                        </div>
                                        <div class="tab-pane fade" id="tabs-7-2">
                                            <p>We are proud to offer excellent quality and value for money in our tours,
                                                which give you the
                                                chance to experience your chosen destination in an authentic and exciting
                                                way.</p>
                                            <div class="group-md group-middle"><a
                                                    class="button button-secondary button-pipaluk"
                                                    href="#footer">Get in Touch</a><a
                                                    class="button button-black-outline button-md" href="/about.php">Read
                                                    More</a></div>
                                        </div>
                                        <div class="tab-pane fade" id="tabs-7-3">
                                            <p>Our mission is to provide the ultimate travel planning experience while
                                                becoming a one-stop shop
                                                for every travel service available in the industry.</p>
                                            <div class="group-md group-middle"><a
                                                    class="button button-secondary button-pipaluk"
                                                    href="#footer">Get in Touch</a><a
                                                    class="button button-black-outline button-md" href="/about.php">Read
                                                    More</a></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <!--	Our Services-->
            <section class="section section-sm">
                <div class="container">
                    <h3>Our Services</h3>
                    <div class="row row-30">
                        <div class="col-sm-6 col-lg-4">
                            <article class="box-icon-classic">
                                <div
                                    class="unit box-icon-classic-body flex-column flex-md-row text-md-left flex-lg-column text-lg-center flex-xl-row text-xl-left">
                                    <div class="unit-left">
                                        <div class="box-icon-classic-icon fl-bigmug-line-circular220"></div>
                                    </div>
                                    <div class="unit-body">
                                        <h5 class="box-icon-classic-title"><a>Wide Variety of Tours</a></h5>
                                        <p class="box-icon-classic-text">We offer a wide variety of personally picked tours
                                            with destinations
                                            all over the globe.</p>
                                    </div>
                                </div>
                            </article>
                        </div>
                        <div class="col-sm-6 col-lg-4">
                            <article class="box-icon-classic">
                                <div
                                    class="unit box-icon-classic-body flex-column flex-md-row text-md-left flex-lg-column text-lg-center flex-xl-row text-xl-left">
                                    <div class="unit-left">
                                        <div class="box-icon-classic-icon fl-bigmug-line-favourites5"></div>
                                    </div>
                                    <div class="unit-body">
                                        <h5 class="box-icon-classic-title"><a>Highly Qualified Service</a></h5>
                                        <p class="box-icon-classic-text">Our tour managers are qualified, skilled, and
                                            friendly to bring you
                                            the best service.</p>
                                    </div>
                                </div>
                            </article>
                        </div>
                        <div class="col-sm-6 col-lg-4">
                            <article class="box-icon-classic">
                                <div
                                    class="unit box-icon-classic-body flex-column flex-md-row text-md-left flex-lg-column text-lg-center flex-xl-row text-xl-left">
                                    <div class="unit-left">
                                        <div class="box-icon-classic-icon fl-bigmug-line-headphones32"></div>
                                    </div>
                                    <div class="unit-body">
                                        <h5 class="box-icon-classic-title"><a>24/7 Support</a></h5>
                                        <p class="box-icon-classic-text">You can always get professional support from our
                                            staff 24/7 and ask
                                            any question you have.</p>
                                    </div>
                                </div>
                            </article>
                        </div>
                        <div class="col-sm-6 col-lg-4"></div>
                        <div class="col-sm-6 col-lg-4">
                            <article class="box-icon-classic">
                                <div
                                    class="unit box-icon-classic-body flex-column flex-md-row text-md-left flex-lg-column text-lg-center flex-xl-row text-xl-left">
                                    <div class="unit-left">
                                        <div class="box-icon-classic-icon fl-bigmug-line-wallet26"></div>
                                    </div>
                                    <div class="unit-body">
                                        <h5 class="box-icon-classic-title"><a>Best Price Guarantee</a></h5>
                                        <p class="box-icon-classic-text">If you find tours that are cheaper than ours, we
                                            will compensate the
                                            difference.</p>
                                    </div>
                                </div>
                            </article>
                        </div>
                        <div class="col-sm-6 col-lg-4"></div>
                    </div>
                </div>
            </section>
            <!-- Hot tours-->
            <section class="section section-sm bg-default">
                <div class="container">
                    <h3 class="oh-desktop"><span class="d-inline-block wow slideInDown">Hot Tours</span></h3>
                    <div class="row row-sm row-40 row-md-50">
                        <div class="col-sm-6 col-md-12 wow fadeInRight">
                            <!-- Product Big-->
                            <article class="product-big">
                                <div class="unit flex-column flex-md-row align-items-md-stretch">
                                    <div class="unit-left"><a class="product-big-figure" href="#"><img
                                                src="images/product-big-1-600x366.jpg" alt="" width="600"
                                                height="366" /></a></div>
                                    <div class="unit-body">
                                        <div class="product-big-body">
                                            <h5 class="product-big-title"><a href="#">Benidorm, Spain</a></h5>
                                            <div class="group-sm group-middle justify-content-start">
                                                <div class="product-big-rating"><span
                                                        class="icon material-icons-star"></span><span
                                                        class="icon material-icons-star"></span><span
                                                        class="icon material-icons-star"></span><span
                                                        class="icon material-icons-star"></span><span
                                                        class="icon material-icons-star_half"></span>
                                                </div><a class="product-big-reviews" href="#">4 customer reviews</a>
                                            </div>
                                            <p class="product-big-text">Benidorm is a buzzing resort with a big reputation
                                                for beach holidays.
                                                Situated in sunny Costa Blanca, the town is one of the original Spanish
                                                beach resorts...</p><a class="button button-black-outline button-ujarak"
                                                href="#">Buy This Tour</a>
                                            <div class="product-big-price-wrap"><span class="product-big-price">$790</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </article>
                        </div>
                        <div class="col-sm-6 col-md-12 wow fadeInLeft">
                            <!-- Product Big-->
                            <article class="product-big">
                                <div class="unit flex-column flex-md-row align-items-md-stretch">
                                    <div class="unit-left"><a class="product-big-figure" href="#"><img
                                                src="images/product-big-2-600x366.jpg" alt="" width="600"
                                                height="366" /></a></div>
                                    <div class="unit-body">
                                        <div class="product-big-body">
                                            <h5 class="product-big-title"><a href="#">Mauritius Island, Africa</a></h5>
                                            <div class="group-sm group-middle justify-content-start">
                                                <div class="product-big-rating"><span
                                                        class="icon material-icons-star"></span><span
                                                        class="icon material-icons-star"></span><span
                                                        class="icon material-icons-star"></span><span
                                                        class="icon material-icons-star"></span><span
                                                        class="icon material-icons-star_half"></span>
                                                </div><a class="product-big-reviews" href="#">5 customer reviews</a>
                                            </div>
                                            <p class="product-big-text">The beautiful and inviting island nation of
                                                Mauritius is an ideal ‘flop
                                                and drop’ at the conclusion of your safari. Indulge in the delightful scents
                                                of the fragrant...
                                            </p><a class="button button-black-outline button-ujarak" href="#">Buy This
                                                Tour</a>
                                            <div class="product-big-price-wrap"><span class="product-big-price">$890</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </article>
                        </div>
                    </div>
                </div>
            </section>
            <!-- Section Subscribe-->
            <section class="section bg-default text-center offset-top-50">
                <div class="parallax-container" data-parallax-img="images/parallax-1-1920x850.jpg">
                    <div class="parallax-content section-xl section-inset-custom-1 context-dark bg-overlay-2-21">
                        <div class="container">
                            <h2 class="heading-2 oh font-weight-normal wow slideInDown"><span
                                    class="d-block font-weight-semi-bold">First-class Impressions</span><span
                                    class="d-block font-weight-light">are Waiting for You!</span></h2>
                            <p class="text-width-medium text-spacing-75 wow fadeInLeft" data-wow-delay=".1s">Our agency
                                offers travelers
                                various tours and excursions with destinations all over the world. Browse our website to
                                find your dream
                                tour!</p><a class="button button-secondary button-pipaluk" href="/tours.php">Book a Tour Now</a>
                        </div>
                    </div>
                </div>
            </section>
        CONTENT,
    ]);
?>