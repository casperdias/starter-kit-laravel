<script setup lang="ts">
import { Avatar, AvatarFallback, AvatarImage } from '@/components/ui/avatar';
import { Card, CardContent, CardHeader } from '@/components/ui/card';
import { Input } from '@/components/ui/input';
import { changePage } from '@/composables/paginationHelper';
import { useInitials } from '@/composables/useInitials';
import { useRoute } from '@/composables/useRoute';
import { CursorPagination, User } from '@/types';
import { useInfiniteScroll } from '@vueuse/core';
import { Search } from 'lucide-vue-next';
import { computed, ref, useTemplateRef } from 'vue';

const route = useRoute();

const props = defineProps<{
    users: CursorPagination<User>;
}>();

const searchTerm = ref(route().params.search || '');

const { getInitials } = useInitials();

const showAvatar = computed(() => {
    return (user: User) => user.avatar && user.avatar !== '';
});

const userList = useTemplateRef<HTMLElement>('userList');

useInfiniteScroll(
    userList,
    () => {
        changePage(props.users.meta.path, props.users.meta.next_cursor as string, 'cursor', ['users']);
    },
    {
        distance: 10,
        canLoadMore: () => props.users.meta.next_cursor !== null,
    },
);
</script>

<template>
    <Card class="col-span-1">
        <CardHeader>
            <div class="relative flex h-full w-full items-center">
                <Input id="search" type="text" name="search" placeholder="Search..." class="w-full pl-10" v-model="searchTerm" />
                <span class="absolute inset-y-0 start-0 flex items-center justify-center px-2">
                    <Search class="size-6 text-muted-foreground" />
                </span>
            </div>
        </CardHeader>
        <CardContent>
            <div class="h-[75vh] divide-y divide-border overflow-y-auto" ref="userList">
                <div v-for="user in users.data" :key="user.id" class="flex items-center gap-3 py-2">
                    <Avatar class="h-8 w-8 overflow-hidden rounded-lg">
                        <AvatarImage v-if="showAvatar(user)" :src="user.avatar!" :alt="user.name" />
                        <AvatarFallback class="rounded-lg text-black dark:text-white">
                            {{ getInitials(user.name) }}
                        </AvatarFallback>
                    </Avatar>
                    <div class="flex items-center gap-3">
                        <p class="text-sm leading-5 font-medium">
                            {{ user.name }}
                        </p>
                    </div>
                </div>
            </div>
        </CardContent>
    </Card>
</template>
