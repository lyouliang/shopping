<script setup lang="ts">
    import { ref } from 'vue';
    const hello = ref("test");
</script>
<template>
    <div>
        <h1>{{ hello }}</h1>
    </div>
</template>