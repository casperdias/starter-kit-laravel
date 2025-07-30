<script setup lang="ts">
import NavFooter from '@/components/NavFooter.vue';
import NavMain from '@/components/NavMain.vue';
import NavUser from '@/components/NavUser.vue';
import { Sidebar, SidebarContent, SidebarFooter, SidebarHeader, SidebarMenu, SidebarMenuButton, SidebarMenuItem } from '@/components/ui/sidebar';
import { AppPageProps, type NavItem } from '@/types';
import { Link, usePage } from '@inertiajs/vue3';
import { BookOpen, Folder, LayoutGrid } from 'lucide-vue-next';
import { computed } from 'vue';
import AppLogo from './AppLogo.vue';

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
    admin: [
        {
            title: 'Master',
            href: '/admin',
            icon: LayoutGrid,
            permission: 'admin',
        },
    ],
};

const footerNavItems: NavItem[] = [
    {
        title: 'Github Repo',
        href: 'https://github.com/laravel/vue-starter-kit',
        icon: Folder,
        permission: 'admin',
    },
    {
        title: 'Documentation',
        href: 'https://laravel.com/docs/starter-kits#vue',
        icon: BookOpen,
    },
];

function filterNavItems(items: NavItem[]): NavItem[] {
    return items.filter((item) => !item.permission || permissions.value.includes(item.permission));
}

// Apply filtering to navigation items
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
