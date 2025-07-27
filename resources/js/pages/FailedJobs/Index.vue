<script setup lang="ts">
import { Table, TableBody, TableCaption, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
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
            <Table>
                <TableCaption>A list of your recent invoices.</TableCaption>
                <TableHeader>
                    <TableRow>
                        <TableHead class="w-[100px]"> Job Name </TableHead>
                        <TableHead>Queue</TableHead>
                        <TableHead class="w-[100px]">exception</TableHead>
                        <TableHead class="text-right"> Failed at </TableHead>
                    </TableRow>
                </TableHeader>
                <TableBody>
                    <TableRow v-for="job in jobs" :key="job.id">
                        <TableCell class="font-medium">
                            {{ job.job_name }}
                        </TableCell>
                        <TableCell>{{ job.queue }}</TableCell>
                        <TableCell class="line-clamp-4 max-w-[200px] text-ellipsis">{{ job.exception }}</TableCell>
                        <TableCell class="text-right">
                            {{ job.failed_at }}
                        </TableCell>
                    </TableRow>
                </TableBody>
            </Table>
        </div>
    </AppLayout>
</template>
