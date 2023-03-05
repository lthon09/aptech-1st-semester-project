<?php
    require_once "../global.php";

    render_template("regular", [
        "title" => "About Us",
        "navigation_bar" => true,
        "footer" => true,
        "content" => <<<HTML
            <!-- Why choose us-->
            <section class="section section-sm section-first bg-default text-md-left">
                <div class="container">
                    <div class="row row-50 justify-content-center align-items-xl-center">
                        <div class="col-md-10 col-lg-5 col-xl-6"><img src="/static/assets/images/about.jpg" alt="" width="100%"
                                height="100%" />
                        </div>
                        <div class="col-md-10 col-lg-7 col-xl-6">
                            <h1 class="text-spacing-25 font-weight-normal title-opacity-9">Why choose us</h1>
                            <!-- Bootstrap tabs-->
                            <div class="tabs-custom tabs-horizontal tabs-line" id="tabs-4">
                                <!-- Nav tabs-->
                                <ul class="nav nav-tabs">
                                    <li class="nav-item" role="presentation"><a class="nav-link active" href="#tabs-4-1"
                                            data-toggle="tab">Experience</a></li>
                                    <li class="nav-item" role="presentation"><a class="nav-link" href="#tabs-4-2"
                                            data-toggle="tab">Skills</a></li>
                                    <li class="nav-item" role="presentation"><a class="nav-link" href="#tabs-4-3"
                                            data-toggle="tab">Mission</a></li>
                                </ul>
                                <!-- Tab panes-->
                                <div class="tab-content">
                                    <div class="tab-pane fade show active" id="tabs-4-1">
                                        <p>Pleasant Tours is committed to bringing our clients the best in value and quality travel arrangements. We are passionate about travel and sharing the world's wonders with you.</p>
                                        <!-- Linear progress bar-->
                                        <article class="progress-linear progress-secondary">
                                            <div class="progress-header">
                                                <p>Tours</p>
                                            </div>
                                            <div class="progress-bar-linear-wrap">
                                                <div class="progress-bar-linear" data-gradient=""><span
                                                        class="progress-value">79</span><span
                                                        class="progress-marker"></span></div>
                                            </div>
                                        </article>
                                        <!-- Linear progress bar-->
                                        <article class="progress-linear progress-orange">
                                            <div class="progress-header">
                                                <p>Excursions</p>
                                            </div>
                                            <div class="progress-bar-linear-wrap">
                                                <div class="progress-bar-linear" data-gradient=""><span
                                                        class="progress-value">72</span><span
                                                        class="progress-marker"></span></div>
                                            </div>
                                        </article>
                                    </div>
                                    <div class="tab-pane fade" id="tabs-4-2">
                                        <div class="row row-40 justify-content-center text-center inset-top-10">
                                            <div class="col-sm-4">
                                                <!-- Circle Progress Bar-->
                                                <div class="progress-bar-circle" data-value="0.87" data-gradient="#01b3a7"
                                                    data-empty-fill="transparent" data-size="150" data-thickness="12"
                                                    data-reverse="true"><span></span></div>
                                                <p class="progress-bar-circle-title">Tours</p>
                                            </div>
                                            <div class="col-sm-4">
                                                <!-- Circle Progress Bar-->
                                                <div class="progress-bar-circle" data-value="0.74" data-gradient="#01b3a7"
                                                    data-empty-fill="transparent" data-size="150" data-thickness="12"
                                                    data-reverse="true"><span></span></div>
                                                <p class="progress-bar-circle-title">Excursions</p>
                                            </div>
                                        </div>
                                        <div class="group-md group-middle"><a
                                                class="button button-width-xl-230 button-primary button-pipaluk"
                                                href="/contact.php">Get in touch</a></div>
                                    </div>
                                    <div class="tab-pane fade" id="tabs-4-3">
                                        <p>Our mission is to provide the ultimate travel planning experience while becoming a one-stop shop for every travel service available in the industry.</p>
                                        <div class="group-md group-middle"><a
                                                class="button button-width-xl-230 button-primary button-pipaluk"
                                                href="/contact.php">Get in touch</a></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <!--Counters-->
            <!-- Counter Classic-->
            <section class="section section-fluid bg-default">
                <div class="parallax-container" data-parallax-img="images/bg-counter-2.jpg">
                    <div class="parallax-content section-xl context-dark bg-overlay-26">
                        <div class="container">
                            <div class="row row-50 justify-content-center border-classic">
                                <div class="col-sm-6 col-md-5 col-lg-3">
                                    <div class="counter-classic">
                                        <div class="counter-classic-number"><span class="counter">12</span>
                                        </div>
                                        <h5 class="counter-classic-title">Awards</h5>
                                    </div>
                                </div>
                                <div class="col-sm-6 col-md-5 col-lg-3">
                                    <div class="counter-classic">
                                        <div class="counter-classic-number"><span class="counter">194</span>
                                        </div>
                                        <h5 class="counter-classic-title">Tours</h5>
                                    </div>
                                </div>
                                <div class="col-sm-6 col-md-5 col-lg-3">
                                    <div class="counter-classic">
                                        <div class="counter-classic-number"><span class="counter">2</span><span
                                                class="symbol">k</span>
                                        </div>
                                        <h5 class="counter-classic-title">Travelers</h5>
                                    </div>
                                </div>
                                <div class="col-sm-6 col-md-5 col-lg-3">
                                    <div class="counter-classic">
                                        <div class="counter-classic-number"><span class="counter">25</span>
                                        </div>
                                        <h5 class="counter-classic-title">Team members</h5>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        HTML,
    ]);
?>