<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Dialog, DialogClose, DialogContent, DialogDescription, DialogHeader, DialogTitle, DialogTrigger } from '@/components/ui/dialog';
import { Input } from '@/components/ui/input';
import { MessageSquare, Search, UserRound, X } from 'lucide-vue-next';
import { onMounted, ref } from 'vue';
import axios from 'axios';
import { User } from '@/types';
import { useRoute } from '@/composables/useRoute';

const route = useRoute();

const searchTerm = ref('');
const users = ref<User[]>([]);

onMounted(() => {
    axios.get(route('external.user-list'))
        .then(response => {
            console.log('User List:', response.data);
        })
        .catch(error => {
            console.error('Error fetching user list:', error);
        });
});
</script>

<template>
    <Dialog>
        <DialogTrigger as-child>
            <Button class="w-full">
                <UserRound />
                <p class="hidden lg:block">Private</p>
            </Button>
        </DialogTrigger>
        <DialogContent :hide-close="true">
            <DialogHeader class="flex flex-row items-center justify-between">
                <div class="flex items-center gap-3">
                    <MessageSquare class="size-10" />
                    <div class="hidden max-w-[400px] space-y-2 md:block">
                        <DialogTitle>Private Chat</DialogTitle>
                        <DialogDescription>Search User, Then Select</DialogDescription>
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
            <div class="relative flex h-full w-full items-center">
                <Input id="search" type="text" name="search" placeholder="Search..." class="w-full pl-10" v-model="searchTerm" />
                <span class="absolute inset-y-0 start-0 flex items-center justify-center px-2">
                    <Search class="size-6 text-muted-foreground" />
                </span>
            </div>
        </DialogContent>
    </Dialog>
</template>
