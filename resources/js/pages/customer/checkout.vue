<script setup>
import { ref, watch, computed, onMounted } from 'vue';
import { Confirm, Toast } from '../../plugins/swal';
import { useUserStore } from '../../stores/user';
import { useRouter } from 'vue-router';
import { number_format } from '../../helpers';
import modalAddress from '../../components/modal-address.vue';
import distance from '../../components/distance.vue';
import LongText from '../../components/long-text.vue';

const user = useUserStore();

const carts = ref(JSON.parse(localStorage.getItem('carts') ?? '[]'));
axios.post('carts', { id: carts.value.map(c => c.product_id) }).then(({ data }) => {
    carts.value = carts.value.map(c => {
        const product = data.find(d => d.id == c.product_id);
        return { ...c, product, total: c.quantity * product.price };
    });
})

const changeQuantity = async (type, cart) => {
    if (type == '-' && cart.quantity == '1') {
        const { isConfirmed } = await Confirm.fire({
            title: 'Apakah anda ingin menghapus barang ini dari keranjang?',
            text: 'Barang ditambahkan lagi ke keranjang dihalaman utama.'
        });
        if (isConfirmed) carts.value.splice(carts.value.indexOf(cart), 1);
        else return
    }
    if (type == '+' && cart.quantity == cart.product.availableStock) {
        Toast.fire({ title: 'Mencapai batas maks', icon: 'warning' });
        return;
    }
    cart.quantity += type == '-' ? -1 : 1;
    cart.total = cart.quantity * cart.product.price;
    localStorage.setItem('carts', JSON.stringify(carts.value));
}
const setCart = () => {
    localStorage.setItem('carts', JSON.stringify(carts.value.map(c => {
        if (c.quantity > c.product.availableStock) c.quantity = c.product.availableStock;
        return { ...c, total: c.quantity * c.product.price };
    })));
}

const address = ref({});
const addresses = ref([]);
const formDataModal = ref({});
const formData = ref({ delivery_fee: 0 });
axios.get('transaction/get-transaction-number').then(({ data }) => {
    formData.value.transaction_number = data;
})
const getAddresses = () => {
    axios.get('address/' + user?.user.id).then(({ data }) => {
        addresses.value = data;
    })
}
if (user?.user) getAddresses();
watch(() => user?.user, getAddresses);
const total = computed(() => {
    return number_format(carts.value.reduce((a, b) => a + b.total, 0) * (formData.value?.days ?? 1) + formData.value.delivery_fee);
})
watch(() => formData.value.shipping_method, (type) => {
    if (type != 'delivery') formData.value.delivery_fee = 0;
})
const router = useRouter();
const loading = ref({ checkout: false, address: false });
const submit = () => {
    loading.value.checkout = true;
    const data = {};
    for (let i in formData.value) {
        data[i] = formData.value[i];
    }
    data.delivery = formData.value.shipping_method == 'delivery' ? 1 : 0;
    data.products = carts.value;
    data.order_datetime = (new Date()).toISOString();
    axios.post('transaction', data).then(({ data }) => {
        localStorage.removeItem('carts');
        Toast.fire({ title: data.message });
        router.push('/customer/transaction/' + formData.value.transaction_number);
        if (data.snapToken)
            snap.pay(data.snapToken, {
                onSuccess: function (result) {
                    axios.post(`transaction/${result.order_id.split('-')[1]}/midtrans-callback`, result);
                },
                onPending: function (result) {
                    axios.post(`transaction/${result.order_id.split('-')[1]}/midtrans-callback`, result);
                },
            })
    }).catch(({ response }) => {
        Toast.fire({ title: response?.data?.message, icon: 'error' });
    }).finally(() => loading.value.checkout = false);
}
var modal = null;
onMounted(() => {
    modal = new bootstrap.Modal(document.querySelector('#modal-address'));
})
const submitModal = () => {
    loading.value.address = true;
    axios.post('address', formDataModal.value).then(({ data }) => {
        Toast.fire({ title: data });
        getAddresses();
        modal.toggle();
    }).finally(() => loading.value.address = false);
}
watch(() => formData.value.address_id, (id) => {
    address.value = addresses.value.find(a => a.id == id);
})
</script>

<template>
    <div>
        <div class="pagetitle">
            <h1>Checkout</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><router-link to="/customer/home">Home</router-link></li>
                    <li class="breadcrumb-item active">Checkout</li>
                </ol>
            </nav>
        </div><!-- End Page Title -->

        <section class="section checkout">
            <h2 class="text-muted">List Produk</h2>
            <div class="row">
                <div class="col-md-6 col-12">
                    <div class="card flex-row align-items-center mb-2" v-for="cart in carts">
                        <img :src="cart?.product?.picture" :alt="cart?.product?.picture" class="card-img-left"
                            style="max-width: 30px; max-width: 100px;">
                        <div class="card-body pb-2">
                            <h5 class="card-title pb-0 pt-2 mb-0">{{ cart?.product?.name }}</h5>
                            <p class="card-text mb-0">
                                <LongText :text="cart?.product?.description ?? ''" :length="35" />
                            </p>
                            <div class="row justify-content-between align-items-end">
                                <div class="col-md-8 col-12">
                                    <p class="card-text">
                                        <span class="text-muted">{{ number_format(cart?.product?.price ?? 0) }}</span>
                                        <br>
                                        <span class="fw-bold">Total :
                                            {{ number_format(cart.total) }}
                                        </span>
                                    </p>
                                </div>
                                <div class="col-md-4 col-12">
                                    <div class="input-group">
                                        <button class="btn btn-sm btn-secondary"
                                            @click.prevent="changeQuantity('-', cart)">
                                            <i class="bx bx-minus"></i>
                                        </button>
                                        <input type="number" style="max-width: 40px;" v-model="cart.quantity" min="0"
                                            :max="cart?.product?.availableStock" class="form-control form-control-sm"
                                            @update:model-value="setCart">
                                        <button class="btn btn-sm btn-secondary"
                                            @click.prevent="changeQuantity('+', cart)">
                                            <i class="bx bx-plus"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="alert alert-info text-center py-2">
                        <h3 class="my-0 py-0">Subtotal : {{ number_format(carts.reduce((a, b) => a + b.total, 0)) }}
                        </h3>
                    </div>
                    <div class="alert alert-warning" v-if="carts.length == 0">
                        Belum ada barang di keranjang, silahkan pilih terlebih dahulu!...
                    </div>
                </div>
                <div class="col-md-6 col-12">
                    <div class="card">
                        <h3 class="card-title d-flex justify-content-between px-3">
                            <h5>{{ user?.user?.name }}</h5>
                            <h5>{{ user?.user?.email }}</h5>
                        </h3>
                        <div class="card-body">
                            <div class="row g-2">
                                <div class="col-6">
                                    <div class="form-group mb-2">
                                        <label for="transaction_number" class="form-label">Nomor Transaksi</label>
                                        <input type="text" disabled v-model="formData.transaction_number"
                                            class="form-control">
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="payment_method" class="form-label">Pembayaran</label>
                                        <select id="payment_method" v-model="formData.payment_method"
                                            class="form-select">
                                            <option value="Cash">Tunai</option>
                                            <option>Transfer</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="shipping_method" class="form-label">Pengiriman</label>
                                        <select id="shipping_method" v-model="formData.shipping_method"
                                            class="form-select">
                                            <option value="pick-up">Diambil</option>
                                            <option value="delivery">Diantar</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="days" class="form-label">Lama Sewa</label>
                                        <div class="input-group">
                                            <input type="number" id="days" min="1" v-model="formData.days"
                                                class="form-control">
                                            <span class="input-group-text">Hari</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group" v-if="formData.shipping_method == 'delivery'">
                                <label for="address_id" class="form-label">Alamat</label>
                                <div class="input-group">
                                    <select id="address_id" v-model="formData.address_id" class="form-select">
                                        <option v-for="address in addresses" :value="address.id">
                                            ({{ address.type }}) {{ address.street_name }}
                                        </option>
                                    </select>
                                    <button class="btn btn-success" @click="modal.toggle()">
                                        <i class="bi bi-plus"></i>
                                    </button>
                                </div>
                            </div>
                            <distance v-if="formData.shipping_method == 'delivery'" class="my-2" :form-data="formData"
                                :address="address" />
                            <div class="form-group">
                                <label for="payment_method" class="form-label">Total</label>
                                <div class="input-group">
                                    <input id="payment_method" class="form-control" disabled :value="total" />
                                    <span class="input-group-text" v-if="formData.delivery_fee > 0">
                                        Ongkir: {{ number_format(formData.delivery_fee) }}
                                    </span>
                                </div>
                            </div>
                            <button @click="submit" :disabled="carts.length == 0 || loading.checkout"
                                class="btn btn-success w-100 mt-3">
                                <span v-if="loading.checkout" class="spinner-border spinner-border-sm" role="status"
                                    aria-hidden="true"></span>
                                Sewa
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <modal-address :formData="formDataModal" @submited="submitModal" :loading="loading.address" />
    </div>
</template>

<style scoped>
input[type="number"]::-webkit-outer-spin-button,
input[type="number"]::-webkit-inner-spin-button {
    -webkit-appearance: none;
    margin: 0;
}

input[type="number"] {
    -moz-appearance: textfield;
}
</style>

<route lang="yaml">
    meta:
        layout: admin
</route>
