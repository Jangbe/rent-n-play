<script setup>
import { ref, onMounted } from 'vue';
import { Confirm, Toast } from '../../../plugins/swal';

const categories = ref([]);
const fetchData = () => axios.get('category').then(({ data }) => {
    categories.value = data;
})
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
    formSetting.value.url = 'category' + (type == 'create' ? '' : '/' + data.id);
    modal.value.toggle();
}
const loading = ref(false);
const submit = () => {
    loading.value = true;
    axios.post(formSetting.value.url, { ...formData.value, _method: formSetting.value.method })
        .then(({ data }) => {
            modal.value.toggle();
            fetchData();
            Toast.fire({ title: data });
        })
        .catch(({ response }) => Toast.fire({ title: response?.data?.message, icon: 'error' }))
        .finally(() => loading.value = false);
}
const deleteRow = (row) => {
    Confirm.fire({ icon: 'warning' }).then(({ isConfirmed }) => {
        if (isConfirmed) {
            axios.delete('category/' + row.id).then(({ data }) => {
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
            <h1>Data Kategori</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><router-link to="/admin/dashboard">Home</router-link></li>
                    <li class="breadcrumb-item">Master</li>
                    <li class="breadcrumb-item active">Kategori</li>
                </ol>
            </nav>
        </div><!-- End Page Title -->

        <section class="section">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title mb-0 d-flex align-items-baseline justify-content-between">
                        Tabel Kategori
                        <div>
                            <button @click="openModal('create')" class="btn btn-sm btn-primary">
                                <i class="bx bx-plus"></i>
                            </button>
                            <button @click="fetchData" class="btn btn-sm btn-info">
                                <i class="bx bx-refresh"></i>
                            </button>
                        </div>
                    </h5>
                    <DataTable class="table table-hover table-striped" :data="categories"
                        :columns="['no', 'name', 'action'].map(c => ({ data: c }))">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Nama Kategori</th>
                                <th scope="col">Aksi</th>
                            </tr>
                        </thead>
                        <template #column-0="{ rowIndex }">
                            {{ rowIndex + 1 }}
                        </template>
                        <template #column-2="{ rowData }">
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
                            <h4 class="modal-title">Form Kategori</h4>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="name" class="form-label">Nama Kategori</label>
                                <input type="text" v-model="formData.name" class="form-control">
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
