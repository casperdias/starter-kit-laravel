import type { LucideIcon } from 'lucide-vue-next';
import type { Config } from 'ziggy-js';

export interface Auth {
    user: User;
}

export interface BreadcrumbItem {
    title: string;
    href: string;
}

export interface NavItem {
    title: string;
    href: string;
    icon?: LucideIcon;
    isActive?: boolean;
    permissions?: string[];
}

export type AppPageProps<T extends Record<string, unknown> = Record<string, unknown>> = T & {
    name: string;
    quote: { message: string; author: string };
    auth: Auth;
    ziggy: Config & { location: string };
    sidebarOpen: boolean;
    flash: {
        success: string | null;
        warning: string | null;
        info: string | null;
        message: string | null;
    };
};

export interface Notification {
    id: string;
    type: string;
    data: {
        message: string;
        link?: string;
        [key: string]: unknown;
    };
    read_at: string | null;
}

export interface User {
    id: number;
    name: string;
    email: string;
    avatar?: string;
    email_verified_at: string | null;
    created_at: string;
    updated_at: string;
    role: {
        id: number;
        display_name: string;
    };
    roles: {
        id: number;
        display_name: string;
    }[];
    permissions: string[];
}

export interface Pagination<T> {
    data: T[];
    links: {
        first: string | null;
        last: string | null;
        prev: string | null;
        next: string | null;
    };
    meta: {
        current_page: number;
        from: number;
        last_page: number;
        links: {
            label: string;
            url: string | null;
            active: boolean;
        }[];
        path: string;
        per_page: number;
        to: number;
        total: number;
    };
}

export interface Show<T> {
    data: T;
}

export interface Permission {
    id: number;
    name: string;
    display_name: string;
    description: string;
    created_at: string;
}

export interface Role {
    id: number;
    name: string;
    display_name: string;
    description: string;
    created_at: string;
}

export type BreadcrumbItemType = BreadcrumbItem;
