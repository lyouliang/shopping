<template>
  <div>
    <h1>Shopping Cart ({{ itemCount }} items)</h1>
    <div v-if="cart.items.length === 0">Your cart is empty</div>
    <ul v-else>
      <li v-for="item in cart.items" :key="item.id">
        {{ item.product.name }} - {{ item.quantity }} x ${{ item.product.price }}
        <button @click="updateQuantity(item.id, item.quantity + 1)">+</button>
        <button @click="updateQuantity(item.id, item.quantity - 1)" :disabled="item.quantity <= 1">-</button>
        <button @click="removeItem(item.id)">Remove</button>
      </li>
    </ul>
    <p>Total: ${{ totalPrice }}</p>
  </div>
</template>

<script setup lang="ts">
import { computed } from 'vue';
import { router } from '@inertiajs/vue3';

const props = defineProps({
  cart: Object,
  auth: Object,
});

const itemCount = computed(() => {
  return props.cart.items.reduce((total, item) => total + item.quantity, 0);
});

const totalPrice = computed(() => {
  return props.cart.items.reduce((total, item) => total + item.quantity * item.product.price, 0);
});

function updateQuantity(itemId, quantity) {
  router.put(route('cart.update', itemId), { quantity }, {
    preserveState: true,
    onError: (errors) => {
      console.error('Update error:', errors);
    },
  });
}

function removeItem(itemId) {
  router.delete(route('cart.remove', itemId), {
    preserveState: true,
  });
}
</script>

<style>
.error {
  color: red;
}
</style>