<style scoped>
.text-wrap {
    border-top-right-radius: 25px;
    border-bottom-right-radius: 25px;
}
</style>

<template>
    <div class="wrap d-md-flex">
        <div class="text-wrap p-4 p-lg-5 text-center d-flex align-items-center order-md-last">
            <div class="text w-100">
                <h2>Selamat Datang</h2>
                <p>Belum punya akun?</p>
                <router-link to="register" class="btn btn-white btn-outline-white">Daftar</router-link>
            </div>
        </div>
        <div class="login-wrap p-4 p-lg-5">
            <div class="d-flex">
                <div class="w-100">
                    <h3 class="mb-4">Masuk</h3>
                </div>
                <div class="w-100">
                    <p class="social-media d-flex justify-content-end">
                        <a href="#" class="social-icon d-flex align-items-center justify-content-center"><span
                                class="fa fa-google"></span></a>
                    </p>
                </div>
            </div>
            <form action="#" class="signin-form">
                <div class="form-group mb-3">
                    <label class="label" for="name">Email</label>
                    <input type="text" class="form-control" placeholder="Email" required>
                </div>
                <div class="form-group mb-3">
                    <label class="label" for="password">Katasandi</label>
                    <input type="password" class="form-control" placeholder="Katasandi" required>
                </div>
                <div class="form-group">
                    <button type="submit" class="form-control btn btn-primary submit px-3">Masuk</button>
                </div>
                <div class="form-group d-md-flex">
                    <div class="w-50 text-left">
                        <label class="checkbox-wrap checkbox-primary mb-0">Ingat Saya
                            <input type="checkbox" checked>
                            <span class="checkmark"></span>
                        </label>
                    </div>
                    <div class="w-50 text-md-right">
                        <router-link to="forgot-password">Lupa Katasandi</router-link>
                    </div>
                </div>
            </form>
        </div>
    </div>
</template>

<style scoped></style>

<route lang="yaml">
    meta:
        layout: auth
</route>
