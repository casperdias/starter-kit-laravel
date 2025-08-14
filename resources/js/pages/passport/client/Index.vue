<script setup lang="ts">
import { AddClient } from '@/components/passport/client';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { Input } from '@/components/ui/input';
import AppLayout from '@/layouts/AppLayout.vue';
import { BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';
import { BookKey, Search } from 'lucide-vue-next';
import { ref } from 'vue';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Passport Client',
        href: route('passport-clients.index'),
    },
];

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
                </CardContent>
            </Card>
        </div>
    </AppLayout>
</template>
