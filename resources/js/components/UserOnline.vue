<script setup lang="ts">
import { User } from '@/types';
import { useEchoPresence } from '@laravel/echo-vue';
import { createGlobalState, useStorage } from '@vueuse/core';
import { Badge } from './ui/badge';

const useState = createGlobalState(() => useStorage<User[]>('user-online', []));
const userOnline = useState();
const { channel } = useEchoPresence('presence-online');

channel()
    .here((users: User[]) => {
        userOnline.value = users;
    })
    .joining((user: User) => {
        userOnline.value.push(user);
    })
    .leaving((user: User) => {
        userOnline.value = userOnline.value.filter((u) => u.id !== user.id);
    });
</script>

<template>
    <Badge class="gap-2">
        <p>{{ userOnline.length }} Online</p>
        <div class="flex items-center justify-center gap-2">
            <div class="group relative flex h-3 w-3">
                <span
                    class="absolute inline-flex h-full w-full animate-ping rounded-full"
                    :class="userOnline.length > 0 ? 'bg-green-600' : 'bg-red-600'"
                ></span>
                <span class="inline-block h-3 w-3 rounded-full" :class="userOnline.length > 0 ? 'bg-green-600' : 'bg-red-600'"></span>
            </div>
        </div>
    </Badge>
</template>
