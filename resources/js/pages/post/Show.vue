<script setup lang="ts">
import Button from '@/components/ui/button/Button.vue';
import Textarea from '@/components/ui/textarea/Textarea.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { BreadcrumbItem } from '@/types';

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
        <div class="my-12 mx-auto w-full max-w-2xl">
           <div>
             <h1 class="text-2xl font-semibold tracking wide">{{ post.title }}</h1>
             <p class="">{{ post.description }}</p>
            <!-- {{ post }} -->
           </div>
           <div>
                <form @submit.prevent="submit">
                    <div class="relative">
                        <Textarea class="h-full w-full"></Textarea>
                        <Button class="absolute bottom-4 right-4 z-10">Submit</Button>
                    </div>
                </form>
            </div>
        </div>
    </AppLayout>
</template>