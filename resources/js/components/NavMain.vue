<script setup lang="ts">
import { SidebarGroup, SidebarGroupLabel, SidebarMenu, SidebarMenuButton, SidebarMenuItem } from '@/components/ui/sidebar';
import { type NavItem } from '@/types';
import { Link, usePage } from '@inertiajs/vue3';

defineProps<{
    items: NavItem[];
    groupLabel: string | number;
}>();

const page = usePage();
</script>

<template>
    <SidebarGroup class="px-2 py-0">
        <SidebarGroupLabel>{{ String(groupLabel).charAt(0).toUpperCase() + String(groupLabel).slice(1) }}</SidebarGroupLabel>
        <SidebarMenu>
            <SidebarMenuItem v-for="item in items" :key="item.title">
                <SidebarMenuButton
                    as-child
                    :is-active="page.url === item.href || page.url.startsWith(item.href + '/') || page.url.startsWith(item.href + '?')"
                    :tooltip="item.title"
                >
                    <template v-if="!(page.url === item.href || page.url.startsWith(item.href + '/') || page.url.startsWith(item.href + '?'))">
                        <Link :href="item.href" preserve-scroll preserve-state>
                            <component :is="item.icon" />
                            <span>{{ item.title }}</span>
                        </Link>
                    </template>
                    <template v-else>
                        <div class="flex cursor-default items-center gap-2">
                            <component :is="item.icon" />
                            <span>{{ item.title }}</span>
                        </div>
                    </template>
                </SidebarMenuButton>
            </SidebarMenuItem>
        </SidebarMenu>
    </SidebarGroup>
</template>
