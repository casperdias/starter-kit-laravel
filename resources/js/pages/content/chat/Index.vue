<script setup lang="ts">
import { MakeGroup, MakePrivate } from '@/components/content/chat';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { Input } from '@/components/ui/input';
import { useRoute } from '@/composables/useRoute';
import AppLayout from '@/layouts/AppLayout.vue';
import { BreadcrumbItem, Conversation, CursorPagination } from '@/types';
import { Head } from '@inertiajs/vue3';
import { MessagesSquare, Search } from 'lucide-vue-next';
import { ref } from 'vue';

const route = useRoute();

defineProps<Props>();

interface Props {
    conversations: CursorPagination<Conversation>;
}

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Chat',
        href: route('chats.index'),
    },
];

const searchTerm = ref(route().params.search || '');
</script>

<template>
    <Head title="Chat" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="grid grid-cols-1 gap-4 px-4 pt-6 pb-4">
            <Card>
                <CardHeader class="grid grid-cols-1 gap-x-0 gap-y-4 md:grid-cols-2 md:gap-x-4 md:gap-y-0">
                    <div class="flex items-center space-x-5">
                        <MessagesSquare class="h-10 w-10" />
                        <div class="flex flex-col gap-2">
                            <CardTitle class="w-full">Chat</CardTitle>
                            <CardDescription class="hidden font-semibold lg:block"> View and Create your own chat room. </CardDescription>
                        </div>
                    </div>
                    <div class="relative flex h-full w-full items-center">
                        <Input id="search" type="text" name="search" placeholder="Search..." class="w-full pl-10" v-model="searchTerm" />
                        <span class="absolute inset-y-0 start-0 flex items-center justify-center px-2">
                            <Search class="size-6 text-muted-foreground" />
                        </span>
                    </div>
                </CardHeader>
                <CardContent>
                    <div class="grid grid-cols-2 gap-2">
                        <MakePrivate />
                        <MakeGroup />
                    </div>
                </CardContent>
            </Card>
        </div>
    </AppLayout>
</template>
