<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Rent N Play</title>
    @vite(['resources/css/app.css', 'resources/sass/app.scss'])
</head>

<body>
    <div id="app"></div>

    <!-- Vendor JS Files -->
    <script src="/guest/vendor/purecounter/purecounter_vanilla.js"></script>
    <script src="/guest/vendor/aos/aos.js"></script>
    <script src="/guest/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="/guest/vendor/glightbox/js/glightbox.min.js"></script>
    <script src="/guest/vendor/isotope-layout/isotope.pkgd.min.js"></script>
    <script src="/guest/vendor/swiper/swiper-bundle.min.js"></script>
    <script src="/guest/vendor/php-email-form/validate.js"></script>

    <script src="/admin/vendor/apexcharts/apexcharts.min.js"></script>
    <script src="/admin/vendor/chart.js/chart.umd.js"></script>
    <script src="/admin/vendor/echarts/echarts.min.js"></script>
    <script src="/admin/vendor/quill/quill.min.js"></script>
    <script src="/admin/vendor/simple-datatables/simple-datatables.js"></script>
    <script src="/admin/vendor/tinymce/tinymce.min.js"></script>
    <script src="/admin/vendor/php-email-form/validate.js"></script>

    <!-- Template Main JS File -->
    @vite(['resources/js/app.js'])
</body>

</html>
