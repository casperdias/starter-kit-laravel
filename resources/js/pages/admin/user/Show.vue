<script setup lang="ts">
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { Input } from '@/components/ui/input';
import { Switch } from '@/components/ui/switch';
import { Table, TableBody, TableCell, TableEmpty, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import AppLayout from '@/layouts/AppLayout.vue';
import { BreadcrumbItem, Pagination, Role, User } from '@/types';
import { Head, router } from '@inertiajs/vue3';
import { ArchiveX, Search, UserCheck } from 'lucide-vue-next';
import { onBeforeUnmount, ref, watch } from 'vue';
import { toast } from 'vue-sonner';

const props = defineProps<Props>();
interface Props {
    user: User;
    roles: Pagination<
        Role & {
            status: boolean;
            active: boolean;
        }
    >;
}

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Master',
        href: '/admin',
    },
    {
        title: 'Users',
        href: '/admin/users',
    },
    {
        title: props.user.name,
        href: `/admin/users/${props.user.id}`,
    },
];

const updateRoleStatus = (role: Role, status: boolean) => {
    router.put(
        route('admin.users.roles.update', { user: props.user.id, role: role.id }),
        { status },
        {
            preserveScroll: true,
            preserveState: true,
            onSuccess: () => {
                toast.success(`Role ${role.display_name} updated successfully`, {
                    description: `Role is now ${status ? 'Assigned' : 'Revoked'}.`,
                    closeButton: true,
                });
            },
            onError: (error) => {
                toast.error(`Failed to update role ${role.display_name}`, {
                    description: error.message || 'An error occurred while updating the role.',
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
            props.roles.meta.path,
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
    <Head title="User" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="grid grid-cols-1 gap-4 px-4 pt-6 pb-4">
            <Card>
                <CardHeader class="grid grid-cols-1 gap-4 md:grid-cols-2 md:gap-0">
                    <div class="flex items-center space-x-5">
                        <UserCheck class="h-10 w-10" />
                        <div class="flex flex-col gap-2">
                            <CardTitle class="w-full">{{ user.name }}</CardTitle>
                            <CardDescription class="hidden font-semibold lg:block"> Manage User Role </CardDescription>
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
                                <TableHead class="text-center">Active</TableHead>
                                <TableHead class="text-center">Actions</TableHead>
                            </TableRow>
                        </TableHeader>
                        <TableBody>
                            <template v-if="roles.data.length > 0">
                                <TableRow v-for="role in roles.data" :key="role.id">
                                    <TableCell class="text-center">{{ role.id }}</TableCell>
                                    <TableCell>{{ role.name }}</TableCell>
                                    <TableCell>{{ role.display_name }}</TableCell>
                                    <TableCell class="text-center">
                                        <div class="flex items-center justify-center">
                                            <div class="group relative flex h-3 w-3">
                                                <span
                                                    class="absolute inline-flex h-full w-full animate-ping rounded-full"
                                                    :class="role.active ? 'bg-green-600' : 'bg-red-600'"
                                                ></span>
                                                <span
                                                    class="inline-block h-3 w-3 rounded-full"
                                                    :class="role.active ? 'bg-green-600' : 'bg-red-600'"
                                                ></span>
                                            </div>
                                        </div>
                                    </TableCell>
                                    <TableCell class="flex items-center justify-center gap-2">
                                        <Switch
                                            v-model="role.status"
                                            v-on:update:model-value="(value) => updateRoleStatus(role, value)"
                                            :label="role.display_name"
                                        />
                                    </TableCell>
                                </TableRow>
                            </template>
                            <template v-else>
                                <TableEmpty colspan="6">
                                    <div class="flex flex-col items-center justify-center space-y-2">
                                        <ArchiveX class="size-10" />
                                        <p class="font-semibold">No roles found.</p>
                                    </div>
                                </TableEmpty>
                            </template>
                        </TableBody>
                    </Table>
                </CardContent>
            </Card>
        </div>
    </AppLayout>
</template>
