<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';


const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Show edit',
        href: '/posts/edit',
    },
];
</script>

<template>
     <AppLayout :breadcrumbs="breadcrumbs">
    <div>Edit</div>
     </AppLayout>
</template>