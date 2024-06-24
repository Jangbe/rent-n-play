<template>
    <div>
        <!-- Google Fonts -->
        <link href="https://fonts.gstatic.com" rel="preconnect">
        <link
            href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i"
            rel="stylesheet">

        <!-- Vendor CSS Files -->
        <link href="/admin/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
        <link href="/admin/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
        <link href="/admin/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
        <link href="/admin/vendor/remixicon/remixicon.css" rel="stylesheet">
        <link href="/admin/vendor/simple-datatables/style.css" rel="stylesheet">

        <!-- Template Main CSS File -->
        <link href="/admin/css/style.css" rel="stylesheet">

        <main>
            <div class="container">

                <section
                    class="section error-404 min-vh-100 d-flex flex-column align-items-center justify-content-center">
                    <h1>404</h1>
                    <h2>Halaman yang kamu cari tidak ada.</h2>
                    <router-link class="btn" to="/home">Kembali ke halaman utama</router-link>
                    <img src="/admin/img/not-found.svg" class="img-fluid py-5" alt="Page Not Found">
                    <div class="credits">
                        Designed by <a href="https://bootstrapmade.com/">BootstrapMade</a>
                    </div>
                </section>

            </div>
        </main><!-- End #main -->
    </div>
</template>
