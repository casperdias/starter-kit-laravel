<script setup lang="ts">
import DefaultPagination from '@/components/DefaultPagination.vue';
import HeadingSmall from '@/components/HeadingSmall.vue';
import { Checkbox } from '@/components/ui/checkbox';
import { Table, TableBody, TableCell, TableEmpty, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import AppLayout from '@/layouts/AppLayout.vue';
import SettingsLayout from '@/layouts/settings/Layout.vue';
import { Notification, Pagination, type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';
import { ArchiveX } from 'lucide-vue-next';
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
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbItems">
        <Head title="Notifications" />

        <SettingsLayout>
            <div class="space-y-6">
                <HeadingSmall title="Notifications" description="List of all your notifications" />
                <Table>
                    <TableHeader>
                        <TableRow>
                            <TableHead class="text-center">#</TableHead>
                            <TableHead>Type</TableHead>
                            <TableHead>Message</TableHead>
                            <TableHead>Created At</TableHead>
                            <TableHead>Status</TableHead>
                        </TableRow>
                    </TableHeader>
                    <TableBody>
                        <template v-if="notifications.data.length > 0">
                            <TableRow v-for="notification in notifications.data" :key="notification.id">
                                <TableCell class="text-center">
                                    <Checkbox
                                        v-if="!notification.read_at"
                                        @update:model-value="
                                            (val) => {
                                                if (val) {
                                                    checkedNotifications.push(notification.id);
                                                } else {
                                                    const index = checkedNotifications.indexOf(notification.id);
                                                    if (index > -1) {
                                                        checkedNotifications.splice(index, 1);
                                                    }
                                                }
                                            }
                                        "
                                        :model-value="checkedNotifications.includes(notification.id)"
                                    />
                                    <span v-else class="text-muted-foreground">-</span>
                                </TableCell>
                                <TableCell>{{ notification.data.type }}</TableCell>
                                <TableCell>{{ notification.data.message }}</TableCell>
                                <TableCell>{{ notification.created_at }}</TableCell>
                                <TableCell>{{ notification.read_at ? 'Read' : 'Unread' }}</TableCell>
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
                <DefaultPagination :pagination="notifications" />
            </div>
        </SettingsLayout>
    </AppLayout>
</template>
