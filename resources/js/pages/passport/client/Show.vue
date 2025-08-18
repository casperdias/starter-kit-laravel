<script setup lang="ts">
import DefaultPagination from '@/components/DefaultPagination.vue';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { Table, TableBody, TableCell, TableEmpty, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import AppLayout from '@/layouts/AppLayout.vue';
import { BreadcrumbItem, Pagination, PassportAccessToken, PassportClient } from '@/types';
import { Head } from '@inertiajs/vue3';
import { ArchiveX, KeyRound } from 'lucide-vue-next';

const props = defineProps<Props>();
interface Props {
    client: PassportClient;
    tokens: Pagination<PassportAccessToken>;
}

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Passport Client',
        href: route('passport-clients.index'),
    },
    {
        title: props.client.name,
        href: route('passport-clients.show', props.client.id),
    },
];
</script>

<template>
    <Head title="Passport Client Token" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="grid grid-cols-1 gap-4 px-4 pt-6 pb-4">
            <Card>
                <CardHeader class="grid grid-cols-1 gap-4 md:grid-cols-2 md:gap-0">
                    <div class="flex items-center space-x-5">
                        <KeyRound class="h-10 w-10" />
                        <div class="flex flex-col gap-2">
                            <CardTitle class="w-full">Passport Client: {{ client.name }}</CardTitle>
                            <CardDescription class="hidden font-semibold lg:block"> List of all created tokens. </CardDescription>
                        </div>
                    </div>
                </CardHeader>
                <CardContent class="space-y-4">
                    <Table>
                        <TableHeader>
                            <TableRow>
                                <TableHead>Name</TableHead>
                                <TableHead>ID</TableHead>
                                <TableHead>User</TableHead>
                                <TableHead>Created At</TableHead>
                                <TableHead class="text-center">Actions</TableHead>
                            </TableRow>
                        </TableHeader>
                        <TableBody>
                            <template v-if="tokens.data.length > 0">
                                <TableRow v-for="token in tokens.data" :key="token.id">
                                    <TableCell>{{ token.name || 'N/A' }}</TableCell>
                                    <TableCell>{{ token.id }}</TableCell>
                                    <TableCell>
                                        {{ token.user ? token.user.name : 'N/A' }}
                                    </TableCell>
                                    <TableCell>{{ token.created_at }}</TableCell>
                                    <TableCell class="space-x-2 text-center"> </TableCell>
                                </TableRow>
                            </template>
                            <template v-else>
                                <TableEmpty colspan="4">
                                    <div class="flex flex-col items-center justify-center space-y-2">
                                        <ArchiveX class="size-10" />
                                        <p class="font-semibold">No clients found.</p>
                                    </div>
                                </TableEmpty>
                            </template>
                        </TableBody>
                    </Table>
                    <DefaultPagination :pagination="tokens" />
                </CardContent>
            </Card>
        </div>
    </AppLayout>
</template>
