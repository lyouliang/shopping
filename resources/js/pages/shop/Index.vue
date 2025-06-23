<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import {ref, computed} from 'vue';
import Navbar from '@/components/Navbar.vue';
const shopIndex = "This is the shop index page";
defineProps({ 'products': Array });

interface Product {
    id: number;
    name: string;
    price: number;
    image: string;
}

interface CartItem {
    product: Product;
    quantity: number;
}

//Cart state
const cartItems = ref<CartItem[]>([]);
const isCartOpen = ref(false);








</script>
<template>
    <div class="min-h-screen bg-gray-50">
        <Navbar :cartItems="cartItems" @toggle-cart="isCartOpen = !isCartOpen"/>
    </div>
    <div class="flex min-h-[840px] flex-col bg-white">
        <h1 class="text-black">{{ shopIndex }}</h1>
        <Link href="/login">Login</Link>
        <Link href="/register">Register</Link>
        <li v-for="product in products" :key="product.id">
            {{ product.name }}
        </li>
    </div>

</template>