<script setup lang="ts">
import DefaultPagination from '@/components/DefaultPagination.vue';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { Input } from '@/components/ui/input';
import { Switch } from '@/components/ui/switch';
import { Table, TableBody, TableCell, TableEmpty, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import { useRoute } from '@/composables/useRoute';
import AppLayout from '@/layouts/AppLayout.vue';
import { BreadcrumbItem, Pagination, Permission, Role } from '@/types';
import { Head, router } from '@inertiajs/vue3';
import { ArchiveX, Search, UserCheck } from 'lucide-vue-next';
import { onBeforeUnmount, ref, watch } from 'vue';
import { toast } from 'vue-sonner';

const route = useRoute();

const props = defineProps<Props>();
interface Props {
    role: Role;
    permissions: Pagination<Permission & { status: boolean }>;
}

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Master',
        href: '/admin',
    },
    {
        title: 'Roles',
        href: '/admin/roles',
    },
    {
        title: props.role.display_name,
        href: `/admin/roles/${props.role.id}`,
    },
];

const updatePermissionStatus = (permission: Permission, status: boolean) => {
    router.put(
        route('admin.roles.permissions.update', { role: props.role.id, permission: permission.id }),
        { status },
        {
            preserveScroll: true,
            preserveState: true,
            onSuccess: () => {
                toast.success(`Permission ${permission.display_name} updated successfully`, {
                    description: `Status is now ${status ? 'active' : 'inactive'}.`,
                    closeButton: true,
                });
            },
            onError: (error) => {
                toast.error(`Failed to update permission ${permission.display_name}`, {
                    description: error.message || 'An error occurred while updating the permission.',
                    closeButton: true,
                });
            },
        },
    );
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
    <Head title="Role" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="grid grid-cols-1 gap-4 px-4 pt-6 pb-4">
            <Card>
                <CardHeader class="grid grid-cols-1 gap-x-0 gap-y-4 md:grid-cols-2 md:gap-x-4 md:gap-y-0">
                    <div class="flex items-center space-x-5">
                        <UserCheck class="h-10 w-10" />
                        <div class="flex flex-col gap-2">
                            <CardTitle class="w-full">{{ role.display_name }} Setting</CardTitle>
                            <CardDescription class="hidden font-semibold lg:block">Manage Permission for {{ role.display_name }}</CardDescription>
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
                    <Table>
                        <TableHeader>
                            <TableRow>
                                <TableHead class="text-center">ID</TableHead>
                                <TableHead>Name</TableHead>
                                <TableHead>Display Name</TableHead>
                                <TableHead>Description</TableHead>
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
                                    <TableCell class="flex items-center justify-center gap-2">
                                        <Switch
                                            v-model="permission.status"
                                            v-on:update:model-value="(value) => updatePermissionStatus(permission, value)"
                                            :label="permission.display_name"
                                        />
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
                    <DefaultPagination :pagination="permissions" :only="['permissions']" />
                </CardContent>
            </Card>
        </div>
    </AppLayout>
</template>
