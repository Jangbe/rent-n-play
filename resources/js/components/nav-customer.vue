<script setup>
import { useRoute } from 'vue-router';

const route = useRoute();
</script>

<template>
    <ul class="sidebar-nav" id="sidebar-nav">

        <li class="nav-item">
            <router-link active-class="active" class="nav-link" to="/customer/home">
                <i class="bi bi-house"></i>
                <span>Halaman Utama</span>
            </router-link>
        </li><!-- End Dashboard Nav -->

        <li class="nav-item">
            <router-link active-class="active" class="nav-link" to="/customer/checkout">
                <i class="bi bi-cart"></i>
                <span>Checkout</span>
            </router-link>
        </li><!-- End Checkout Nav -->

        <li class="nav-item">
            <router-link active-class="active"
                :class="{ 'nav-link': true, 'active': route.meta.navActiveLinks == 'customer.transaction' }"
                to="/customer/transaction">
                <i class="bi bi-cash"></i>
                <span>Transaksi</span>
            </router-link>
        </li><!-- End Transaction Nav -->

    </ul>
</template>
