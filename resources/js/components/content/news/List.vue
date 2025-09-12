<script setup lang="ts">
import DefaultPagination from '@/components/DefaultPagination.vue';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardHeader } from '@/components/ui/card';
import { Input } from '@/components/ui/input';
import { ScrollArea } from '@/components/ui/scroll-area';
import { useRoute } from '@/composables/useRoute';
import { News, Pagination } from '@/types';

import { Link, router } from '@inertiajs/vue3';
import { ArchiveX, Filter, Megaphone, Newspaper, Plus, Search, ShieldPlus } from 'lucide-vue-next';
import { onBeforeUnmount, ref, watch } from 'vue';

const route = useRoute();

const props = defineProps<Props>();

interface Props {
    news: Pagination<News>;
}

const icon = (type: string) => {
    switch (type) {
        case 'announcement':
            return Megaphone;
        case 'update':
            return ShieldPlus;
        case 'news':
        default:
            return Newspaper;
    }
};

const searchTerm = ref(route().params.search || '');
let searchTimeout: ReturnType<typeof setTimeout>;

watch(searchTerm, (newTerm) => {
    clearTimeout(searchTimeout);
    searchTimeout = setTimeout(() => {
        router.get(
            props.news.meta.path,
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
    <div class="grid grid-cols-1 gap-4 px-4 pt-6 pb-4">
        <Card>
            <CardHeader class="grid grid-cols-1 gap-x-0 gap-y-4 md:grid-cols-4 md:gap-x-4 md:gap-y-0">
                <div class="relative col-span-1 flex h-full w-full items-center md:col-span-3">
                    <Input id="search" type="text" name="search" placeholder="Search..." class="w-full pl-10" v-model="searchTerm" />
                    <span class="absolute inset-y-0 start-0 flex items-center justify-center px-2">
                        <Search class="size-6 text-muted-foreground" />
                    </span>
                </div>
                <Button variant="secondary" class="@container col-span-1 md:col-span-1">
                    <Filter />
                    <span class="@max-[80px]:hidden"> Filter </span>
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
        <ScrollArea class="h-[600px] w-full">
            <div class="space-y-4">
                <template v-if="news.data.length === 0">
                    <Button variant="ghost" class="h-fit w-full border p-2">
                        <div class="flex flex-col items-center justify-center space-y-2">
                            <ArchiveX class="size-10" />
                            <p class="font-semibold">No news found.</p>
                        </div>
                    </Button>
                </template>
                <template v-else>
                    <Button v-for="item in news.data" :key="item.id" variant="ghost" class="h-fit w-full border p-2">
                        <div class="flex w-full flex-col gap-2">
                            <div class="flex items-center gap-2 px-2">
                                <component :is="icon(item.type)" class="size-10" />
                                <h2 class="text-xl font-bold">{{ item.title || 'Judul' }}</h2>
                            </div>
                            <div class="flex items-center justify-between pl-4">
                                <p class="text-sm font-semibold text-muted-foreground">By {{ item.author || 'Unknown' }}</p>
                                <p class="text-sm text-muted-foreground">{{ item.diff_created_at }}</p>
                            </div>
                        </div>
                    </Button>
                </template>
            </div>
        </ScrollArea>
        <DefaultPagination :pagination="news" />
    </div>
</template>
