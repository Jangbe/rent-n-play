<script setup>
import { ref } from 'vue';
import { number_format } from '../../helpers';
import { useRouter } from 'vue-router';

const router = useRouter();
const transactions = ref([]);
axios.get('transaction').then(({ data }) => {
    transactions.value = data;
    console.log(data);
})
</script>

<template>
    <div>
        <div class="pagetitle">
            <h1>Transaksi</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><router-link to="/customer/dashboard">Home</router-link></li>
                    <li class="breadcrumb-item active">transaksi</li>
                </ol>
            </nav>
        </div><!-- End Page Title -->

        <section class="section checkout">
            <h2 class="text-muted">Riwayat Transaksi</h2>
            <div class="card mb-2" v-for="transaction in transactions"
                @click="router.push('transaction/' + transaction.transaction_number)">
                <div class="card-body">
                    <h5 class="card-title mb-0 pb-2"><b>No Transaksi : </b> {{ transaction.transaction_number }}</h5>
                    <p class="card-text mb-0"><b>Metode Pembayaran : </b> {{ transaction.payment_method }}</p>
                    <p class="card-text mb-0"><b>Total Pembayaran : </b>
                        {{ number_format(transaction.transaction_details.reduce((a, b) => a + b.total,
                transaction.delivery_fee)) }}
                    </p>
                </div>
            </div>
        </section>
    </div>
</template>

<route lang="yaml">
    meta:
        layout: admin
</route>
