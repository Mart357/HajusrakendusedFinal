<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { BreadcrumbItem } from '@/types';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Edit post',
        href: '/posts/edit',
    },
];
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbs">
        <div>Edit</div>
    </AppLayout>
    
</template>