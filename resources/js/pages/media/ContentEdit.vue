<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';

defineProps({
    content: Object,
});

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: '/dashboard',
    },
    {
        title: 'Conteúdos',
        href: '/media/contents',
    },
    {
        title: 'Editar Conteúdo',
        href: '/media/contents',
    },
];
</script>

<template>
    <Head title="Media Conteúdos" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4"></div>
    </AppLayout>
</template>
