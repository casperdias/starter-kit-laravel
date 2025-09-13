<script setup lang="ts">
import { ScrollArea } from '@/components/ui/scroll-area';
import { Separator } from '@/components/ui/separator';
import { News } from '@/types';
import { Megaphone, Newspaper, ShieldPlus } from 'lucide-vue-next';
import { computed, nextTick, onMounted, ref, shallowRef, watch } from 'vue';

const props = defineProps<{
    news: News | null;
}>();

const icon = computed(() => {
    if (!props.news) return Newspaper;
    switch (props.news.type) {
        case 'announcement':
            return Megaphone;
        case 'update':
            return ShieldPlus;
        case 'news':
        default:
            return Newspaper;
    }
});

const options = {
    theme: 'bubble',
    placeholder: 'No content',
    readOnly: true,
};
const editor = ref();
const QuillyEditor = shallowRef();

const localContent = ref(props.news ? props.news.content : '');

watch(
    () => props.news && props.news.content,
    (newVal) => {
        if (newVal !== localContent.value) {
            localContent.value = newVal || '';
        }
    },
);

const initializeEditor = async () => {
    if (!QuillyEditor.value) return;
    const Quill = (await import('quill')).default;
    await nextTick();
    editor.value?.initialize(Quill);
};

onMounted(async () => {
    const { QuillyEditor: QE } = await import('vue-quilly');
    QuillyEditor.value = QE;
    await initializeEditor();
});

watch(
    () => props.news,
    async () => {
        if (QuillyEditor.value) {
            await initializeEditor();
        }
    },
);
</script>

<template>
    <div class="grid grid-cols-1 gap-4 px-4 pt-6 pb-4">
        <template v-if="news">
            <ScrollArea class="h-[85vh] w-full rounded-md border p-3">
                <div class="mb-2 flex items-center gap-2 px-2">
                    <component :is="icon" class="size-10" />
                    <div class="w-full space-y-1">
                        <div class="flex items-center justify-between">
                            <h2 class="text-xl font-bold">{{ news.title || 'Judul' }}</h2>
                            <p class="text-sm text-muted-foreground">{{ news.created_at || 'Unknown' }}</p>
                        </div>
                        <p class="text-sm text-muted-foreground">By {{ news.author || 'Unknown' }}</p>
                        <p class="text-xs text-muted-foreground">{{ news.diff_created_at }}</p>
                    </div>
                </div>
                <Separator class="my-2" />
                <component :is="QuillyEditor" v-if="QuillyEditor" ref="editor" :options="options" v-model="localContent" />
            </ScrollArea>
        </template>
        <template v-else>
            <div class="flex h-[85vh] w-full flex-col items-center justify-center rounded-md border p-3 text-center text-muted-foreground">
                <Newspaper class="mb-4 size-12 opacity-30" />
                <div class="space-y-2">
                    <h2 class="text-xl font-bold">No News Selected</h2>
                    <p class="text-sm">Select a news item from the list to view its details here.</p>
                </div>
            </div>
        </template>
    </div>
</template>
