<script setup lang="ts">
import AlertDialog from '@/components/ui/alert-dialog/AlertDialog.vue';
import AlertDialogAction from '@/components/ui/alert-dialog/AlertDialogAction.vue';
import AlertDialogCancel from '@/components/ui/alert-dialog/AlertDialogCancel.vue';
import AlertDialogContent from '@/components/ui/alert-dialog/AlertDialogContent.vue';
import AlertDialogDescription from '@/components/ui/alert-dialog/AlertDialogDescription.vue';
import AlertDialogFooter from '@/components/ui/alert-dialog/AlertDialogFooter.vue';
import AlertDialogHeader from '@/components/ui/alert-dialog/AlertDialogHeader.vue';
import AlertDialogTitle from '@/components/ui/alert-dialog/AlertDialogTitle.vue';
import Button from '@/components/ui/button/Button.vue';
import Table from '@/components/ui/table/Table.vue';
import TableBody from '@/components/ui/table/TableBody.vue';
import TableCell from '@/components/ui/table/TableCell.vue';
import TableHead from '@/components/ui/table/TableHead.vue';
import TableHeader from '@/components/ui/table/TableHeader.vue';
import TableRow from '@/components/ui/table/TableRow.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { BreadcrumbItem } from '@/types';
import { Link, router } from '@inertiajs/vue3';
import { PenSquare, Trash } from 'lucide-vue-next';
import { ref } from 'vue';


defineProps(['posts']);

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Posts',
        href: '/posts',
    },
];

const postToDelete = ref();

const deletePost = () => {
    router.delete(route('posts.destroy', postToDelete.value), {
        onSuccess: () => {
            postToDelete.value = undefined;
        },
    });
}

</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="mx-auto my-12 w-full max-w-screen-lg">
            <div class="rounded-md border shadow-sm">
                <Table>
                    <TableHeader>
                        <TableRow>
                            <TableHead>Title</TableHead>
                            <TableHead class="text-right">Created At</TableHead>
                            <TableHead class="sr-only">Actions</TableHead>
                        </TableRow>
                    </TableHeader>
                    <TableBody>
                        <TableRow v-for="post in posts" :key="post.id">
                        <TableCell>{{ post.title }}</TableCell>
                    <TableCell class="text-right">{{ post.created_at_for_humans }}</TableCell>
                    <TableCell class="text-right">
                        <div class="flex justify-end gap-2">
                        <Button @click="postToDelete = post" variant="destructive" size="icon">
                            <Trash class="size-4" />
                        </Button>
                        <Button :as="Link" :href="route('posts.edit', post)" variant="outline" size="icon">
                            <PenSquare class="size-4" />
                        </Button>
                    </div>
                </Tablecell>
            </TableRow>
        </TableBody>
    </Table>
        </div>
    </div>
<AlertDialog :open="!!postToDelete">
    <AlertDialogContent>
        <AlertDialogHeader>
            <AlertDialogTitle>Are you absolutely sure?</AlertDialogTitle>
            <AlertDialogDescription>
                This action cannot be undone. This will permanently delete your account and remove your data from our servers.
            </AlertDialogDescription>
        </AlertDialogHeader>
        <AlertDialogFooter>
            <AlertDialogCancel @click="postToDelete = undefined">Cancel</AlertDialogCancel>
            <AlertDialogAction @click="deletePost"> Continue</AlertDialogAction>
        </AlertDialogFooter>
    </AlertDialogContent>
</AlertDialog>
    </AppLayout>
</template>