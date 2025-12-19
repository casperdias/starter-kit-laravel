<script setup lang="ts">
import { AddRole, EditRole } from '@/components/admin/role';
import Confirmation from '@/components/Confirmation.vue';
import DefaultPagination from '@/components/DefaultPagination.vue';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { InputGroup, InputGroupAddon, InputGroupInput } from '@/components/ui/input-group';
import { Table, TableBody, TableCell, TableEmpty, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import { useRoute } from '@/composables/useRoute';
import AppLayout from '@/layouts/AppLayout.vue';
import { BreadcrumbItem, Pagination, Role } from '@/types';
import { Head, Link, router, usePage } from '@inertiajs/vue3';
import { ArchiveX, Search, ShieldPlus, Trash2, UserCog } from 'lucide-vue-next';
import { onBeforeUnmount, ref, watch } from 'vue';
import { toast } from 'vue-sonner';
const route = useRoute();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Master',
        href: '/admin',
    },
    {
        title: 'Roles',
        href: '/admin/roles',
    },
];

const page = usePage();

const props = defineProps<Props>();
interface Props {
    roles: Pagination<Role>;
}

const deleteRole = (roleId: number) => {
    router.delete(route('admin.roles.destroy', roleId), {
        preserveScroll: true,
        preserveState: true,
        onSuccess: () => {
            toast.success('Role deleted successfully', {
                description: page.props.flash.success || 'Role has been deleted.',
                closeButton: true,
            });
        },
        onError: (error) => {
            toast.error('Failed to delete role', {
                description: error.message || 'An error occurred while deleting the role.',
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
    <Head title="Role" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="grid grid-cols-1 gap-4 px-4 pt-6 pb-4">
            <Card>
                <CardHeader class="grid grid-cols-1 gap-x-0 gap-y-4 md:grid-cols-2 md:gap-x-4 md:gap-y-0">
                    <div class="flex items-center space-x-5">
                        <UserCog class="h-10 w-10" />
                        <div class="flex flex-col gap-2">
                            <CardTitle class="w-full">Role</CardTitle>
                            <CardDescription class="hidden font-semibold lg:block"> Manage your roles here. </CardDescription>
                        </div>
                    </div>
                    <InputGroup>
                        <InputGroupInput placeholder="Search..." v-model="searchTerm" />
                        <InputGroupAddon>
                            <Search />
                        </InputGroupAddon>
                        <InputGroupAddon align="inline-end"> {{ roles.meta.total }} Result/s </InputGroupAddon>
                    </InputGroup>
                </CardHeader>
                <CardContent class="space-y-4">
                    <AddRole />
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
                            <template v-if="roles.data.length > 0">
                                <TableRow v-for="role in roles.data" :key="role.id">
                                    <TableCell class="text-center">{{ role.id }}</TableCell>
                                    <TableCell>{{ role.name }}</TableCell>
                                    <TableCell>{{ role.display_name }}</TableCell>
                                    <TableCell class="max-w-62.5 wrap-break-word whitespace-normal">{{ role.description }}</TableCell>
                                    <TableCell>{{ role.created_at }}</TableCell>
                                    <TableCell class="flex items-center justify-center gap-2">
                                        <EditRole :id="role.id" />
                                        <Confirmation
                                            :on-confirm="() => deleteRole(role.id)"
                                            :title="'Delete Role'"
                                            :description="'Confirm deletion of this role'"
                                        >
                                            <template #trigger>
                                                <Button variant="destructive">
                                                    <Trash2 />
                                                </Button>
                                            </template>
                                            <div class="grid gap-2">
                                                <p>Apakah anda yakin ingin menghapus role ini?</p>
                                                <p class="w-fit rounded-lg bg-red-500 px-3 py-2 font-bold text-white">{{ role.name }}</p>
                                                <p class="text-sm text-red-600">This action cannot be undone.</p>
                                            </div>
                                        </Confirmation>
                                        <Link :href="route('admin.roles.show', role.id)" class="text-blue-500 hover:underline">
                                            <Button>
                                                <ShieldPlus />
                                            </Button>
                                        </Link>
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
                    <DefaultPagination :pagination="roles" :only="['roles']" />
                </CardContent>
            </Card>
        </div>
    </AppLayout>
</template>
