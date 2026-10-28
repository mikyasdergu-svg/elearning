<script setup lang="ts">
import {
    SidebarGroup,
    SidebarGroupContent,
    SidebarMenu,
    SidebarMenuButton,
    SidebarMenuItem,
} from '@/components/ui/sidebar';
import { Link } from '@inertiajs/vue3';
import type { InertiaLinkProps } from '@inertiajs/vue3';
import type { NavItem } from '@/types';

type Props = {
    items: NavItem[];
    class?: string;
};

defineProps<Props>();

const externalUrlPattern = /^(https?:|mailto:|tel:)/;

function isExternalUrl(href: NonNullable<InertiaLinkProps['href']>) {
    const url = typeof href === 'string' ? href : href.url;
    return typeof url === 'string' && externalUrlPattern.test(url);
}

function linkHref(item: NavItem) {
    return typeof item.href === 'string' ? item.href : item.href.url;
}
</script>

<template>
    <SidebarGroup
        :class="`group-data-[collapsible=icon]:p-0 ${$props.class || ''}`"
    >
        <SidebarGroupContent>
            <SidebarMenu>
                <SidebarMenuItem v-for="item in items" :key="item.title">
                    <template v-if="isExternalUrl(linkHref(item))">
                        <a
                            :href="linkHref(item)"
                            target="_blank"
                            rel="noopener noreferrer"
                            class="flex items-center gap-2 rounded-2xl px-3 py-2 text-sm text-neutral-600 hover:bg-muted hover:text-foreground"
                        >
                            <component v-if="item.icon" :is="item.icon" class="h-4 w-4" />
                            <span>{{ item.title }}</span>
                        </a>
                    </template>
                    <template v-else>
                        <Link
                            :href="item.href"
                            class="flex items-center gap-2 rounded-2xl px-3 py-2 text-sm text-neutral-600 hover:bg-muted hover:text-foreground"
                        >
                            <component v-if="item.icon" :is="item.icon" class="h-4 w-4" />
                            <span>{{ item.title }}</span>
                        </Link>
                    </template>
                </SidebarMenuItem>
            </SidebarMenu>
        </SidebarGroupContent>
    </SidebarGroup>
</template>
