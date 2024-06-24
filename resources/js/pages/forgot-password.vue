<style scoped>
.text-wrap {
    border-top-left-radius: 25px;
    border-bottom-left-radius: 25px;
}
</style>

<script setup>
import { ref } from 'vue';
import { useRouter } from 'vue-router';
import { Toast } from '../plugins/swal';

const router = useRouter();

const formData = ref({});
const loading = ref(false);
const submit = () => {
    loading.value = true;
    axios.post('auth/forgot-password', formData.value)
        .then(({ data }) => {
            Toast.fire(({ title: data.message }))
            router.push('/reset-password?email=' + formData.value.email);
        })
        .catch(({ response }) => { Toast.fire({ title: response?.data?.message, icon: 'error' }) })
        .finally(() => loading.value = false);
}
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
            <form action="#" @submit.prevent="submit" class="signin-form">
                <div class="form-group mb-3">
                    <label class="label" for="name">Email</label>
                    <input type="text" v-model="formData.email" class="form-control" placeholder="Email" required>
                </div>
                <div class="form-group">
                    <button type="submit" class="form-control btn btn-primary submit px-3" :disabled="loading">
                        <span v-if="loading" class="spinner-border spinner-border-sm" role="status"
                            aria-hidden="true"></span>
                        Kirim
                    </button>
                </div>
            </form>
        </div>
    </div>
</template>

<style scoped></style>

<route lang="yaml">
    meta:
        layout: auth
        redirectIfLoggedIn: true
</route>
