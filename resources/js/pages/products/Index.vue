<script setup lang="ts">
import Button from '@/components/ui/button/Button.vue';
import Card from '@/components/ui/card/Card.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { ShoppingCart, Trash } from 'lucide-vue-next';
import { Link, router } from '@inertiajs/vue3';



const props = defineProps(['products']);

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
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        {{  $page.props.cart }}
        <div>
            <Button @click="clear" size="icon" variant="destructive" class="relative">
                <Trash class="size-5" />
            </Button>
                        <Button :as="Link" :href="route('cart.checkout')" size="icon" variant="outline" class="relative">
                <ShoppingCart class="h-6 w-6" />
                <div class="absolute -top-1 -right-1 flex items-center justify-center rounded-full bg-red-500 text-white text-[10px] font-medium min-h-5 min-w-5">
                    {{ $page.props.cartCount }}
                </div>
            </Button>
        </div>
        <Card v-for="product in products" :key="product.id" class="product-item flex w-80 flex-col p-4">
        <div class="size-72 object-contain">
            <img :src="product.image" :alt="product.name" />
        </div>
            <h2 class="text-lg font-semibold mt-4">{{ product.name }}</h2>
            <p class="text-gray-600 mt-2">{{ product.description }}</p>
            <p class="text-xl font-bold mt-4">{{ formatCurrency(product.price) }}</p>
            <Button class="mt-4 w-full" @click="addToCart(product)">Add to Cart</Button>
        </Card>
    </div>
</AppLayout>
</template>