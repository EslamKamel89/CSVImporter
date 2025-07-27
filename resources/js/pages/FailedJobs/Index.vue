<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Failed Jobs',
        href: '/failed-jobs',
    },
];
defineProps<{
    jobs: {
        id: number;
        queue: string;
        exception: string;
        failed_at: string;
        job_name: string;
        data: string;
    }[];
}>();
</script>
<template>
    <Head title="Failed Jobs" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex flex-col flex-1 h-full gap-4 p-4 overflow-x-auto rounded-xl">
            <h2>Failed jobs</h2>
            <pre>{{ jobs }}</pre>
        </div>
    </AppLayout>
</template>
