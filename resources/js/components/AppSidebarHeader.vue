<script setup lang="ts">
import Breadcrumbs from '@/components/Breadcrumbs.vue';
import { SidebarTrigger } from '@/components/ui/sidebar';
import type { AppPageProps, BreadcrumbItemType } from '@/types';
import { useForm, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';
import { toast } from 'vue-sonner';
import { Badge } from './ui/badge';
import { Select, SelectContent, SelectGroup, SelectItem, SelectTrigger, SelectValue } from './ui/select';

const page = usePage<AppPageProps>();
const roles = computed(() => page.props.auth.user.roles || []);

const form = useForm({
    role: page.props.auth.user.role?.id || null,
});

withDefaults(
    defineProps<{
        breadcrumbs?: BreadcrumbItemType[];
    }>(),
    {
        breadcrumbs: () => [],
    },
);

const changeRole = () => {
    form.put(route('change-role'), {
        preserveScroll: true,
        onSuccess: () => {
            toast.success('Role changed successfully.', {
                description: 'Your role has been updated.',
                closeButton: true,
            });
        },
        onError: (errors) => {
            toast.error('Failed to change role.', {
                description: errors ?? 'An error occurred while changing your role.',
                closeButton: true,
            });
        },
    });
};
</script>

<template>
    <header
        class="flex h-16 shrink-0 items-center justify-between gap-2 border-b border-sidebar-border/70 px-6 transition-[width,height] ease-linear group-has-data-[collapsible=icon]/sidebar-wrapper:h-12 md:px-4"
    >
        <div class="flex items-center gap-2">
            <SidebarTrigger class="-ml-1" />
            <template v-if="breadcrumbs && breadcrumbs.length > 0">
                <Breadcrumbs :breadcrumbs="breadcrumbs" />
            </template>
        </div>
        <Badge v-if="roles.length <= 1">
            {{ roles.length === 1 ? roles[0].display_name : 'Tidak Ada Role' }}
        </Badge>
        <Select v-else v-model="form.role" @update:model-value="changeRole">
            <SelectTrigger>
                <SelectValue placeholder="Select role" />
            </SelectTrigger>
            <SelectContent>
                <SelectGroup>
                    <SelectItem v-for="role in roles" :key="role.id" :value="role.id">
                        {{ role.display_name }}
                    </SelectItem>
                </SelectGroup>
            </SelectContent>
        </Select>
    </header>
</template>
