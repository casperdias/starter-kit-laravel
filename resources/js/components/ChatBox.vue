<script setup lang="ts">
import { Message } from '@/types';

defineProps<{
    messages: Message[];
}>();
</script>

<template>
    <div class="flex-1 space-y-3 overflow-y-auto rounded border bg-sidebar px-3 py-2 text-xs">
        <!-- Message List -->
        <div v-for="(msg, idx) in messages" :key="idx" class="space-y-1">
            <p class="font-bold">
                {{ msg.user.name }}
            </p>
            <div class="rounded bg-background p-2">
                {{ msg.message }}
            </div>
            <p class="text-[10px] text-muted-foreground">
                {{ msg.created_at }}
            </p>
        </div>
    </div>
</template>
