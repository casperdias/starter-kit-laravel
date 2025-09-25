<script setup lang="ts">
import NavFooter from '@/components/NavFooter.vue';
import NavMain from '@/components/NavMain.vue';
import NavUser from '@/components/NavUser.vue';
import { Sidebar, SidebarContent, SidebarFooter, SidebarHeader, SidebarMenu, SidebarMenuButton, SidebarMenuItem } from '@/components/ui/sidebar';
import { useRoute } from '@/composables/useRoute';
import { AppPageProps, type NavItem } from '@/types';
import { Link, usePage } from '@inertiajs/vue3';
import { BookOpen, Folder, LayoutGrid, MessageCircleMore, Newspaper } from 'lucide-vue-next';
import { computed } from 'vue';
import AppLogo from './AppLogo.vue';
const route = useRoute();

const page = usePage<AppPageProps>();
const permissions = computed(() => page.props.auth.user.permissions);

const navItems: { [key: string]: NavItem[] } = {
    platform: [
        {
            title: 'Dashboard',
            href: '/dashboard',
            icon: LayoutGrid,
        },
    ],
    content: [
        {
            title: 'News',
            href: '/news',
            icon: Newspaper,
        },
        {
            title: 'Chats',
            href: '/chats',
            icon: MessageCircleMore,
        },
    ],
    admin: [
        {
            title: 'Master',
            href: '/admin',
            icon: LayoutGrid,
            permissions: ['user', 'role', 'permission'],
        },
    ],
};

const footerNavItems: NavItem[] = [
    {
        title: 'Github Repo',
        href: 'https://github.com/laravel/vue-starter-kit',
        icon: Folder,
        permissions: ['view-repo'],
    },
    {
        title: 'Documentation',
        href: 'https://laravel.com/docs/starter-kits#vue',
        icon: BookOpen,
    },
];

function filterNavItems(items: NavItem[]): NavItem[] {
    return items.filter((item) => {
        if (!item.permissions || item.permissions.length === 0) {
            return true;
        }
        return item.permissions.some((perm) => permissions.value.includes(perm));
    });
}

const filteredNavItems = computed(() => {
    const filtered: { [key: string]: NavItem[] } = {};
    for (const [groupLabel, items] of Object.entries(navItems)) {
        const filteredItems = filterNavItems(items);
        if (filteredItems.length > 0) {
            filtered[groupLabel] = filteredItems;
        }
    }
    return filtered;
});

const filteredFooterNavItems = computed(() => filterNavItems(footerNavItems));
</script>

<template>
    <Sidebar collapsible="offcanvas" variant="inset">
        <SidebarHeader>
            <SidebarMenu>
                <SidebarMenuItem>
                    <SidebarMenuButton size="lg" as-child>
                        <Link :href="route('dashboard')">
                            <AppLogo />
                        </Link>
                    </SidebarMenuButton>
                </SidebarMenuItem>
            </SidebarMenu>
        </SidebarHeader>

        <SidebarContent>
            <NavMain v-for="(items, groupLabel) in filteredNavItems" :key="groupLabel" :items="items" :groupLabel="groupLabel" />
        </SidebarContent>

        <SidebarFooter>
            <NavFooter :items="filteredFooterNavItems" />
            <NavUser />
        </SidebarFooter>
    </Sidebar>
    <slot />
</template>
