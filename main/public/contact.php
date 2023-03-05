<?php
    require_once "../global.php";

    render_template("regular", [
        "title" => "Contact Us",
        "navigation_bar" => true,
        "footer" => true,
        "content" => <<<HTML
            <!-- RD Google Map-->
            <iframe src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d232.74512086741743!2d105.8189512!3d21.0358094!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3135ab0d127a01e7%3A0xab069cd4eaa76ff2!2zMjg1IMSQ4buZaSBD4bqlbiwgVsSpbmggUGjDuiwgQmEgxJDDrG5oLCBIw6AgTuG7mWkgMTAwMDAw!5e0!3m2!1sen!2s!4v1678015634923!5m2!1sen!2s" width="100%" height="450px" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
            <!-- Contact information-->
            <section class="section section-sm section-first bg-default">
                <div class="container">
                    <div class="row row-30 justify-content-center">
                        <div class="col-sm-8 col-md-6 col-lg-4">
                            <article class="box-contacts">
                                <div class="box-contacts-body">
                                    <div class="box-contacts-icon fl-bigmug-line-cellphone55"></div>
                                    <div class="box-contacts-decor"></div>
                                    <p class="box-contacts-link"><a href="tel:1800 1141">1800 1141</a></p>
                                </div>
                            </article>
                        </div>
                        <div class="col-sm-8 col-md-6 col-lg-4">
                            <article class="box-contacts">
                                <div class="box-contacts-body">
                                    <div class="box-contacts-icon fl-bigmug-line-chat55"></div>
                                    <div class="box-contacts-decor"></div>
                                    <p class="box-contacts-link"><a href="mailto:pgood8471@gmail.com">pgood8471@gmail.com</a></p>
                                </div>
                            </article>
                        </div>
                        <div class="col-sm-8 col-md-6 col-lg-4">
                            <article class="box-contacts">
                                <div class="box-contacts-body">
                                    <div class="box-contacts-icon fl-bigmug-line-up104"></div>
                                    <div class="box-contacts-decor"></div>
                                    <p class="box-contacts-link"><a href="https://goo.gl/maps/w24MCzmGU1HuQtFSA">285 Doi Can, Vinh Phu, Ba Dinh, Hanoi 100000</a></p>
                                </div>
                            </article>
                        </div>
                    </div>
                </div>
            </section>
        HTML,
    ]);
?>