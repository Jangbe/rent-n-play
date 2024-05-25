<script setup>
import { ref, onMounted } from 'vue';
import { Confirm, Toast } from '../../../plugins/swal';
import { number_format } from '../../../helpers';
import LongText from '../../../components/long-text.vue';

const refDataTable = ref(null);
const categories = ref([]);
const columns = [
    { data: 'no', orderable: false, sortable: false, searchable: false },
    { data: 'picture', orderable: false, sortable: false, searchable: false },
    { data: 'category.name', orderable: false, sortable: true, searchable: true },
    { data: 'name', orderable: false, sortable: true, searchable: true },
    { data: 'description', orderable: false, sortable: false, searchable: true },
    { data: 'price', orderable: false, sortable: true, searchable: true },
    { data: 'amount', orderable: false, sortable: true, searchable: true },
    { data: 'action', orderable: false, sortable: false, searchable: false },
];
const ajax = { url: '/api/product', headers: { Authorization: 'Bearer ' + localStorage.getItem('accessToken') } };
const fetchData = () => {
    axios.get('category').then(({ data }) => categories.value = data)
    if (refDataTable.value) refDataTable.value.dt.draw();
}
fetchData();

const formData = ref({});
const formSetting = ref({ method: 'POST', url: '' });
const modal = ref(null);
onMounted(() => {
    modal.value = new bootstrap.Modal(document.querySelector('#modal-form'), {});
})
const openModal = (type, data = {}) => {
    formData.value = { ...data };
    formSetting.value.method = type == 'create' ? 'POST' : 'PUT';
    formSetting.value.url = 'product' + (type == 'create' ? '' : '/' + data.id);
    modal.value.toggle();
}
const setPicture = (data) => {
    formData.value.picture = data.target.files[0];
}
const submit = () => {
    const data = new FormData();
    for (const i in formData.value) {
        data.append(i, formData.value[i]);
    }
    data.append('_method', formSetting.value.method);
    axios.post(formSetting.value.url, data)
        .then(({ data }) => {
            modal.value.toggle();
            fetchData();
            Toast.fire({ title: data });
        })
}
const deleteRow = (row) => {
    Confirm.fire({ icon: 'warning' }).then(({ isConfirmed }) => {
        if (isConfirmed) {
            axios.delete('product/' + row.id).then(({ data }) => {
                fetchData();
                Toast.fire({ title: data });
            })
        }
    })
}
</script>

<template>
    <div>
        <div class="pagetitle">
            <h1>Data Barang</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><router-link to="/admin/dashboard">Home</router-link></li>
                    <li class="breadcrumb-item">Master</li>
                    <li class="breadcrumb-item active">Barang</li>
                </ol>
            </nav>
        </div><!-- End Page Title -->

        <section class="section">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title mb-0 d-flex align-items-baseline justify-content-between">
                        Tabel Barang
                        <div>
                            <button @click="openModal('create')" class="btn btn-sm btn-primary">
                                <i class="bx bx-plus"></i>
                            </button>
                            <button @click="fetchData" class="btn btn-sm btn-info">
                                <i class="bx bx-refresh"></i>
                            </button>
                        </div>
                    </h5>
                    <DataTable class="table table-hover table-striped" :options="{ processing: true, serverSide: true }"
                        :ajax="ajax" :columns="columns" ref="refDataTable">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Gambar</th>
                                <th scope="col">Kategori</th>
                                <th scope="col">Nama Barang</th>
                                <th scope="col">Deksripsi</th>
                                <th scope="col">Harga</th>
                                <th scope="col">Jumlah</th>
                                <th scope="col">Aksi</th>
                            </tr>
                        </thead>
                        <template #column-0="{ rowIndex }">
                            {{ rowIndex + 1 }}
                        </template>
                        <template #column-1="{ rowData }">
                            <img style="max-height: 100px; max-width: 100px;" :src="rowData.picture">
                        </template>
                        <template #column-4="{ rowData }">
                            <LongText :text="rowData.description" :length="50" />
                        </template>
                        <template #column-5="{ rowData }">
                            <span>{{ number_format(rowData.price) }}</span>
                        </template>
                        <template #column-7="{ rowData }">
                            <button @click="openModal('edit', rowData)" class="btn btn-sm btn-warning">
                                <i class="bx bx-pencil"></i>
                            </button>
                            <button @click="deleteRow(rowData)" class="btn btn-sm btn-danger">
                                <i class="bx bx-trash"></i>
                            </button>
                        </template>
                    </DataTable>
                </div>
            </div>
        </section>

        <div class="modal fade" tabindex="-1" id="modal-form">
            <div class="modal-dialog">
                <form action="#" method="POST" @submit.prevent="submit">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">Form Barang</h4>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="category" class="form-label">Kategori</label>
                                <select id="category" v-model="formData.category_id" class="form-select">
                                    <option value=""></option>
                                    <option v-for="category in categories" :value="category.id">
                                        {{ category.name }}
                                    </option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="picture" class="form-label">Gambar Barang</label>
                                <input type="file" accept="image/*" id="picture" @change="setPicture"
                                    class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="name" class="form-label">Nama Barang</label>
                                <input type="text" id="name" v-model="formData.name" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="description" class="form-label">Deskripsi</label>
                                <textarea id="description" v-model="formData.description" class="form-control" />
                            </div>
                            <div class="row">
                                <div class="col-md-8 col-7">
                                    <div class="form-group">
                                        <label for="price" class="form-label">Harga</label>
                                        <input type="number" id="price" v-model="formData.price" class="form-control" />
                                    </div>
                                </div>
                                <div class="col-md-4 col-5">
                                    <div class="form-group">
                                        <label for="amount" class="form-label">Jumlah</label>
                                        <input type="number" id="amount" v-model="formData.amount"
                                            class="form-control" />
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                            <button class="btn btn-primary">Simpan</button>
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
