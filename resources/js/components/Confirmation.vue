<script setup lang="ts">
import { Button } from './ui/button';
import { Dialog, DialogClose, DialogContent, DialogDescription, DialogFooter, DialogHeader, DialogTitle, DialogTrigger } from './ui/dialog';

defineProps<{
    title: string;
    description: string;
    onConfirm: () => void;
}>();
</script>

<template>
    <Dialog>
        <DialogTrigger as-child>
            <slot name="trigger" />
        </DialogTrigger>
        <DialogContent>
            <DialogHeader>
                <DialogTitle>{{ title }}</DialogTitle>
                <DialogDescription>{{ description }}</DialogDescription>
            </DialogHeader>
            <slot />
            <DialogFooter>
                <DialogClose as-child>
                    <Button variant="destructive">Cancel</Button>
                </DialogClose>
                <Button @click="onConfirm"> Confirm </Button>
            </DialogFooter>
        </DialogContent>
    </Dialog>
</template>
