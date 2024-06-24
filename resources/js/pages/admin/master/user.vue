<script setup>
import { ref, onMounted, watch } from 'vue';
import { useOnlineStore } from '../../../stores/online';
import { number_format } from '../../../helpers';
import { useRouter } from 'vue-router';

const router = useRouter();
const users = ref([]);
const fetchData = () => axios.get('master/user').then(({ data }) => {
    users.value = data;
})
fetchData();

const user = ref({});
const modal = ref(null);
onMounted(() => {
    modal.value = new bootstrap.Modal(document.querySelector('#modal-info'), {});
})
const openModal = (data) => {
    user.value = data;
    modal.value.toggle();
}
const online = useOnlineStore();
watch(() => online.users, fetchData, { deep: true })
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
const showTransaction = (transaction_number) => {
    modal.value.toggle();
    router.push('/admin/transaction/' + transaction_number);
}
</script>

<template>
    <div>
        <div class="pagetitle">
            <h1>Data Pengguna</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><router-link to="/admin/dashboard">Home</router-link></li>
                    <li class="breadcrumb-item">Master</li>
                    <li class="breadcrumb-item active">Pengguna</li>
                </ol>
            </nav>
        </div><!-- End Page Title -->

        <section class="section">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title mb-0 d-flex align-items-baseline justify-content-between">
                        Tabel Pengguna
                        <div>
                            <button @click="fetchData" class="btn btn-sm btn-info">
                                <i class="bx bx-refresh"></i>
                            </button>
                        </div>
                    </h5>
                    <DataTable class="table table-hover table-striped" :data="users" ref="refDataTable"
                        :columns="['no', 'name', 'email', 'transactions', 'status', 'action'].map(c => ({ data: c, name: c, className: 'text-center' }))">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Nama Pengguna</th>
                                <th scope="col">Email</th>
                                <th scope="col">Transaksi</th>
                                <th scope="col">Status</th>
                                <th scope="col">Aksi</th>
                            </tr>
                        </thead>
                        <template #column-0="{ rowIndex }">
                            {{ rowIndex + 1 }}
                        </template>
                        <template #column-transactions="{ rowData }">
                            <p class="text-center">{{ rowData.transactions.length }}</p>
                        </template>
                        <template #column-status="{ rowData }">
                            <span class="badge bg-success"
                                v-if="online.users.find(u => u.id == rowData.id)">Online</span>
                            <span class="badge bg-danger" v-else>Offline</span>
                        </template>
                        <template #column-action="{ rowData }">
                            <button @click="openModal(rowData)" class="btn btn-sm btn-info mx-auto">
                                <i class="bi bi-info-circle"></i>
                            </button>
                        </template>
                    </DataTable>
                </div>
            </div>
        </section>

        <div class="modal fade" tabindex="-1" id="modal-info">
            <div class="modal-dialog modal-lg">
                <form action="#" method="POST" @submit.prevent="submit">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">Info Pengguna</h4>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-5 col-12">
                                    <img :src="user.avatar" alt="" class="img-fluid d-block mx-auto"
                                        style="max-height: 100px; max-width: 100px">
                                    <table>
                                        <tr>
                                            <td><b>Nama</b></td>
                                            <td>:</td>
                                            <td>{{ user.name }}</td>
                                        </tr>
                                        <tr>
                                            <td><b>Email</b></td>
                                            <td>:</td>
                                            <td>{{ user.email }}</td>
                                        </tr>
                                    </table>
                                </div>
                                <div class="col-md-7 col-12">
                                    <h4>Riwayat Transaksi</h4>
                                    <div class="card bg-white mb-2" style="cursor: pointer;"
                                        v-for="transaction in user.transactions ?? []"
                                        @click="showTransaction(transaction.transaction_number)">
                                        <div class="card-body">
                                            <h5 class="card-title mb-0 pb-2">
                                                <b>No Transaksi : </b> {{ transaction.transaction_number }}
                                                <span
                                                    :class="`badge bg-${resolveStatus(transaction.status)} text-white`">
                                                    {{ transaction.status }}
                                                </span>
                                            </h5>
                                            <p class="card-text mb-0"><b>Metode Pembayaran : </b>
                                                {{ transaction.payment_method }}</p>
                                            <p class="card-text mb-0"><b>Total Pembayaran : </b>
                                                {{ number_format(transaction.total) }}
                                            </p>
                                        </div>
                                    </div>
                                    <div class="alert alert-warning text-center" v-if="user.transactions?.length == 0">
                                        Belum ada transaksi pada pengguna ini.
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<route lang="yaml">
    meta:
        layout: admin
</route>
