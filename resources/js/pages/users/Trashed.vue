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
        title: 'Restore Users',
        href: '/users/trashed',
    },
];
</script>

<template>

    <Head title="Restore Users" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4 overflow-x-auto">
            <div class="flex items-center gap-4" v-for="user in users" :key="user.id">
                <span>
                    {{ user.full_name }}
                </span>
                <Link method="post" as="button" :href="`/users/${user.id}`"
                    class="flex items-center gap-x-3 rounded-lg px-3 py-2 text-sm font-medium hover:bg-green-900 bg-green-500">
                Restore
                </Link>
            </div>
        </div>
    </AppLayout>
</template>
