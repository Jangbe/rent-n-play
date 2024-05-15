<template>
    <ul class="sidebar-nav" id="sidebar-nav">

        <li class="nav-item">
            <router-link active-class="active" class="nav-link" to="/admin/dashboard">
                <i class="bi bi-grid"></i>
                <span>Dashboard</span>
            </router-link>
        </li><!-- End Dashboard Nav -->

        <li class="nav-item">
            <a :class="{ 'nav-link': true, 'active': $route.path.startsWith('/admin/master') }"
                data-bs-target="#components-nav" data-bs-toggle="collapse" href="#">
                <i class="bi bi-menu-button-wide"></i><span>Data Master</span><i class="bi bi-chevron-down ms-auto"></i>
            </a>
            <ul id="components-nav"
                :class="{ 'nav-content': true, 'collapse': !$route.path.startsWith('/admin/master') }"
                data-bs-parent="#sidebar-nav">
                <li>
                    <router-link active-class="active" to="/admin/master/category">
                        <i class="bi bi-circle"></i><span>Kategori</span>
                    </router-link>
                </li>
                <li>
                    <router-link active-class="active" to="/admin/master/product">
                        <i class="bi bi-circle"></i><span>Barang</span>
                    </router-link>
                </li>
                <li>
                    <router-link active-class="active" to="/admin/master/user">
                        <i class="bi bi-circle"></i><span>Pengguna</span>
                    </router-link>
                </li>
            </ul>
        </li><!-- End Master Nav -->
    </ul>
</template>
