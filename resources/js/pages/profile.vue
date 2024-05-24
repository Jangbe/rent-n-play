<script setup>
import { ref, watch, onMounted } from 'vue';
import { useUserStore } from '../stores/user';
import { Toast } from '../plugins/swal';
import ModalAddress from '../components/modal-address.vue';

const identities = ref([]);
const addresses = ref([]);
const userStore = useUserStore();
const formData = ref({});
const getCustomerDetail = (u) => {
    axios.get('identity/' + u?.id).then(({ data }) => identities.value = data)
    axios.get('address/' + u?.id).then(({ data }) => addresses.value = data)
}

var modalIdentity = null;
var modalAddress = null;

const getData = (u) => {
    formData.value = { ...u };
    user.value = u;
    if (u?.role == 'Customer') getCustomerDetail(u);
    modalIdentity = new bootstrap.Modal(document.querySelector('#modal-identity'));
    modalAddress = new bootstrap.Modal(document.querySelector('#modal-address'));
}
const user = ref(userStore?.user ?? {});
onMounted(() => {
    if (userStore?.user) getData(userStore.user);
})
watch(() => userStore.user, getData);

const preview = ref(null);
const selectAvatar = () => {
    const input = document.createElement('input');
    input.type = 'file';
    input.accept = 'image/*';
    input.onchange = (e) => {
        formData.value.avatar = e.target.files[0];
        preview.value = URL.createObjectURL(e.target.files[0]);
        setTimeout(() => URL.revokeObjectURL(preview.value), 1000);
    }
    input.click();
}

const formDataModal = ref({});
const formSettingModal = ref({ method: 'POST', action: '' });
const refModalAddress = ref(null);
const openModal = (type, action, data = {}) => {
    previewAttachment.value = null;
    formDataModal.value = data;
    formDataModal.value._method = type == 'create' ? 'POST' : 'PUT';
    formSettingModal.value.method = type == 'create' ? 'POST' : 'PUT';
    formSettingModal.value.action = action + (type == 'create' ? '' : '/' + data.id);
    if (formSettingModal.value.action.startsWith('identity')) modalIdentity.toggle();
    else modalAddress.toggle();
    if (data.lat && data.lng) {
        refModalAddress.value.setPlace(data);
    } else {
        refModalAddress.value.removeMarker();
    }
}

const previewAttachment = ref(null);
const changeAttachment = (e) => {
    formDataModal.value.attachment = e.target.files[0];
    previewAttachment.value = URL.createObjectURL(e.target.files[0]);
    setTimeout(() => URL.revokeObjectURL(previewAttachment.value), 1000);
}

const submitModal = () => {
    const data = new FormData();
    for (const i in formDataModal.value) data.append(i, formDataModal.value[i]);
    axios.post(formSettingModal.value.action, data).then(({ data }) => {
        getCustomerDetail(user.value);
        if (formSettingModal.value.action.startsWith('identity')) modalIdentity.toggle();
        else modalAddress.toggle();
        Toast.fire({ title: data });
    }).catch(({ response }) => {
        Toast.fire({ title: response?.data?.message ?? 'Internal server error', icon: 'error' });
    })
}

const submit = (type) => {
    const data = new FormData();
    for (const i in formData.value) data.append(i, formData.value[i]);
    axios.post('update-' + type, data).then(({ data }) => {
        Toast.fire({ title: data });
        userStore.getUser();
    }).catch(({ response }) => {
        Toast.fire({ title: response?.data?.message ?? 'Internal server error', icon: 'error' });
    })
}
</script>

<template>
    <div>
        <div class="pagetitle">
            <h1>Profile</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><router-link to="/customer/home">Home</router-link></li>
                    <li class="breadcrumb-item">Users</li>
                    <li class="breadcrumb-item active">Profile</li>
                </ol>
            </nav>
        </div><!-- End Page Title -->

        <section class="section profile">
            <div class="row">
                <div class="col-xl-4">

                    <div class="card">
                        <div class="card-body profile-card pt-4 d-flex flex-column align-items-center">

                            <img :src="user?.avatar" alt="Profile" class="rounded-circle">
                            <h2>{{ user?.name }}</h2>
                            <h3>{{ user?.role }}</h3>
                        </div>
                    </div>

                </div>

                <div class="col-xl-8">

                    <div class="card">
                        <div class="card-body pt-3">
                            <!-- Bordered Tabs -->
                            <ul class="nav nav-tabs nav-tabs-bordered">

                                <li class="nav-item">
                                    <button class="nav-link active" data-bs-toggle="tab"
                                        data-bs-target="#profile-edit">Edit
                                        Profile</button>
                                </li>

                                <li class="nav-item">
                                    <button class="nav-link" data-bs-toggle="tab"
                                        data-bs-target="#profile-change-password">Ubah Katasandi</button>
                                </li>

                                <template v-if="user.role == 'Customer'">
                                    <!-- <li class="nav-item">
                                        <button class="nav-link" data-bs-toggle="tab"
                                            data-bs-target="#profile-add-identity">Tambah Identitas / Jaminan</button>
                                    </li> -->

                                    <li class="nav-item">
                                        <button class="nav-link" data-bs-toggle="tab"
                                            data-bs-target="#profile-add-address">Alamat</button>
                                    </li>
                                </template>

                            </ul>
                            <div class="tab-content pt-2">

                                <div class="tab-pane fade show active profile-edit pt-3" id="profile-edit">

                                    <!-- Profile Edit Form -->
                                    <form @submit.prevent="(submit)('profile')">
                                        <div class="row mb-3">
                                            <label for="profileImage" class="col-md-4 col-lg-3 col-form-label">
                                                Profile Image</label>
                                            <div class="col-md-8 col-lg-9">
                                                <img :src="preview ?? user.avatar" alt="Profile">
                                                <div class="pt-2">
                                                    <button type="button" @click="selectAvatar"
                                                        class="btn btn-primary btn-sm" title="Upload new profile image">
                                                        <i class="bi bi-upload"></i>
                                                    </button>
                                                    <button type="button" class="btn btn-danger btn-sm"
                                                        title="Remove my profile image">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row mb-3">
                                            <label for="fullName" class="col-md-4 col-lg-3 col-form-label">
                                                Full Name</label>
                                            <div class="col-md-8 col-lg-9">
                                                <input type="text" class="form-control" id="fullName"
                                                    v-model="formData.name">
                                            </div>
                                        </div>

                                        <div class="row mb-3">
                                            <label for="Email" class="col-md-4 col-lg-3 col-form-label">Email</label>
                                            <div class="col-md-8 col-lg-9">
                                                <input type="email" class="form-control" id="Email"
                                                    v-model="formData.email">
                                            </div>
                                        </div>

                                        <div class="text-center">
                                            <button type="submit" class="btn btn-primary">Save Changes</button>
                                        </div>
                                    </form><!-- End Profile Edit Form -->

                                </div>

                                <div class="tab-pane fade pt-3" id="profile-change-password">
                                    <!-- Change Password Form -->
                                    <form @submit.prevent="(submit)('password')">

                                        <div class="row mb-3">
                                            <label for="currentPassword"
                                                class="col-md-4 col-lg-3 col-form-label">Current Password</label>
                                            <div class="col-md-8 col-lg-9">
                                                <input v-model="formData.old_password" type="password"
                                                    class="form-control" id="currentPassword">
                                            </div>
                                        </div>

                                        <div class="row mb-3">
                                            <label for="newPassword" class="col-md-4 col-lg-3 col-form-label">New
                                                Password</label>
                                            <div class="col-md-8 col-lg-9">
                                                <input v-model="formData.password" type="password" class="form-control"
                                                    id="newPassword">
                                            </div>
                                        </div>

                                        <div class="row mb-3">
                                            <label for="renewPassword" class="col-md-4 col-lg-3 col-form-label">Re-enter
                                                New Password</label>
                                            <div class="col-md-8 col-lg-9">
                                                <input v-model="formData.password_confirmation" type="password"
                                                    class="form-control" id="renewPassword">
                                            </div>
                                        </div>

                                        <div class="text-center">
                                            <button type="submit" class="btn btn-primary">Change Password</button>
                                        </div>
                                    </form><!-- End Change Password Form -->

                                </div>

                                <!-- <div class="tab-pane fade pt-3" id="profile-add-identity"
                                    v-if="user.role == 'Customer'">
                                    <button class="btn btn-sm btn-primary" @click="openModal('create', 'identity')">
                                        Tambah Identitas
                                    </button>

                                    <div class="row grid mt-2">
                                        <div class="col-md-6 col-12 grid-item" v-for="identity in identities">
                                            <div class="card">
                                                <img :src="'/storage/' + identity.attachment" alt=""
                                                    class="card-img-top">
                                                <div class="card-body">
                                                    <h4 class="card-title my-0 pb-0">Tipe : {{ identity.type }}</h4>
                                                    <h5 class="card-text my-0">
                                                        Nomer Identitas : {{ identity.identity_number }}
                                                    </h5>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div> -->

                                <div class="tab-pane fade pt-3" id="profile-add-address" v-if="user.role == 'Customer'">
                                    <button class="btn btn-sm btn-primary mb-2" @click="openModal('create', 'address')">
                                        Tambah Alamat
                                    </button>

                                    <div class="card mb-2" style="cursor: pointer;" v-for="address in addresses"
                                        @click="openModal('edit', 'address', address)">
                                        <div class="card-body">
                                            <p class="mb-0 mt-2"><b>Tipe</b> : {{ address.type }}</p>
                                            <p class="my-0"><b>Alamat</b> : {{ address.street_name }}</p>
                                            <p class="my-0"><b>Nama Kontak</b> : {{ address.contact_name }}</p>
                                            <p class="my-0"><b>Nomer Kontak</b> : {{ address.contact_phone }}</p>
                                            <p class="mb-0"><b>Detail</b> : {{ address.detail }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>

                    </div><!-- End Bordered Tabs -->

                </div>
            </div>

        </section>

        <div class="modal fade" id="modal-identity">
            <div class="modal-dialog">
                <form action="#" class="modal-content" @submit.prevent="submitModal">
                    <div class="modal-header">
                        <h5 class="modal-title">Form Identitas</h5>
                        <button type="button" class="btn-close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-4">
                                <div class="form-group">
                                    <label for="type" class="form-label">Tipe</label>
                                    <select v-model="formDataModal.type" id="type" class="form-select">
                                        <option>KTP</option>
                                        <option>KTM</option>
                                        <option>SIM</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-8">
                                <div class="form-group">
                                    <label for="identity_number" class="form-label">Nomer Identitias</label>
                                    <input v-model="formDataModal.identity_number" id="identity_number"
                                        class="form-control" type="text">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="attachmend" class="form-label">Bukti / Lampiran</label>
                            <input id="attachmend" class="form-control" type="file" @change="changeAttachment"
                                accept="image/*">
                        </div>
                        <img class="img-fluid mt-3" :src="previewAttachment ?? formDataModal.attachment"
                            alt="Preview Gambar">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                        <button class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>

        <modal-address ref="refModalAddress" :formData="formDataModal" @submited="submitModal" />
    </div>
</template>

<route lang="yaml">
    meta:
        layout: admin
</route>
