<template>
    <div class="bg-white">
        <div class="mx-auto max-w-2xl px-4 py-16 sm:px-6 sm:py-24 lg:max-w-7xl lg:px-8">
            <h2 class="text-2xl font-bold tracking-tight text-gray-900">Customers also purchased</h2>

            <div class="mt-6 grid grid-cols-1 gap-x-6 gap-y-10 sm:grid-cols-2 lg:grid-cols-4 xl:gap-x-8">
                <div v-for="product in props.products" :key="product.id" class="group relative">
                    <img :src="product.image_path" :alt="product.name"
                        class="aspect-square w-full rounded-md bg-gray-200 object-cover group-hover:opacity-75 lg:aspect-auto lg:h-80" />
                    <div class="mt-4 flex justify-between">
                        <div>
                            <h3 class="text-sm text-gray-700">
                                <a :href="product.image_path">
                                    <span aria-hidden="true" class="absolute inset-0" />
                                    {{ product.name }}
                                </a>
                            </h3>
                        </div>
                        <p class="text-sm font-medium text-gray-900">{{ product.price }}</p>
                    </div>
                </div>
            </div>
            <Card class="w-[350px]">
                <CardHeader>
                    <CardTitle>Create project</CardTitle>
                    <CardDescription>Deploy your new project in one-click.</CardDescription>
                </CardHeader>
                <CardContent>
                    <form>
                        <div class="grid items-center w-full gap-4">
                            <div class="flex flex-col space-y-1.5">
                                <Label for="name">Name</Label>
                                <Input id="name" placeholder="Name of your project" />
                            </div>
                            <div class="flex flex-col space-y-1.5">
                                <Label for="framework">Framework</Label>
                                <Select>
                                    <SelectTrigger id="framework">
                                        <SelectValue placeholder="Select" />
                                    </SelectTrigger>
                                    <SelectContent position="popper">
                                        <SelectItem value="nuxt">
                                            Nuxt
                                        </SelectItem>
                                        <SelectItem value="next">
                                            Next.js
                                        </SelectItem>
                                        <SelectItem value="sveltekit">
                                            SvelteKit
                                        </SelectItem>
                                        <SelectItem value="astro">
                                            Astro
                                        </SelectItem>
                                    </SelectContent>
                                </Select>
                            </div>
                        </div>
                    </form>
                </CardContent>
                <CardFooter class="flex justify-between px-6 pb-6">
                    <Button variant="outline">
                        Cancel
                    </Button>
                    <Button>Deploy</Button>
                </CardFooter>
            </Card>
        </div>
    </div>
    <!-- <div class="min-h-screen bg-gray-50">
        <Navbar :cartItems="cartItems" @toggle-cart="isCartOpen = !isCartOpen" />
        <main class="container mx-auto px-4 py-8">
            <div class="flex flex-col md:flex-row gap-6">
                <Card v-for="product in props.products" :key="product.id">
                    <div>
                        <img :src="product.image_path" :alt="product.name" class="aspect-square w-full rounded-md bg-gray-200 object-cover group-hover:opacity-75 lg:aspect-auto lg:h-80" />
                    </div>
                    <CardContent>
                        <h3>{{ product.name }}</h3>
                        <div>
                            <span class="font-bold">${{ product.price }}</span>
                            <Button size="sm" @click="$emit('add-to-cart')">
                                <ShoppingCart class="h-4 w-4 mr-1" />
                                Add to Cart
                            </Button>
                        </div>
                    </CardContent>
                </Card>
            </div>
        </main>
    </div> -->
</template>
<script setup lang="ts">

import { Button } from '@/components/ui/button'
import {
    Card,
    CardContent,
    CardDescription,
    CardFooter,
    CardHeader,
    CardTitle,
} from '@/components/ui/card'
import { Input } from '@/components/ui/input'
import { Label } from '@/components/ui/label'
import {
    Select,
    SelectContent,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from '@/components/ui/select'

import { ref } from 'vue';
import Navbar from '@/components/Navbar.vue';
import { ShoppingCart } from 'lucide-vue-next';
import axios from 'axios';
import { onMounted } from 'vue';

const getCart = async () => {
    try {
        const response = await axios.get('/cart');
        console.log(response);
    } catch (error) {
        console.error(error);
    }
}

onMounted(() => {
    getCart();
})

const props = defineProps({
    'products': Object
});

interface Product {
    id: number;
    name: string;
    price: number;
    image_path: string;
}

interface CartItem {
    product: Product;
    quantity: number;
}

//Cart state
const cartItems = ref<CartItem[]>([]);
const isCartOpen = ref(false);

const addToCart = (product: Product) => {
    const existingItems = cartItems.value.find(item => item.product.id === product.id);
    if (existingItems) {
        existingItems.quantity += 1;
    } else {
        cartItems.value.push({
            product,
            quantity: 1
        });
    }
    isCartOpen.value = true;
};

const removeFromCart = (productId: number) => {
    cartItems.value = cartItems.value.filter(item => item.product.id !== productId);
};

const updateCartQuantity = (productId: number, quantity: number) => {
    const item = cartItems.value.find(item => item.product.id === productId);
    if (item) {
        if (quantity <= 0) {
            removeFromCart(productId);
        } else {
            item.quantity = quantity;
        }
    }
};

</script>
