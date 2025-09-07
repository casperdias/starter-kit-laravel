<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Dialog, DialogContent, DialogDescription, DialogHeader, DialogTrigger } from '@/components/ui/dialog';
import DialogTitle from '@/components/ui/dialog/DialogTitle.vue';
import { ScrollArea } from '@/components/ui/scroll-area';
import { AppPageProps } from '@/types';
import { InertiaForm, usePage } from '@inertiajs/vue3';
import { Fullscreen, Megaphone, Newspaper, ShieldPlus } from 'lucide-vue-next';
import { computed } from 'vue';

const props = defineProps<{
    form: InertiaForm<{ title: string; type: string; content: string }>;
}>();

const page = usePage<AppPageProps>();
const user = computed(() => page.props.auth.user);

const icon = computed(() => {
    switch (props.form.type) {
        case 'announcement':
            return Megaphone;
        case 'update':
            return ShieldPlus;
        case 'news':
        default:
            return Newspaper;
    }
});
</script>

<template>
    <Dialog>
        <DialogTrigger as-child>
            <Button variant="outline" class="w-1/2 lg:w-auto">
                <Fullscreen />
                Preview
            </Button>
        </DialogTrigger>
        <DialogContent class="sm:max-w-3xl">
            <DialogHeader>
                <DialogTitle>Preview</DialogTitle>
                <DialogDescription> View how your news article will look before publishing. </DialogDescription>
            </DialogHeader>
            <ScrollArea class="h-[70vh] w-full rounded-md border p-3">
                <!-- Title -->
                <div class="flex items-center gap-2">
                    <component :is="icon" class="size-10" />
                    <div class="space-y-1">
                        <h2 class="text-xl font-bold">{{ form.title || 'Judul' }}</h2>
                        <p class="text-sm text-muted-foreground">By {{ user.name }}</p>
                    </div>
                </div>
                <!-- Content -->
                <div class="prose mt-4 max-w-none"></div>
            </ScrollArea>
        </DialogContent>
    </Dialog>
</template>
