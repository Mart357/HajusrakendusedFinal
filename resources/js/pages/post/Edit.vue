<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { useForm } from '@inertiajs/vue3';

// Props passed from the backend
const props = defineProps({
    post: Object,
});

// Use Inertia's `useForm` to handle form state and submission
const form = useForm({
    title: props.post.title,
    description: props.post.description,
});

// Function to handle form submission
const submit = () => {
    form.put(route('posts.update', props.post.id), {
        onSuccess: () => {
            alert('Post updated successfully!');
        },
    });
};
</script>

<template>
    <AppLayout>
        <div class="max-w-2xl mx-auto my-12">
            <h1 class="text-2xl font-bold mb-6">Edit Post</h1>
            <form @submit.prevent="submit" class="space-y-4">
                <div>
                    <label class="block mb-1">Title</label>
                    <input
                        v-model="form.title"
                        type="text"
                        class="w-full border rounded p-2"
                        required
                    />
                </div>
                <div>
                    <label class="block mb-1">Description</label>
                    <textarea
                        v-model="form.description"
                        class="w-full border rounded p-2"
                        required
                    ></textarea>
                </div>
                <Button type="submit">Update Post</Button>
            </form>
        </div>
    </AppLayout>
</template>