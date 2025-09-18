<script setup lang="ts">
import { useRoute } from '@/composables/useRoute';
import { router } from '@inertiajs/vue3';
import { ArchiveX } from 'lucide-vue-next';
import { Button } from '../ui/button';

const route = useRoute();

const props = defineProps<{
    notificationIds: string[];
}>();

const emits = defineEmits<{
    cleared: [];
}>();

const markAsRead = () => {
    router.post(
        route('notifications.mark-as-read'),
        {
            ids: props.notificationIds,
        },
        {
            preserveScroll: true,
            onSuccess: () => {
                emits('cleared');
            },
        },
    );
};

const markAllAsRead = () => {
    router.post(
        route('notifications.mark-all-as-read'),
        {},
        {
            preserveScroll: true,
            onSuccess: () => {
                emits('cleared');
            },
        },
    );
};
</script>

<template>
    <div class="flex items-center gap-2">
        <Button variant="outline" :disabled="notificationIds.length === 0" @click="markAsRead">
            <ArchiveX />
            Mark as Read ({{ notificationIds.length }})
        </Button>
        <Button @click="markAllAsRead">
            <ArchiveX />
            Mark All as Read
        </Button>
    </div>
</template>
