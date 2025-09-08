<script setup lang="ts">
import Preview from '@/components/content/news/Preview.vue';
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Select, SelectContent, SelectGroup, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import { useRoute } from '@/composables/useRoute';
import AppLayout from '@/layouts/AppLayout.vue';
import { BreadcrumbItem } from '@/types';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { Newspaper, Plus, X } from 'lucide-vue-next';
import Quill from 'quill';
import { onMounted, ref } from 'vue';
import { QuillyEditor } from 'vue-quilly';

const route = useRoute();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'News',
        href: route('news.index'),
    },
    {
        title: 'Create',
        href: route('news.create'),
    },
];

const form = useForm({
    title: '',
    type: 'news',
    content: '',
});

const options = {
    theme: 'snow',
    modules: {
        toolbar: [
            [{ font: [] }, { size: [] }],
            ['bold', 'italic', 'underline', 'strike'],
            [{ color: [] }, { background: [] }],
            [{ script: 'super' }, { script: 'sub' }],
            [{ header: '1' }, { header: '2' }, 'blockquote', 'code-block'],
            [{ list: 'ordered' }, { list: 'bullet' }, { indent: '-1' }, { indent: '+1' }],
            ['direction', { align: [] }],
            ['clean'],
        ],
    },
    placeholder: 'Start writing your news content here...',
    readOnly: false,
};
const editor = ref<InstanceType<typeof QuillyEditor>>();
onMounted(() => {
    const instance = editor.value?.initialize(Quill);
    if (!instance) {
        console.warn('Quill editor failed to initialize.');
    }
});
</script>

<template>
    <Head title="Create News" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="grid grid-cols-1 gap-4 px-4 pt-6 pb-4">
            <Card>
                <CardHeader class="grid grid-cols-1 gap-x-0 gap-y-4 md:grid-cols-2 md:gap-x-4 md:gap-y-0">
                    <div class="flex items-center space-x-5">
                        <Newspaper class="h-10 w-10" />
                        <div class="flex flex-col gap-2">
                            <CardTitle class="w-full">News</CardTitle>
                            <CardDescription class="hidden font-semibold lg:block"> Create your news articles here. </CardDescription>
                        </div>
                    </div>
                    <div class="flex h-full w-full items-center justify-end-safe">
                        <Link :href="route('news.index')" class="w-full md:w-auto">
                            <Button class="w-full md:w-auto" variant="destructive">
                                <X />
                                <span class="ml-2">Cancel</span>
                            </Button>
                        </Link>
                    </div>
                </CardHeader>
                <CardContent>
                    <div class="grid grid-cols-1 gap-4 lg:grid-cols-4">
                        <div class="col-span-1 space-y-1 lg:col-span-2">
                            <Label for="title" class="font-semibold">Title</Label>
                            <Input v-model="form.title" type="text" id="title" placeholder="Enter the title of your news article" required />
                            <InputError :message="form.errors.title" />
                        </div>
                        <div class="col-span-1 space-y-1">
                            <Label for="type" class="font-semibold">Type</Label>
                            <Select v-model="form.type" required>
                                <SelectTrigger class="w-full" id="type">
                                    <SelectValue placeholder="Select a type" />
                                </SelectTrigger>
                                <SelectContent>
                                    <SelectGroup>
                                        <SelectItem value="news">News</SelectItem>
                                        <SelectItem value="announcement">Announcement</SelectItem>
                                        <SelectItem value="update">Update</SelectItem>
                                    </SelectGroup>
                                </SelectContent>
                            </Select>
                            <InputError :message="form.errors.type" />
                        </div>
                        <div class="col-span-1 flex items-center justify-end gap-1">
                            <Preview :form="form" />
                            <Button class="w-1/2 lg:w-auto">
                                <Plus />
                                Publish
                            </Button>
                        </div>
                    </div>
                    <div class="space-y-1">
                        <Label for="content" class="mt-4 mb-2 font-semibold">Content</Label>
                        <QuillyEditor ref="editor" v-model="form.content" :options="options" :is-semantic-html-model="true" />
                    </div>
                </CardContent>
            </Card>
        </div>
    </AppLayout>
</template>
