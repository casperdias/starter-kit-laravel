<script setup lang="ts">
import ChatComplaint from '@/components/ChatComplaint.vue';
import { Toaster } from '@/components/ui/sonner';
import AppLayout from '@/layouts/app/AppSidebarLayout.vue';
import type { AppPageProps, BreadcrumbItemType } from '@/types';
import { usePage } from '@inertiajs/vue3';
import { computed } from 'vue';
import 'vue-sonner/style.css';

interface Props {
    breadcrumbs?: BreadcrumbItemType[];
}

const page = usePage<AppPageProps>();
const permissions = computed(() => page.props.auth.user.permissions);
const isAdmin = computed(() => permissions.value.includes('admin'));

withDefaults(defineProps<Props>(), {
    breadcrumbs: () => [],
});
</script>

<template>
    <Toaster />
    <AppLayout :breadcrumbs="breadcrumbs">
        <slot />
    </AppLayout>
    <!-- Chat Button -->
    <ChatComplaint v-if="!isAdmin" :user="page.props.auth.user" />
</template>
