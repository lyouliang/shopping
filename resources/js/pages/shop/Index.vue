<script setup lang="ts">

import CartSheet from '@/components/CartSheet.vue';
import NavMain from '@/components/NavMain.vue';
import { Card, CardContent } from '@/components/ui/card';
import { NavigationMenu, NavigationMenuContent } from '@/components/ui/navigation-menu';
import { useForm, router, usePage } from '@inertiajs/vue3';
import { ref, computed, watch} from 'vue';
import debounce from 'lodash/debounce';


const props = defineProps({
    products: Array,
    auth: Object,
    cart: Object,
    filters: Object,
});

const page = usePage();

// Initialize search input from filters
const search = ref(props.filters.search || '');

// Toggle sheet visibility
const isSheetOpen = ref(false);

const forms = ref(props.products.reduce((acc, product) => {
    acc[product.id] = useForm({
        product_id: product.id,
        quantity: 1,
    });
    return acc;
}, {}));

// Update forms when products change (due to search)
watch(() => props.products, (newProducts) => {
  forms.value = newProducts.reduce((acc, product) => {
    acc[product.id] = forms.value[product.id] || useForm({
      product_id: product.id,
      quantity: 1,
    });
    return acc;
  }, {});
});

// Debounced search function
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

// Watch search input for changes
watch(search, () => {
  performSearch();
});


// Initialize forms for updating cart item quantities
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
    });
}

function updateQuantity(itemId) {
    updateForms.value[itemId].put(route('cart.update', itemId), {
        onSuccess: () => console.log('Quantity updated'),
        onError: (errors) => console.error('Update error:', errors),
        preserveState: true,
    });
}

function removeItem(itemId) {
    router.delete(route('cart.remove', itemId), {
        onSuccess: () => console.log('Item removed'),
        onError: (errors) => console.error('Remove error:', errors),
        preserveState: true,
    });
}

// Compute total items and price
const itemCount = computed(() => {
    return props.cart.items.reduce((total, item) => total + item.quantity, 0);
});

const totalPrice = computed(() => {
    return props.cart.items.reduce((total, item) => total + item.quantity * item.product.price, 0);
});

</script>
<template>
    <div class="container mx-auto p-4 relative">
        <!-- Cart Toggle Button -->
        <button @click="isSheetOpen = true"
            class="fixed top-4 right-4 bg-gray-800 text-white p-3 rounded-full flex items-center">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
            </svg>
            <span class="ml-2">{{ itemCount }}</span>
        </button>
        <CartSheet :cart="cart" :is-open="isSheetOpen" @update:is-open="isSheetOpen = $event" />

        <!-- Search Bar -->
        <div class="mb-6">
            <label for="search" class="sr-only">Search products</label>
            <input v-model="search" type="text" id="search" placeholder="Search products by name..."
                class="w-full p-2 border rounded-lg focus:ring-2 focus:ring-gray-300" />
        </div>

        <!-- Products Section -->
        <h1 class="text-2xl font-bold mb-6">Products</h1>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            <div v-for="product in products" :key="product.id" class="group relative">
                <div>
                    <img :src="product.image_path" :alt="product.name"
                        class="aspect-square w-full rounded-md bg-gray-200 object-cover group-hover:opacity-75 lg:aspect-auto lg:h-80" />
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
                <div class="mt-4">
                    <form @submit.prevent="addToCart(product.id)" class="relative z-10">
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
            </div>
        </div>
    </div>
</template>
