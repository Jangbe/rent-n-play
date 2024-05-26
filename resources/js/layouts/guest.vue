<template>
    <div>
        <!-- Google Fonts -->
        <link
            href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i"
            rel="stylesheet">

        <!-- Vendor CSS Files -->
        <link href="/guest/vendor/aos/aos.css" rel="stylesheet">
        <link href="/guest/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
        <link href="/guest/vendor/glightbox/css/glightbox.min.css" rel="stylesheet">
        <link href="/guest/vendor/remixicon/remixicon.css" rel="stylesheet">
        <link rel="stylesheet" href="/guest/css/style.css">

        <!-- ======= Header ======= -->
        <header id="header" class="header fixed-top">
            <div class="container-fluid container-xl d-flex align-items-center justify-content-between">

                <router-link to="/home" class="logo d-flex align-items-center">
                    <img src="/guest/img/logo.png" alt="">
                    <span>Rent N Play</span>
                </router-link>

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

        <router-view @aos_init="aos_init"></router-view>

        <!-- ======= Footer ======= -->
        <footer id="footer" class="footer">

            <div class="footer-top">
                <div class="container">
                    <div class="row gy-4">
                        <div class="col-lg-7 col-md-12 footer-info">
                            <router-link to="/home" class="logo d-flex align-items-center">
                                <img src="/guest/img/logo.png" alt="">
                                <span>Rent N Play</span>
                            </router-link>
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
                            <h4>Tautan Berguna</h4>
                            <ul>
                                <li><i class="bi bi-chevron-right"></i> <a class="scrollto" href="#home">Home</a></li>
                                <li><i class="bi bi-chevron-right"></i> <a class="scrollto" href="#about">Tentang Kami</a></li>
                                <li><i class="bi bi-chevron-right"></i> <a class="scrollto" href="#product">Produk</a></li>
                                <li><i class="bi bi-chevron-right"></i> <router-link to="/customer/checkout">Checkout</router-link></li>
                            </ul>
                        </div>

                        <!-- <div class="col-lg-2 col-6 footer-links">
                            <h4>Our Services</h4>
                            <ul>
                                <li><i class="bi bi-chevron-right"></i> <a href="#">Web Design</a></li>
                                <li><i class="bi bi-chevron-right"></i> <a href="#">Web Development</a></li>
                                <li><i class="bi bi-chevron-right"></i> <a href="#">Product Management</a></li>
                                <li><i class="bi bi-chevron-right"></i> <a href="#">Marketing</a></li>
                                <li><i class="bi bi-chevron-right"></i> <a href="#">Graphic Design</a></li>
                            </ul>
                        </div> -->

                        <div class="col-lg-3 col-md-12 footer-checkout text-center text-md-start">
                            <h4>Kontak Kami</h4>
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
                    Designed by <a href="https://bootstrapmade.com/">BootstrapMade</a>
                </div>
            </div>
        </footer><!-- End Footer -->

        <a href="#" class="back-to-top d-flex align-items-center justify-content-center">
            <i class="bi bi-arrow-up-short"></i></a>
    </div>
</template>

<script>
import { useUserStore } from '../stores/user';

export default {
    data: () => ({ userStore: useUserStore() }),
    methods: {
        aos_init: function () {
            AOS.init({
                duration: 1000,
                easing: "ease-in-out",
                once: true,
                mirror: false
            });
        }
    },
    mounted() {
        const select = (el, all = false) => {
            el = el.trim()
            if (all) {
                return [...document.querySelectorAll(el)]
            } else {
                return document.querySelector(el)
            }
        }

        const on = (type, el, listener, all = false) => {
            if (all) {
                select(el, all).forEach(e => e.addEventListener(type, listener))
            } else {
                select(el, all).addEventListener(type, listener)
            }
        }

        const onscroll = (el, listener) => {
            el.addEventListener('scroll', listener)
        }

        let navbarlinks = select('#navbar .scrollto', true)
        const navbarlinksActive = () => {
            let position = window.scrollY + 200
            navbarlinks.forEach(navbarlink => {
                if (!navbarlink.hash) return
                let section = select(navbarlink.hash)
                if (!section) return
                if (position >= section.offsetTop && position <= (section.offsetTop + section.offsetHeight)) {
                    navbarlink.classList.add('active')
                } else {
                    navbarlink.classList.remove('active')
                }
            })
        }
        navbarlinksActive();
        onscroll(document, navbarlinksActive)

        const scrollto = (el) => {
            let header = select('#header')
            let offset = header.offsetHeight

            if (!header.classList.contains('header-scrolled')) {
                offset -= 10
            }

            let elementPos = select(el).offsetTop
            window.scrollTo({
                top: elementPos - offset,
                behavior: 'smooth'
            })
        }

        let selectHeader = select('#header')
        if (selectHeader) {
            const headerScrolled = () => {
                if (window.scrollY > 100) {
                    selectHeader.classList.add('header-scrolled')
                } else {
                    selectHeader.classList.remove('header-scrolled')
                }
            }
            window.addEventListener('load', headerScrolled)
            onscroll(document, headerScrolled)
        }

        let backtotop = select('.back-to-top')
        if (backtotop) {
            const toggleBacktotop = () => {
                if (window.scrollY > 100) {
                    backtotop.classList.add('active')
                } else {
                    backtotop.classList.remove('active')
                }
            }
            window.addEventListener('load', toggleBacktotop)
            onscroll(document, toggleBacktotop)
        }

        on('click', '.mobile-nav-toggle', function (e) {
            select('#navbar').classList.toggle('navbar-mobile')
            this.classList.toggle('bi-list')
            this.classList.toggle('bi-x')
        })

        on('click', '.navbar .dropdown > a', function (e) {
            if (select('#navbar').classList.contains('navbar-mobile')) {
                e.preventDefault()
                this.nextElementSibling.classList.toggle('dropdown-active')
            }
        }, true)

        on('click', '.scrollto', function (e) {
            if (select(this.hash)) {
                e.preventDefault()

                let navbar = select('#navbar')
                if (navbar.classList.contains('navbar-mobile')) {
                    navbar.classList.remove('navbar-mobile')
                    let navbarToggle = select('.mobile-nav-toggle')
                    navbarToggle.classList.toggle('bi-list')
                    navbarToggle.classList.toggle('bi-x')
                }
                scrollto(this.hash)
            }
        }, true)

        if (window.location.hash) {
            if (select(window.location.hash)) {
                scrollto(window.location.hash)
            }
        }

        this.aos_init();
    }
}
</script>