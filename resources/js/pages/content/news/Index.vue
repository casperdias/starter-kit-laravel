<script lang="ts" setup>
import { Display, List } from '@/components/content/news';
import { ResizableHandle, ResizablePanel, ResizablePanelGroup } from '@/components/ui/resizable';
import { useRoute } from '@/composables/useRoute';
import AppLayout from '@/layouts/AppLayout.vue';
import { BreadcrumbItem, CursorPagination, News } from '@/types';
import { Head } from '@inertiajs/vue3';
import { ref } from 'vue';

const route = useRoute();

defineProps<Props>();

interface Props {
    news: CursorPagination<News>;
}

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'News',
        href: route('news.index'),
    },
];

const selectedNews = ref<News | null>(null);
</script>

<template>
    <Head title="News" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <ResizablePanelGroup direction="horizontal">
            <ResizablePanel :default-size="30" :min-size="30" :max-size="50">
                <List :news="news" @select-news="selectedNews = $event" />
            </ResizablePanel>
            <ResizableHandle with-handle />
            <ResizablePanel :default-size="70">
                <Display :news="selectedNews" />
            </ResizablePanel>
        </ResizablePanelGroup>
    </AppLayout>
</template>
