<script setup lang="ts">
import { Link, router } from '@inertiajs/vue3';
import Button from '@/components/ui/button/Button.vue';
import AppLayout from '@/layouts/AppLayout.vue';

defineProps(['posts']);

const deletePost = (postId) => {
    if (confirm('Are you sure you want to delete this post?')) {
        router.delete(route('posts.destroy', postId));
    }
};
</script>

<template>
    <AppLayout>
        <div class="mx-auto my-12 w-full max-w-3xl">
            <div class="mb-4 flex justify-end">
                <Button :as="Link" :href="route('posts.create')" variant="primary">
                    Add New Post
                </Button>
            </div>
            <table class="min-w-full border">
                <thead>
                    <tr>
                        <th class="px-4 py-2 border">Title</th>
                        <th class="px-4 py-2 border">Created At</th>
                        <th class="px-4 py-2 border">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="post in posts" :key="post.id">
                        <td class="px-4 py-2 border">{{ post.title }}</td>
                        <td class="px-4 py-2 border">{{ post.created_at_for_humans }}</td>
                        <td class="px-4 py-2 border flex gap-2">
                            <Button :as="Link" :href="route('posts.show', post.id)" size="sm">View</Button>
                            <Button :as="Link" :href="route('posts.edit', post.id)" size="sm" variant="outline">Edit</Button>
                            <Button @click="deletePost(post.id)" size="sm" variant="destructive">Delete</Button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </AppLayout>
</template>