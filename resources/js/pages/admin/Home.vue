<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardDescription, CardFooter, CardHeader, CardTitle } from '@/components/ui/card';
import { useRoute } from '@/composables/useRoute';
import AppLayout from '@/layouts/AppLayout.vue';
import { AppPageProps, type BreadcrumbItem } from '@/types';
import { Head, Link, usePage } from '@inertiajs/vue3';
import { Eye, ShieldPlus, User, UserCog } from 'lucide-vue-next';
import { computed } from 'vue';
const route = useRoute();

const page = usePage<AppPageProps>();
const permissions = computed(() => page.props.auth.user.permissions);

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Master',
        href: '/admin',
    },
];

const cards = [
    {
        title: 'User',
        description: 'Pengaturan User Aplikasi',
        icon: User,
        url: route('admin.users.index'),
        list: ['CRUD User', 'Pengaturan Role User'],
        permissions: ['user'],
    },
    {
        title: 'Role',
        description: 'Pengaturan Role Aplikasi',
        icon: UserCog,
        url: route('admin.roles.index'),
        list: ['CRUD Role', 'Pengaturan Permission Role'],
        permissions: ['role'],
    },
    {
        title: 'Permission',
        description: 'Pengaturan Permission Aplikasi',
        icon: ShieldPlus,
        url: route('admin.permissions.index'),
        list: ['CRUD Permission'],
        permissions: ['permission'],
    },
];

const filteredCards = computed(() => {
    return cards.filter((card) => {
        if (!card.permissions || card.permissions.length === 0) {
            return true;
        }
        return card.permissions.some((perm) => permissions.value.includes(perm));
    });
});
</script>

<template>
    <Head title="Master" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="grid grid-cols-1 gap-4 px-4 pt-6 pb-4">
            <div class="grid w-full auto-rows-min gap-4">
                <Card
                    v-for="(card, index) in filteredCards"
                    :key="index"
                    class="relative h-fit overflow-hidden rounded-xl border border-sidebar-border/70 dark:border-sidebar-border"
                >
                    <CardHeader>
                        <CardTitle>{{ card.title }}</CardTitle>
                        <CardDescription>{{ card.description }}</CardDescription>
                    </CardHeader>
                    <CardContent class="flex items-center space-x-3">
                        <component :is="card.icon" class="text-primary-500 min-h-20 min-w-20" />
                        <ul class="flex list-disc flex-col gap-2 pl-5">
                            <li v-for="(item, index) in card.list" :key="index" class="text-sm font-bold">
                                {{ item }}
                            </li>
                        </ul>
                    </CardContent>
                    <CardFooter>
                        <Link class="w-full" :href="card.url">
                            <Button class="w-full cursor-pointer" variant="default">
                                <Eye class="ml-2 h-6 w-6" />
                                Lihat Data
                            </Button>
                        </Link>
                    </CardFooter>
                </Card>
            </div>
        </div>
    </AppLayout>
</template>
