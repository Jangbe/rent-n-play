<template>
    <ul class="sidebar-nav" id="sidebar-nav">

        <li class="nav-item">
            <router-link active-class="active" class="nav-link" to="/customer/dashboard">
                <i class="bi bi-grid"></i>
                <span>Dashboard</span>
            </router-link>
        </li><!-- End Dashboard Nav -->

        <li class="nav-item">
            <router-link active-class="active" class="nav-link" to="/customer/checkout">
                <i class="bi bi-cart"></i>
                <span>Checkout</span>
            </router-link>
        </li><!-- End Checkout Nav -->

        <li class="nav-item">
            <router-link active-class="active" class="nav-link" to="/customer/transaction">
                <i class="bi bi-cash"></i>
                <span>Transaksi</span>
            </router-link>
        </li><!-- End Transaction Nav -->

    </ul>
</template>
