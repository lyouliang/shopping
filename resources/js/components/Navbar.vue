<script setup lang="ts">
import { Button } from './ui/button';
import Badge from './ui/badge/Badge.vue';
import { ShoppingBag, Menu } from 'lucide-vue-next';
import { computed } from 'vue';

interface CartItem {
  product: {
    id: number;
    name: string;
    price: number;
    image: string;
  };
  quantity: number;
}

const props = defineProps<{
  cartItems: CartItem[];
}>();

const emit = defineEmits(['toggle-cart']);

const totalItems = computed(() => {
  return props.cartItems.reduce((total, item) => total + item.quantity, 0);
});

</script>
<template>
  <header class="bg-white shadow-sm sticky top-0 z-10">
    <div class="container mx-auto px-4 py-4">
      <div class="flex items-center justify-between">
        <div class="flex items-center space-x-4">
          <h1 class="text-xl font-bold">T-Shirt Shop</h1>
          <nav class="hidden md:flex space-x-4">
            <a href="#" class="text-gray-600 hover:text-gray-900">Home</a>
            <a href="#" class="text-gray-600 hover:text-gray-900">Shop</a>
            <a href="#" class="text-gray-600 hover:text-gray-900">About</a>
            <a href="#" class="text-gray-600 hover:text-gray-900">Contact</a>
          </nav>
        </div>
        
        <div class="flex items-center space-x-4">
          <div class="relative">
            <Button variant="ghost" @click="$emit('toggle-cart')">
              <ShoppingBag class="h-5 w-5 mr-1" />
              <span>Cart</span>
              <Badge v-if="totalItems > 0" class="ml-1">{{ totalItems }}</Badge>
            </Button>
          </div>
          
          <Button variant="ghost" size="icon" class="md:hidden">
            <Menu class="h-5 w-5" />
          </Button>
        </div>
      </div>
    </div>
  </header>
</template>