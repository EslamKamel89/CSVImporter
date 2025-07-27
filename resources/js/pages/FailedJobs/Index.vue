<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Table, TableBody, TableCaption, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import { Tooltip, TooltipContent, TooltipProvider, TooltipTrigger } from '@/components/ui/tooltip';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import showToast from '@/utils/toast';
import { Head, router } from '@inertiajs/vue3';
import { RotateCcw } from 'lucide-vue-next';
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
const retry = async (id: number) => {
    router.post(
        route('failed-jobs.retry', { id }),
        {},
        {
            onSuccess: () => {
                showToast({ isSuccess: true, description: 'Your job is added to the queue successfully' });
            },
            onError: () => {
                showToast({ isSuccess: false, description: 'Sorry, something went wrong. please try again later' });
            },
        },
    );
};
</script>
<template>
    <Head title="Failed Jobs" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex flex-col flex-1 h-full gap-4 p-4 overflow-x-auto rounded-xl">
            <Table>
                <TableCaption>A list of your Failed jobs.</TableCaption>
                <TableHeader>
                    <TableRow>
                        <TableHead class=""> Job Name </TableHead>
                        <TableHead>Queue</TableHead>
                        <TableHead class="">exception</TableHead>
                        <TableHead class="w-fit"> Failed at </TableHead>
                        <TableHead class="text-right"> Actions </TableHead>
                    </TableRow>
                </TableHeader>
                <TableBody>
                    <TableRow v-for="job in jobs" :key="job.id">
                        <TableCell class="font-medium w-fit">
                            {{ job.job_name }}
                        </TableCell>
                        <TableCell>{{ job.queue }}</TableCell>

                        <TableCell class="line-clamp-4 max-w-[200px]">
                            <TooltipProvider>
                                <Tooltip>
                                    <TooltipTrigger class="text-ellipsis">{{ job.exception }}</TooltipTrigger>
                                    <TooltipContent class="m-4 line-clamp-none text-wrap">
                                        <p class="m-4 w-[500px]">{{ job.exception }}</p>
                                    </TooltipContent>
                                </Tooltip>
                            </TooltipProvider>
                        </TableCell>
                        <TableCell class="ps-4">
                            {{ job.failed_at }}
                        </TableCell>
                        <TableCell class="text-right">
                            <Button @click="retry(job.id)" variant="outline"><RotateCcw /></Button>
                        </TableCell>
                    </TableRow>
                </TableBody>
            </Table>
        </div>
    </AppLayout>
</template>
