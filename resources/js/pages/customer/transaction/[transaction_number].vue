<script setup>
import { ref, watch } from "vue";
import { useRoute } from "vue-router";
import { number_format } from "../../../helpers";
import { Toast } from "../../../plugins/swal";
import { useCoorStore } from "../../../stores/coor";

const route = useRoute();
const transaction = ref({ total: 0, transaction_details: [] });
const time = ref(null);
axios.get("transaction/" + route.params.transaction_number).then(({ data }) => {
    transaction.value = data;
    time.value =
        moment(data.order_datetime)
            .add(1, "day")
            // .set('hour', 23).set('minute', 59).set('second', 59)
            .toDate() - new Date();
});
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
const paymentCheckStatus = () => {
    if (transaction.value.snap_token && transaction.value.status == "pending")
        snap.pay(transaction.value.snap_token, {
            onSuccess: function (result) {
                axios.post(
                    `transaction/${result.order_id.split("-")[1]
                    }/midtrans-callback`,
                    result
                );
            },
            onPending: function (result) {
                axios.post(
                    `transaction/${result.order_id.split("-")[1]
                    }/midtrans-callback`,
                    result
                );
            }
        });
    else Toast.fire({ title: "Maaf tidak bisa cek status", icon: "error" });
};
const center = useCoorStore()
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
                        <img :src="'/storage/' + cart.product.picture" :alt="cart.product.picture" class="card-img-left"
                            style="max-width: 30px; max-width: 100px;" />
                        <div class="card-body pb-2">
                            <h5 class="card-title pb-0 pt-2 mb-0">{{ cart.product.name }}</h5>
                            <p class="card-text mb-0">{{ cart.product.description }}</p>
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
                                            ({{ transaction.address?.type }}) {{ transaction.address?.street_name }}
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
                            <a :href="`https://www.google.com/maps/search/?api=1&query=${center.lat}%2C${center.lng}`"
                                target="_blank" class="btn btn-success w-100 mt-2">Cek Lokasi</a>
                            <button class="btn btn-info w-100 mt-2"
                                v-if="transaction.payment_method == 'Transfer' && transaction.status == 'pending'"
                                @click="paymentCheckStatus">Bayar</button>
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
        navActiveLinks: customer.transaction
</route>
