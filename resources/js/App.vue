<script setup>
import { useUserStore } from './stores/user';

const user = useUserStore();
user.getUser();
</script>

<template>
    <div>
        <RouterView></RouterView>
    </div>
</template>

<style></style>
