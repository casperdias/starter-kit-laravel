<script setup lang="ts">
import { Conversation, CursorPagination } from '@/types';

const props = defineProps<Props>();

interface Props {
    conversations: CursorPagination<Conversation>;
}
</script>

<template>
    <div class="h-[69vh] divide-y divide-border overflow-y-auto pr-5"></div>
</template>
