<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Dialog, DialogClose, DialogContent, DialogDescription, DialogHeader, DialogTitle, DialogTrigger } from '@/components/ui/dialog';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Textarea } from '@/components/ui/textarea';
import { useForm } from '@inertiajs/vue3';
import { MessagesSquare, UsersRound, X } from 'lucide-vue-next';

const form = useForm({
    name: '',
    type: 'group',
    description: '',
    avatar: '',
});
</script>

<template>
    <Dialog>
        <DialogTrigger as-child>
            <Button class="w-full">
                <UsersRound />
                <p class="hidden lg:block">Group</p>
            </Button>
        </DialogTrigger>
        <DialogContent :hide-close="true">
            <DialogHeader class="flex flex-row items-center justify-between">
                <div class="flex items-center gap-3">
                    <MessagesSquare class="size-10" />
                    <div class="hidden max-w-[400px] space-y-2 md:block">
                        <DialogTitle>Group Chat</DialogTitle>
                        <DialogDescription>Search User, Select User, Create Group</DialogDescription>
                    </div>
                </div>
                <div class="flex items-center justify-end gap-2">
                    <DialogClose as-child>
                        <Button variant="destructive" size="sm">
                            <X class="h-4 w-4" />
                            <p class="hidden md:block">Tutup</p>
                        </Button>
                    </DialogClose>
                </div>
            </DialogHeader>
            <div class="space-y-2">
                <Label for="name">Group Name</Label>
                <Input id="name" v-model="form.name" />
            </div>
            <div class="space-y-2">
                <Label for="description">Description</Label>
                <Textarea id="description" v-model="form.description" />
            </div>
        </DialogContent>
    </Dialog>
</template>
