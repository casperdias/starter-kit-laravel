<script setup lang="ts">
import { AddUser, EditUser } from '@/components/admin/user';
import Confirmation from '@/components/Confirmation.vue';
import DefaultPagination from '@/components/DefaultPagination.vue';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { Input } from '@/components/ui/input';
import { Table, TableBody, TableCell, TableEmpty, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import { route } from '@/composables/useRoute';
import AppLayout from '@/layouts/AppLayout.vue';
import { BreadcrumbItem, Pagination, User } from '@/types';
import { Head, Link, router, usePage } from '@inertiajs/vue3';
import { ArchiveX, Mail, Search, Trash2, UserCog, User as UserIcon } from 'lucide-vue-next';
import { onBeforeUnmount, ref, watch } from 'vue';
import { toast } from 'vue-sonner';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Master',
        href: '/admin',
    },
    {
        title: 'Users',
        href: '/admin/users',
    },
];

const page = usePage();

const props = defineProps<Props>();
interface Props {
    users: Pagination<User>;
}

const deleteUser = (userId: number) => {
    router.delete(route('admin.users.destroy', userId), {
        preserveScroll: true,
        preserveState: true,
        onSuccess: () => {
            toast.success('User deleted successfully', {
                description: page.props.flash.success || 'User has been deleted.',
                closeButton: true,
            });
        },
        onError: (error) => {
            toast.error('Failed to delete user', {
                description: error.message || 'An error occurred while deleting the user.',
                closeButton: true,
            });
        },
    });
};

const sendVerificationEmail = (userId: number) => {
    router.post(route('admin.verification.send.id', userId), undefined, {
        onSuccess: () => {
            toast.success('Verification email sent successfully', {
                description: 'A verification email has been sent to the user.',
                closeButton: true,
            });
        },
        onError: (error) => {
            toast.error('Failed to send verification email', {
                description: error.message || 'An error occurred while sending the verification email.',
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
            props.users.meta.path,
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
                        <UserIcon class="h-10 w-10" />
                        <div class="flex flex-col gap-2">
                            <CardTitle class="w-full">User</CardTitle>
                            <CardDescription class="hidden font-semibold lg:block"> Manage your users here. </CardDescription>
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
                    <AddUser />
                    <Table>
                        <TableHeader>
                            <TableRow>
                                <TableHead class="text-center">ID</TableHead>
                                <TableHead>Name</TableHead>
                                <TableHead>Email</TableHead>
                                <TableHead>Created At</TableHead>
                                <TableHead>Email Verified At</TableHead>
                                <TableHead class="text-center">Actions</TableHead>
                            </TableRow>
                        </TableHeader>
                        <TableBody>
                            <template v-if="users.data.length > 0">
                                <TableRow v-for="user in users.data" :key="user.id">
                                    <TableCell class="text-center">{{ user.id }}</TableCell>
                                    <TableCell>{{ user.name }}</TableCell>
                                    <TableCell>{{ user.email }}</TableCell>
                                    <TableCell>{{ user.created_at }}</TableCell>
                                    <TableCell>{{ user.email_verified_at || 'Not Verified' }}</TableCell>
                                    <TableCell class="flex items-center justify-center gap-2">
                                        <EditUser :id="user.id" />
                                        <Confirmation
                                            v-if="user.id !== page.props.auth.user.id"
                                            :on-confirm="() => deleteUser(user.id)"
                                            :title="'Delete User'"
                                            :description="'Confirm deletion of this user'"
                                        >
                                            <template #trigger>
                                                <Button variant="destructive">
                                                    <Trash2 />
                                                </Button>
                                            </template>
                                            <div class="grid gap-2">
                                                <p>Apakah anda yakin ingin menghapus user ini?</p>
                                                <p class="w-fit rounded-lg bg-red-500 px-3 py-2 font-bold text-white">{{ user.name }}</p>
                                                <p class="text-sm text-red-600">This action cannot be undone.</p>
                                            </div>
                                        </Confirmation>
                                        <Link :href="route('admin.users.show', user.id)">
                                            <Button>
                                                <UserCog />
                                            </Button>
                                        </Link>
                                        <Button @click="sendVerificationEmail(user.id)" v-if="!user.email_verified_at">
                                            <Mail />
                                        </Button>
                                    </TableCell>
                                </TableRow>
                            </template>
                            <template v-else>
                                <TableEmpty colspan="6">
                                    <div class="flex flex-col items-center justify-center space-y-2">
                                        <ArchiveX class="size-10" />
                                        <p class="font-semibold">No users found.</p>
                                    </div>
                                </TableEmpty>
                            </template>
                        </TableBody>
                    </Table>
                    <DefaultPagination :pagination="users" />
                </CardContent>
            </Card>
        </div>
    </AppLayout>
</template>
