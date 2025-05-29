<script setup lang="ts">
import Button from '@/components/ui/button/Button.vue';
import Card from '@/components/ui/card/Card.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { Link } from '@inertiajs/vue3';
import { ref, computed } from 'vue';

const props = defineProps(['movies', 'otherMovies']);

const search = ref('');

// Only show movies with a non-empty title
const filteredMovies = computed(() => {
    const addedMovies = props.movies.filter(movie => (movie.title ?? '').trim() !== '');
    if (!search.value) return addedMovies;
    return addedMovies.filter(movie =>
        (movie.title ?? '').toLowerCase().includes(search.value.toLowerCase()) ||
        (movie.description ?? '').toLowerCase().includes(search.value.toLowerCase()) ||
        (movie.author ?? '').toLowerCase().includes(search.value.toLowerCase()) ||
        (movie.publication_year ?? '').toLowerCase().includes(search.value.toLowerCase())
    );
});
</script>

<template>
    <AppLayout>
        <!-- Header and Add Button -->
        <div class="flex justify-between items-center mb-8">
            <h1 class="text-2xl font-bold">Movies</h1>
            <Button :as="Link" :href="route('subjects.create')" variant="primary">
                Add new movie
            </Button>
        </div>

        <!-- Search Bar -->
        <input
            v-model="search"
            type="text"
            placeholder="Search movies..."
            class="w-full mb-8 border rounded p-2"
        />

        <!-- Movies Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
            <Card v-for="movie in filteredMovies" :key="movie.id" class="flex flex-col p-4 shadow hover:shadow-lg transition">
                <div class="flex justify-center items-center h-56 mb-4">
                    <img v-if="movie.image" :src="movie.image" :alt="movie.title" class="max-h-full max-w-full object-contain rounded" />
                </div>
                <h2 class="text-lg font-semibold mt-2">{{ movie.title }}</h2>
                <p class="text-gray-600 mt-1 line-clamp-2">{{ movie.description }}</p>
                <div class="mt-3 text-sm text-gray-700">
                    <div><span class="font-semibold">Director:</span> {{ movie.author }}</div>
                    <div><span class="font-semibold">Release year:</span> {{ movie.publication_year }}</div>
                </div>
            </Card>
        </div>

        <!-- Teiste isikute filmid -->
        <div class="mt-12">
            <h2 class="text-xl font-bold mb-4">Teiste kasutajate filmid</h2>
            <div v-if="props.otherMovies && props.otherMovies.length" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
                <Card v-for="movie in props.otherMovies" :key="movie.id" class="flex flex-col p-4 shadow hover:shadow-lg transition">
                    <div class="flex justify-center items-center h-56 mb-4">
                        <img v-if="movie.image" :src="movie.image" :alt="movie.title" class="max-h-full max-w-full object-contain rounded" />
                    </div>
                    <h2 class="text-lg font-semibold mt-2">{{ movie.title }}</h2>
                    <p class="text-gray-600 mt-1 line-clamp-2">{{ movie.description }}</p>
                    <div class="mt-3 text-sm text-gray-700">
                        <div><span class="font-semibold">Director:</span> {{ movie.author }}</div>
                        <div><span class="font-semibold">Release year:</span> {{ movie.publication_year }}</div>
                    </div>
                </Card>
            </div>
            <div v-else class="text-gray-500">Teiste kasutajate filme ei leitud.</div>
        </div>
    </AppLayout>
</template>