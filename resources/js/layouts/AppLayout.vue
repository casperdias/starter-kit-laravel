<script setup lang="ts">
import { Toaster } from '@/components/ui/sonner';
import AppLayout from '@/layouts/app/AppSidebarLayout.vue';
import type { BreadcrumbItemType, NotificationData } from '@/types';
import { usePage } from '@inertiajs/vue3';
import { useEchoNotification } from '@laravel/echo-vue';
import { onMounted, onUnmounted } from 'vue';
import { toast } from 'vue-sonner';
import 'vue-sonner/style.css';

const user = usePage().props.auth.user;
const userId = user.id;

interface Props {
    breadcrumbs?: BreadcrumbItemType[];
}

withDefaults(defineProps<Props>(), {
    breadcrumbs: () => [],
});

const { listen, stopListening } = useEchoNotification(`App.Models.Auth.User.${userId}`, (notification: NotificationData) => {
    toast.success('New Notification', {
        description: notification.message,
        closeButton: true,
        position: 'top-right',
    });
});

onUnmounted(() => {
    stopListening();
});

onMounted(() => {
    listen();
});
</script>

<template>
    <Toaster />
    <AppLayout :breadcrumbs="breadcrumbs">
        <slot />
    </AppLayout>
</template>
