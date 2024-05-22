<script setup>
import { ref } from 'vue';
import { number_format } from '../../../helpers';
import { useRouter } from 'vue-router';

const router = useRouter();
const transactions = ref([]);
axios.get('transaction').then(({ data }) => {
    transactions.value = data.map(d => ({ ...d, total: d.transaction_details.reduce((a, b) => a + b.total, d.delivery_fee) }));
})
Echo.channel('order-status-updated')
    .listen('OrderStatusUpdatedEvent', ({ transaction: data }) => {
        data.total = data.transaction_details.reduce((a, b) => a + b.total, data.delivery_fee);
        const index = transactions.value.findIndex(t => t.id == data.id);
        transactions.value[index] = data;
    });
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
