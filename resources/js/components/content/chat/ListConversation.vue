<script setup lang="ts">
import { Avatar, AvatarFallback, AvatarImage } from '@/components/ui/avatar';
import { Button } from '@/components/ui/button';
import { getInitials } from '@/composables/useInitials';
import { AppPageProps, Conversation, CursorPagination } from '@/types';
import { InfiniteScroll, router, usePage } from '@inertiajs/vue3';
import { ArchiveX, MessageSquare } from 'lucide-vue-next';
import { onBeforeUnmount, toRef, watch } from 'vue';

const page = usePage<AppPageProps>();
const me = page.props.auth.user;

const props = defineProps<Props>();

interface Props {
    conversations: CursorPagination<Conversation>;
    search: string;
}

const searchTerm = toRef(() => props.search);
let searchTimeout: ReturnType<typeof setTimeout>;

watch(searchTerm, (newSearch) => {
    clearTimeout(searchTimeout);
    searchTimeout = setTimeout(() => {
        router.get(
            props.conversations.meta.path,
            {
                search: newSearch,
            },
            {
                preserveState: true,
                preserveScroll: true,
                replace: true,
            },
        );
    }, 500);
});

onBeforeUnmount(() => {
    if (searchTimeout) clearTimeout(searchTimeout);
});

const conversationInfo = (data: Conversation) => {
    if (data.type == 'group') {
        return {
            avatar: data.avatar,
            name: data.name,
            last_message: data.last_message?.message ?? '-',
            last_update: data.last_message?.updated_at ?? data.created_at,
        };
    } else {
        const otherUser = data.participants.find((user) => user.id !== me.id);

        return {
            avatar: otherUser?.avatar,
            name: otherUser?.name,
            last_message: data.last_message?.message ?? '-',
            last_update: data.last_message?.updated_at ?? data.created_at,
        };
    }
};
</script>

<template>
    <div class="grid grid-cols-1 gap-4 px-4 pt-6 pb-4">
        <div class="h-[66vh] overflow-y-auto pr-5">
            <InfiniteScroll data="conversations" class="space-y-4" preserve-url>
                <template v-if="conversations.data.length === 0">
                    <Button variant="ghost" class="h-fit w-full border px-2 py-4">
                        <div class="flex flex-col items-center justify-center space-y-2">
                            <ArchiveX class="size-10" />
                            <p class="font-semibold">No conversation found.</p>
                        </div>
                    </Button>
                </template>
                <template v-else>
                    <div v-for="item in conversations.data" :key="item.id" class="flex items-center justify-between rounded border p-2">
                        <div class="flex items-center gap-2">
                            <Avatar class="h-8 w-8 overflow-hidden rounded-lg">
                                <AvatarImage v-if="item.avatar" :src="conversationInfo(item).avatar!" :alt="conversationInfo(item).name" />
                                <AvatarFallback class="rounded-lg text-black dark:text-white">
                                    {{ getInitials(conversationInfo(item).name) }}
                                </AvatarFallback>
                            </Avatar>
                            <div class="grid flex-1 text-left text-sm leading-tight">
                                <span class="flex items-center gap-1 truncate font-medium">
                                    {{ conversationInfo(item).name }}
                                    <p class="text-xs text-foreground italic">
                                        {{ item.type === 'group' ? '(' + item.participants.length + ' members)' : '' }}
                                    </p>
                                </span>
                                <span class="max-w-20 truncate text-xs text-muted-foreground">{{ conversationInfo(item).last_message }}</span>
                                <span class="truncate text-xs text-muted-foreground">{{ conversationInfo(item).last_update }}</span>
                            </div>
                        </div>
                        <Button size="sm">
                            <MessageSquare />
                        </Button>
                    </div>
                </template>
            </InfiniteScroll>
        </div>
    </div>
</template>
