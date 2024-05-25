<script setup>
import { ref, watch } from 'vue';

const props = defineProps({
    text: { type: String, required: true },
    length: { type: Number, default: 100 },
})
const result = ref(props.text.substring(0, props.length));
const more = ref(false);
watch(() => props.text, (text) => {
    result.value = text.substring(0, props.length);
})
const toggle = () => {
    more.value = !more.value;
    result.value = more.value ? props.text : props.text.substring(0, props.length);
}
</script>

<template>
    <span>
        {{ result }}
        <span @click="toggle" class="text-primary" style="cursor: pointer">
            {{ more ? 'sedikit' : '...selengkapnya' }}
        </span>
    </span>
</template>