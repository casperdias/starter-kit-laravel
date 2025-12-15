<script setup lang="ts">
import { Avatar, AvatarFallback, AvatarImage } from '@/components/ui/avatar';
import { Separator } from '@/components/ui/separator';
import { conversationInfo } from '@/composables/converastionHelper';
import { getInitials } from '@/composables/useInitials';
import { useRoute } from '@/composables/useRoute';
import { AppPageProps, Conversation } from '@/types';
import { usePage } from '@inertiajs/vue3';
import { MessageCircleX } from 'lucide-vue-next';
import { watch } from 'vue';
import { toast } from 'vue-sonner';

const page = usePage<AppPageProps>();
const me = page.props.auth.user;

const route = useRoute();

const props = defineProps<{
    conversation: Conversation | null;
}>();

watch(
    () => props.conversation,
    (newConversation) => {
        toast.success(`Selected conversation with ID: ${newConversation?.id}` || 'No conversation selected');
    },
    { immediate: true },
);
</script>

<template>
    <div class="grid grid-cols-1 gap-4 px-4 pt-6 pb-4">
        <template v-if="conversation">
            <div class="flex w-full flex-col gap-2">
                <Avatar class="size-10 overflow-hidden rounded-lg">
                    <AvatarImage
                        v-if="conversation.avatar"
                        :src="conversationInfo(conversation, me).avatar!"
                        :alt="conversationInfo(conversation, me).name"
                    />
                    <AvatarFallback class="rounded-lg text-black dark:text-white">
                        {{ getInitials(conversationInfo(conversation, me).name) }}
                    </AvatarFallback>
                </Avatar>
            </div>
            <Separator class="my-2" />
        </template>
        <template v-else>
            <div class="flex h-[66vh] w-full flex-col items-center justify-center text-center text-muted-foreground">
                <MessageCircleX class="mb-4 size-12 opacity-30" />
                <div class="space-y-2">
                    <h2 class="text-xl font-bold">No Chat Selected</h2>
                    <p class="text-sm">Select a chat from the list to view its details here.</p>
                </div>
            </div>
        </template>
    </div>
</template>
