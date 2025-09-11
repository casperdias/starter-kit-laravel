<script lang="ts" setup>
import List from '@/components/content/news/List.vue';
import { ResizableHandle, ResizablePanel, ResizablePanelGroup } from '@/components/ui/resizable';
import { useRoute } from '@/composables/useRoute';
import AppLayout from '@/layouts/AppLayout.vue';
import { BreadcrumbItem, News, Pagination } from '@/types';
import { Head } from '@inertiajs/vue3';

const route = useRoute();

defineProps<Props>();

interface Props {
    news: Pagination<News>;
}

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'News',
        href: route('news.index'),
    },
];
</script>

<template>
    <Head title="News" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <ResizablePanelGroup direction="horizontal">
            <ResizablePanel :default-size="30">
                <List :news="news" />
            </ResizablePanel>
            <ResizableHandle with-handle />
            <ResizablePanel :default-size="70"> </ResizablePanel>
        </ResizablePanelGroup>
    </AppLayout>
</template>
