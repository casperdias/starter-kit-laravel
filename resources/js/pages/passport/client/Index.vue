<script setup lang="ts">
import Confirmation from '@/components/Confirmation.vue';
import DefaultPagination from '@/components/DefaultPagination.vue';
import { AddClient } from '@/components/passport/client';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { Input } from '@/components/ui/input';
import { Table, TableBody, TableCell, TableEmpty, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import AppLayout from '@/layouts/AppLayout.vue';
import { BreadcrumbItem, Pagination, PassportClient } from '@/types';
import { Head } from '@inertiajs/vue3';
import { ArchiveX, BookKey, Search, Trash2 } from 'lucide-vue-next';
import { ref } from 'vue';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Passport Client',
        href: route('passport-clients.index'),
    },
];

const props = defineProps<Props>();
interface Props {
    clients: Pagination<PassportClient>;
}

const deleteClient = (clientId: string) => {
    console.log(clientId);
};

const searchTerm = ref(route().params.search || '');
</script>

<template>
    <Head title="Passport Client Management" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="grid grid-cols-1 gap-4 px-4 pt-6 pb-4">
            <Card>
                <CardHeader class="grid grid-cols-1 gap-4 md:grid-cols-2 md:gap-0">
                    <div class="flex items-center space-x-5">
                        <BookKey class="h-10 w-10" />
                        <div class="flex flex-col gap-2">
                            <CardTitle class="w-full">Passport Client</CardTitle>
                            <CardDescription class="hidden font-semibold lg:block"> Manage your app Oauth Clients here. </CardDescription>
                        </div>
                    </div>
                    <div class="relative flex h-full w-full items-center">
                        <Input id="search" type="text" name="search" placeholder="Search..." class="w-full pl-10" v-model="searchTerm" />
                        <span class="absolute inset-y-0 start-0 flex items-center justify-center px-2">
                            <Search class="size-6 text-muted-foreground" />
                        </span>
                    </div>
                </CardHeader>
                <CardContent class="space-y-4">
                    <AddClient />
                    <Table>
                        <TableHeader>
                            <TableRow>
                                <TableHead>Name</TableHead>
                                <TableHead>ID</TableHead>
                                <TableHead>Redirect URIs</TableHead>
                                <TableHead>Confidential</TableHead>
                                <TableHead>Grant Types</TableHead>
                                <TableHead>Created By</TableHead>
                                <TableHead>Actions</TableHead>
                            </TableRow>
                        </TableHeader>
                        <TableBody>
                            <template v-if="clients.data.length > 0">
                                <TableRow v-for="client in clients.data" :key="client.id">
                                    <TableCell>{{ client.name }}</TableCell>
                                    <TableCell>{{ client.id }}</TableCell>
                                    <TableCell>
                                        <ul class="list-inside list-disc">
                                            <li v-for="uri in client.redirect" :key="uri">{{ uri }}</li>
                                        </ul>
                                    </TableCell>
                                    <TableCell>
                                        <Badge :variant="client.confidential ? 'default' : 'destructive'">
                                            {{ client.confidential ? 'Yes' : 'No' }}
                                        </Badge>
                                    </TableCell>
                                    <TableCell>
                                        <ul class="list-inside list-disc">
                                            <li v-for="grantType in client.grant_types" :key="grantType">{{ grantType }}</li>
                                        </ul>
                                    </TableCell>
                                    <TableCell>
                                        {{ client.owner ? client.owner.name : 'N/A' }}
                                    </TableCell>
                                    <TableCell class="text-center">
                                        <Confirmation
                                            :on-confirm="() => deleteClient(client.id)"
                                            :title="'Delete Client'"
                                            :description="'Confirm deletion of this client'"
                                        >
                                            <template #trigger>
                                                <Button variant="destructive">
                                                    <Trash2 />
                                                </Button>
                                            </template>
                                            <div class="grid gap-2">
                                                <p>Apakah anda yakin ingin menghapus client ini?</p>
                                                <p class="w-fit rounded-lg bg-red-500 px-3 py-2 font-bold text-white">{{ client.name }}</p>
                                                <p class="text-sm text-red-600">This action cannot be undone.</p>
                                            </div>
                                        </Confirmation>
                                    </TableCell>
                                </TableRow>
                            </template>
                            <template v-else>
                                <TableEmpty colspan="7">
                                    <div class="flex flex-col items-center justify-center space-y-2">
                                        <ArchiveX class="size-10" />
                                        <p class="font-semibold">No clients found.</p>
                                    </div>
                                </TableEmpty>
                            </template>
                        </TableBody>
                    </Table>
                    <DefaultPagination :pagination="clients" />
                </CardContent>
            </Card>
        </div>
    </AppLayout>
</template>
