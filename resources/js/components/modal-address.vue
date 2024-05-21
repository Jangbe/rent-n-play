<script setup>
// import { defineProps } from 'vue';

const props = defineProps({
    formData: {
        type: Object,
        default: {}
    }
})
const emit = defineEmits(['submited']);
</script>

<template>
    <div class="modal fade" id="modal-address">
        <div class="modal-dialog">
            <form action="#" class="modal-content" @submit.prevent="emit('submited')">
                <div class="modal-header">
                    <h5 class="modal-title">Form Alamat</h5>
                    <button type="button" class="btn-close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-4">
                            <div class="form-group">
                                <label for="type" class="form-label">Tipe</label>
                                <select v-model="formData.type" id="type" class="form-select">
                                    <option>Rumah</option>
                                    <option>Kosan</option>
                                    <option>Kantor</option>
                                    <option>Ma'had</option>
                                    <option>Dan Lain-Lain</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-8">
                            <div class="form-group">
                                <label for="street_name" class="form-label">Nama Jalan</label>
                                <input v-model="formData.street_name" id="street_name" class="form-control" type="text">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="contact_name" class="form-label">Nama Kontak</label>
                        <input v-model="formData.contact_name" id="contact_name" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="contact_phone" class="form-label">Nomer Kontak</label>
                        <input v-model="formData.contact_phone" id="contact_phone" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="detail" class="form-label">Alamat Lengkap</label>
                        <textarea v-model="formData.detail" id="detail" class="form-control" />
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    <button class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</template>
