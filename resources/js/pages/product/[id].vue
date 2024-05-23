<script setup>
import { ref } from 'vue';
import { useRoute } from 'vue-router';
import { number_format } from '../../helpers';
import { Toast } from '../../plugins/swal';

const route = useRoute();
const product = ref({});
axios.get('popular-product/' + route.params.id).then(({ data }) => {
    product.value = data;
})
const addToCart = () => {
    Toast.fire({ title: 'Barang berhasil ditambahkan ke keranjang', icon: 'info' });
    const carts = JSON.parse(localStorage.getItem('carts') ?? '[]');
    const index = carts.findIndex(c => c.product_id == product.value.id);
    if (index >= 0) {
        carts[index].quantity++;
    } else {
        carts.push({ product_id: product.value.id, quantity: 1 });
    }
    localStorage.setItem('carts', JSON.stringify(carts));
}
</script>
<template>
    <main id="main">

        <!-- ======= Product Details Section ======= -->
        <section id="product-details" class="product-details" style="padding-top: 100px;">
            <div class="container">

                <div class="row gy-4">

                    <div class="col-lg-8">
                        <div class="product-details-slider swiper">
                            <div class="swiper-wrapper align-items-center">

                                <div class="swiper-slide">
                                    <img :src="'/storage/' + product.picture" alt="">
                                </div>

                            </div>
                            <div class="swiper-pagination"></div>
                        </div>
                    </div>

                    <div class="col-lg-4 text-white">
                        <div class="product-info">
                            <h3>Informasi Barang</h3>
                            <ul>
                                <li><strong>Nama Barang</strong>: {{ product.name }}</li>
                                <li><strong>Kategori</strong>: {{ product.category?.name }}</li>
                                <li><strong>Harga</strong>: {{ number_format(product.price ?? 0) }}</li>
                                <li><strong>Stok Tersisa</strong>: {{ product.availableStock }}</li>
                                <li><strong>Deskripsi</strong>: {{ product.description }}</li>
                            </ul>
                            <button class="btn btn-info mt-4 w-100" v-if="product.id" @click="addToCart">
                                <i class="bi bi-cart"></i>
                                Masukan Ke Keranjang
                            </button>
                        </div>
                    </div>

                </div>

            </div>
        </section><!-- End Product Details Section -->

    </main><!-- End #main -->
</template>

<route lang="yaml">
    meta:
        layout: guest
</route>