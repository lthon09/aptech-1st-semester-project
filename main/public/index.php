<?php
    require_once "../global.php";

    $hot_tours = "";
    $other_tours = "";

    $connection = connect();

    $statement = $connection -> prepare("
        SELECT * FROM HotTours;
    ");

    $statement -> execute();

    if ($statement -> rowCount() === 0) {
        $hot_tours = <<<HTML
            <div class="col">
                <h5 class="box-icon-classic-title" style="font-weight:normal">No Hot Tour</h5>
            </div>
        HTML;
    } else {
        foreach (($statement -> fetchAll()) as $hot_tour) {
            $id = $hot_tour["Tour"];

            $statement = $connection -> prepare("
                SELECT * FROM Tours WHERE ID = :id LIMIT 1;
            ");

            $statement -> execute(["id" => $id]);

            $tour = $statement -> fetch();

            $name = htmlentities($tour["Name"]);
            $country = htmlentities($tour["Country"]);
            $description = htmlentities($tour["ShortDescription"]);
            $original_price = $tour["Price"];
            $sale = $tour["Sale"];
            $_original_price = "$" . format_price($original_price, (int)$original_price, $original_price);
            $avatar = $tour["Avatar"];

            $_sale = "";

            if ($sale !== 0) {
                $discounted_price = "$" . calculate_discounted_price($original_price, $sale);

                $_sale = <<<HTML
                    <span style="color:#d3d3d3;text-decoration:line-through;font-size:17px">{$_original_price}</span>
                HTML;

                $_price = <<<HTML
                    <span style="color:red">{$discounted_price}</span>
                HTML;
            } else {
                $_price = $_original_price;
            }

            $hot_tours .= <<<HTML
                <div class="col-sm-6 col-md-12 wow fadeInRight">
                    <!-- Product Big-->
                    <article class="product-big">
                        <div class="unit flex-column flex-md-row align-items-md-stretch">
                            <div class="unit-left"><img class="product-big-figure"
                                        src="/static/assets/tours/{$avatar}" alt="" width="600"
                                        height="366" /></div>
                            <div class="unit-body">
                                <div class="product-big-body">
                                    <h5 class="product-big-title">{$name}, {$country}</h5>
                                    <p class="product-big-text">{$description}</p><a class="button button-black-outline button-ujarak"
                                        href="/tour.php?id={$id}">Learn More</a>
                                    <div class="product-big-price-wrap">
                                        <span class="product-big-price" style="display:flex;flex-direction:column;gap:10px">
                                            {$_sale}
                                            {$_price}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </article>
                </div>
            HTML;
        }

        $other_tours = <<<HTML
            <section class="section section-top-1">
                <div class="container">
                    <div class="box-categories cta-box-wrap"></div>
                    <a class="link-classic wow fadeInUp" href="/tours.php">Other Tours<span></span></a>
                    <!-- Owl Carousel-->
                </div>
            </section>
        HTML;
    }

    render_template("regular", [
        "title" => "Home",
        "navigation_bar" => true,
        "footer" => true,
        "content" => <<<HTML
            <!-- Swiper-->
            <section class="section swiper-container swiper-slider swiper-slider-corporate swiper-pagination-style-2"
                data-loop="true" data-autoplay="5000" data-simulate-touch="true" data-nav="false" data-direction="vertical">
                <div class="swiper-wrapper text-left">
                    <div class="swiper-slide context-dark" data-slide-bg="/static/assets/images/slide1.jpg">
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
                                        </h2><a class="button button-default-outline button-ujarak" href="/contact.php"
                                            data-caption-animate="fadeInLeft" data-caption-delay="0">Get in touch</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="swiper-slide context-dark" data-slide-bg="/static/assets/images/slide2.jpg">
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
                                        </h2><a class="button button-default-outline button-ujarak" href="/contact.php"
                                            data-caption-animate="fadeInLeft" data-caption-delay="0">Get in touch</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="swiper-slide context-dark" data-slide-bg="/static/assets/images/slide3.jpg">
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
                                        </h2><a class="button button-default-outline button-ujarak" href="/contact.php"
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
            <section class="section section-top-1">
                <div class="container">
                    <div class="box-categories cta-box-wrap"></div>
                    <a class="link-classic wow fadeInUp" href="/tours.php">View Tours<span></span></a>
                    <!-- Owl Carousel-->
                </div>
            </section>
            <!-- Discover New Horizons-->
            <section class="section section-sm section-first bg-default text-md-left">
                <div class="container">
                    <div class="row row-50 align-items-center justify-content-center justify-content-xl-between">
                        <div class="col-lg-6 text-center wow fadeInUp"><img src="/static/assets/images/discover.jpg" alt=""
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
                                            <p>Pleasant Tours is committed to bringing our clients the best in value and
                                                quality travel
                                                arrangements. We are passionate about travel and sharing the world's wonders
                                                with you.</p>
                                            <div class="group-md group-middle"><a
                                                    class="button button-secondary button-pipaluk"
                                                    href="/contact.php">Get in Touch</a><a
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
                                                    href="/contact.php">Get in Touch</a><a
                                                    class="button button-black-outline button-md" href="/about.php">Read
                                                    More</a></div>
                                        </div>
                                        <div class="tab-pane fade" id="tabs-7-3">
                                            <p>Our mission is to provide the ultimate travel planning experience while
                                                becoming a one-stop shop
                                                for every travel service available in the industry.</p>
                                            <div class="group-md group-middle"><a
                                                    class="button button-secondary button-pipaluk"
                                                    href="/contact.php">Get in Touch</a><a
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
                                        <h5 class="box-icon-classic-title">Wide Variety of Tours</h5>
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
                                        <h5 class="box-icon-classic-title">Highly Qualified Service</h5>
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
                                        <h5 class="box-icon-classic-title">24/7 Support</h5>
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
                                        <h5 class="box-icon-classic-title">Best Price Guarantee</h5>
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
                        {$hot_tours}
                    </div>
                </div>
                {$other_tours}
            </section>
            <!-- Section Subscribe-->
            <section class="section bg-default text-center offset-top-50">
                <div class="parallax-container" data-parallax-img="/static/assets/images/book_now.jpg">
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
        HTML,
    ]);
?>