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

<script setup>
import { ref, onMounted, defineEmits } from 'vue';
import { Toast } from '../plugins/swal';
import { useUserStore } from '../stores/user';

const userStore = useUserStore();
const emit = defineEmits(['aos_init']);
const categories = ref([]);
const products = ref([]);
var productIsotope = null;
const fetchData = async () => {
    await axios.get('category').then(({ data }) => categories.value = data);
    await axios.get('product').then(({ data }) => products.value = data);
    const productLightbox = GLightbox({
        selector: '.product-lightbox'
    });
    let productContainer = document.querySelector('.product-container');
    if (productContainer) {
        setTimeout(() => {
            productIsotope = new Isotope(productContainer, {
                itemSelector: '.product-item',
                layoutMode: 'fitRows'
            });
        }, 200);
    }
}
fetchData();

const filterBy = (filter) => {
    let productFilters = document.querySelectorAll('#product-filters li');
    productFilters.forEach(function (el) {
        el.classList.remove('filter-active');
        const filterData = el.attributes['data-filter'].value;
        if (filter == '*') {
            document.querySelector('#product-filters li[data-filter="*"]').classList.add('filter-active');
        } else if (filterData == filter)
            el.classList.add('filter-active');
    });

    productIsotope.arrange({ filter });
}

const addToCart = (product) => {
    Toast.fire({ title: 'Barang berhasil ditambahkan ke keranjang', icon: 'info' });
    const carts = JSON.parse(localStorage.getItem('carts') ?? '[]');
    const index = carts.findIndex(c => c.product_id == product.id);
    if (index >= 0) {
        carts[index].quantity++;
    } else {
        carts.push({ product_id: product.id, quantity: 1 });
    }
    localStorage.setItem('carts', JSON.stringify(carts));
}
</script>

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
                        <li>
                            <router-link class="nav-link" to="/customer/checkout">Checkout</router-link>
                        </li>
                        <li v-if="userStore.user == null">
                            <router-link class="getstarted" to="login">Login</router-link>
                        </li>
                        <li v-else>
                        <router-link class="getstarted"
                            :to="userStore.user.role == 'Admin' ? '/admin/dashboard' : '/customer/home'">Dashboard</router-link>
                        </li>
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
                                <li @click="filterBy('*')" data-filter="*" class="filter-active">Semua</li>
                                <li v-for="category in categories" :data-filter="'.id-' + category.id"
                                    @click="filterBy('.id-' + category.id)">
                                    {{ category.name }}
                                </li>
                            </ul>
                        </div>
                    </div>

                    <div class="row gy-4 product-container" data-aos="fade-up" data-aos-delay="200">

                        <div v-for="product in products"
                            :class="['col-lg-4 col-md-6 product-item', 'id-' + product.category_id]">
                            <div class="product-wrap">
                                <img :src="'/storage/' + product.picture" class="img-fluid" alt="">
                                <div class="product-info">
                                    <h4>{{ product.name }}</h4>
                                    <p>{{ product.category.name }}</p>
                                    <div class="product-links">
                                        <a :href="'/storage/' + product.picture" data-gallery="productGallery"
                                            class="product-lightbox" :title="product.description"><i
                                                class="bi bi-plus"></i></a>
                                        <router-link :to="'/product/' + product.id" title="Lihat Detail">
                                            <i class="bi bi-link"></i>
                                        </router-link>
                                        <a href="#" @click.prevent="addToCart(product)" title="More Details">
                                            <i class="bi bi-cart"></i>
                                        </a>
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

                        <div class="col-lg-4 col-md-6 d-flex align-items-stretch" data-aos="fade-up"
                            data-aos-delay="100">
                            <div class="member">
                                <div class="member-img">
                                    <img src="/guest/img/team/devi-mulyana.jpg" class="img-fluid" alt="">
                                    <div class="social">
                                        <a href=""><i class="bi bi-twitter"></i></a>
                                        <a href=""><i class="bi bi-facebook"></i></a>
                                        <a href=""><i class="bi bi-instagram"></i></a>
                                        <a href=""><i class="bi bi-linkedin"></i></a>
                                    </div>
                                </div>
                                <div class="member-info">
                                    <h4>Devi Mulyana</h4>
                                    <span>1227050035</span>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-4 col-md-6 d-flex align-items-stretch" data-aos="fade-up"
                            data-aos-delay="200">
                            <div class="member">
                                <div class="member-img">
                                    <img src="/guest/img/team/alya.jpg" class="img-fluid" alt="">
                                    <div class="social">
                                        <a href=""><i class="bi bi-twitter"></i></a>
                                        <a href=""><i class="bi bi-facebook"></i></a>
                                        <a href=""><i class="bi bi-instagram"></i></a>
                                        <a href=""><i class="bi bi-linkedin"></i></a>
                                    </div>
                                </div>
                                <div class="member-info">
                                    <h4>Alya Nurul Lathifah</h4>
                                    <span>1227050018</span>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-4 col-md-6 d-flex align-items-stretch" data-aos="fade-up"
                            data-aos-delay="300">
                            <div class="member">
                                <div class="member-img">
                                    <img src="/guest/img/team/alfin.jpg" class="img-fluid" alt="">
                                    <div class="social">
                                        <a href=""><i class="bi bi-twitter"></i></a>
                                        <a href=""><i class="bi bi-facebook"></i></a>
                                        <a href=""><i class="bi bi-instagram"></i></a>
                                        <a href=""><i class="bi bi-linkedin"></i></a>
                                    </div>
                                </div>
                                <div class="member-info">
                                    <h4>Alfin Nurul Yamin</h4>
                                    <span>1227050014</span>
                                </div>
                            </div>
                        </div>

                    </div>

                    <div class="row gy-4 mt-4">

                        <div class="col-lg-4 col-md-6 d-flex align-items-stretch" data-aos="fade-up"
                            data-aos-delay="100">
                            <div class="member">
                                <div class="member-img">
                                    <img src="/guest/img/team/dika-haekal.jpg" class="img-fluid" alt="">
                                    <div class="social">
                                        <a href=""><i class="bi bi-twitter"></i></a>
                                        <a href=""><i class="bi bi-facebook"></i></a>
                                        <a href=""><i class="bi bi-instagram"></i></a>
                                        <a href=""><i class="bi bi-linkedin"></i></a>
                                    </div>
                                </div>
                                <div class="member-info">
                                    <h4>Dika Haekal Firza Pratama</h4>
                                    <span>1227050036</span>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-4 col-md-6 d-flex align-items-stretch" data-aos="fade-up"
                            data-aos-delay="300">
                            <div class="member">
                                <div class="member-img">
                                    <img src="/guest/img/team/amanda.jpg" class="img-fluid" alt="">
                                    <div class="social">
                                        <a href=""><i class="bi bi-twitter"></i></a>
                                        <a href=""><i class="bi bi-facebook"></i></a>
                                        <a href=""><i class="bi bi-instagram"></i></a>
                                        <a href=""><i class="bi bi-linkedin"></i></a>
                                    </div>
                                </div>
                                <div class="member-info">
                                    <h4>Amanda Zulfa Salsabila</h4>
                                    <span>1227050019</span>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-4 col-md-6 d-flex align-items-stretch" data-aos="fade-up"
                            data-aos-delay="200">
                            <div class="member">
                                <div class="member-img">
                                    <img src="/guest/img/team/aghiz.jpg" class="img-fluid" alt="">
                                    <div class="social">
                                        <a href=""><i class="bi bi-twitter"></i></a>
                                        <a href=""><i class="bi bi-facebook"></i></a>
                                        <a href=""><i class="bi bi-instagram"></i></a>
                                        <a href=""><i class="bi bi-linkedin"></i></a>
                                    </div>
                                </div>
                                <div class="member-info">
                                    <h4>Aghiz Ghifari</h4>
                                    <span>1227050008</span>
                                </div>
                            </div>
                        </div>

                    </div>

                </div>

            </section><!-- End Team Section -->

            <!-- ======= Contact Section ======= -->
            <section id="checkout" class="checkout">

                <div class="container" data-aos="fade-up">

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
