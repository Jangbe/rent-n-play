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

const formData = ref({});

const submit = () => {
    axios.post('auth/register', formData.value).then(({ data }) => {
        console.log(data);
    }).catch(response => {
        if(response?.response?.data){
            console.log(response.response.data.message);
        }
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
                        <a href="#" class="social-icon d-flex align-items-center justify-content-center"><span
                                class="fa fa-google"></span></a>
                    </p>
                </div>
            </div>
            <form action="#" @submit.prevent="submit" class="signin-form">
                <div class="form-group">
                    <label class="label" for="name">Nama</label>
                    <input type="text" v-model="formData.name" class="form-control" placeholder="Nama" required>
                </div>
                <div class="form-group">
                    <label class="label" for="email">Email</label>
                    <input type="email" v-model="formData.email" class="form-control" placeholder="Email" required>
                </div>
                <div class="form-group">
                    <label class="label" for="password">Katasandi</label>
                    <input type="password" v-model="formData.password" class="form-control" placeholder="Katasandi" required>
                </div>
                <div class="form-group">
                    <label class="label" for="password_confirmation">Konfirmasi Katasandi</label>
                    <input type="password" v-model="formData.password_confirmation" class="form-control" placeholder="Katasandi" required>
                </div>
                <div class="form-group mt-4">
                    <button type="submit" class="form-control btn btn-primary submit px-3">Daftar</button>
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
