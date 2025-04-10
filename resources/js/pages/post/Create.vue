<script setup lang="ts">
import InputError from '@/components/InputError.vue';
import Button from '@/components/ui/button/Button.vue';
import Card from '@/components/ui/card/Card.vue';
import CardContent from '@/components/ui/card/CardContent.vue';
import CardDescription from '@/components/ui/card/CardDescription.vue';
import CardFooter from '@/components/ui/card/CardFooter.vue';
import CardHeader from '@/components/ui/card/CardHeader.vue';
import CardTitle from '@/components/ui/card/CardTitle.vue';
import Input from '@/components/ui/input/Input.vue';
import Label from '@/components/ui/label/Label.vue';
import Textarea from '@/components/ui/textarea/Textarea.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { useForm } from '@inertiajs/vue3';
import { useTitle } from '@vueuse/core';


const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Show create',
        href: '/posts/create',
    },
];

const form = useForm({
  title: '',
  description: ''
});

const submit = () => {
    // Handle form submission
    form.post(route('posts.store'));
}
</script>

<template>
     <AppLayout :breadcrumbs="breadcrumbs">
    <div class="my-12 mx-auto max-w-lg w-full">
      <form @submit.prevent="submit">
          <Card>
            <CardHeader>
              <CardTitle>Create post</CardTitle>
              <CardDescription>Create a new blog post</CardDescription>
            </CardHeader>
            <CardContent class="flex flex-col gap-4">
              <div>
                <Label>Title</Label>
                <Input v-model="form.title" />
                <InputError :message="form.errors.title" />
              </div>
              <div>
                <Label>Description</Label>
                <Textarea v-model="form.description" />
                <InputError :message="form.errors.description" />
              </div>
            </CardContent>
            <CardFooter>
              <Button type="submit">Submit</Button>
            </CardFooter>
          </Card>
        </form>
        <!-- <pre>{{ form }}</pre> -->   
    </div>
     </AppLayout>
</template>