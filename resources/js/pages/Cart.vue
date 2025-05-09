<script setup lang="ts">
import Input from '@/components/ui/input/Input.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { router } from '@inertiajs/vue3';

const formatCurrency = (amount: number) => {
    return new Intl.NumberFormat('et', {
        style: 'currency',
        currency: 'EUR',
    }).format(amount);
};

const updateCart = (id: number, quantity: number | string) => {
    // Update the cart with the new quantity
    router.post(route('cart.update'), {
        id: id,
        quantity: quantity,
    });
};
// Provided cart data with image URLs
const props = defineProps({
    cart: Object,
});

// const totalPrice = computed(() => {    <=======SEE OLI SIIS KUI FRONT-s oli ARVUTIS
//   return Object.values(props.cart || {}).reduce((sum, item) => {
//     return sum + item.price * item.quantity;
//   }, 0);
// });
</script>

<template>
    <AppLayout>
        <div class="container mx-auto p-6">
            <h1 class="mb-6 text-2xl font-bold">Checkout</h1>
            <div class="rounded-lg bg-white p-4 shadow-md">
                <table class="w-full border-collapse text-left">
                    <thead>
                        <tr>
                            <th class="border-b px-4 py-2">Image</th>
                            <th class="border-b px-4 py-2">Product</th>
                            <th class="border-b px-4 py-2">Price</th>
                            <th class="border-b px-4 py-2">Quantity</th>
                            <th class="border-b px-4 py-2">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(item, id) in $page.props.cart" :key="id">
                            <td class="border-b px-4 py-2">
                                <img :src="item.image" alt="Product Image" class="h-16 w-16 rounded object-cover" />
                            </td>
                            <td class="border-b px-4 py-2">{{ item.name }}</td>
                            <td class="border-b px-4 py-2">{{ formatCurrency(item.price) }}</td>
                            <td class="border-b px-4 py-2">
                                <Input
                                    class="w-full max-w-24 text-right"
                                    @update:model-value="(value) => updateCart(id, value)"
                                    type="number"
                                    :min="0"
                                    :model-value="item.quantity"
                                >
                                </Input>
                            </td>
                            <td class="border-b px-4 py-2">{{ formatCurrency(item.price * item.quantity) }}</td>
                        </tr>
                    </tbody>
                </table>
                <div class="mt-4 flex justify-end">
                    <p class="text-lg font-bold">Total: {{ formatCurrency($page.props.cartTotal) }}</p>
                </div>
                <div class="mt-6 flex justify-end">
                    <button class="rounded bg-blue-600 px-6 py-2 text-white hover:bg-blue-700">Proceed to Payment</button>
                </div>
            </div>
        </div>
    </AppLayout>
</template>