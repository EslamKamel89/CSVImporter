<script setup lang="ts">
import Button from '@/components/ui/button/Button.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';
import { ref } from 'vue';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'CSV',
        href: route('csv.upload'),
    },
];
const files = ref<File[]>();
const submit = () => {};
const uploadFiles = () => {};
</script>
<template>
    <Head title="Dashboard" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="p-6">
            <h1 class="mb-4 text-2xl font-bold">Csv Importer</h1>
            <form @submit.prevent="submit" enctype="multipart/form-data" class="flex flex-col space-y-3">
                <div class="grid w-full items-center gap-1.5">
                    <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white" for="csv">Csv</label>
                    <input
                        class="block w-full p-2 mb-5 text-xs text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 focus:outline-none dark:border-gray-600 dark:bg-gray-700 dark:text-gray-400 dark:placeholder-gray-400"
                        id="csv"
                        type="file"
                        multiple
                        accept=".csv"
                        @change="uploadFiles"
                    />
                </div>
                <Button type="submit">Upload</Button>
            </form>
        </div>
    </AppLayout>
</template>
