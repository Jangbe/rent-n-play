<script setup>
import { ref, watch, onMounted } from "vue";
import { useRoute } from "vue-router";
import { number_format } from "../../../helpers";
import { Confirm, Toast } from "../../../plugins/swal";
import { useCoorStore } from "../../../stores/coor";
import longText from "../../../components/long-text.vue";

const route = useRoute();
const transaction = ref({ total: 0, transaction_details: [] });
const time = ref(null);
axios.get("transaction/" + route.params.transaction_number).then(({ data }) => {
    transaction.value = data;
    formExtraTime.value.subtotal = data.subtotal;
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
                axios.post(`transaction/${result.order_id.split("-")[1]}/midtrans-callback`, result);
            },
            onPending: function (result) {
                axios.post(`transaction/${result.order_id.split("-")[1]}/midtrans-callback`, result);
            }
        });
    else Toast.fire({ title: "Maaf tidak bisa cek status", icon: "error" });
};
const center = useCoorStore();
const formData = ref({});
const loading = ref(false);
const comment = () => {
    Confirm.fire({
        title: 'Apakah anda yakin akan memberikan testimoni?',
        text: 'Testimoni anda tidak bisa diedit dan dihapus!',
        cancelButtonColor: 'var(--bs-warning)',
        confirmButtonColor: 'var(--bs-success)',
    }).then(({ isConfirmed }) => {
        if (isConfirmed) {
            loading.value = true;
            formData.value.transaction_id = transaction.value.id;
            axios.post('testimonial', formData.value).then(({ data }) => {
                Toast.fire({ title: data.message });
                transaction.value.testimonial = data.testimonial;
            }).catch(({ response }) => {
                Toast.fire({ title: response.data?.message, icon: 'error' });
            }).finally(() => loading.value = false);
        }
    })
}
var modal = null;
onMounted(() => {
    modal = new bootstrap.Modal(document.querySelector('#modal-extra-time'));
})
const formExtraTime = ref({ days: 1 });
const addExtraTime = () => {
    axios.post(`transaction/${route.params.transaction_number}/extra-time`, formExtraTime.value).then(({ data }) => {
        Toast.fire({ title: data.message });
        modal.toggle();
        if (data.snapToken)
            snap.pay(data.snapToken, {
                onSuccess: function (result) {
                    axios.post(`transaction/${route.params.transaction_number}/${result.order_id.split('-')[1]}/callback`, result);
                },
                onPending: function (result) {
                    axios.post(`transaction/${route.params.transaction_number}/${result.order_id.split('-')[1]}/callback`, result);
                },
            })
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
                                    <td style="width: 170px;"><b>Metode Pembayaran</b></td>
                                    <td>:</td>
                                    <td>{{ transaction.payment_method }}</td>
                                </tr>
                                <tr>
                                    <td><b>Durasi Sewa</b></td>
                                    <td>:</td>
                                    <td>
                                        {{ transaction.days }} hari
                                        <span v-if="transaction?.extra_times?.filter(et => et.is_paid)?.length > 0">
                                            (+{{ transaction.extra_times.reduce((a, b) => a + b.days, 0) }} hari perpanjangan)
                                        </span>
                                    </td>
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
                                            <button class="btn btn-warning" v-else>
                                                <i class="bx bx-info-circle"></i>
                                            </button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <a :href="`https://www.google.com/maps/search/?api=1&query=${center.lat}%2C${center.lng}`"
                                target="_blank" class="btn btn-success w-100 mt-2"
                                v-if="transaction.status != 'completed'">Cek Lokasi</a>
                            <template v-else-if="transaction?.testimonial == null">
                                <hr>
                                <h3 class="text-center">Berikan Testimoni Anda Jika Berkenan</h3>
                                <star-rating :increment=".5" v-model:rating="formData.rating"
                                    class="justify-content-center" />
                                <div class="form-group">
                                    <label for="comment" class="form-label">Komentar</label>
                                    <textarea id="comment" v-model="formData.comment" class="form-control" rows="5" />
                                </div>
                                <button class="btn btn-primary w-100 mt-2" @click="comment" :disabled="loading">
                                    <span v-if="loading" class="spinner-border spinner-border-sm" role="status"
                                        aria-hidden="true"></span>
                                    Simpan
                                </button>
                            </template>
                            <template v-else-if="transaction?.testimonial">
                                <hr>
                                <h3 class="text-center">Testimoni Anda</h3>
                                <star-rating :increment=".5" :read-only="true"
                                    v-model:rating="transaction.testimonial.rating" class="justify-content-center" />
                                <div class="form-group">
                                    <label for="comment" class="form-label">Komentar</label>
                                    <textarea id="comment" readonly v-model="transaction.testimonial.comment"
                                        class="form-control" rows="5" />
                                </div>
                            </template>
                            <button class="btn btn-info w-100 mt-2"
                                v-if="transaction.payment_method == 'Transfer' && transaction.status == 'pending'"
                                @click="paymentCheckStatus">Bayar</button>
                            <button class="btn btn-warning w-100 mt-2" v-if="transaction.status == 'ongoing'"
                                @click="modal?.toggle()">
                                Perpanjang Waktu
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <div class="modal fade" id="modal-extra-time">
            <div class="modal-dialog modal-sm-fullscreen">
                <form @submit.prevent="addExtraTime" class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Form Perpanjang Waktu</h5>
                        <button class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="payment_method-et" class="form-label">Metode Pembayaran</label>
                            <select id="payment_method-et" v-model="formExtraTime.payment_method" class="form-select">
                                <option value="Cash">Tunai</option>
                                <option>Transfer</option>
                            </select>
                        </div>
                        <div class="row g-2">
                            <div class="col-8">
                                <div class="form-group">
                                    <label for="total-et" class="form-label">Total Tambahan</label>
                                    <input type="text" id="total-et" readonly class="form-control"
                                        :value="number_format(formExtraTime.subtotal * formExtraTime?.days ?? 1)">
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="form-group">
                                    <label for="days-et" class="form-label">Tambahan Hari</label>
                                    <input type="number" id="days-et" class="form-control" min="1"
                                        v-model="formExtraTime.days">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                        <button class="btn btn-primary" :disabled="loading">
                            <span v-if="loading" class="spinner-border spinner-border-sm" role="status"
                                aria-hidden="true"></span>
                            Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
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
