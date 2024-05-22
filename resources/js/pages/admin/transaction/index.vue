<script setup>
import { ref } from 'vue';
import { number_format } from '../../../helpers';
import { useRouter } from 'vue-router';

const router = useRouter();
const transactions = ref([]);
axios.get('transaction').then(({ data }) => {
    transactions.value = data;
})
const resolveStatus = (status) => {
    switch (status) {
        case 'pending':
            return 'warning';
        case 'paid':
            return 'success';
        case 'ongoing':
            return 'secondary';
        case 'completed':
            return 'primary';
    }
}

Echo.channel('order-placed')
    .listen('OrderPlacedEvent', ({ transaction }) => {
        transactions.value.unshift(transaction);
    })
Echo.channel('order-status-updated')
    .listen('OrderStatusUpdatedEvent', ({ transaction: data }) => {
        const index = transactions.value.findIndex(t => t.id == data.id);
        transactions.value[index] = data;
    });
</script>

<template>
    <div>
        <div class="pagetitle">
            <h1>Transaksi</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><router-link to="/customer/dashboard">Home</router-link></li>
                    <li class="breadcrumb-item active">Transaksi</li>
                </ol>
            </nav>
        </div><!-- End Page Title -->

        <section class="section checkout row">
            <h2 class="text-muted">Riwayat Transaksi</h2>
            <div class="col-md-7 col-12 mx-auto" v-for="transaction in transactions">
                <div class="card mb-2" style="cursor: pointer;"
                    @click="router.push('transaction/' + transaction.transaction_number)">
                    <div class="card-body">
                        <h5 class="card-title mb-0 pb-2">
                            <b>No Transaksi : </b> {{ transaction.transaction_number }}
                            <span :class="`badge bg-${resolveStatus(transaction.status)} text-white`">
                                {{ transaction.status }}
                            </span>
                        </h5>
                        <p class="card-text mb-0"><b>Customer : </b> {{ transaction.user.name }}</p>
                        <p class="card-text mb-0"><b>Metode Pembayaran : </b> {{ transaction.payment_method }}</p>
                        <p class="card-text mb-0"><b>Total Pembayaran : </b>
                            {{ number_format(transaction.total) }}
                        </p>
                    </div>
                </div>
            </div>
        </section>
    </div>
</template>

<route lang="yaml">
    meta:
        layout: admin
</route>
