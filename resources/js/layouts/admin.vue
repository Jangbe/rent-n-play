<template>
    <div>
        <!-- Google Fonts -->
        <link href="https://fonts.gstatic.com" rel="preconnect">
        <link
            href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i"
            rel="stylesheet">

        <!-- Vendor CSS Files -->
        <link href="/admin/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
        <link href="/admin/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
        <link href="/admin/vendor/quill/quill.snow.css" rel="stylesheet">
        <link href="/admin/vendor/quill/quill.bubble.css" rel="stylesheet">
        <link href="/admin/vendor/remixicon/remixicon.css" rel="stylesheet">

        <!-- Template Main CSS File -->
        <link href="/admin/css/style.css" rel="stylesheet">

        <!-- ======= Header ======= -->
        <header id="header" class="header fixed-top d-flex align-items-center">

            <div class="d-flex align-items-center justify-content-between">
                <router-link to="/home" class="logo d-flex align-items-center">
                    <img src="/admin/img/logo.png" alt="">
                    <span class="d-none d-lg-block">Rent n Play</span>
                </router-link>
                <i class="bi bi-list toggle-sidebar-btn"></i>
            </div><!-- End Logo -->

            <div class="search-bar">
                <form class="search-form d-flex align-items-center" method="POST" action="#">
                    <input type="text" name="query" placeholder="Search" title="Enter search keyword">
                    <button type="submit" title="Search"><i class="bi bi-search"></i></button>
                </form>
            </div><!-- End Search Bar -->

            <nav class="header-nav ms-auto">
                <ul class="d-flex align-items-center">

                    <li class="nav-item d-block d-lg-none">
                        <a class="nav-link nav-icon search-bar-toggle " href="#">
                            <i class="bi bi-search"></i>
                        </a>
                    </li><!-- End Search Icon-->

                    <li class="nav-item dropdown">

                        <a class="nav-link nav-icon" href="#" data-bs-toggle="dropdown">
                            <i class="bi bi-bell"></i>
                            <span v-if="newNotification > 0" class="badge bg-primary badge-number">
                                {{ newNotification }}
                            </span>
                        </a><!-- End Notification Icon -->

                        <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow notifications">
                            <li class="dropdown-header">
                                Kamu punya {{ newNotification }} notifikasi baru
                                <a href="#" @click.prevent="readAllNotification">
                                    <span class="badge rounded-pill bg-primary p-2 ms-2">
                                        Tandai telah dibaca semua
                                    </span>
                                </a>
                                <a href="#" @click.prevent="readAllNotification">
                                    <span class="badge rounded-pill bg-danger p-2 ms-2">
                                        Hapus semua
                                    </span>
                                </a>
                            </li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>

                            <li v-for="notification in user?.user?.notifications ?? []"
                                :class="{ 'notification-item': true, 'unread': notification.read_at == null }"
                                style="cursor: pointer" @click="readNotification(notification)">
                                <i :class="'bi bi-exclamation-circle text-' + notification.data.icon"></i>
                                <div>
                                    <h4>{{ resolveTitle(notification.type) }}</h4>
                                    <p>{{ notification.data.message }}</p>
                                    <p>{{ notification.time ?? '' }}</p>
                                </div>
                            </li>

                        </ul><!-- End Notification Dropdown Items -->

                    </li><!-- End Notification Nav -->

                    <li class="nav-item dropdown pe-3">

                        <a class="nav-link nav-profile d-flex align-items-center pe-0" href="#"
                            data-bs-toggle="dropdown">
                            <img :src="user?.user?.avatar" alt="Profile" class="rounded-circle">
                            <span class="d-none d-md-block dropdown-toggle ps-2">{{ user?.user?.name }}</span>
                        </a><!-- End Profile Iamge Icon -->

                        <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile">
                            <li class="dropdown-header">
                                <h6>{{ user?.user?.name }}</h6>
                                <span>{{ user?.user?.role }}</span>
                            </li>

                            <li>
                                <hr class="dropdown-divider">
                            </li>

                            <li>
                                <router-link class="dropdown-item d-flex align-items-center" to="/profile">
                                    <i class="bi bi-person"></i>
                                    <span>My Profile</span>
                                </router-link>
                            </li>

                            <li>
                                <hr class="dropdown-divider">
                            </li>

                            <li>
                                <a class="dropdown-item d-flex align-items-center" @click.prevent="logout" href="#">
                                    <i class="bi bi-box-arrow-right"></i>
                                    <span>Sign Out</span>
                                </a>
                            </li>

                        </ul><!-- End Profile Dropdown Items -->
                    </li><!-- End Profile Nav -->

                </ul>
            </nav><!-- End Icons Navigation -->

        </header><!-- End Header -->

        <!-- ======= Sidebar ======= -->
        <aside id="sidebar" class="sidebar">

            <nav-admin v-if="user?.user?.role == 'Admin'" />
            <nav-customer v-else />

        </aside><!-- End Sidebar-->

        <main id="main" class="main">
            <router-view></router-view>
        </main><!-- End #main -->

        <!-- ======= Footer ======= -->
        <footer id="footer" class="footer">
            <div class="copyright">
                &copy; Copyright <strong><span>Kelompok 1</span></strong>. All Rights Reserved
            </div>
            <div class="credits">
                Designed by <a href="https://bootstrapmade.com/">BootstrapMade</a>
            </div>
        </footer><!-- End Footer -->

        <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i
                class="bi bi-arrow-up-short"></i></a>
    </div>
</template>

<script>
import { Toast } from '../plugins/swal';
import { useUserStore } from '../stores/user';
import navAdmin from '../components/nav-admin.vue';
import navCustomer from '../components/nav-customer.vue';

export default {
    data: () => ({ user: useUserStore(), interval: null, sound: new Audio('/notification.mp3') }),
    components: { navAdmin, navCustomer },
    watch: {
        '$route.path': function () { setTimeout(this.checkNavActive, 100) },
        'user.user': function (user) {
            Echo.private('App.Models.User.' + user.id)
                .notification((notification) => {
                    this.sound.play();
                    this.user.user.notifications.unshift(notification);
                })
        }
    },
    computed: {
        newNotification: function () {
            return this.user?.user?.notifications?.filter(n => n.read_at == null)?.length ?? 0;
        }
    },
    methods: {
        checkNavActive: function () {
            document.querySelectorAll('.sidebar-nav a').forEach(node => {
                node.classList.add('collapsed');
                if (node.classList.contains('active')) {
                    node.classList.remove('collapsed');
                }
            })
        },
        logout: function () {
            axios.post('auth/logout').catch(({ response }) => {
                if (response?.data) {
                    Toast.fire({ title: response.data.message, icon: 'error' });
                }
            }).finally(() => {
                localStorage.removeItem('accessToken');
                window.axios.defaults.headers.common['Authorization'] = null;
                this.$router.replace('/login');
            })
        },
        resolveTitle: (type) => {
            switch (type) {
                case 'App\\Notifications\\OrderPlaced':
                    return 'Pesanan Baru';
                default:
                    return 'Notifikasi Baru';
            }
        },
        readAllNotification: function () {
            axios.post('read-all-notification').then(({ data }) => {
                this.user.user.notifications = data;
            })
        },
        readNotification: function (notification) {
            axios.post('read-notification/' + notification.id).then(({ data }) => {
                notification.read_at = data.read_at;
                if (notification.data.url)
                    this.$router.push(notification.data.url)
            })
        }
    },
    beforeUnmount() {
        clearInterval(this.interval);
    },
    mounted() {
        this.interval = setInterval(() => {
            this.user?.user?.notifications.map(n => {
                n.time = moment(n.created_at).fromNow();
                return n;
            })
        }, 1000);
        this.checkNavActive();
        /**
         * Easy selector helper function
         */
        const select = (el, all = false) => {
            el = el.trim()
            if (all) {
                return [...document.querySelectorAll(el)]
            } else {
                return document.querySelector(el)
            }
        }

        /**
         * Easy event listener function
         */
        const on = (type, el, listener, all = false) => {
            if (all) {
                select(el, all).forEach(e => e.addEventListener(type, listener))
            } else {
                select(el, all).addEventListener(type, listener)
            }
        }

        /**
         * Easy on scroll event listener
         */
        const onscroll = (el, listener) => {
            el.addEventListener('scroll', listener)
        }

        /**
         * Sidebar toggle
         */
        if (select('.toggle-sidebar-btn')) {
            on('click', '.toggle-sidebar-btn', function (e) {
                select('body').classList.toggle('toggle-sidebar')
            })
        }

        /**
         * Search bar toggle
         */
        if (select('.search-bar-toggle')) {
            on('click', '.search-bar-toggle', function (e) {
                select('.search-bar').classList.toggle('search-bar-show')
            })
        }

        /**
         * Navbar links active state on scroll
         */
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
        window.addEventListener('load', navbarlinksActive)
        onscroll(document, navbarlinksActive)

        /**
         * Toggle .header-scrolled class to #header when page is scrolled
         */
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

        /**
         * Back to top button
         */
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

        /**
         * Initiate tooltips
         */
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        })

        /**
         * Initiate quill editors
         */
        if (select('.quill-editor-default')) {
            new Quill('.quill-editor-default', {
                theme: 'snow'
            });
        }

        if (select('.quill-editor-bubble')) {
            new Quill('.quill-editor-bubble', {
                theme: 'bubble'
            });
        }

        if (select('.quill-editor-full')) {
            new Quill(".quill-editor-full", {
                modules: {
                    toolbar: [
                        [{
                            font: []
                        }, {
                            size: []
                        }],
                        ["bold", "italic", "underline", "strike"],
                        [{
                            color: []
                        },
                        {
                            background: []
                        }
                        ],
                        [{
                            script: "super"
                        },
                        {
                            script: "sub"
                        }
                        ],
                        [{
                            list: "ordered"
                        },
                        {
                            list: "bullet"
                        },
                        {
                            indent: "-1"
                        },
                        {
                            indent: "+1"
                        }
                        ],
                        ["direction", {
                            align: []
                        }],
                        ["link", "image", "video"],
                        ["clean"]
                    ]
                },
                theme: "snow"
            });
        }

        /**
         * Initiate Bootstrap validation check
         */
        var needsValidation = document.querySelectorAll('.needs-validation')

        Array.prototype.slice.call(needsValidation)
            .forEach(function (form) {
                form.addEventListener('submit', function (event) {
                    if (!form.checkValidity()) {
                        event.preventDefault()
                        event.stopPropagation()
                    }

                    form.classList.add('was-validated')
                }, false)
            })

        /**
         * Autoresize echart charts
         */
        // const mainContainer = select('#main');
        // if (mainContainer) {
        //     setTimeout(() => {
        //         new ResizeObserver(function () {
        //             select('.echart', true).forEach(getEchart => {
        //                 echarts.getInstanceByDom(getEchart).resize();
        //             })
        //         }).observe(mainContainer);
        //     }, 200);
        // }
    }
}
</script>

<style scoped>
.unread {
    background: rgba(0, 0, 0, .1)
}
</style>
