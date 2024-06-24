<script setup>
import { ref, computed, watch } from 'vue';
import { number_format } from '../../helpers';
import { useRoute, useRouter } from 'vue-router';
import { Toast } from '../../plugins/swal';
import debounce from 'debounce';

const route = useRoute();
const router = useRouter();
const products = ref([]);
const recordsTotal = ref(0);
const currentPage = ref(1);
const length = 36;
const page = computed(() => ({ start: (currentPage.value - 1) * length, length }));
const pages = computed(() => {
    const total = Math.ceil(recordsTotal.value / length);
    const data = [];
    const max = 5;
    if (total <= 1) return [];
    if (total <= 2) {
        for (let i = 1; i <= total; i++) data.push(i);
    } else {
        if (currentPage.value > max - 1) data.push(1, '...');

        const page = { start: Math.max(1, currentPage.value - (Math.floor(max / 2) - 1)), end: Math.min(total, currentPage.value + (Math.floor(max / 2) - 1)) };
        if (currentPage.value < max) {
            page.start = 1;
            page.end = max;
        } else if (currentPage.value > total - (max - 1)) {
            page.start = total - (max - 1);
            page.end = total;
        }

        for (let i = page.start; i <= page.end; i++) data.push(i);

        if (currentPage.value < total - (max - 2)) data.push('...', total);
    }
    return data;
});
const draw = () => {
    const params = {
        draw: 0,
        columns: [
            { data: 'picture' },
            { data: 'category.name' },
            { data: 'name' },
            { data: 'description' },
            { data: 'price' },
            { data: 'amount' },
        ],
        ...page.value,
        search: {
            value: route.query.search,
            regex: false
        },
        category: route.query.category,
        _: (new Date).getTime()
    }
    axios.get('product', { params }).then(({ data }) => {
        products.value = data.data;
        recordsTotal.value = data.recordsFiltered;
    })
}
draw();
const search = debounce(draw, 700);
watch(() => route.query, search);
const changePage = (page) => {
    if (page == 'next') currentPage.value++
    else if (page == 'prev') currentPage.value--
    else currentPage.value = page;
    draw();
}
const addToCart = (product) => {
    Toast.fire({ title: 'Barang berhasil ditambahkan ke keranjang', icon: 'info' });
    const carts = JSON.parse(localStorage.getItem('carts') ?? '[]');
    const index = carts.findIndex(c => c.product_id == product.id);
    if (index >= 0) {
        carts[index].quantity++;
    } else {
        carts.push({ product_id: product.id, quantity: 1 });
    }
    localStorage.setItem('carts', JSON.stringify(carts));
}
</script>

<template>
    <div>
        <div class="pagetitle">
            <h1>Halaman Utama</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><router-link to="/home">Home</router-link></li>
                    <li class="breadcrumb-item active">Halaman Utama</li>
                </ol>
            </nav>
        </div><!-- End Page Title -->

        <section class="section dashboard">
            <div class="row gx-2 gy-3">
                <div class="col-md-3 col-sm-4 col-6" v-for="(p, i) in products">
                    <div class="card bg-white h-100">
                        <img :src="p.picture" class="card-img-top" :alt="p.name">
                        <div class="card-body pb-0">
                            <h5 class="card-title pb-0 mb-1" style="font-size: 16px;">{{ p.name }}</h5>
                            <p class="card-text mb-1"><strong>{{ number_format(p.price) }}</strong></p>
                            <p class="card-text mb-0">Tersisa {{ p.availableStock }}</p>
                        </div>
                        <div class="card-footer pt-0">
                            <div class="d-flex" style="gap: 10px;">
                                <button class="btn btn-sm btn-secondary w-100"
                                    @click="router.push('/product/' + p.id)">
                                    <i class="bi bi-info-circle"></i>
                                    Detail
                                </button>
                                <button class="btn btn-sm btn-success" @click="addToCart(p)">
                                    <i class="bx bx-cart"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <nav aria-label="Page navigation example" class="mt-3">
                <ul class="pagination justify-content-center">
                    <li :class="`page-item ${page.start == 0 ? 'disabled' : ''}`">
                        <button class="page-link" @click="changePage('prev')" aria-label="Previous">
                            <span aria-hidden="true">&laquo;</span>
                        </button>
                    </li>
                    <li v-for="p in pages"
                        :class="`page-item ${currentPage == p ? 'active' : ''} ${p == '...' ? 'disabled' : ''}`">
                        <button class="page-link" @click="changePage(p)">{{ p }}</button>
                    </li>
                    <li :class="`page-item ${page.start + page.length == recordsTotal ? 'disabled' : ''}`">
                        <button class="page-link" @click="changePage('next')" aria-label="Next">
                            <span aria-hidden="true">&raquo;</span>
                        </button>
                    </li>
                </ul>
            </nav>
        </section>
    </div>
</template>

<style scoped>
.card {
    margin-bottom: 20px;
}

.card-img-top {
    height: 200px;
    object-fit: cover;
}

.card-title {
    font-size: 1.25rem;
    font-weight: bold;
}

.card-text {
    color: #555;
}
</style>

<route lang="yaml">
    meta:
        layout: admin
</route>
