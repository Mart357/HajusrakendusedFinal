<script setup lang="ts">
import Button from '@/components/ui/button/Button.vue';
import Textarea from '@/components/ui/textarea/Textarea.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { BreadcrumbItem } from '@/types';
import { useForm } from '@inertiajs/vue3';


const props = defineProps(['post'])

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Show post',
        href: '/posts/show',
    },
];

const form = useForm({
    comment: ''
})

const submit = () => {
    form.post(route('comments.store', props.post), {
        preserveScroll: true,
        onSuccess: () => {
            form.reset()
        }
    })
};



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
                        <Textarea v-model="form.comment" class="h-full w-full"></Textarea>
                        <Button class="absolute bottom-4 right-4 z-10">Submit</Button>
                    </div>
                </form>
                <div class="mt-4">
                    <h2 class="text-lg font-semibold">Comments</h2>
                    <ul>
                        <li v-for="comment in post.comments" :key="comment.id" class="border-b py-2">
                            <p class="text-sm text-gray-600">{{ comment.user.name }}:</p>
                            <p>{{ comment.comment }}</p>
                            <p class="text-xs text-gray-500">
                                
                                 {{ Math.floor((new Date() - new Date(comment.created_at)) / 60000) }} minutes ago
                            </p>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </AppLayout>
</template>