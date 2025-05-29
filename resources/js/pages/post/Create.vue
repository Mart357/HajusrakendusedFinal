<script setup lang="ts">

import Button from '@/components/ui/button/Button.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { useForm } from '@inertiajs/vue3';

const form = useForm({
    title: '',
    description: '',
});

const submit = () => {
    form.post(route('posts.store'), {
        onSuccess: () => form.reset(),
    });
};

</script>

<template>
    <AppLayout>
        <div class="max-w-2xl mx-auto my-12">
            <h1 class="text-2xl font-bold mb-6">Add New Post</h1>
            <form @submit.prevent="submit" class="space-y-4">
                <div>
                    <label class="block mb-1">Title</label>
                    <input v-model="form.title" type="text" class="w-full border rounded p-2" required />
                </div>
                <div>
                    <label class="block mb-1">Description</label>
                    <textarea v-model="form.description" class="w-full border rounded p-2" required></textarea>
                </div>
                <Button type="submit">Submit</Button>
            </form>
        </div>
    </AppLayout>
</template>