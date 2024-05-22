<script setup>
import { ref, watch, computed, onMounted } from 'vue';
import { Confirm, Toast } from '../../plugins/swal';
import { useUserStore } from '../../stores/user';
import { useRouter } from 'vue-router';
import { number_format } from '../../helpers';
import modalAddress from '../../components/modal-address.vue';

const user = useUserStore();

const carts = ref([]);
axios.get('product').then(({ data }) => {
    carts.value = JSON.parse(localStorage.getItem('carts') ?? '[]')
        .map(c => {
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
    if (type == '+' && cart.quantity == cart.product.amount) {
        Toast.fire({ title: 'Mencapai batas maks', icon: 'warning' });
        return;
    }
    cart.quantity += type == '-' ? -1 : 1;
    cart.total = cart.quantity * cart.product.price;
    localStorage.setItem('carts', JSON.stringify(carts.value));
}
const setCart = () => {
    localStorage.setItem('carts', JSON.stringify(carts.value.map(c => {
        if (c.quantity > c.product.amount) c.quantity = c.product.amount;
        return { ...c, total: c.quantity * c.product.price };
    })));
}

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
    return number_format(carts.value.reduce((a, b) => a + b.total, formData.value.delivery_fee));
})
watch(() => formData.value.type, (type) => {
    formData.value.delivery_fee = type == 'delivery' ? 10000 : 0;
})
const router = useRouter();
const submit = () => {
    const data = {};
    for (let i in formData.value) {
        data[i] = formData.value[i];
    }
    data.delivery = formData.value.type == 'delivery' ? 1 : 0;
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
                onError: function (result) {
                    console.log(result);
                },
            })
    }).catch(({ response }) => {
        Toast.fire({ title: response?.data?.message, icon: 'error' });
    })
}
var modal = null;
onMounted(() => {
    modal = new bootstrap.Modal(document.querySelector('#modal-address'));
})
const submitModal = () => {
    axios.post('address', formDataModal.value).then(({ data }) => {
        Toast.fire({ title: data });
        getAddresses();
        modal.toggle();
    })
}
</script>

<template>
    <div>
        <div class="pagetitle">
            <h1>Checkout</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><router-link to="/customer/dashboard">Home</router-link></li>
                    <li class="breadcrumb-item active">Checkout</li>
                </ol>
            </nav>
        </div><!-- End Page Title -->

        <section class="section checkout">
            <h2 class="text-muted">List Produk</h2>
            <div class="row">
                <div class="col-md-6 col-12">
                    <div class="card flex-row align-items-center mb-2" v-for="cart in carts">
                        <img :src="'/storage/' + cart.product.picture" :alt="cart.product.picture" class="card-img-left"
                            style="max-width: 30px; max-width: 100px;">
                        <div class="card-body pb-2">
                            <h5 class="card-title pb-0 pt-2 mb-0">{{ cart.product.name }}</h5>
                            <p class="card-text mb-0">{{ cart.product.description }}</p>
                            <div class="row justify-content-between align-items-end">
                                <div class="col-md-8 col-12">
                                    <p class="card-text">
                                        <span class="text-muted">{{ number_format(cart.product.price) }}</span> <br>
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
                                            :max="cart.product.amount" class="form-control form-control-sm"
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
                            <div class="form-group">
                                <label for="transaction_number" class="form-label">Nomor Transaksi</label>
                                <input type="text" disabled v-model="formData.transaction_number" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="payment_method" class="form-label">Metode Pembayaran</label>
                                <select id="payment_method" v-model="formData.payment_method" class="form-select">
                                    <option>Cash</option>
                                    <option>Transfer</option>
                                </select>
                            </div>
                            <div class="form-control mt-2 text-center">
                                <div class="form-check form-check-inline">
                                    <label for="delivery" class="form-check-label">Diantar</label>
                                    <input type="radio" name="type" value="delivery" v-model="formData.type"
                                        id="delivery" class="form-check-input">
                                </div>
                                <div class="form-check form-check-inline">
                                    <label for="take" class="form-check-label">Diambil</label>
                                    <input type="radio" name="type" value="take" v-model="formData.type" id="take"
                                        class="form-check-input">
                                </div>
                            </div>
                            <div class="form-group" v-if="formData.type == 'delivery'">
                                <label for="address_id" class="form-label">Alamat</label>
                                <div class="input-group">
                                    <select id="address_id" v-model="formData.address_id" class="form-select">
                                        <option v-for="address in addresses" :value="address.id">
                                            ({{ address.type }}) {{ address.detail }}
                                        </option>
                                    </select>
                                    <button class="btn btn-success" @click="modal.toggle()">
                                        <i class="bi bi-plus"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="payment_method" class="form-label">Total</label>
                                <input id="payment_method" class="form-control" disabled :value="total" />
                            </div>
                            <button @click="submit" :disabled="carts.length == 0"
                                class="btn btn-success w-100 mt-3">Sewa</button>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <modal-address :formData="formDataModal" @submited="submitModal" />
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
