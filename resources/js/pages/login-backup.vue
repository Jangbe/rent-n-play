<template>
    <div style="background: url(/guest/img/background.png); background-size: cover; height: 100vh;">
        <div class="container d-flex justify-content-center align-items-center" style="height: 100vh">
            <div class="w-50 h-50 d-flex" style="border-radius: 25px; background-color: #031c32;">
                <div style="background-color: white; height: 100%; border-radius: 25px;">
                    <h2 style="color: #031c32; font-weight: bold; text-align: center; padding: 25px 0;">MASUK</h2>
                    <form style="padding: 0 20px;">
                        <div class="form-group">
                            <label for="email" class="form-label px-3 m-0">Masukan Email</label>
                            <input type="text" id="email" class="form-control" placeholder="Email" style="background-color: #00000020; border-radius: 20px;">
                        </div>
                        <div class="form-group">
                            <label for="password" class="form-label px-3 m-0">Masukan Password</label>
                            <input type="password" id="password" class="form-control" placeholder="Password" style="background-color: #00000020; border-radius: 20px;">
                        </div>
                        <router-link class="d-block text-decoration-none w-100 py-2" style="text-align: right" to="/forgot-password">Lupa kata sandi?</router-link>
                        <button class="btn w-100" style="background-color: #031c32; color: white; font-weight: bold; border-radius: 20px">
                            Masuk
                        </button>
                    </form>
                </div>
                <div style="padding: 0 20px; color: white; align-items: center;">
                    <h1>Logo</h1>
                    <h1 class="my-0 py-0" style="font-size: 45px; font-weight: bold;">Halo,</h1>
                    <h5 class="my-0 py-0">Belum punya akun?</h5>
                    <router-link to="/" class="btn btn-light mt-3 fw-bold d-block" style="border-radius: 20px">Daftar</router-link>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped></style>

<route lang="yaml">
    meta:
        layout: auth
</route>
