<style scoped>
.text-wrap {
    border-top-left-radius: 25px;
    border-bottom-left-radius: 25px;
}

.form-group {
    margin-bottom: 0;
}

.label {
    margin-bottom: 0;
}
</style>

<script setup>
import { ref } from 'vue';
import { Toast } from '../plugins/swal';
import { useRouter } from 'vue-router';
import { useUserStore } from '../stores/user';
import { useOnlineStore } from '../stores/online';

const online = useOnlineStore();
const user = useUserStore();
const router = useRouter();
const formData = ref({});
const errors = ref({});

const loggedIn = async (token) => {
    localStorage.setItem('accessToken', token);
    window.axios.defaults.headers.common['Authorization'] = 'Bearer ' + token;
    window.Echo.options.auth.headers.Authorization = `Bearer ${token}`;
    await user.getUser();
    online.join();
    router.replace('/customer/home');
}

const loading = ref(false);
const submit = () => {
    loading.value = true;
    errors.value = {};
    axios.post('auth/register', formData.value)
        .then(({ data }) => loggedIn(data.access_token))
        .catch(({ response }) => {
            if (response?.data) {
                errors.value = response.data.errors;
                Toast.fire({ title: response.data.message, icon: 'error' });
            }
        })
        .finally(() => loading.value = false);
}

const oAuth = (provider) => {
    const authWindow = window.open('/auth/google', '_blank', 'width=600&height=400');

    window.addEventListener('message', function (event) {
        if (event.source == authWindow) loggedIn(event.data);
    })
}
</script>

<template>
    <div class="wrap d-md-flex">
        <div class="text-wrap p-4 p-lg-5 text-center d-flex align-items-center">
            <div class="text w-100">
                <h2>Selamat Datang</h2>
                <p>Sudah punya akun?</p>
                <router-link to="login" class="btn btn-white btn-outline-white">Masuk</router-link>
            </div>
        </div>
        <div class="login-wrap p-4 p-lg-5">
            <div class="d-flex">
                <div class="w-100">
                    <h3 class="mb-4">Daftar</h3>
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
                <div class="form-group">
                    <label class="label" for="name">Nama</label>
                    <input type="text" v-model="formData.name"
                        :class="{ 'form-control': true, 'is-invalid': errors.name }" placeholder="Nama" required>
                    <div v-if="errors.name" class="invalid-feedback">{{ errors.name[0] }}</div>
                </div>
                <div class="form-group">
                    <label class="label" for="email">Email</label>
                    <input type="email" v-model="formData.email"
                        :class="{ 'form-control': true, 'is-invalid': errors.email }" placeholder="Email" required>
                    <div v-if="errors.email" class="invalid-feedback">{{ errors.email[0] }}</div>
                </div>
                <div class="form-group">
                    <label class="label" for="password">Katasandi</label>
                    <input type="password" v-model="formData.password"
                        :class="{ 'form-control': true, 'is-invalid': errors.password }" placeholder="Katasandi"
                        required>
                    <div v-if="errors.password" class="invalid-feedback">{{ errors.password[0] }}</div>
                </div>
                <div class="form-group">
                    <label class="label" for="password_confirmation">Konfirmasi Katasandi</label>
                    <input type="password" v-model="formData.password_confirmation" class="form-control"
                        placeholder="Katasandi" required>
                </div>
                <div class="form-group mt-4">
                    <button type="submit" class="form-control btn btn-primary submit px-3" :disabled="loading">
                        <span v-if="loading" class="spinner-border spinner-border-sm" role="status"
                            aria-hidden="true"></span>
                        Daftar
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
