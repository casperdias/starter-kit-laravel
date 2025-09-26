<script setup lang="ts">
import { Button } from '@/components/ui/button';
import {
    Dialog,
    DialogClose,
    DialogContent,
    DialogDescription,
    DialogFooter,
    DialogHeader,
    DialogTitle,
    DialogTrigger,
} from '@/components/ui/dialog';
import { Label } from '@/components/ui/label';
import { Select, SelectContent, SelectGroup, SelectItem, SelectLabel, SelectTrigger, SelectValue } from '@/components/ui/select';
import { Filter, ListFilter, X } from 'lucide-vue-next';
import { ref, watch } from 'vue';

const props = defineProps<Props>();
interface Props {
    category: string;
    open: boolean;
}
const emit = defineEmits<{
    (e: 'update:category', value: string): void;
    (e: 'update:open', value: boolean): void;
}>();

const categoryLocal = ref(props.category);
const openLocal = ref(props.open);

watch(
    () => props.open,
    (val) => (openLocal.value = val),
);

watch(openLocal, (val) => emit('update:open', val));

const apply = () => {
    emit('update:category', categoryLocal.value);
};
</script>

<template>
    <Dialog v-model:open="openLocal">
        <DialogTrigger as-child>
            <Button variant="secondary" class="@container col-span-1 md:col-span-1">
                <ListFilter />
                <span class="@max-[80px]:hidden"> Filter </span>
            </Button>
        </DialogTrigger>
        <DialogContent>
            <DialogHeader>
                <DialogTitle> Filter News</DialogTitle>
                <DialogDescription> Filter news by category </DialogDescription>
            </DialogHeader>
            <Label for="category">Category</Label>
            <Select v-model="categoryLocal">
                <SelectTrigger class="w-full" id="category">
                    <SelectValue placeholder="Filter Status" />
                </SelectTrigger>
                <SelectContent>
                    <SelectGroup>
                        <SelectLabel>Category</SelectLabel>
                        <SelectItem value="all">All</SelectItem>
                        <SelectItem value="news">News</SelectItem>
                        <SelectItem value="announcement">Announcement</SelectItem>
                        <SelectItem value="update">Update</SelectItem>
                    </SelectGroup>
                </SelectContent>
            </Select>
            <DialogFooter class="flex w-full gap-2">
                <DialogClose as-child>
                    <Button variant="destructive" class="flex-1">
                        <X />
                        Close
                    </Button>
                </DialogClose>
                <Button class="flex-1" @click="apply">
                    <Filter />
                    Apply
                </Button>
            </DialogFooter>
        </DialogContent>
    </Dialog>
</template>
