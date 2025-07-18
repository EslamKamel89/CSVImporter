<script setup lang="ts">
import Session from '@/components/Shared/Session.vue';
import Button from '@/components/ui/button/Button.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { AppPageProps, BreadcrumbItem } from '@/types';
import { Head, router, usePage } from '@inertiajs/vue3';
import { useEchoPublic } from '@laravel/echo-vue';
import { onMounted, ref } from 'vue';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'CSV',
        href: route('csv.upload'),
    },
];
const page = usePage<AppPageProps>();
const files = ref<File[]>();
const uploadFiles = (e: Event) => {
    const target = e.target as HTMLInputElement;
    // console.dir(e);
    files.value = Array.from(target?.files ?? []);
};
const submit = () => {
    const formData = new FormData();
    files.value?.forEach((file, index) => {
        formData.append(`csv-files[${index}]`, file);
    });
    router.post(route('csv.import'), formData);
};
onMounted(() => {
    const channel = useEchoPublic('csv-progress', '.CsvRowProcessed', (e) => {
        console.dir(e);
    });
    channel.listen();
});
</script>
<template>
    <Head title="Dashboard" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="p-6">
            <h1 class="mb-4 text-2xl font-bold">CSV Importer</h1>
            <Session />
            <form @submit.prevent="submit" enctype="multipart/form-data" class="flex flex-col">
                <div class="grid w-full items-center gap-1.5">
                    <label class="mb-2 block text-sm font-medium text-gray-900 dark:text-white" for="csv">Csv</label>
                    <input
                        class="block w-full cursor-pointer rounded-lg border border-gray-300 bg-gray-50 p-2 text-xs text-gray-900 focus:outline-none dark:border-gray-600 dark:bg-gray-700 dark:text-gray-400 dark:placeholder-gray-400"
                        id="csv"
                        type="file"
                        multiple
                        accept=".csv"
                        @change="uploadFiles"
                    />
                    <div class="text-xs font-thin text-red-500">{{ page.props.errors['csv-files'] }}</div>
                    <div class="text-xs font-thin text-red-500" v-for="(file, i) in files" :key="i">{{ page.props.errors[`csv-files.${i}`] }}</div>
                </div>
                <Button type="submit" class="mt-5 w-fit">Upload</Button>
            </form>
        </div>
    </AppLayout>
</template>
