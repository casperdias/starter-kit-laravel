<script setup lang="ts">
import { Message, User } from '@/types';
import { onUpdated, ref } from 'vue';

defineProps<{
    messages: Message[];
    user: User;
}>();

const scrollRef = ref<HTMLElement | null>(null);

onUpdated(() => {
    if (scrollRef.value) {
        scrollRef.value.scrollTop = scrollRef.value.scrollHeight;
    }
});
</script>

<template>
    <div ref="scrollRef" class="flex-1 space-y-3 overflow-y-auto rounded border bg-sidebar px-3 py-2 text-xs">
        <!-- Message List -->
        <div
            v-for="(msg, idx) in messages"
            :key="idx"
            :class="{
                'flex justify-end': msg.user.id === user.id,
                'flex justify-start': msg.user.id !== user.id,
            }"
        >
            <div class="max-w-[70%] space-y-1">
                <p class="font-bold">
                    {{ msg.user.name }}
                </p>
                <div class="rounded p-2" :class="msg.user.id === user.id ? 'bg-blue-100' : 'bg-background'">
                    <span v-if="msg.taggedUser" class="font-bold"> @{{ msg.taggedUser.name }} </span>
                    {{ msg.message }}
                </div>
                <p class="text-[10px] text-muted-foreground">
                    {{ msg.created_at }}
                </p>
            </div>
        </div>
    </div>
</template>
