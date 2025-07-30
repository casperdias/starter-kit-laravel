<script setup lang="ts">
import { changePage } from '@/composables/indexHelper';
import { cn } from '@/lib/utils';
import { Pagination as PaginationType } from '@/types';
import { HTMLAttributes } from 'vue';
import { Button } from './ui/button';
import {
    Pagination,
    PaginationEllipsis,
    PaginationFirst,
    PaginationLast,
    PaginationList,
    PaginationListItem,
    PaginationNext,
    PaginationPrevious,
} from './ui/pagination';

const props = defineProps<{
    class?: HTMLAttributes['class'];
    pagination: PaginationType<any>;
    pageParam?: string;
}>();
</script>

<template>
    <Pagination
        v-slot="{ page }"
        :items-per-page="pagination.meta.per_page"
        :total="pagination.meta.total"
        :default-page="pagination.meta.current_page"
    >
        <PaginationList v-slot="{ items }" :class="cn('flex items-center justify-center gap-1', props.class)">
            <PaginationFirst @click="changePage(pagination.meta.path, 1, props.pageParam)" />
            <PaginationPrevious @click="changePage(pagination.meta.path, page - 1, props.pageParam)" />

            <template v-for="(item, index) in items">
                <PaginationListItem v-if="item.type === 'page'" :key="index" :value="item.value" as-child>
                    <Button
                        :variant="item.value === page ? 'default' : 'outline'"
                        @click="changePage(pagination.meta.path, item.value, props.pageParam)"
                        >{{ item.value }}</Button
                    >
                </PaginationListItem>
                <PaginationEllipsis v-else :key="item.type" :index="index" />
            </template>

            <PaginationNext @click="changePage(pagination.meta.path, page + 1, props.pageParam)" />
            <PaginationLast @click="changePage(pagination.meta.path, pagination.meta.last_page, props.pageParam)" />
        </PaginationList>
    </Pagination>
</template>
