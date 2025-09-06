<script setup lang="ts">
import { useRoute } from '@/composables/useRoute';
import AppLayout from '@/layouts/AppLayout.vue';
import { BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';

const route = useRoute();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'News',
        href: route('news.index'),
    },
    {
        title: 'Create',
        href: route('news.create'),
    },
];
</script>

<template>
    <Head title="Create News" />

    <AppLayout :breadcrumbs="breadcrumbs"> </AppLayout>
</template>
