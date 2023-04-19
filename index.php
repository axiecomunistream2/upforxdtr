<?php 
session_start();
require_once(__DIR__ . '/include/Core.php'); 
require_once(__DIR__ . '/include/Config.php');
require_once(__DIR__ . '/include/PDOQuery.php');
?>
<!DOCTYPE html>
<html><head>
<?php echo MinifyTemplate(__DIR__ . '/template/header.php'); ?>
</head><body>
<?php echo MinifyTemplate(__DIR__ . '/template/navbar.php'); ?>
    <main class="page landing-page bg-light">
        <section data-aos="fade-up" data-aos-duration="800" class="portfolio-block block-intro">
            <div class="container">
                <div data-bs-hover-animate="rubberBand" class="avatar" style="background-image:url(&quot;assets/img/30265303_182950442506471_4327087990607183872_o.png&quot;);"></div>
                <div class="about-me">
                    <h4 class="mb-4" data-aos="zoom-in"><?php if (!isset($_SESSION['username'])) { echo 'Welcome to <strong>Fun Shop</strong><br>The #1 Blox Fruits Shop'; } else { echo 'Welcome back, <strong>'.$_SESSION['username'].'</strong>!'; } ?></h4>
                    <?php
                    if (isset($_SESSION['username'])) {
                        echo '<a class="btn btn-outline-primary" role="button" href="shop.php" data-aos="flip-down">VISIT OUR SHOP</a></div>';
                    }else{
                        echo '<div class="btn-group mr-2" role="group"><a class="btn btn-outline-primary" role="button" href="login.php" data-aos="flip-down">LOGIN</a></div><a class="btn btn-outline-primary" role="button" href="register.php" data-aos="flip-down">REGISTER</a></div></div>';
                    }
                    ?>
            </div>
            <div class="swiper-slide" style="background-image:url(https://placeholdit.imgix.net/~text?txtsize=68&amp;txt=Slideshow+Image&amp;w=1920&amp;h=500);"></div>
        </section>
        <section class="portfolio-block call-to-action" style="padding:0px;">
            <section data-aos="fade-up" data-aos-duration="950" data-aos-once="true" class="portfolio-block website gradient">
                <div class="container">
                    <div class="row align-items-center">
                        <div class="col-md-12 col-lg-5 offset-lg-1 text text-dark" data-aos="fade-up">
                            <h3 style="width:auto;margin-right:0px;"><strong>About Us</strong></h3>
                            <p>Fun was founded in 2021 when the Covid-19 case was on the rise. Fun was set up to be a provider of high quality and affordable game accounts.<br><br></p>
                        </div>
                        <div class="col-md-12 col-lg-5" data-bs-hover-animate="pulse">
                            <div class="portfolio-laptop-mockup">
                                <div class="screen">
                                    <div class="screen-content" style="background-image:url(&quot;assets/img/bg.jpg&quot;);background-size:cover;background-position:35%;"></div>
                                </div>
                                <div class="keyboard"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </section>
        <section data-aos="fade-up" data-aos-duration="800" class="portfolio-block skills" style="padding:50px;height:auto;">
            <div class="container">
                <div class="text-dark heading">
                    <h2 style="padding:10px;"><strong>service</strong><br></h2>
                </div>
                <section class="portfolio-block photography">
                    <div class="swiper-slide" style="background-image:url(https://placeholdit.imgix.net/~text?txtsize=68&amp;txt=Slideshow+Image&amp;w=1920&amp;h=500);"></div>
                </section>
                <div class="row" data-aos="zoom-in" style="width:auto;">
                    <div class="col-md-4" data-aos="fade-up" data-aos-duration="800" data-aos-delay="500">
                        <div class="card special-skill-item border-0 bg-light text-dark">
                            <div class="card-header bg-transparent border-0"><i class="icon ion-ios-game-controller-a" data-bs-hover-animate="pulse"></i></div>
                            <div class="card-body">
                                <h3 class="card-title">Blox Fruits Account</h3>
                                <p class="card-text">High quality Blox Fruits account and 100% safe.<br></p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4" data-aos="fade-up" data-aos-duration="800" data-aos-delay="1000">
                        <div class="card special-skill-item border-0 bg-light text-dark">
                            <div class="card-header bg-transparent border-0"><i class="icon ion-android-cart" data-bs-hover-animate="pulse"></i></div>
                            <div class="card-body">
                                <h3 class="card-title">Shop</h3>
                                <p class="card-text">Easy to use website.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4" data-aos="fade-up" data-aos-duration="800" data-aos-delay="1500">
                        <div class="card special-skill-item border-0 bg-light text-dark">
                            <div class="card-header bg-transparent border-0"><i class="icon ion-settings" data-bs-hover-animate="pulse"></i></div>
                            <div class="card-body">
                                <h3 class="card-title">Support</h3>
                                <p class="card-text">24/7 with our best support</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>
    <div class="simple-slider">
        <div class="swiper-container">
            <div class="swiper-wrapper"></div>
            <div class="swiper-pagination"></div>
            <div class="swiper-button-prev"></div>
            <div class="swiper-button-next"></div>
        </div>
    </div>
<?php echo MinifyTemplate(__DIR__ . '/template/footer.php'); ?>
</body>
</html>