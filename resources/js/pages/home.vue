<style scoped>
.header .logo span {
    color: #fff;
}

#navbar li a {
    color: #aaa !important;
}

#navbar li:hover a,
#navbar li a.active {
    color: #fff !important;
}

.hero h1,
.hero h2 {
    color: #fff;
}

.header.header-scrolled {
    background-color: #031c32;
}

section {
    background-color: #031c32;
}
</style>

<template>
    <div>
        <!-- ======= Header ======= -->
        <header id="header" class="header fixed-top">
            <div class="container-fluid container-xl d-flex align-items-center justify-content-between">

                <a href="index.html" class="logo d-flex align-items-center">
                    <img src="/guest/img/logo.png" alt="">
                    <span>Rent N Play</span>
                </a>

                <nav id="navbar" class="navbar">
                    <ul>
                        <li><a class="nav-link scrollto active" href="#hero">Home</a></li>
                        <li><a class="nav-link scrollto" href="#about">About</a></li>
                        <li><a class="nav-link scrollto" href="#product">Product</a></li>
                        <li><a class="nav-link scrollto" href="#checkout">Checkout</a></li>
                        <li><router-link class="getstarted" to="login">Login</router-link></li>
                    </ul>
                    <i class="bi bi-list mobile-nav-toggle"></i>
                </nav><!-- .navbar -->

            </div>
        </header><!-- End Header -->

        <!-- ======= Hero Section ======= -->
        <section id="hero" class="hero d-flex align-items-center"
            style="background: url(/guest/img/background.png); background-size: cover;">
            <div class="container">
                <div class="row">
                    <div class="col-lg-6 d-flex flex-column justify-content-center">
                        <h1 data-aos="fade-up">Tempat Penyewaan Alat Camping</h1>
                        <h2 data-aos="fade-up" data-aos-delay="400">We are team of talented designers making websites
                            with Bootstrap</h2>
                        <div data-aos="fade-up" data-aos-delay="600">
                            <div class="text-center text-lg-start">
                                <a href="#about"
                                    class="btn-get-started scrollto d-inline-flex align-items-center justify-content-center align-self-center">
                                    <span>Mulai</span>
                                    <i class="bi bi-arrow-right"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 hero-img" data-aos="zoom-out" data-aos-delay="200">
                        <!-- <img src="/guest/img/background.png" class="img-fluid" alt=""> -->
                    </div>
                </div>
            </div>

        </section><!-- End Hero -->

        <main id="main">
            <!-- ======= About Section ======= -->
            <section id="about" class="about">

                <div class="container" data-aos="fade-up">
                    <div class="row gx-0">

                        <div class="col-lg-6 d-flex flex-column justify-content-center" data-aos="fade-up"
                            data-aos-delay="200">
                            <div class="content">
                                <h3>Tentang Kita</h3>
                                <p>
                                    Selamat datang di Rent N Play, penyedia terkemuka dalam penyewaan alat camping. Kami
                                    menyediakan peralatan camping berkualitas tinggi untuk memastikan pengalaman outdoor
                                    Anda menjadi tak terlupakan. Dengan pilihan luas dan layanan pelanggan yang ramah,
                                    kami siap membantu Anda menjelajahi alam bebas dengan percaya diri dan kenyamanan.
                                    Temukan kemudahan dan keandalan dengan Rent N Play untuk setiap petualangan Anda.
                                    Jadi, siap untuk memulai petualangan camping Anda? Segera hubungi Rent N Play dan
                                    biarkan kami membantu Anda menjadikan setiap momen di alam bebas sebagai pengalaman
                                    yang tak terlupakan.
                                </p>
                                <div class="text-center text-lg-start">
                                    <a href="#"
                                        class="btn-read-more d-inline-flex align-items-center justify-content-center align-self-center">
                                        <span>Baca Selengkapnya</span>
                                        <i class="bi bi-arrow-right"></i>
                                    </a>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-6 d-flex align-items-center" data-aos="zoom-out" data-aos-delay="200">
                            <!-- <img src="/guest/img/about.jpg" class="img-fluid" alt=""> -->
                        </div>

                    </div>
                </div>

            </section><!-- End About Section -->

            <!-- ======= Product Section ======= -->
            <section id="product" class="product">

                <div class="container" data-aos="fade-up">

                    <header class="section-header">
                        <h2>Produk</h2>
                        <p>Produk terbaru kami</p>
                    </header>

                    <div class="row" data-aos="fade-up" data-aos-delay="100">
                        <div class="col-lg-12 d-flex justify-content-center">
                            <ul id="product-filters">
                                <li data-filter="*" class="filter-active">All</li>
                                <li data-filter=".filter-app">App</li>
                                <li data-filter=".filter-card">Card</li>
                                <li data-filter=".filter-web">Web</li>
                            </ul>
                        </div>
                    </div>

                    <div class="row gy-4 product-container" data-aos="fade-up" data-aos-delay="200">

                        <div class="col-lg-4 col-md-6 product-item filter-app">
                            <div class="product-wrap">
                                <img src="/guest/img/product/product-1.jpg" class="img-fluid" alt="">
                                <div class="product-info">
                                    <h4>App 1</h4>
                                    <p>App</p>
                                    <div class="product-links">
                                        <a href="/guest/img/product/product-1.jpg" data-gallery="productGallery"
                                            class="portfolio-lightbox" title="App 1"><i class="bi bi-plus"></i></a>
                                        <a href="product-details.html" title="More Details"><i
                                                class="bi bi-link"></i></a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-4 col-md-6 product-item filter-web">
                            <div class="product-wrap">
                                <img src="/guest/img/product/product-2.jpg" class="img-fluid" alt="">
                                <div class="product-info">
                                    <h4>Web 3</h4>
                                    <p>Web</p>
                                    <div class="product-links">
                                        <a href="/guest/img/product/product-2.jpg" data-gallery="productGallery"
                                            class="portfolio-lightbox" title="Web 3"><i class="bi bi-plus"></i></a>
                                        <a href="product-details.html" title="More Details"><i
                                                class="bi bi-link"></i></a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-4 col-md-6 product-item filter-app">
                            <div class="product-wrap">
                                <img src="/guest/img/product/product-3.jpg" class="img-fluid" alt="">
                                <div class="product-info">
                                    <h4>App 2</h4>
                                    <p>App</p>
                                    <div class="product-links">
                                        <a href="/guest/img/product/product-3.jpg" data-gallery="productGallery"
                                            class="portfolio-lightbox" title="App 2"><i class="bi bi-plus"></i></a>
                                        <a href="product-details.html" title="More Details"><i
                                                class="bi bi-link"></i></a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-4 col-md-6 product-item filter-card">
                            <div class="product-wrap">
                                <img src="/guest/img/product/product-4.jpg" class="img-fluid" alt="">
                                <div class="product-info">
                                    <h4>Card 2</h4>
                                    <p>Card</p>
                                    <div class="product-links">
                                        <a href="/guest/img/product/product-4.jpg" data-gallery="productGallery"
                                            class="portfolio-lightbox" title="Card 2"><i class="bi bi-plus"></i></a>
                                        <a href="product-details.html" title="More Details"><i
                                                class="bi bi-link"></i></a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-4 col-md-6 product-item filter-web">
                            <div class="product-wrap">
                                <img src="/guest/img/product/product-5.jpg" class="img-fluid" alt="">
                                <div class="product-info">
                                    <h4>Web 2</h4>
                                    <p>Web</p>
                                    <div class="product-links">
                                        <a href="/guest/img/product/product-5.jpg" data-gallery="productGallery"
                                            class="portfolio-lightbox" title="Web 2"><i class="bi bi-plus"></i></a>
                                        <a href="product-details.html" title="More Details"><i
                                                class="bi bi-link"></i></a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-4 col-md-6 product-item filter-app">
                            <div class="product-wrap">
                                <img src="/guest/img/product/product-6.jpg" class="img-fluid" alt="">
                                <div class="product-info">
                                    <h4>App 3</h4>
                                    <p>App</p>
                                    <div class="product-links">
                                        <a href="/guest/img/product/product-6.jpg" data-gallery="productGallery"
                                            class="portfolio-lightbox" title="App 3"><i class="bi bi-plus"></i></a>
                                        <a href="product-details.html" title="More Details"><i
                                                class="bi bi-link"></i></a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-4 col-md-6 product-item filter-card">
                            <div class="product-wrap">
                                <img src="/guest/img/product/product-7.jpg" class="img-fluid" alt="">
                                <div class="product-info">
                                    <h4>Card 1</h4>
                                    <p>Card</p>
                                    <div class="product-links">
                                        <a href="/guest/img/product/product-7.jpg" data-gallery="productGallery"
                                            class="portfolio-lightbox" title="Card 1"><i class="bi bi-plus"></i></a>
                                        <a href="product-details.html" title="More Details"><i
                                                class="bi bi-link"></i></a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-4 col-md-6 product-item filter-card">
                            <div class="product-wrap">
                                <img src="/guest/img/product/product-8.jpg" class="img-fluid" alt="">
                                <div class="product-info">
                                    <h4>Card 3</h4>
                                    <p>Card</p>
                                    <div class="product-links">
                                        <a href="/guest/img/product/product-8.jpg" data-gallery="productGallery"
                                            class="portfolio-lightbox" title="Card 3"><i class="bi bi-plus"></i></a>
                                        <a href="product-details.html" title="More Details"><i
                                                class="bi bi-link"></i></a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-4 col-md-6 product-item filter-web">
                            <div class="product-wrap">
                                <img src="/guest/img/product/product-9.jpg" class="img-fluid" alt="">
                                <div class="product-info">
                                    <h4>Web 3</h4>
                                    <p>Web</p>
                                    <div class="product-links">
                                        <a href="/guest/img/product/product-9.jpg" data-gallery="productGallery"
                                            class="portfolio-lightbox" title="Web 3"><i class="bi bi-plus"></i></a>
                                        <a href="product-details.html" title="More Details"><i
                                                class="bi bi-link"></i></a>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>

                </div>

            </section><!-- End Product Section -->

            <!-- ======= Testimonials Section ======= -->
            <section id="testimonials" class="testimonials">

                <div class="container" data-aos="fade-up">

                    <header class="section-header">
                        <h2>Testimonials</h2>
                        <p>What they are saying about us</p>
                    </header>

                    <div class="testimonials-slider swiper" data-aos="fade-up" data-aos-delay="200">
                        <div class="swiper-wrapper">

                            <div class="swiper-slide">
                                <div class="testimonial-item">
                                    <div class="stars">
                                        <i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i
                                            class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i
                                            class="bi bi-star-fill"></i>
                                    </div>
                                    <p>
                                        Proin iaculis purus consequat sem cure digni ssim donec porttitora entum
                                        suscipit rhoncus. Accusantium quam, ultricies eget id, aliquam eget nibh et.
                                        Maecen aliquam, risus at semper.
                                    </p>
                                    <div class="profile mt-auto">
                                        <img src="/guest/img/testimonials/testimonials-1.jpg" class="testimonial-img"
                                            alt="">
                                        <h3>Saul Goodman</h3>
                                        <h4>Ceo &amp; Founder</h4>
                                    </div>
                                </div>
                            </div><!-- End testimonial item -->

                            <div class="swiper-slide">
                                <div class="testimonial-item">
                                    <div class="stars">
                                        <i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i
                                            class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i
                                            class="bi bi-star-fill"></i>
                                    </div>
                                    <p>
                                        Export tempor illum tamen malis malis eram quae irure esse labore quem cillum
                                        quid cillum eram malis quorum velit fore eram velit sunt aliqua noster fugiat
                                        irure amet legam anim culpa.
                                    </p>
                                    <div class="profile mt-auto">
                                        <img src="/guest/img/testimonials/testimonials-2.jpg" class="testimonial-img"
                                            alt="">
                                        <h3>Sara Wilsson</h3>
                                        <h4>Designer</h4>
                                    </div>
                                </div>
                            </div><!-- End testimonial item -->

                            <div class="swiper-slide">
                                <div class="testimonial-item">
                                    <div class="stars">
                                        <i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i
                                            class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i
                                            class="bi bi-star-fill"></i>
                                    </div>
                                    <p>
                                        Enim nisi quem export duis labore cillum quae magna enim sint quorum nulla quem
                                        veniam duis minim tempor labore quem eram duis noster aute amet eram fore quis
                                        sint minim.
                                    </p>
                                    <div class="profile mt-auto">
                                        <img src="/guest/img/testimonials/testimonials-3.jpg" class="testimonial-img"
                                            alt="">
                                        <h3>Jena Karlis</h3>
                                        <h4>Store Owner</h4>
                                    </div>
                                </div>
                            </div><!-- End testimonial item -->

                            <div class="swiper-slide">
                                <div class="testimonial-item">
                                    <div class="stars">
                                        <i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i
                                            class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i
                                            class="bi bi-star-fill"></i>
                                    </div>
                                    <p>
                                        Fugiat enim eram quae cillum dolore dolor amet nulla culpa multos export minim
                                        fugiat minim velit minim dolor enim duis veniam ipsum anim magna sunt elit fore
                                        quem dolore labore illum veniam.
                                    </p>
                                    <div class="profile mt-auto">
                                        <img src="/guest/img/testimonials/testimonials-4.jpg" class="testimonial-img"
                                            alt="">
                                        <h3>Matt Brandon</h3>
                                        <h4>Freelancer</h4>
                                    </div>
                                </div>
                            </div><!-- End testimonial item -->

                            <div class="swiper-slide">
                                <div class="testimonial-item">
                                    <div class="stars">
                                        <i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i
                                            class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i
                                            class="bi bi-star-fill"></i>
                                    </div>
                                    <p>
                                        Quis quorum aliqua sint quem legam fore sunt eram irure aliqua veniam tempor
                                        noster veniam enim culpa labore duis sunt culpa nulla illum cillum fugiat legam
                                        esse veniam culpa fore nisi cillum quid.
                                    </p>
                                    <div class="profile mt-auto">
                                        <img src="/guest/img/testimonials/testimonials-5.jpg" class="testimonial-img"
                                            alt="">
                                        <h3>John Larson</h3>
                                        <h4>Entrepreneur</h4>
                                    </div>
                                </div>
                            </div><!-- End testimonial item -->

                        </div>
                        <div class="swiper-pagination"></div>
                    </div>

                </div>

            </section><!-- End Testimonials Section -->

            <!-- ======= Team Section ======= -->
            <section id="team" class="team">

                <div class="container" data-aos="fade-up">

                    <header class="section-header">
                        <h2>Team</h2>
                        <p>Our hard working team</p>
                    </header>

                    <div class="row gy-4">

                        <div class="col-lg-3 col-md-6 d-flex align-items-stretch" data-aos="fade-up"
                            data-aos-delay="100">
                            <div class="member">
                                <div class="member-img">
                                    <img src="/guest/img/team/team-1.jpg" class="img-fluid" alt="">
                                    <div class="social">
                                        <a href=""><i class="bi bi-twitter"></i></a>
                                        <a href=""><i class="bi bi-facebook"></i></a>
                                        <a href=""><i class="bi bi-instagram"></i></a>
                                        <a href=""><i class="bi bi-linkedin"></i></a>
                                    </div>
                                </div>
                                <div class="member-info">
                                    <h4>Walter White</h4>
                                    <span>Chief Executive Officer</span>
                                    <p>Velit aut quia fugit et et. Dolorum ea voluptate vel tempore tenetur ipsa quae
                                        aut. Ipsum exercitationem iure minima enim corporis et voluptate.</p>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-3 col-md-6 d-flex align-items-stretch" data-aos="fade-up"
                            data-aos-delay="200">
                            <div class="member">
                                <div class="member-img">
                                    <img src="/guest/img/team/team-2.jpg" class="img-fluid" alt="">
                                    <div class="social">
                                        <a href=""><i class="bi bi-twitter"></i></a>
                                        <a href=""><i class="bi bi-facebook"></i></a>
                                        <a href=""><i class="bi bi-instagram"></i></a>
                                        <a href=""><i class="bi bi-linkedin"></i></a>
                                    </div>
                                </div>
                                <div class="member-info">
                                    <h4>Sarah Jhonson</h4>
                                    <span>Product Manager</span>
                                    <p>Quo esse repellendus quia id. Est eum et accusantium pariatur fugit nihil minima
                                        suscipit corporis. Voluptate sed quas reiciendis animi neque sapiente.</p>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-3 col-md-6 d-flex align-items-stretch" data-aos="fade-up"
                            data-aos-delay="300">
                            <div class="member">
                                <div class="member-img">
                                    <img src="/guest/img/team/team-3.jpg" class="img-fluid" alt="">
                                    <div class="social">
                                        <a href=""><i class="bi bi-twitter"></i></a>
                                        <a href=""><i class="bi bi-facebook"></i></a>
                                        <a href=""><i class="bi bi-instagram"></i></a>
                                        <a href=""><i class="bi bi-linkedin"></i></a>
                                    </div>
                                </div>
                                <div class="member-info">
                                    <h4>William Anderson</h4>
                                    <span>CTO</span>
                                    <p>Vero omnis enim consequatur. Voluptas consectetur unde qui molestiae deserunt.
                                        Voluptates enim aut architecto porro aspernatur molestiae modi.</p>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-3 col-md-6 d-flex align-items-stretch" data-aos="fade-up"
                            data-aos-delay="400">
                            <div class="member">
                                <div class="member-img">
                                    <img src="/guest/img/team/team-4.jpg" class="img-fluid" alt="">
                                    <div class="social">
                                        <a href=""><i class="bi bi-twitter"></i></a>
                                        <a href=""><i class="bi bi-facebook"></i></a>
                                        <a href=""><i class="bi bi-instagram"></i></a>
                                        <a href=""><i class="bi bi-linkedin"></i></a>
                                    </div>
                                </div>
                                <div class="member-info">
                                    <h4>Amanda Jepson</h4>
                                    <span>Accountant</span>
                                    <p>Rerum voluptate non adipisci animi distinctio et deserunt amet voluptas. Quia aut
                                        aliquid doloremque ut possimus ipsum officia.</p>
                                </div>
                            </div>
                        </div>

                    </div>

                </div>

            </section><!-- End Team Section -->

            <!-- ======= Contact Section ======= -->
            <section id="checkout" class="checkout">

                <div class="container" data-aos="fade-up">

                    <header class="section-header">
                        <h2>Contact</h2>
                        <p>Contact Us</p>
                    </header>

                    <div class="row gy-4">

                        <div class="col-lg-6">

                            <div class="row gy-4">
                                <div class="col-md-6">
                                    <div class="info-box">
                                        <i class="bi bi-geo-alt"></i>
                                        <h3>Address</h3>
                                        <p>A108 Adam Street,<br>New York, NY 535022</p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="info-box">
                                        <i class="bi bi-telephone"></i>
                                        <h3>Call Us</h3>
                                        <p>+1 5589 55488 55<br>+1 6678 254445 41</p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="info-box">
                                        <i class="bi bi-envelope"></i>
                                        <h3>Email Us</h3>
                                        <p>info@example.com<br>checkout@example.com</p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="info-box">
                                        <i class="bi bi-clock"></i>
                                        <h3>Open Hours</h3>
                                        <p>Monday - Friday<br>9:00AM - 05:00PM</p>
                                    </div>
                                </div>
                            </div>

                        </div>

                        <div class="col-lg-6">
                            <form action="forms/checkout.php" method="post" class="php-email-form">
                                <div class="row gy-4">

                                    <div class="col-md-6">
                                        <input type="text" name="name" class="form-control" placeholder="Your Name"
                                            required>
                                    </div>

                                    <div class="col-md-6 ">
                                        <input type="email" class="form-control" name="email" placeholder="Your Email"
                                            required>
                                    </div>

                                    <div class="col-md-12">
                                        <input type="text" class="form-control" name="subject" placeholder="Subject"
                                            required>
                                    </div>

                                    <div class="col-md-12">
                                        <textarea class="form-control" name="message" rows="6" placeholder="Message"
                                            required></textarea>
                                    </div>

                                    <div class="col-md-12 text-center">
                                        <div class="loading">Loading</div>
                                        <div class="error-message"></div>
                                        <div class="sent-message">Your message has been sent. Thank you!</div>

                                        <button type="submit">Send Message</button>
                                    </div>

                                </div>
                            </form>

                        </div>

                    </div>

                </div>

            </section><!-- End Contact Section -->

        </main><!-- End #main -->

        <!-- ======= Footer ======= -->
        <footer id="footer" class="footer">

            <div class="footer-top">
                <div class="container">
                    <div class="row gy-4">
                        <div class="col-lg-5 col-md-12 footer-info">
                            <a href="index.html" class="logo d-flex align-items-center">
                                <img src="/guest/img/logo.png" alt="">
                                <span>FlexStart</span>
                            </a>
                            <p>Cras fermentum odio eu feugiat lide par naso tierra. Justo eget nada terra videa magna
                                derita valies darta donna mare fermentum iaculis eu non diam phasellus.</p>
                            <div class="social-links mt-3">
                                <a href="#" class="twitter"><i class="bi bi-twitter"></i></a>
                                <a href="#" class="facebook"><i class="bi bi-facebook"></i></a>
                                <a href="#" class="instagram"><i class="bi bi-instagram"></i></a>
                                <a href="#" class="linkedin"><i class="bi bi-linkedin"></i></a>
                            </div>
                        </div>

                        <div class="col-lg-2 col-6 footer-links">
                            <h4>Useful Links</h4>
                            <ul>
                                <li><i class="bi bi-chevron-right"></i> <a href="#">Home</a></li>
                                <li><i class="bi bi-chevron-right"></i> <a href="#">About us</a></li>
                                <li><i class="bi bi-chevron-right"></i> <a href="#">Services</a></li>
                                <li><i class="bi bi-chevron-right"></i> <a href="#">Terms of service</a></li>
                                <li><i class="bi bi-chevron-right"></i> <a href="#">Privacy policy</a></li>
                            </ul>
                        </div>

                        <div class="col-lg-2 col-6 footer-links">
                            <h4>Our Services</h4>
                            <ul>
                                <li><i class="bi bi-chevron-right"></i> <a href="#">Web Design</a></li>
                                <li><i class="bi bi-chevron-right"></i> <a href="#">Web Development</a></li>
                                <li><i class="bi bi-chevron-right"></i> <a href="#">Product Management</a></li>
                                <li><i class="bi bi-chevron-right"></i> <a href="#">Marketing</a></li>
                                <li><i class="bi bi-chevron-right"></i> <a href="#">Graphic Design</a></li>
                            </ul>
                        </div>

                        <div class="col-lg-3 col-md-12 footer-checkout text-center text-md-start">
                            <h4>Contact Us</h4>
                            <p>
                                A108 Adam Street <br>
                                New York, NY 535022<br>
                                United States <br><br>
                                <strong>Phone:</strong> +1 5589 55488 55<br>
                                <strong>Email:</strong> info@example.com<br>
                            </p>

                        </div>

                    </div>
                </div>
            </div>

            <div class="container">
                <div class="copyright">
                    &copy; Copyright <strong><span>Kelompok 1</span></strong>. All Rights Reserved
                </div>
                <div class="credits">
                    <!-- All the links in the footer should remain intact. -->
                    <!-- You can delete the links only if you purchased the pro version. -->
                    <!-- Licensing information: https://bootstrapmade.com/license/ -->
                    <!-- Purchase the pro version with working PHP/AJAX checkout form: https://bootstrapmade.com/flexstart-bootstrap-startup-template/ -->
                    Designed by <a href="https://bootstrapmade.com/">BootstrapMade</a>
                </div>
            </div>
        </footer><!-- End Footer -->

        <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i
                class="bi bi-arrow-up-short"></i></a>
    </div>
</template>

<route lang="yaml">
    meta:
        layout: guest
</route>
