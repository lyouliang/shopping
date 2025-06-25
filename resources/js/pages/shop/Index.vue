<template>
    <div>
        <Card v-for="product in props.products" :key="product.id">
            <CardContent>
                <form @submit.prevent="addToCart(product.id)">
                    <button type="submit">{{ addToCartText }}</button>
                </form>
            </CardContent>
        </Card>
    </div>
</template>
<script setup lang="ts">

import {Card, CardContent }from '@/components/ui/card';
import { useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps({
    products: Array,
    auth: Object,
});

function addToCart(productId: string) {
    form.product_id = productId;
    form.post(route('cart.add'), {
        onError:(errors) => {
            console.error('Form submission errors:', errors);
        }
    })
}

const addToCartText = "Add To Cart";

const form = useForm({
    quantity: 1,
    product_id: '',
})
</script>
