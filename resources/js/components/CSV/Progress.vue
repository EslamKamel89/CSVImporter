<script setup lang="ts">
import { AppPageProps } from '@/types';
import { usePage } from '@inertiajs/vue3';
import { useEchoPublic } from '@laravel/echo-vue';
import { onMounted, ref, watch } from 'vue';
import { toast } from 'vue-sonner';

const page = usePage<AppPageProps>();
const progress = ref({
    total: 0,
    processed: 0,
    percent: 0,
});
watch(
    () => page.props?.session?.data?.total,
    () => {
        progress.value.total = page.props?.session?.data?.total ?? 0;
    },
);
onMounted(() => {
    const csvRowProccessed = useEchoPublic('csv-progress', '.CsvRowProcessed', (e: { row: number }) => {
        progress.value.processed++;
        if (progress.value.total !== 0) {
            progress.value.percent = Math.round((progress.value.processed / progress.value.total) * 100);
        }
    });
    csvRowProccessed.listen();
    progress.value.total = page.props?.session?.data?.total ?? 0;
    const batchCompleted = useEchoPublic('csv-progress', '.BatchCompleted', (e: { status: 'success' | 'failed' }) => {
        if (e.status == 'success') {
            toast('Success', {
                description: 'All your files is uploaded successfully',
                style: { backgroundColor: 'green', color: 'white', padding: '5px', border: '1px solid', borderRadius: '10px' },
            });
        }
        if (e.status == 'failed') {
            toast('Failed', {
                description: 'Sorry, something went wrong. please try again later',
                style: { backgroundColor: 'red', color: 'white', padding: '5px', border: '1px solid', borderRadius: '10px' },
            });
        }
    });
    batchCompleted.listen();
});
</script>
<template>
    <div>
        <div class="relative mx-5 h-9 w-full rounded bg-gray-200">
            <div
                class="h-9 bg-blue-300"
                :style="{
                    width: `${progress.percent}%`,
                }"
            ></div>
            <div class="absolute inset-0 flex h-full w-full items-center justify-center font-bold text-black">{{ progress.percent }}%</div>
        </div>
        <ul class="mx-5 flex w-full space-x-5">
            <li>total: {{ progress.total }}</li>
            <li>processed: {{ progress.processed }}</li>
        </ul>
    </div>
</template>
