<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { BreadcrumbItem } from '@/types';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Posts',
        href: '/posts',
    },
];
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbs">
        <div>index</div>
    </AppLayout>
    
</template>