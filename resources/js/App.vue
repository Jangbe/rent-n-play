<script setup>
import { watch } from 'vue';
import { useUserStore } from './stores/user';
import { useOnlineStore } from './stores/online';

const user = useUserStore();
const online = useOnlineStore();
user.getUser();
watch(() => user.user, (user) => {
    if (user) online.join();
})
</script>

<template>
    <div>
        <RouterView></RouterView>
    </div>
</template>

<style></style>
