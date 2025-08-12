<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Passport',
        href: '/passport',
    },
    {
        title: 'Client',
        href: '/passport/clients',
    },
];
</script>

<template>
    <Head title="Passport Client Management" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="grid grid-cols-1 gap-4 px-4 pt-6 pb-4"></div>
    </AppLayout>
</template>
