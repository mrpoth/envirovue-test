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
];
</script>

<template>

    <Head title="Dashboard" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4 overflow-x-auto">
            <div class="flex" v-for="user in users" :key="user.id">
                <Link :href="`/users/${user.id}`"
                    class="flex items-center gap-x-3 rounded-lg px-3 py-2 text-sm font-medium hover:bg-accent">
                {{ user.full_name }}
                </Link>
                <Link method="delete" as="button" :href="`/users/${user.id}`"
                    class="flex items-center gap-x-3 rounded-lg px-3 py-2 text-sm font-medium hover:bg-accent bg-red-600">
                Delete
                </Link>
            </div>
        </div>
    </AppLayout>
</template>
