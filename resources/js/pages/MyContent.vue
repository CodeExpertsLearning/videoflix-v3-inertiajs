<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';

defineProps({
    contents: {},
});

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Meu Conteúdo',
        href: '/my-contents',
    },
];
</script>
<template>
    <Head title="Meu Conteúdos" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <div class="py-12">
                <div class="mx-auto w-full sm:px-6 lg:px-8">
                    <div class="overflow-hidden shadow-sm sm:rounded-lg" v-if="contents">
                        <div class="w-full p-2" v-for="(contentGroup, index) of contents" :key="index">
                            <div class="mb-10 w-full px-2 py-4">
                                <strong class="text-xl text-gray-400"> {{ index == 1 ? 'Filmes' : 'Series' }}</strong>
                            </div>

                            <div class="mb-10 w-full px-4 md:grid md:grid-cols-2 md:gap-0.5 lg:grid-cols-3">
                                <div
                                    class="mb-28 h-[380px] w-[420px] rounded bg-sidebar shadow-lg shadow-black transition duration-300 ease-in-out hover:shadow-gray-400"
                                    v-for="content of contentGroup"
                                    :key="content.id"
                                >
                                    <img
                                        v-if="content.cover"
                                        :src="`/storage/${content.cover}`"
                                        :alt="`Capa do conteúdo: ${content.title}`"
                                        class="mb-8 block h-[50%] w-[420px] rounded-t"
                                    />

                                    <img
                                        v-else
                                        src="/storage/no-photo.jpg"
                                        :alt="`Capa do conteúdo: ${content.title}`"
                                        class="mb-8 block h-[40%] w-[360px] rounded-t"
                                    />

                                    <div class="relative h-[60%] px-4 pb-4 text-white">
                                        <h5 class="mb-4 text-2xl font-extrabold" v-text="content.title"></h5>

                                        <p class="mb-20 text-xl leading-4" v-text="content.description"></p>

                                        <a
                                            :href="route('watch.video', content)"
                                            class="absolute bottom-0.5 left-0 right-0 mt-8 block w-full rounded-b bg-white px-2 py-4 text-center text-2xl font-bold text-gray-900 transition duration-300 ease-in-out hover:border-t hover:border-white hover:bg-sidebar hover:text-white"
                                            >Assistir</a
                                        >
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
