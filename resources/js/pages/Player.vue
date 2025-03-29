<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, usePage } from '@inertiajs/vue3';

import PlayerFull from '@/components/videosflix/PlayerFull.vue';
import PlayerList from '@/components/videosflix/PlayerList.vue';

const props = defineProps({
    content: {},
});

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Meu Conteúdo',
        href: '/my-contents',
    },
    {
        title: 'Assistir',
        href: `/videos/${usePage().props.content.id}`,
    },
];
</script>
<template>
    <Head title="Meu Conteúdos" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <div class="">
                <div class="py-12">
                    <div class="mx-auto w-full sm:px-6 lg:px-8">
                        <PlayerList :videos="content.valid_videos" v-if="content.type === 2" />
                        <PlayerFull v-if="content.type === 1" :video="content.valid_videos[0]" />
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
