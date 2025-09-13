<script setup lang="ts">
import { ScrollArea } from '@/components/ui/scroll-area';
import { Separator } from '@/components/ui/separator';
import { Megaphone, Newspaper, ShieldPlus } from 'lucide-vue-next';
import { computed, nextTick, onMounted, ref, shallowRef, watch } from 'vue';

const props = defineProps<{
    news: {
        title: string;
        type: string;
        content: string;
        author?: string;
        diff_created_at?: string;
    };
}>();

const icon = computed(() => {
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
const localContent = ref(props.news.content);

watch(
    () => props.news.content,
    (newVal) => {
        if (newVal !== localContent.value) {
            localContent.value = newVal;
        }
    },
);

onMounted(async () => {
    const { QuillyEditor: QE } = await import('vue-quilly');
    QuillyEditor.value = QE;
    const Quill = (await import('quill')).default;
    await nextTick();
    editor.value?.initialize(Quill);
});
</script>

<template>
    <div class="grid grid-cols-1 gap-4 px-4 pt-6 pb-4">
        <ScrollArea class="h-[85vh] w-full rounded-md border p-3">
            <div class="mb-2 flex items-center gap-2 px-2">
                <component :is="icon" class="size-10" />
                <div class="space-y-1">
                    <h2 class="text-xl font-bold">{{ news.title || 'Judul' }}</h2>
                    <p class="text-sm text-muted-foreground">By {{ news.author || 'Unknown' }}</p>
                    <p class="text-xs text-muted-foreground">{{ news.diff_created_at }}</p>
                </div>
            </div>
            <Separator class="my-2" />
            <component :is="QuillyEditor" v-if="QuillyEditor" ref="editor" :options="options" v-model="localContent" />
        </ScrollArea>
    </div>
</template>
