<script setup>
import { ref, onMounted, defineEmits } from 'vue';
import { Toast } from '../plugins/swal';
import { useUserStore } from '../stores/user';
import { number_format } from '../helpers';
import { Pagination, Autoplay } from 'swiper/modules';
import { Swiper, SwiperSlide } from 'swiper/vue';
import 'swiper/css';
import 'swiper/css/navigation';
import 'swiper/css/pagination';
import 'swiper/css/scrollbar';

const userStore = useUserStore();
const emit = defineEmits(['aos_init']);
const categories = ref([]);
const products = ref([]);
var productIsotope = null;
const fetchData = async () => {
    await axios.get('category').then(({ data }) => categories.value = data);
    await axios.get('popular-product?all=true').then(({ data }) => products.value = data);
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
const testimonials = ref([]);
const swiperTestimonial = {
    modules: [Pagination, Autoplay],
    speed: 600,
    loop: true,
    autoplay: { delay: 5000, disableOnInteraction: false },
    slidesPerView: 'auto',
    pagination: { type: 'bullets', clickable: true },
    breakpoints: {
        320: { slidesPerView: 1, spaceBetween: 40 },
        1200: { slidesPerView: 3, }
    }
}
axios.get('testimonial').then(({ data }) => testimonials.value = data);

const teams = [
    { nim: '1227050035', avatar: 'devi-mulyana', name: 'Devi Mulyana' },
    { nim: '1227050018', avatar: 'alya', name: 'Alya Nurul Lathifah' },
    { nim: '1227050014', avatar: 'alfin', name: 'Alfin Nurul Yamin' },
    { nim: '1227050036', avatar: 'dika-haekal', name: 'Dika Haekal Firza Pratama' },
    { nim: '1227050019', avatar: 'amanda', name: 'Amanda Zulfa Salsabila' },
    { nim: '1227050008', avatar: 'aghiz', name: 'Aghiz Ghifari' },
];
</script>

<template>
    <div>
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
                        <p>Produk terpopuler kami</p>
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
                                <img :src="product.picture" class="img-fluid" alt="">
                                <div class="product-info">
                                    <h4>{{ product.name }}</h4>
                                    <p>{{ number_format(product.price) }}</p>
                                    <p>{{ product.category.name }}</p>
                                    <div class="product-links">
                                        <a :href="product.picture" data-gallery="productGallery"
                                            class="product-lightbox" :title="product.description"><i
                                                class="bi bi-info"></i></a>
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
                        <h2>Testimoni</h2>
                        <p>Apa yang mereka katakan tentang kami</p>
                    </header>

                    <swiper class="testimonials-slider" v-bind="swiperTestimonial">

                        <swiper-slide v-for="testimonial in testimonials">
                            <div class="testimonial-item">
                                <div class="stars">
                                    <star-rating :inline="true" :star-size="20" :increment=".5" :read-only="true"
                                        :show-rating="false" :rating="testimonial.rating" />
                                </div>
                                <p>{{ testimonial.comment }}</p>
                                <div class="profile mt-auto">
                                    <img :src="testimonial.user?.avatar" class="testimonial-img" alt="">
                                    <h3>{{ testimonial.user?.name }}</h3>
                                </div>
                            </div>
                        </swiper-slide>

                    </swiper>

                </div>

            </section><!-- End Testimonials Section -->

            <!-- ======= Team Section ======= -->
            <section id="team" class="team">

                <div class="container" data-aos="fade-up">

                    <header class="section-header">
                        <h2 class="text-black">Team</h2>
                        <p class="text-dark">Kelompok 1</p>
                    </header>

                    <div class="row gy-4">

                        <div class="col-lg-4 col-md-6 d-flex align-items-stretch" data-aos="fade-up"
                            data-aos-delay="100" v-for="team in teams">
                            <div class="member">
                                <div class="member-img">
                                    <img :src="`/guest/img/team/${team.avatar}.jpg`" class="img-fluid" alt="">
                                    <div class="social">
                                        <a href=""><i class="bi bi-twitter"></i></a>
                                        <a href=""><i class="bi bi-facebook"></i></a>
                                        <a href=""><i class="bi bi-instagram"></i></a>
                                        <a href=""><i class="bi bi-linkedin"></i></a>
                                    </div>
                                </div>
                                <div class="member-info">
                                    <h4>{{ team.name }}</h4>
                                    <span>{{ team.nim }}</span>
                                </div>
                            </div>
                        </div>

                    </div>

                </div>

            </section><!-- End Team Section -->

        </main><!-- End #main -->
    </div>
</template>

<route lang="yaml">
    meta:
        layout: guest
</route>
