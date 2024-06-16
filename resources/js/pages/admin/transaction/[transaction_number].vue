<script setup>
import { ref, watch } from "vue";
import { useRoute } from "vue-router";
import { number_format } from "../../../helpers";
import { Toast, Confirm } from "../../../plugins/swal";
import { useCoorStore } from "../../../stores/coor";
import LongText from "../../../components/long-text.vue";

const route = useRoute();
const transaction = ref({ total: 0, transaction_details: [] });
const time = ref(null);
const fetchData = () =>
    axios
        .get("transaction/" + route.params.transaction_number)
        .then(({ data }) => {
            formData.value = { ...data };
            transaction.value = data;
            setTime();
        });
const setTime = () => {
    if (transaction.value.status != 'ongoing') return;
    time.value =
        moment(transaction.value.order_datetime)
            .add((transaction.value?.extra_times ?? []).filter(e => e.is_paid).reduce((a, b) => a + b.days, transaction.value.days), "day")
            // .set('hour', 23).set('minute', 59).set('second', 59)
            .toDate() - new Date();
};
watch(transaction, setTime);
fetchData();
const statusses = {
    pending: "Ditunda",
    paid: "Dibayar",
    ongoing: "Sedang Berjalan",
    completed: "Selesai"
};
const resolveStatus = status => {
    switch (status) {
        case "pending":
            return "warning";
        case "paid":
            return "success";
        case "ongoing":
            return "secondary";
        case "completed":
            return "primary";
    }
};
function initPusher() {
    Echo.channel("order-status-updated").listen(
        "OrderStatusUpdatedEvent",
        ({ transaction: data }) => {
            if (
                data.transaction_number == transaction.value.transaction_number
            ) {
                transaction.value = data;
            }
        }
    );
}
initPusher();
watch(() => route.params, initPusher);
watch(() => route.params, fetchData);
const formData = ref({});
const updateStatus = () => {
    axios
        .put("transaction/" + route.params.transaction_number, formData.value)
        .then(({ data }) => {
            Toast.fire({ title: data });
            transaction.value.status = formData.value.status;
        });
};
const center = useCoorStore();
const verifyPaid = (id) => {
    Confirm.fire({
        title: 'Apakah kamu akan mengkonfirmasi pembayaran ini?'
    }).then(({ isConfirmed }) => {
        if (isConfirmed) axios.post(`transaction/${id}/verify-paid`);
    })
}
</script>

<template>
    <div>
        <div class="pagetitle">
            <h1>Transaksi Detail</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <router-link to="/customer/home">Home</router-link>
                    </li>
                    <li class="breadcrumb-item">
                        <router-link to="/customer/transaction">Transaksi</router-link>
                    </li>
                    <li class="breadcrumb-item active">{{ route.params.transaction_number }}</li>
                </ol>
            </nav>
        </div>
        <!-- End Page Title -->

        <section class="section transaction">
            <div class="row">
                <div class="col-md-6 col-12 order-md-2">
                    <div class="card bg-white flex-row align-items-center mb-2"
                        v-for="cart in transaction.transaction_details">
                        <img :src="cart.product.picture" :alt="cart.product.picture" class="card-img-left"
                            style="max-width: 30px; max-width: 100px;" />
                        <div class="card-body pb-2">
                            <h5 class="card-title pb-0 pt-2 mb-0">{{ cart.product.name }}</h5>
                            <p class="card-text mb-0">
                                <long-text :text="cart.product.description" :length="35" />
                            </p>
                            <p class="card-text">
                                <span class="text-muted">
                                    {{ number_format(cart.product.price) }} x {{ cart.quantity }}
                                </span>
                                <br />
                                <span class="fw-bold">
                                    Total :
                                    {{ number_format(cart.total) }}
                                </span>
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-12 order-md-1">
                    <div class="card bg-white mb-2">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-baseline">
                                <h5 class="card-title mb-0 pb-2">
                                    <b>No Transaksi :</b>
                                    {{ transaction.transaction_number }}
                                </h5>
                                <span :class="`badge bg-${resolveStatus(transaction.status)} text-white`">
                                    {{ transaction.status }}</span>
                            </div>
                            <div v-if="transaction.status == 'ongoing'"
                                style="border: 1px solid black; padding: 15px; margin-bottom: 10px; border-radius: 7px;">
                                <h4 class="text-center">Sisa Waktu</h4>
                                <vue-countdown :time="time" v-slot="{ days, hours, minutes, seconds }">
                                    <div class="d-flex justify-content-center align-items-center text-center"
                                        style="gap: 10px;">
                                        <div class="box">
                                            <p>{{ ('0' + days).substr(-2, 2) }}</p>
                                            <span>Hari</span>
                                        </div>:
                                        <div class="box">
                                            <p>{{ ('0' + hours).substr(-2, 2) }}</p>
                                            <span>Jam</span>
                                        </div>:
                                        <div class="box">
                                            <p>{{ ('0' + minutes).substr(-2, 2) }}</p>
                                            <span>Menit</span>
                                        </div>:
                                        <div class="box">
                                            <p>{{ ('0' + seconds).substr(-2, 2) }}</p>
                                            <span>Detik</span>
                                        </div>
                                    </div>
                                </vue-countdown>
                            </div>
                            <table>
                                <tr>
                                    <td><b>Customer</b></td>
                                    <td>:</td>
                                    <td>{{ transaction?.user?.name }}</td>
                                </tr>
                                <tr>
                                    <td style="width: 170px;"><b>Metode Pembayaran</b></td>
                                    <td>:</td>
                                    <td>{{ transaction.payment_method }}</td>
                                </tr>
                                <tr>
                                    <td><b>Durasi Sewa</b></td>
                                    <td>:</td>
                                    <td>{{ transaction.days }} hari</td>
                                </tr>
                                <tr>
                                    <td><b>Diantar</b></td>
                                    <td>:</td>
                                    <td>{{ transaction.delivery ? 'Ya' : 'Tidak' }}</td>
                                </tr>
                                <template v-if="transaction.delivery == 1">
                                    <tr>
                                        <td>Alamat</td>
                                        <td>:</td>
                                        <td>
                                            <a :href="`https://www.google.com/maps/dir/?api=1&origin=${center.lat},${center.lng}&destination=${transaction.address?.lat},${transaction.address?.lng}&travelmode=driving`"
                                                target="_blank">
                                                ({{ transaction.address?.type }}) {{ transaction.address?.street_name }}
                                            </a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Nama Kontak</td>
                                        <td>:</td>
                                        <td>{{ transaction.address?.contact_name }}</td>
                                    </tr>
                                    <tr>
                                        <td>Nomor Kontak</td>
                                        <td>:</td>
                                        <td>{{ transaction.address?.contact_phone }}</td>
                                    </tr>
                                    <tr>
                                        <td>Detail</td>
                                        <td>:</td>
                                        <td>{{ transaction.address?.detail }}</td>
                                    </tr>
                                </template>
                                <tr>
                                    <td><b>Biaya Ongkir</b></td>
                                    <td>:</td>
                                    <td>{{ number_format(transaction.delivery_fee) }}</td>
                                </tr>
                                <tr>
                                    <td><b>Total Pembayaran</b></td>
                                    <td>:</td>
                                    <td>{{ number_format(transaction.total) }}</td>
                                </tr>
                            </table>
                            <h5 class="text-primary fw-bold text-center my-1"
                                v-if="transaction.extra_times?.length > 0">Tambahan Waktu</h5>
                            <table class="table table-striped" v-if="transaction.extra_times?.length > 0">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Hari</th>
                                        <th>Total</th>
                                        <th>Dibayar</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(et, i) in transaction.extra_times ?? []">
                                        <td>{{ i + 1 }}</td>
                                        <td>{{ et.days }}</td>
                                        <td>{{ number_format(transaction.subtotal * et.days) }}</td>
                                        <td>
                                            <button class="btn btn-success" v-if="et.is_paid" disabled>
                                                <i class="bx bx-check"></i>
                                            </button>
                                            <button class="btn btn-warning" v-else @click="verifyPaid(et.id)">
                                                <i class="bx bx-check"></i>
                                            </button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <div class="form-group mt-3">
                                <div class="input-group">
                                    <select v-model="formData.status" class="form-select">
                                        <option v-for="(status, i) in statusses" :value="i">{{ status }}</option>
                                    </select>
                                    <button class="btn btn-success" @click="updateStatus">Simpan</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</template>

<style scoped>
.box {
    border: 1.5px solid black;
    width: 60px;
    height: 85px;
    border-radius: 12px;
}

.box p {
    font-size: 34px;
    font-weight: bold;
    margin-bottom: 0;
}
</style>

<route lang="yaml">
    meta:
        layout: admin
        navActiveLinks: admin.transaction
</route>
