<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Rent N Play</title>
    {{-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous"> --}}
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

    <!-- Template Main JS File -->
    {{-- <script src="/guest/js/main.js"></script> --}}
    @vite(['resources/js/app.js'])
</body>

</html>
