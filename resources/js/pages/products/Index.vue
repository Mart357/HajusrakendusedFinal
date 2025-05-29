<script setup lang="ts">
import Button from '@/components/ui/button/Button.vue';
import Card from '@/components/ui/card/Card.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { ShoppingCart, Trash } from 'lucide-vue-next';
import { Link, router } from '@inertiajs/vue3';

const props = defineProps(['products', 'success', 'message']);

const addToCart = (product: any) => {
    router.post(route('cart.add', product), undefined, {
        preserveScroll: true,
    });
};

const clear = () => {
    router.post(route('cart.clear'));
};

const formatCurrency = (amount: number) => {
    return new Intl.NumberFormat('et', {
        style: 'currency',
        currency: 'EUR',
    }).format(amount);
};
</script>

<template>
    <AppLayout>
        <div v-if="success" class="p-4 bg-green-100 text-green-700 rounded mb-4">
            {{ message }}
        </div>

        <!-- Cart Controls Bar -->
        <div class="sticky top-4 z-10 flex justify-end mb-8">
            <div class="flex gap-2 bg-white/80 backdrop-blur rounded-lg shadow px-4 py-2 items-center">
                <Button @click="clear" size="icon" variant="destructive" title="Clear Cart">
                    <Trash class="size-5" />
                </Button>
                <Button :as="Link" :href="route('cart.checkout')" size="icon" variant="outline" class="relative" title="Go to Cart">
                    <ShoppingCart class="h-6 w-6" />
                    <div v-if="$page.props.cartCount > 0"
                        class="absolute -top-1 -right-1 flex items-center justify-center rounded-full bg-red-500 text-white text-[10px] font-medium min-h-5 min-w-5">
                        {{ $page.props.cartCount }}
                    </div>
                </Button>
            </div>
        </div>

        <!-- Products Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
            <Card v-for="product in products" :key="product.id" class="flex flex-col p-4 shadow hover:shadow-lg transition">
                <div class="flex justify-center items-center h-56 mb-4">
                    <img :src="product.image" :alt="product.name" class="max-h-full max-w-full object-contain rounded" />
                </div>
                <h2 class="text-lg font-semibold mt-2">{{ product.name }}</h2>
                <p class="text-gray-600 mt-1 line-clamp-2">{{ product.description }}</p>
                <p class="text-xl font-bold mt-3 mb-2">{{ formatCurrency(product.price) }}</p>
                <Button class="mt-auto w-full" @click="addToCart(product)">Add to Cart</Button>
            </Card>
        </div>
    </AppLayout>
</template>