<script setup lang="ts">
import { changePage } from '@/composables/paginationHelper';
import { cn } from '@/lib/utils';
import { Pagination as PaginationType } from '@/types';
import { HTMLAttributes, ref, watch } from 'vue';
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
    pagination: PaginationType<unknown>;
    pageParam?: string;
    only: string[];
}>();

const currentPage = ref(props.pagination.meta.current_page);

watch(
    () => props.pagination.meta.current_page,
    (newPage) => {
        currentPage.value = newPage;
    },
);
</script>

<template>
    <Pagination
        :items-per-page="pagination.meta.per_page"
        :total="pagination.meta.total"
        :default-page="currentPage"
        @update:page="
            (page: number) => {
                currentPage = page;
                changePage(pagination.meta.path, page, pageParam, only);
            }
        "
    >
        <PaginationList v-slot="{ items }" :class="cn('flex items-center justify-center gap-1', props.class)">
            <PaginationFirst :disabled="currentPage === 1" />
            <PaginationPrevious :disabled="currentPage === 1" />

            <template v-for="(item, index) in items">
                <PaginationListItem v-if="item.type === 'page'" :key="index" :value="item.value" as-child>
                    <Button :variant="item.value === currentPage ? 'default' : 'outline'">{{ item.value }}</Button>
                </PaginationListItem>
                <PaginationEllipsis v-else :key="item.type" :index="index" />
            </template>

            <PaginationNext :disabled="currentPage === pagination.meta.last_page" />
            <PaginationLast :disabled="currentPage === pagination.meta.last_page" />
        </PaginationList>
    </Pagination>
</template>
