<!-- resources/js/Components/CartSheet.vue -->
<script setup lang="ts">
import { ref, computed } from 'vue';
import { useForm, router } from '@inertiajs/vue3';

const props = defineProps({
  cart: Object,
  isOpen: Boolean,
});

const emit = defineEmits(['update:isOpen']);

const updateForms = ref(props.cart.items.reduce((acc, item) => {
  acc[item.id] = useForm({
    quantity: item.quantity,
  });
  return acc;
}, {}));

function updateQuantity(itemId) {
  updateForms.value[itemId].put(route('cart.update', itemId), {
    preserveState: true,
  });
}

function removeItem(itemId) {
  router.delete(route('cart.remove', itemId), {
    preserveState: true,
  });
}

const totalPrice = computed(() => {
  return props.cart.items.reduce((total, item) => total + item.quantity * item.product.price, 0);
});
</script>

<template>
  <div v-if="isOpen" class="fixed inset-0 bg-black bg-opacity-50 z-40" @click="emit('update:isOpen', false)"></div>
  <div
    class="fixed top-0 right-0 h-full w-full sm:w-96 bg-white shadow-lg z-50 transform transition-transform duration-300"
    :class="{ 'translate-x-0': isOpen, 'translate-x-full': !isOpen }"
  >
    <div class="p-4 h-full flex flex-col">
      <div class="flex justify-between items-center mb-4">
        <h2 class="text-xl font-bold">Your Cart</h2>
        <button @click="emit('update:isOpen', false)" class="text-gray-500 hover:text-gray-700">
          <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
          </svg>
        </button>
      </div>
      <p v-if="$page.props.flash.error" class="text-red-500 mb-4">{{ $page.props.flash.error }}</p>
      <div v-if="cart.items.length === 0" class="text-gray-500 flex-grow">Your cart is empty</div>
      <div v-else class="flex-grow overflow-y-auto">
        <div v-for="item in cart.items" :key="item.id" class="flex items-start mb-4 border-b pb-4">
          <img
            :src="item.product.image_path"
            :alt="item.product.name"
            class="w-16 h-16 object-cover rounded-md mr-4"
          />
          <div class="flex-grow">
            <p class="text-sm font-medium">{{ item.product.name }}</p>
            <p class="text-sm text-gray-500">${{ item.product.price }} x {{ item.quantity }}</p>
            <!-- <form @submit.prevent="updateQuantity(item.id)" class="flex items-center">
              <input
                v-model.number="updateForms[item.id].quantity"
                type="number"
                min="1"
                class="w-16 p-1 border rounded"
              />
              <button type="submit" class="ml-2 text-sm text-blue-500 hover:underline">Update</button>
            </form> -->
            <p class="text-sm font-bold">${{ (item.quantity * item.product.price).toFixed(2) }}</p>
            <button
              @click="removeItem(item.id)"
              class="text-red-500 text-sm hover:underline"
            >
              Remove
            </button>
          </div>
        </div>
      </div>
      <div class="border-t pt-4">
        <p class="text-lg font-bold">Total: ${{ totalPrice.toFixed(2) }}</p>
        <a
          :href="route('payment.index')"
          class="block text-center bg-gray-800 text-white py-2 rounded mt-4 hover:bg-gray-700"
        >
          Checkout
        </a>
      </div>
    </div>
  </div>
</template>