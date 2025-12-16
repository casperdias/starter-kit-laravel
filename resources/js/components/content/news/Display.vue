<script setup lang="ts">
import { ScrollArea } from '@/components/ui/scroll-area';
import { Separator } from '@/components/ui/separator';
import { useRoute } from '@/composables/useRoute';
import { News } from '@/types';
import axios from 'axios';
import { Megaphone, Newspaper, ShieldPlus } from 'lucide-vue-next';
import { computed, nextTick, onMounted, ref, shallowRef, watch } from 'vue';

const route = useRoute();

const props = defineProps<{
    news: string | null;
}>();

const newsDetail = ref<News | null>(null);
const localContent = ref('');
const QuillyEditor = shallowRef();
const editor = ref();

const icon = computed(() => {
    if (!newsDetail.value) return Newspaper;
    switch (newsDetail.value.type) {
        case 'announcement':
            return Megaphone;
        case 'update':
            return ShieldPlus;
        default:
            return Newspaper;
    }
});

const options = {
    theme: 'bubble',
    placeholder: 'No content',
    readOnly: true,
};

onMounted(async () => {
    const { QuillyEditor: QE } = await import('vue-quilly');
    QuillyEditor.value = QE;
    await nextTick();
});

const fetchNewsDetail = async (id: string) => {
    const response = await axios.get(route('news.show', { news: id }));
    newsDetail.value = response.data;
    localContent.value = newsDetail.value?.content || '';
    await nextTick();
    if (QuillyEditor.value) {
        editor.value?.initialize((await import('quill')).default);
    }
};

watch(
    () => props.news,
    async (news) => {
        if (news) {
            await fetchNewsDetail(news);
        } else {
            newsDetail.value = null;
            localContent.value = '';
        }
    },
    { immediate: true },
);
</script>

<template>
    <div class="grid grid-cols-1 gap-4 px-4 pt-6 pb-4">
        <template v-if="newsDetail">
            <ScrollArea class="h-[86vh] w-full">
                <div class="flex w-full flex-col gap-2">
                    <div class="flex items-center gap-2 px-2">
                        <component :is="icon" class="size-10" />
                        <div class="flex w-full items-center justify-between gap-1">
                            <h2 class="text-xl font-bold">{{ newsDetail.title || 'Judul' }}</h2>
                            <p class="text-sm text-muted-foreground">{{ newsDetail.created_at || 'Unknown' }}</p>
                        </div>
                    </div>
                    <div class="flex items-center justify-between px-2">
                        <p class="text-sm font-semibold text-muted-foreground">By {{ newsDetail.author || 'Unknown' }}</p>
                        <p class="text-sm text-muted-foreground">{{ newsDetail.diff_created_at }}</p>
                    </div>
                </div>
                <Separator class="my-2" />
                <component :is="QuillyEditor" v-if="QuillyEditor" ref="editor" :options="options" v-model="localContent" />
            </ScrollArea>
        </template>
        <template v-else>
            <div class="flex h-[86vh] w-full flex-col items-center justify-center text-center text-muted-foreground">
                <Newspaper class="mb-4 size-12 opacity-30" />
                <div class="space-y-2">
                    <h2 class="text-xl font-bold">No News Selected</h2>
                    <p class="text-sm">Select a news item from the list to view its details here.</p>
                </div>
            </div>
        </template>
    </div>
</template>
