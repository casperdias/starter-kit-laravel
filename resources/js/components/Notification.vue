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
import { fetchNotifications } from '@/composables/notificationHelper';
import { truncateMessage } from '@/composables/textHelper';
import { useRoute } from '@/composables/useRoute';
import { Notification } from '@/types';
import { Link, usePage } from '@inertiajs/vue3';
import { useEchoModel } from '@laravel/echo-vue';
import { ArchiveX, Bell, Eye, X } from 'lucide-vue-next';
import { computed, onMounted, ref } from 'vue';

const route = useRoute();

const page = usePage();
const auth = computed(() => page.props.auth);

const notifications: Notification[] = [];
const dropdownOpen = ref(false);
const unreadCount = ref(0);

onMounted(() => {
    fetchNotifications(1, 5, false).then((data) => {
        notifications.push(...data.notifications);
        unreadCount.value = data.unread;
    });
});

const { channel } = useEchoModel('App.Models.User', auth.value.user.id);

channel().notification((notification: Notification) => {
    console.log(notification.type);
});
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
            <DropdownMenuLabel class="flex items-center justify-between gap-2">
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
                <DropdownMenuItem class="flex flex-col items-center justify-center gap-2">
                    <ArchiveX class="size-10 text-primary" />
                    <p class="text-sm font-semibold">No new notifications</p>
                </DropdownMenuItem>
            </template>
            <template v-else>
                <DropdownMenuItem v-for="notification in notifications" :key="notification.id">
                    <div class="flex flex-col">
                        <span class="font-medium">{{ notification.data.type }}</span>
                        <span class="text-sm text-muted-foreground">{{ truncateMessage(notification.data.message) }}</span>
                    </div>
                </DropdownMenuItem>
            </template>
            <DropdownMenuSeparator />
            <DropdownMenuLabel>
                <Link :href="route('notifications.index')">
                    <Button variant="ghost" class="w-full border border-primary">
                        <Eye />
                        View All
                    </Button>
                </Link>
            </DropdownMenuLabel>
        </DropdownMenuContent>
    </DropdownMenu>
</template>
