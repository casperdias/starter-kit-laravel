<script setup lang="ts">
import { AddPermission, EditPermission } from '@/components/admin/permission';
import Confirmation from '@/components/Confirmation.vue';
import DefaultPagination from '@/components/DefaultPagination.vue';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { Input } from '@/components/ui/input';
import { Table, TableBody, TableCell, TableEmpty, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import { useRoute } from '@/composables/useRoute';
import AppLayout from '@/layouts/AppLayout.vue';
import { BreadcrumbItem, Pagination, Permission } from '@/types';
import { Head, router, usePage } from '@inertiajs/vue3';
import { ArchiveX, Search, ShieldPlus, Trash2 } from 'lucide-vue-next';
import { onBeforeUnmount, ref, watch } from 'vue';
import { toast } from 'vue-sonner';
const route = useRoute();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Master',
        href: '/admin',
    },
    {
        title: 'Permission',
        href: '/admin/permissions',
    },
];

const page = usePage();

const props = defineProps<Props>();
interface Props {
    permissions: Pagination<Permission>;
}

const deletePermission = (permissionId: number) => {
    router.delete(route('admin.permissions.destroy', permissionId), {
        preserveScroll: true,
        preserveState: true,
        onSuccess: () => {
            toast.success('Permission deleted successfully', {
                description: page.props.flash.success || 'Permission has been deleted.',
                closeButton: true,
            });
        },
        onError: (error) => {
            toast.error('Failed to delete permission', {
                description: error.message || 'An error occurred while deleting the permission.',
                closeButton: true,
            });
        },
    });
};

const searchTerm = ref(route().params.search || '');
let searchTimeout: ReturnType<typeof setTimeout>;

watch(searchTerm, (newTerm) => {
    clearTimeout(searchTimeout);
    searchTimeout = setTimeout(() => {
        router.get(
            props.permissions.meta.path,
            {
                search: newTerm,
            },
            {
                preserveState: true,
                preserveScroll: true,
                replace: true,
            },
        );
    }, 500);
});

onBeforeUnmount(() => {
    if (searchTimeout) clearTimeout(searchTimeout);
});
</script>

<template>
    <Head title="Permission" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="grid grid-cols-1 gap-4 px-4 pt-6 pb-4">
            <Card>
                <CardHeader class="grid grid-cols-1 gap-4 md:grid-cols-2 md:gap-0">
                    <div class="flex items-center space-x-5">
                        <ShieldPlus class="h-10 w-10" />
                        <div class="flex flex-col gap-2">
                            <CardTitle class="w-full">Permission</CardTitle>
                            <CardDescription class="hidden font-semibold lg:block"> Manage your permissions here. </CardDescription>
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
                    <AddPermission />
                    <Table>
                        <TableHeader>
                            <TableRow>
                                <TableHead class="text-center">ID</TableHead>
                                <TableHead>Name</TableHead>
                                <TableHead>Display Name</TableHead>
                                <TableHead>Description</TableHead>
                                <TableHead>Created At</TableHead>
                                <TableHead class="text-center">Actions</TableHead>
                            </TableRow>
                        </TableHeader>
                        <TableBody>
                            <template v-if="permissions.data.length > 0">
                                <TableRow v-for="permission in permissions.data" :key="permission.id">
                                    <TableCell class="text-center">{{ permission.id }}</TableCell>
                                    <TableCell>{{ permission.name }}</TableCell>
                                    <TableCell>{{ permission.display_name }}</TableCell>
                                    <TableCell class="max-w-[250px] break-words whitespace-normal">{{ permission.description }}</TableCell>
                                    <TableCell>{{ permission.created_at }}</TableCell>
                                    <TableCell class="flex items-center justify-center gap-2">
                                        <EditPermission :id="permission.id" />
                                        <Confirmation
                                            :on-confirm="() => deletePermission(permission.id)"
                                            :title="'Delete Permission'"
                                            :description="'Confirm deletion of this permission'"
                                        >
                                            <template #trigger>
                                                <Button variant="destructive">
                                                    <Trash2 />
                                                </Button>
                                            </template>
                                            <div class="grid gap-2">
                                                <p>Apakah anda yakin ingin menghapus permission ini?</p>
                                                <p class="w-fit rounded-lg bg-red-500 px-3 py-2 font-bold text-white">{{ permission.name }}</p>
                                                <p class="text-sm text-red-600">This action cannot be undone.</p>
                                            </div>
                                        </Confirmation>
                                    </TableCell>
                                </TableRow>
                            </template>
                            <template v-else>
                                <TableEmpty colspan="6">
                                    <div class="flex flex-col items-center justify-center space-y-2">
                                        <ArchiveX class="size-10" />
                                        <p class="font-semibold">No permissions found.</p>
                                    </div>
                                </TableEmpty>
                            </template>
                        </TableBody>
                    </Table>
                    <DefaultPagination :pagination="permissions" />
                </CardContent>
            </Card>
        </div>
    </AppLayout>
</template>
