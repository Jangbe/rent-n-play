<script setup>
import { GoogleMap } from 'vue3-google-map';
import { ref, watch } from 'vue';
import { useCoorStore } from '../stores/coor';

const props = defineProps({
    formData: { type: Object, default: {} },
    address: { type: Object, default: {} },
})

const gmaps_key = import.meta.env.VITE_GOOGLE_MAPS_KEY;
const center = useCoorStore();

const mapRef = ref(null)

var directionsService = null;
var directionsRenderer = null;
watch(() => mapRef.value?.ready, (ready) => {
    if (!ready) return

    directionsService = new mapRef.value.api.DirectionsService();
    directionsRenderer = new mapRef.value.api.DirectionsRenderer();

    directionsRenderer.setMap(mapRef.value.map);
})
watch(() => props.address, (address) => {
    const request = {
        origin: center,
        destination: { lat: parseFloat(address.lat), lng: parseFloat(address.lng) },
        travelMode: mapRef.value.api.DirectionsTravelMode.DRIVING
    };
    if (address.hasOwnProperty('lat'))
        directionsService.route(request, function (response, status) {
            if (status == mapRef.value.api.DirectionsStatus.OK) {
                const distance = (response.routes[0].legs[0].distance.value / 1000).toFixed(1);
                if (distance < 1) {
                    props.formData.delivery_fee = 10000;
                } else if (distance < 2) {
                    props.formData.delivery_fee = distance * 10000;
                } else {
                    props.formData.delivery_fee = 10000 + (distance - 1).toFixed(1) * 3500;
                }
                directionsRenderer.setDirections(response);
            } else {
                // oops, there's no route between these two locations
                // every time this happens, a kitten dies
                // so please, ensure your address is formatted properly
            }
        });
}, { deep: true });
</script>

<template>
    <GoogleMap :api-key="gmaps_key" ref="mapRef" style="width: 100%; height: 200px;" :center="center" :zoom="17"
        :libraries="['marker', 'places']" language="id" />
</template>