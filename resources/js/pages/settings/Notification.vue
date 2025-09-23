<script setup lang="ts">
import DefaultPagination from '@/components/DefaultPagination.vue';
import HeadingSmall from '@/components/HeadingSmall.vue';
import { MarkNotificationAsRead } from '@/components/settings';
import { Checkbox } from '@/components/ui/checkbox';
import { Table, TableBody, TableCell, TableEmpty, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import AppLayout from '@/layouts/AppLayout.vue';
import SettingsLayout from '@/layouts/settings/Layout.vue';
import { Notification, Pagination, type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';
import { ArchiveX, CircleCheckBig, User } from 'lucide-vue-next';
import { ref } from 'vue';

defineProps<{
    notifications: Pagination<Notification>;
}>();

const breadcrumbItems: BreadcrumbItem[] = [
    {
        title: 'Notifications',
        href: '/settings/notifications',
    },
];

const checkedNotifications = ref<string[]>([]);
const updateCheckedNotifications = (id: string, checked: boolean) => {
    if (checked) {
        if (!checkedNotifications.value.includes(id)) {
            checkedNotifications.value.push(id);
        }
    } else {
        checkedNotifications.value = checkedNotifications.value.filter((nid) => nid !== id);
    }
};
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbItems">
        <Head title="Notifications" />

        <SettingsLayout>
            <div class="space-y-6">
                <HeadingSmall title="Notifications" description="List of all your notifications" />
                <MarkNotificationAsRead :notification-ids="checkedNotifications" @cleared="() => (checkedNotifications = [])" />
                <Table>
                    <TableHeader>
                        <TableRow>
                            <TableHead class="text-center">#</TableHead>
                            <TableHead class="text-center">Type</TableHead>
                            <TableHead>Message</TableHead>
                            <TableHead>Created At</TableHead>
                            <TableHead>From</TableHead>
                        </TableRow>
                    </TableHeader>
                    <TableBody>
                        <template v-if="notifications.data.length > 0">
                            <TableRow v-for="notification in notifications.data" :key="notification.id">
                                <TableCell class="text-center">
                                    <div class="flex items-center justify-center">
                                        <Checkbox
                                            v-if="!notification.read_at"
                                            @update:model-value="(val) => updateCheckedNotifications(notification.id, Boolean(val))"
                                            :model-value="checkedNotifications.includes(notification.id)"
                                        />
                                        <CircleCheckBig v-else class="text-green-500" />
                                    </div>
                                </TableCell>
                                <TableCell>
                                    <div class="mx-auto w-max rounded-full bg-primary px-3 py-1 text-xs font-semibold text-primary-foreground">
                                        {{ notification.data.type.toUpperCase() }}
                                    </div>
                                </TableCell>
                                <TableCell>
                                    <p>{{ notification.data.message }}</p>
                                    <p v-if="notification.data.title" class="font-semibold italic">"{{ notification.data.title }}"</p>
                                </TableCell>
                                <TableCell>
                                    <p>{{ notification.created_at }}</p>
                                    <p class="text-xs text-muted-foreground">({{ notification.diff_created_at }})</p>
                                </TableCell>
                                <TableCell>
                                    <div class="flex items-center gap-2">
                                        <User />
                                        <span class="font-semibold">{{ notification.data.from || 'System' }}</span>
                                    </div>
                                </TableCell>
                            </TableRow>
                        </template>
                        <template v-else>
                            <TableEmpty colspan="5">
                                <div class="flex flex-col items-center justify-center space-y-2">
                                    <ArchiveX class="size-10" />
                                    <p class="font-semibold">No Notifications</p>
                                </div>
                            </TableEmpty>
                        </template>
                    </TableBody>
                </Table>
                <DefaultPagination :pagination="notifications" :only="['notifications']" />
            </div>
        </SettingsLayout>
    </AppLayout>
</template>
