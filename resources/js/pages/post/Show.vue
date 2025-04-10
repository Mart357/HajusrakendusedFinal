<script setup lang="ts">
import Button from '@/components/ui/button/Button.vue';
import Textarea from '@/components/ui/textarea/Textarea.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';


defineProps(['post'])


const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Show post',
        href: '/posts/show',
    },
];
</script>

<template>
     <AppLayout :breadcrumbs="breadcrumbs">
        <div class="mx-auto my-12 w-full max-w-2xl">
            <h1 class="text-xl font-semibold tracking-wide">{{ post.title }}</h1>
            <p>{{ post.description }}</p>
        </div>
        <div class="mt-8">
            <form @submit.prevent="submit">
                <div class="relative">
                    <Textarea class="h-full w-full" />
                    <Button class="absolute bottom-4 right-4 z-10">Submit</Button>
                </div>
            </form>
        </div>
     </AppLayout>
</template>