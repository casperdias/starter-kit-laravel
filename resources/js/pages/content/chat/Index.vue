<script setup lang="ts">
import { useRoute } from '@/composables/useRoute';
import AppLayout from '@/layouts/AppLayout.vue';
import { BreadcrumbItem, CursorPagination, User } from '@/types';
import { Head } from '@inertiajs/vue3';

const route = useRoute();

defineProps<{
    users: CursorPagination<User>;
}>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Chat',
        href: route('chats.index'),
    },
];
</script>

<template>
    <Head title="Chat" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="grid grid-cols-3 gap-4 px-4 pt-6 pb-4"></div>
    </AppLayout>
</template>
