<script lang="ts" setup>
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardHeader } from '@/components/ui/card';
import { Input } from '@/components/ui/input';
import { useRoute } from '@/composables/useRoute';
import AppLayout from '@/layouts/AppLayout.vue';
import { BreadcrumbItem } from '@/types';
import { Head, Link } from '@inertiajs/vue3';
import { Filter, Plus, Search } from 'lucide-vue-next';
import { ref } from 'vue';

const route = useRoute();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'News',
        href: route('news.index'),
    },
];

const searchTerm = ref(route().params.search || '');
</script>

<template>
    <Head title="News" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="grid grid-cols-1 gap-4 px-4 pt-6 pb-4">
            <Card>
                <CardHeader class="grid grid-cols-1 gap-x-0 gap-y-4 md:grid-cols-4 md:gap-x-4 md:gap-y-0">
                    <div class="relative col-span-1 flex h-full w-full items-center md:col-span-3">
                        <Input id="search" type="text" name="search" placeholder="Search..." class="w-full pl-10" v-model="searchTerm" />
                        <span class="absolute inset-y-0 start-0 flex items-center justify-center px-2">
                            <Search class="size-6 text-muted-foreground" />
                        </span>
                    </div>
                    <Button variant="secondary" class="col-span-1 md:col-span-1">
                        <Filter />
                        Filter
                    </Button>
                </CardHeader>
                <CardContent>
                    <Link :href="route('news.create')">
                        <Button class="w-full">
                            <Plus class="size-4" />
                            Add News
                        </Button>
                    </Link>
                </CardContent>
            </Card>
        </div>
    </AppLayout>
</template>
