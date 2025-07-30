<script setup lang="ts">
import { Toaster } from '@/components/ui/sonner';
import AuthLayout from '@/layouts/auth/AuthSplitLayout.vue';
import 'vue-sonner/style.css';

defineProps<{
    title?: string;
    description?: string;
}>();
</script>

<template>
    <Toaster />
    <AuthLayout :title="title" :description="description">
        <slot />
    </AuthLayout>
</template>
