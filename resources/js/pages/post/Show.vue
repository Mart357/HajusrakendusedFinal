<script setup lang="ts">
import Button from '@/components/ui/button/Button.vue';
import Textarea from '@/components/ui/textarea/Textarea.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { useForm, router } from '@inertiajs/vue3';

const props = defineProps(['post']);

const form = useForm({
    author: '',
    content: '',
});

const submit = () => {
    form.post(route('comments.store', props.post.id), {
        preserveScroll: true,
        onSuccess: () => form.reset(),
    });
};

function deleteComment(commentId: number) {
    if (confirm('Are you sure you want to delete this comment?')) {
        router.delete(route('comments.destroy', commentId));
    }
}
</script>

<template>
    <AppLayout>
        <div class="my-12 mx-auto w-full max-w-2xl">
            <h1 class="text-2xl font-semibold mb-2">{{ post.title }}</h1>
            <p class="mb-6">{{ post.description }}</p>
            <form @submit.prevent="submit" class="mb-6">
                <input v-model="form.author" placeholder="Your Name" required class="mb-2 w-full border rounded p-2" />
                <Textarea v-model="form.content" placeholder="Your Comment" required class="mb-2 w-full" />
                <Button type="submit">Add Comment</Button>
            </form>
            <div>
                <h2 class="text-lg font-semibold mb-2">Comments</h2>
                <ul>
                    <li v-for="comment in post.comments" :key="comment.id" class="mb-2 border-b pb-2 flex justify-between items-center">
                        <div>
                            <p><strong>{{ comment.author }}</strong>: {{ comment.content }}</p>
                        </div>
                        <Button @click="deleteComment(comment.id)" size="sm" variant="destructive">Delete</Button>
                    </li>
                </ul>
            </div>
        </div>
    </AppLayout>
</template>