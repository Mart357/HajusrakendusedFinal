<script setup lang="ts">
import { useForm } from '@inertiajs/vue3';

const form = useForm({
    title: '',
    description: '',
    image: '',
    author: '',
    publication_year: '',
});

function saveMovie() {
    form.post(route('subjects.store'));
}
</script>

<template>
    <div class="max-w-lg mx-auto p-8">
        <h1 class="text-2xl font-bold mb-4">Add New Movie</h1>
        <form @submit.prevent="saveMovie" class="space-y-4">
            <div>
                <label class="block mb-1 font-medium">Title</label>
                <input v-model="form.title" type="text" class="w-full border rounded p-2" required />
            </div>
            <div>
                <label class="block mb-1 font-medium">Description</label>
                <textarea v-model="form.description" class="w-full border rounded p-2" />
            </div>
            <div>
                <label class="block mb-1 font-medium">Image URL</label>
                <input v-model="form.image" type="text" class="w-full border rounded p-2" />
            </div>
            <div>
                <label class="block mb-1 font-medium">Director</label>
                <input v-model="form.author" type="text" class="w-full border rounded p-2" />
            </div>
            <div>
                <label class="block mb-1 font-medium">Release Year</label>
                <input v-model="form.publication_year" type="text" class="w-full border rounded p-2" />
            </div>
            <button type="submit" class="rounded bg-blue-600 px-4 py-2 text-white hover:bg-blue-700">Save movie</button>
        </form>
    </div>
</template>