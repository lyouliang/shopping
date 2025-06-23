<script setup lang="ts">
    import { Link } from '@inertiajs/vue3';
    const page = "This is the shop register page";
</script>
<template>
        <h1>{{ page }}</h1>
        <Link href="/login">Login</Link>
        <Link href="/register">Register</Link>
</template>