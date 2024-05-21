<script setup>
import { ref } from 'vue';
import { useRoute } from 'vue-router';
import { number_format } from '../../../helpers';

const route = useRoute();
const transaction = ref({ total: 0, transaction_details: [] });
axios.get('transaction/' + route.params.transaction_number).then(({ data }) => {
    formData.value = { ...data };
    transaction.value = data;
    transaction.value.total = data.transaction_details.reduce((a, b) => a + b.total, data.delivery_fee);
})
const resolveStatus = (status) => {
    switch (status) {
        case 'pending':
            return 'warning';
        case 'success':
            return 'success';
        case 'finish':
            return 'primary';
    }
}
const formData = ref({});
</script>

<template>
    <div>
        <div class="pagetitle">
            <h1>Transaksi Detail</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><router-link to="/customer/dashboard">Home</router-link></li>
                    <li class="breadcrumb-item"><router-link to="/customer/transaction">Transaksi</router-link></li>
                    <li class="breadcrumb-item active">{{ route.params.transaction_number }}</li>
                </ol>
            </nav>
        </div><!-- End Page Title -->

        <section class="section transaction">
            <div class="row">
                <div class="col-md-6 col-12 order-md-2">
                    <div class="card flex-row align-items-center mb-2" v-for="cart in transaction.transaction_details">
                        <img :src="'/storage/' + cart.product.picture" :alt="cart.product.picture" class="card-img-left"
                            style="max-width: 30px; max-width: 100px;">
                        <div class="card-body pb-2">
                            <h5 class="card-title pb-0 pt-2 mb-0">{{ cart.product.name }}</h5>
                            <p class="card-text mb-0">{{ cart.product.description }}</p>
                            <p class="card-text">
                                <span class="text-muted">{{ number_format(cart.product.price) }}</span>
                                <br>
                                <span class="fw-bold">Total :
                                    {{ number_format(cart.total) }}
                                </span>
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-12 order-md-1">
                    <div class="card mb-2">
                        <div class="card-body">
                            <h5 class="card-title mb-0 pb-2">
                                <b>No Transaksi : </b> {{ transaction.transaction_number }}
                                <span :class="`badge bg-${resolveStatus(transaction.status)} text-white`">
                                    {{ transaction.status }}
                                </span>
                            </h5>
                            <p class="card-text mb-0"><b>Metode Pembayaran : </b> {{ transaction.payment_method }}</p>
                            <p class="card-text mb-0"><b>Diantar : </b> {{ transaction.delivery ? 'Ya' : 'Tidak' }}</p>
                            <p class="card-text mb-0" v-if="transaction.delivery"><b>Alamat : </b><br>
                            <table>
                                <tr>
                                    <td style="width: 150px;">Jalan</td>
                                    <td>{{ transaction.address.street_name }}</td>
                                </tr>
                                <tr>
                                    <td>Nama Kontak</td>
                                    <td>{{ transaction.address.contact_name }}</td>
                                </tr>
                                <tr>
                                    <td>Nomor Kontak</td>
                                    <td>{{ transaction.address.contact_phone }}</td>
                                </tr>
                                <tr>
                                    <td>Alamat</td>
                                    <td>({{ transaction.address.type }}) {{ transaction.address.detail }}</td>
                                </tr>
                            </table>
                            </p>
                            <p class="card-text mb-0" v-if="transaction.delivery">
                                <b>Biaya Ongkir : </b>
                                {{ number_format(transaction.delivery_fee) }}
                            </p>
                            <p class="card-text mb-0"><b>Total Pembayaran : </b>
                                {{ number_format(transaction.total) }}
                            </p>
                            <div class="form-group mt-3">
                                <div class="input-group">
                                    <select v-model="formData.status" class="form-select">
                                        <option value="pending">Tertunda</option>
                                        <option value="success">Dibayar</option>
                                        <option value="finish">Selesai</option>
                                    </select>
                                    <button class="btn btn-success">Simpan</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</template>

<route lang="yaml">
    meta:
        layout: admin
        navActiveLinks: admin.transaction
</route>
