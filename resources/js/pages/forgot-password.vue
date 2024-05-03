<style scoped>
.text-wrap {
    border-top-left-radius: 25px;
    border-bottom-left-radius: 25px;
}
</style>

<script setup>
import { useRouter } from 'vue-router';

const router = useRouter();
</script>

<template>
    <div class="wrap d-md-flex">
        <div class="text-wrap p-4 p-lg-5 text-center d-flex align-items-center">
            <div class="text w-100">
                <h2>Selamat Datang</h2>
                <p>Masukan email kamu, nanti ada kode OTP dikirim ke email kamu.</p>
                <a href="#" @click="router.go(-1)" class="btn btn-white btn-outline-white">Kembali</a>
            </div>
        </div>
        <div class="login-wrap p-4 p-lg-5">
            <div class="d-flex">
                <div class="w-100">
                    <h3 class="mb-4">Lupa Katasandi</h3>
                </div>
            </div>
            <form action="#" class="signin-form">
                <div class="form-group mb-3">
                    <label class="label" for="name">Email</label>
                    <input type="text" class="form-control" placeholder="Email" required>
                </div>
                <div class="form-group">
                    <button type="submit" class="form-control btn btn-primary submit px-3">Kirim</button>
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
