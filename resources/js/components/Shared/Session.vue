<script setup lang="ts">
import { AppPageProps } from '@/types';
import { usePage } from '@inertiajs/vue3';

const page = usePage<AppPageProps>();
</script>
<template>
    <div>
        <div
            v-if="page.props.session?.error"
            class="p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-50 dark:bg-gray-800 dark:text-red-400"
            role="alert"
        >
            <span class="font-medium">Danger!</span> {{ page.props.session?.error }}
        </div>
        <div
            v-if="page.props?.session?.success"
            class="p-4 mb-4 text-sm text-green-800 rounded-lg bg-green-50 dark:bg-gray-800 dark:text-green-400"
            role="alert"
        >
            <span class="font-medium">Success!</span> {{ page.props?.session?.success }}
        </div>
    </div>
</template>
