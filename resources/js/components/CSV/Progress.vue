<script setup lang="ts">
import { AppPageProps } from '@/types';
import { usePage } from '@inertiajs/vue3';
import { useEchoPublic } from '@laravel/echo-vue';
import { onMounted, ref, watch } from 'vue';

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
        progress.value.processed = e.row;
        if (progress.value.total !== 0) {
            progress.value.percent = Math.round((e.row / progress.value.total) * 100);
        }
    });
    csvRowProccessed.listen();
    progress.value.total = page.props?.session?.data?.total ?? 0;
    const batchCompleted = useEchoPublic('csv-progress', '.BatchCompleted', (e: { status: 'success' | 'failed' }) => {
        if (e.status == 'success') {
        }
        if (e.status == 'failed') {
        }
    });
    batchCompleted.listen();
});
</script>
<template>
    <div>
        <div class="relative w-full mx-5 bg-gray-200 rounded h-9">
            <div
                class="bg-blue-300 h-9"
                :style="{
                    width: `${progress.percent}%`,
                }"
            ></div>
            <div class="absolute inset-0 flex items-center justify-center w-full h-full font-bold text-black">{{ progress.percent }}%</div>
        </div>
        <ul class="flex w-full mx-5 space-x-5">
            <li>total: {{ progress.total }}</li>
            <li>processed: {{ progress.processed }}</li>
        </ul>
    </div>
</template>
