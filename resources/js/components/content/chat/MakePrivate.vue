<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Dialog, DialogClose, DialogContent, DialogDescription, DialogHeader, DialogTitle, DialogTrigger } from '@/components/ui/dialog';
import { Input } from '@/components/ui/input';
import UserInfo from '@/components/UserInfo.vue';
import { useUserFetch } from '@/composables/helper';
import { useRoute } from '@/composables/useRoute';
import axios from 'axios';
import { ArchiveX, ChevronDown, LoaderCircle, MessageSquare, Search, UserRound, X } from 'lucide-vue-next';
import { onBeforeUnmount, ref, watch } from 'vue';
import { toast } from 'vue-sonner';

const route = useRoute();

const searchTerm = ref('');
const dialogOpen = ref(false);
let searchTimeout: ReturnType<typeof setTimeout>;

const {
    users,
    hasMore,
    isLoading,
    isLoadingMore,
    fetchUsers,
    loadMore,
    resetUsers,
} = useUserFetch();

watch(dialogOpen, (isOpen) => {
    if (isOpen) {
        fetchUsers();
    } else {
        searchTerm.value = '';
        resetUsers();
    }
});

watch(searchTerm, (newSearchTerm) => {
    if (!dialogOpen.value) return;

    clearTimeout(searchTimeout);

    if (newSearchTerm.trim() === '') {
        fetchUsers();
        return;
    }

    searchTimeout = setTimeout(() => {
        fetchUsers(newSearchTerm);
    }, 500);
});

onBeforeUnmount(() => {
    if (searchTimeout) clearTimeout(searchTimeout);
});

const startChat = (userId: string | number) => {
    axios
        .post(route('chats.store'), {
            type: 'private',
            user_id: userId,
        })
        .then((response) => {
            console.log(response);
        })
        .catch((error) => {
            toast.error('Failed to Start Chat', {
                description: error.message || 'An error occurred',
                closeButton: true,
            });
        });
};
</script>

<template>
    <Dialog v-model:open="dialogOpen">
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
                        <DialogDescription>Search User, Then Chat</DialogDescription>
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
                <Input
                    id="search"
                    type="text"
                    name="search"
                    placeholder="Search..."
                    class="w-full pl-10"
                    v-model="searchTerm"
                    :disabled="isLoading"
                />
                <span class="absolute inset-y-0 start-0 flex items-center justify-center px-2">
                    <Search class="size-6 text-muted-foreground" />
                </span>
            </div>

            <div class="max-h-96 overflow-y-auto">
                <div v-if="isLoading" class="flex flex-col items-center justify-center space-y-2 py-8">
                    <LoaderCircle class="size-10 animate-spin" />
                    <p class="font-semibold">Loading users...</p>
                </div>
                <div v-else-if="users.length === 0" class="flex flex-col items-center justify-center space-y-2 py-8">
                    <ArchiveX class="size-10" />
                    <p class="font-semibold">No users found.</p>
                </div>
                <div v-else class="space-y-3">
                    <div v-for="user in users" :key="user.id" class="flex items-center justify-between rounded border p-2">
                        <div class="flex items-center gap-2">
                            <UserInfo :user="user" :show-email="true" />
                        </div>
                        <Button size="sm" @click="startChat(user.id)">
                            <MessageSquare />
                        </Button>
                    </div>

                    <div v-if="isLoadingMore" class="flex items-center justify-center py-4">
                        <LoaderCircle class="size-6 animate-spin" />
                        <span class="ml-2 text-sm">Loading more...</span>
                    </div>
                </div>
            </div>

            <div v-if="hasMore && !isLoading && users.length > 0" class="flex justify-center">
                <Button @click="loadMore(searchTerm)" variant="outline" size="sm" :disabled="isLoadingMore" class="w-full">
                    <ChevronDown class="mr-2 h-4 w-4" />
                    Load More Users
                </Button>
            </div>
        </DialogContent>
    </Dialog>
</template>
