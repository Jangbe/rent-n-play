<style scoped>
.text-wrap {
    border-top-right-radius: 25px;
    border-bottom-right-radius: 25px;
}
</style>

<script setup>
import { ref } from 'vue';
import { Toast } from '../plugins/swal';
import { useRoute, useRouter } from 'vue-router';
import InputOtp from '../components/input-otp.vue';

const route = useRoute();
const router = useRouter();
const formData = ref({ email: route.query.email, token: route.query.otp });

const loading = ref(false);
const submit = () => {
    loading.value = true;
    axios.post('auth/reset-password', formData.value)
        .then(({ data }) => {
            Toast.fire({ title: data.message });
            router.replace('/login');
        })
        .catch(({ response }) => {
            if (response?.data) {
                Toast.fire({ title: response.data.message, icon: 'error' });
            }
        })
        .finally(() => loading.value = false);
}

</script>

<template>
    <div class="wrap d-md-flex">
        <div class="text-wrap p-4 p-lg-5 text-center d-flex align-items-center order-md-last">
            <div class="text w-100">
                <h2>Selamat Datang</h2>
                <p>Belum punya akun?</p>
                <router-link to="register" class="btn btn-white btn-outline-white">Daftar</router-link>
                <a href="#" @click.prevent="router.replace('/home')" class="btn btn-white btn-outline-white">Home</a>
            </div>
        </div>
        <div class="login-wrap p-4 p-lg-5">
            <div class="d-flex">
                <div class="w-100">
                    <h3 class="mb-4">Reset Katasandi</h3>
                </div>
            </div>
            <form action="#" class="signin-form" @submit.prevent="submit">
                <InputOtp v-model="formData.token" />
                <div class="form-group mb-3">
                    <label class="label" for="password">Katasandi</label>
                    <input v-model="formData.password" type="password" class="form-control" placeholder="Katasandi"
                        required>
                </div>
                <div class="form-group mb-3">
                    <label class="label" for="password">Konfirmasi Katasandi</label>
                    <input v-model="formData.password_confirmation" type="password" class="form-control"
                        placeholder="Katasandi" required>
                </div>
                <div class="form-group">
                    <button type="submit" class="form-control btn btn-primary submit px-3" :disabled="loading">
                        <span v-if="loading" class="spinner-border spinner-border-sm" role="status"
                            aria-hidden="true"></span>
                        Masuk
                    </button>
                </div>
                <div class="form-group d-md-flex">
                    <div class="w-50 text-left">
                        <a href="#" @click="router.go(-1)">Kembali</a>
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
        redirectIfLoggedIn: true
</route>
