<script setup lang="ts">

import CartSheet from '@/components/CartSheet.vue';
import { Card, CardContent, CardFooter } from '@/components/ui/card';
import { useForm, router} from '@inertiajs/vue3';
import { ref, computed, watch } from 'vue';
import debounce from 'lodash/debounce';

const props = defineProps({
    products: Array,
    auth: Object,
    cart: Object,
    filters: Object,
});

const search = ref(props.filters.search || '');

const isSheetOpen = ref(false);

const forms = ref(props.products.reduce((acc, product) => {
    acc[product.id] = useForm({
        product_id: product.id,
        quantity: 1,
    });
    return acc;
}, {}));

watch(() => props.products, (newProducts) => {
    forms.value = newProducts.reduce((acc, product) => {
        acc[product.id] = forms.value[product.id] || useForm({
            product_id: product.id,
            quantity: 1,
        });
        return acc;
    }, {});
});

const performSearch = debounce(() => {
    router.get(
        route('shop.index'),
        { search: search.value },
        {
            preserveState: true,
            preserveScroll: true,
            replace: true, // Update URL without adding to history
        }
    );
}, 300);

watch(search, () => {
    performSearch();
});


const updateForms = ref(props.cart.items.reduce((acc, item) => {
    acc[item.id] = useForm({
        quantity: item.quantity,
    });
    return acc;
}, {}));

function addToCart(productId) {
    forms.value[productId].post(route('cart.add'), {
        onSuccess: () => {
            console.log('Item added to cart');
            isSheetOpen.value = true; // Open sheet after adding item
        },
        onError: (errors) => console.error('Form submission errors:', errors),
        preserveState: true,
        preserveScroll: true,
    });
}

function updateQuantity(itemId) {
  updateForms.value[itemId].put(route('cart.update', itemId), {
    onSuccess: () => console.log('Quantity updated'),
    onError: (errors) => console.error('Update error:', errors),
    preserveState: true,
    preserveScroll: true,
  });
}

const itemCount = computed(() => {
    return props.cart.items.reduce((total, item) => total + item.quantity, 0);
});

const totalPrice = computed(() => {
    return props.cart.items.reduce((total, item) => total + item.quantity * item.product.price, 0);
});

</script>
<template>
    <div class="container mx-auto p-4 relative">
        <div>
            <nav class="bg-white border-gray-200">
                <div class="max-w-screen-xl flex flex-wrap items-center justify-between mx-auto p-4">
                    <a href="/" class="flex items-center space-x-3 rtl:space-x-reverse">
                        <span
                            class="self-center text-2xl font-semibold whitespace-nowrap drop-shadow-gray-900">Apparel-Inc</span>
                    </a>
                    <button data-collapse-toggle="navbar-default" type="button"
                        class="inline-flex items-center p-2 w-10 h-10 justify-center text-sm text-gray-500 rounded-lg md:hidden hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200 dark:text-gray-400 dark:hover:bg-gray-700 dark:focus:ring-gray-600"
                        aria-controls="navbar-default" aria-expanded="false">
                        <span class="sr-only">Open main menu</span>
                        <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 17 14">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M1 1h15M1 7h15M1 13h15" />
                        </svg>
                    </button>
                </div>
            </nav>

        </div>

        <!-- Cart Toggle Button -->
        <button @click="isSheetOpen = true"
            class="fixed top-4 right-4 bg-gray-800 text-white p-3 rounded-full flex items-center z-50">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
            </svg>
            <span class="ml-2">{{ itemCount }}</span>
        </button>
        <CartSheet :cart="cart" :is-open="isSheetOpen" @update:is-open="isSheetOpen = $event" />

        <!-- Search Bar -->
        <div class="w-3/4">
            <label for="search" class="sr-only">Search products</label>
            <input v-model="search" type="text" id="search" placeholder="Search products by name..."
                class="w-full p-2 border rounded-lg focus:ring-2 focus:ring-gray-300" />
        </div>

        <!-- Products Section -->
        <h1 class="text-2xl font-bold mb-6">Products</h1>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            <Card v-for="product in products" :key="product.id" class="group relative">
                <CardContent>
                    <div>
                        <img :src="product.image_path" :alt="product.name"
                            class="aspect-auto w-full rounded-md bg-gray-200 object-cover group-hover:opacity-75 lg:aspect-auto lg:h-80" />
                        <div class="mt-4 flex justify-between">
                            <div>
                                <h3 class="text-sm text-gray-700">
                                    <a :href="product.href">{{ product.name }}</a>
                                </h3>
                                <p class="mt-1 text-sm text-gray-500">{{ product.color }}</p>
                            </div>
                            <p class="text-sm font-medium text-gray-900">${{ product.price }}</p>
                        </div>
                    </div>
                </CardContent>
                <CardFooter class="flex justify-end px-6 pb-6">
                    <div>
                        <form @submit.prevent="addToCart(product.id)">
                            <input type="hidden" v-model="forms[product.id].product_id" />
                            <button type="submit"
                                class="text-gray-900 bg-white border border-gray-300 hover:bg-gray-100 focus:ring-4 focus:ring-gray-100 font-medium rounded-lg text-sm px-5 py-2.5 ml-2">
                                Add to Cart
                            </button>
                            <span v-if="forms[product.id].errors.quantity" class="error block mt-1">
                                {{ forms[product.id].errors.quantity }}
                            </span>
                            <span v-if="forms[product.id].errors.product_id" class="error block mt-1">
                                {{ forms[product.id].errors.product_id }}
                            </span>
                        </form>
                    </div>
                </CardFooter>
            </Card>
        </div>
    </div>
</template>
