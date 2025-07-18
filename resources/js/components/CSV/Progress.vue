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
    const channel = useEchoPublic('csv-progress', '.CsvRowProcessed', (e: { row: number }) => {
        progress.value.processed = e.row;
        if (progress.value.total !== 0) {
            progress.value.percent = Math.round((e.row / progress.value.total) * 100);
        }
    });
    channel.listen();
    progress.value.total = page.props?.session?.data?.total ?? 0;
});
</script>
<template>
    <div>
        <ul>
            <li>total: {{ progress.total }}</li>
            <li>percent: {{ progress.percent }}%</li>
            <li>processed: {{ progress.processed }}</li>
        </ul>
    </div>
</template>
