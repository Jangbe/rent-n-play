<script setup>
import { read } from '@popperjs/core';
import { computed, onMounted, ref, watch } from 'vue';
import { GoogleMap, Marker } from 'vue3-google-map';
import { useCoorStore } from '../stores/coor';

const props = defineProps({
    formData: { type: Object, default: {} },
    loading: { type: Boolean, default: false }
})
const emit = defineEmits(['submited']);
const env = import.meta.env;
const gmaps_key = env.VITE_GOOGLE_MAPS_KEY;

const mapRef = ref(null)
const center = useCoorStore();
const r = ref(false);
const placeMarker = (location, zoom = false) => {
    if (!r.value) return setTimeout(placeMarker, 500)(location, zoom);
    if (marker) {
        marker.setPosition(location);
    } else {
        marker = new mapRef.value.api.Marker({
            position: location,
            map: mapRef.value.map
        });
    }
    marker.setVisible(true);
    props.formData.lat = location.lat();
    props.formData.lng = location.lng();
    if (zoom) {
        mapRef.value.map.setCenter(location);
        mapRef.value.map.setZoom(17);
    }
}

var marker = null;
watch(() => mapRef.value?.ready, (ready) => {
    r.value = ready;
    if (!ready) return
    mapRef.value.map.addListener('click', function (event) {
        placeMarker(event.latLng);
    });

    const input = document.getElementById('street_name');
    const autocomplete = new mapRef.value.api.places.Autocomplete(input);
    mapRef.value.api.event.addListener(autocomplete, 'place_changed', function () {
        props.formData.street_name = input.value;
        const place = autocomplete.getPlace();
        if (place.geometry) placeMarker(place.geometry.location, true)
    })
})

const setPlace = (data) => {
    const location = new mapRef.value.api.LatLng(parseFloat(data.lat), parseFloat(data.lng));
    placeMarker(location, true);
}
const removeMarker = () => {
    if (marker) marker.setVisible(false);
}
defineExpose({ setPlace, removeMarker });
</script>

<template>
    <div class="modal fade" id="modal-address">
        <div class="modal-dialog modal-fullscreen">
            <form action="#" class="modal-content" @submit.prevent="emit('submited')">
                <div class="modal-header">
                    <h5 class="modal-title">Form Alamat</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row mb-3">
                        <div class="col-md-4 col-12">
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
                                        <label for="contact_phone" class="form-label">Nomer Kontak</label>
                                        <input v-model="formData.contact_phone" id="contact_phone" class="form-control">
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-group">
                                        <label for="contact_name" class="form-label">Nama Kontak</label>
                                        <input v-model="formData.contact_name" id="contact_name" class="form-control">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-8 col-12">
                            <div class="form-group">
                                <label for="street_name" class="form-label">Alamat</label>
                                <input v-model="formData.street_name" id="street_name" class="form-control" type="text">
                            </div>
                            <div class="form-group">
                                <label for="detail" class="form-label">Detail</label>
                                <textarea v-model="formData.detail" rows="1" id="detail" class="form-control" />
                            </div>
                        </div>
                    </div>
                    <GoogleMap :api-key="gmaps_key" ref="mapRef" style="width: 100%; height: 500px;" :center="center"
                        :zoom="17" :libraries="['marker', 'places']" language="id">
                        <Marker :options="{ position: center, label: 'R', title: 'Rent N Play' }" />
                    </GoogleMap>
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
</template>

<style>
.pac-container {
    z-index: 10000 !important;
}
</style>