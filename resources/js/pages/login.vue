<style scoped>
.text-wrap {
    border-top-right-radius: 25px;
    border-bottom-right-radius: 25px;
}
</style>

<script setup>
import { ref } from 'vue';
import { Toast } from '../plugins/swal';
import { useRouter } from 'vue-router';
import { useUserStore } from '../stores/user';

const user = useUserStore();
const router = useRouter();
const formData = ref({});

const submit = () => {
    axios.post('auth/login', formData.value).then(async ({ data }) => {
        localStorage.setItem('accessToken', data.access_token);
        window.axios.defaults.headers.common['Authorization'] = 'Bearer ' + data.access_token;
        await user.getUser();
        if (user.user.role == 'Admin')
            router.replace('/admin/dashboard');
        else
            router.replace('/customer/home');
    }).catch(({ response }) => {
        if (response?.data) {
            Toast.fire({ title: response.data.message, icon: 'error' });
        }
    })
}

const oAuth = (provider) => {
    const authWindow = window.open('/auth/google', '_blank', 'width=600&height=400');

    window.addEventListener('message', async function (event) {
        if (event.source == authWindow) {
            localStorage.setItem('accessToken', event.data);
            window.axios.defaults.headers.common['Authorization'] = 'Bearer ' + event.data;
            await user.getUser();
            if (user.user.role == 'Admin')
                router.replace('/admin/dashboard');
            else
                router.replace('/customer/home');
        }
    })
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
                    <h3 class="mb-4">Masuk</h3>
                </div>
                <div class="w-100">
                    <p class="social-media d-flex justify-content-end">
                        <a href="#" @click.prevent="oAuth('google')"
                            class="social-icon d-flex align-items-center justify-content-center"><span
                                class="fa fa-google"></span></a>
                    </p>
                </div>
            </div>
            <form action="#" class="signin-form" @submit.prevent="submit">
                <div class="form-group mb-3">
                    <label class="label" for="name">Email</label>
                    <input v-model="formData.email" type="email" class="form-control" placeholder="Email" required>
                </div>
                <div class="form-group mb-3">
                    <label class="label" for="password">Katasandi</label>
                    <input v-model="formData.password" type="password" class="form-control" placeholder="Katasandi"
                        required>
                </div>
                <div class="form-group">
                    <button type="submit" class="form-control btn btn-primary submit px-3">Masuk</button>
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
