<script setup lang="ts">
import { Button } from '@/components/ui/button';
import {
    DropdownMenu,
    DropdownMenuContent,
    DropdownMenuItem,
    DropdownMenuLabel,
    DropdownMenuSeparator,
    DropdownMenuTrigger,
} from '@/components/ui/dropdown-menu';
import { truncateMessage } from '@/composables/textHelper';
import { Bell, Eye, X } from 'lucide-vue-next';
import { computed, ref } from 'vue';
const notifications = [
    {
        id: 1,
        type: 'App/Notifications/WelcomeUser',
        data: {
            message:
                'Welcome, BAHARUDDIN DIAS SAPUTRA! You have been successfully registered in the SABA DELIVERY TRACKING. Please log in to your account to get started.',
        },
        read_at: null,
    },
    {
        id: 2,
        type: 'App/Notifications/UserDeleted',
        data: {
            message: 'User Jane Doe has been deleted his own Account.',
        },
        read_at: '2025-09-03 15:20:01',
    },
];
const dropdownOpen = ref(false);
const unreadCount = computed(() => notifications.filter((n) => !n.read_at).length);
const notificationType = (type: string) => {
    const lastPart = type.split('/').pop() || '';
    return lastPart.replace(/([A-Z])/g, ' $1').trim();
};
</script>

<template>
    <DropdownMenu v-model:open="dropdownOpen">
        <DropdownMenuTrigger asChild>
            <Button variant="ghost" class="relative rounded-full border border-primary p-2">
                <Bell class="h-5 w-5" />
                <span class="absolute -top-1 -left-1 rounded-full bg-red-500 px-1 text-center text-xs text-white" v-if="unreadCount > 0">
                    {{ unreadCount }}
                </span>
            </Button>
        </DropdownMenuTrigger>
        <DropdownMenuContent>
            <DropdownMenuLabel class="flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <Bell class="h-5 w-5" />
                    <h5 class="text-lg font-bold">Notification</h5>
                </div>
                <Button variant="ghost" @click="dropdownOpen = false" class="text-gray-500 dark:text-gray-400">
                    <X />
                </Button>
            </DropdownMenuLabel>
            <DropdownMenuSeparator />
            <template v-if="notifications.length === 0">
                <DropdownMenuItem>No notifications</DropdownMenuItem>
            </template>
            <template v-else>
                <DropdownMenuItem v-for="notification in notifications" :key="notification.id">
                    <div class="flex flex-col">
                        <span class="font-medium">{{ notificationType(notification.type) }}</span>
                        <span class="text-sm text-muted-foreground">{{ truncateMessage(notification.data.message) }}</span>
                    </div>
                </DropdownMenuItem>
            </template>
            <DropdownMenuSeparator />
            <DropdownMenuLabel>
                <Button variant="ghost" class="w-full border border-primary">
                    <Eye />
                    View All
                </Button>
            </DropdownMenuLabel>
        </DropdownMenuContent>
    </DropdownMenu>
</template>
