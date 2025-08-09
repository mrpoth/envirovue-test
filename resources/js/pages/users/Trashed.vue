<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, Link } from '@inertiajs/vue3';

defineProps({
    users: {
        type: Array,
        required: true,
    }
});

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Users',
        href: '/users',
    },
    {
        title: 'Deleted Users',
        href: '/users/trashed',
    },
];
</script>

<template>

    <Head title="Deleted Users" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4 overflow-x-auto">
            <div class="flex items-center gap-4" v-for="user in users" :key="user.id">
                <span>
                    {{ user.full_name }}
                </span>
                <Link method="patch" as="button" :href="`/users/${user.id}/restore`"
                    class="flex items-center gap-x-3 rounded-lg px-3 py-2 text-sm font-medium hover:bg-green-900 bg-green-500">
                Restore
                </Link>
                <Link method="delete" as="button" :href="`/users/${user.id}/delete`"
                    class="flex items-center gap-x-3 rounded-lg px-3 py-2 text-sm font-medium hover:bg-green-900 bg-red-600">
                Delete permanently
                </Link>
            </div>
        </div>
    </AppLayout>
</template>
