<script setup lang="ts">
import { Head } from '@inertiajs/vue3';

import HeadingSmall from '@/components/HeadingSmall.vue';
import { Notification, Pagination, type BreadcrumbItem } from '@/types';

import DefaultPagination from '@/components/DefaultPagination.vue';
import { Table, TableBody, TableEmpty, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import AppLayout from '@/layouts/AppLayout.vue';
import SettingsLayout from '@/layouts/settings/Layout.vue';
import { ArchiveX } from 'lucide-vue-next';

defineProps<{
    notifications: Pagination<Notification>;
}>();

const breadcrumbItems: BreadcrumbItem[] = [
    {
        title: 'Notifications',
        href: '/settings/notifications',
    },
];
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
                            <TableRow v-for="(notification, index) in notifications.data" :key="notification.id">
                                <TableCell class="text-center">{{ notifications.meta.current_page * index }}</TableCell>
                                <TableCell>{{ notification.data.type }}</TableCell>
                                <TableCell>{{ notification.data.message }}</TableCell>
                                <TableCell></TableCell>
                                <TableCell> </TableCell>
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
