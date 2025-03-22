<script setup lang="ts">
import NavLink from '@/components/TextLink.vue';
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';

defineProps({
    contents: Object,
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
];

const removeContent = (content) => {
    console.log(content);
};
</script>

<template>
    <Head title="Conteúdos" />

    <AppLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">Media Conteúdos</h2>
        </template>

        <div class="py-12">
            <div class="mx-auto w-full sm:px-6 lg:px-8">
                <div class="mb-10 flex w-full justify-end">
                    <NavLink
                        :href="route('media.contents.create')"
                        class="mr-2 rounded border !border-green-600 bg-green-700 px-4 py-2 !text-white transition duration-300 ease-in-out hover:bg-green-900 hover:!text-white"
                        >Criar</NavLink
                    >
                </div>

                <div class="w-full overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="mb-10 w-full overflow-hidden shadow sm:rounded-lg">
                        <Table class="min-w-full divide-y divide-gray-200">
                            <TableHeader>
                                <TableRow>
                                    <TableHead>Capa</TableHead>
                                    <TableHead scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500"
                                        >#</TableHead
                                    >
                                    <TableHead scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500"
                                        >Title</TableHead
                                    >
                                    <TableHead scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">
                                        Criado em
                                    </TableHead>
                                    <TableHead scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500"
                                        >Ação</TableHead
                                    >
                                </TableRow>
                            </TableHeader>
                            <TableBody>
                                <TableRow v-for="content of contents.data" :key="content.id">
                                    <TableCell>
                                        <img v-if="content.cover" :src="`/storage/${content.cover}`" class="w-[90px] rounded bg-white p-1 shadow" />
                                        <img v-else src="/storage/no-photo.jpg" class="w-[90px] rounded bg-white p-1 shadow" />
                                    </TableCell>
                                    <TableCell class="whitespace-nowrap px-6 py-4">
                                        <div class="text-sm">
                                            {{ content.id }}
                                        </div>
                                    </TableCell>

                                    <TableCell class="whitespace-nowrap px-6 py-4">
                                        <div class="text-sm">
                                            {{ content.title }}
                                        </div>
                                    </TableCell>

                                    <TableCell class="whitespace-nowrap px-6 py-4">
                                        <span class="inline-flex rounded-full bg-green-100 px-2 text-xs font-semibold leading-5 text-green-800">
                                            {{ content.created_at }}
                                        </span>
                                    </TableCell>

                                    <TableCell class="whitespace-nowrap px-6 py-4 text-right text-sm font-medium">
                                        <div class="flex">
                                            <NavLink
                                                :href="route('media.contents.edit', content.id)"
                                                class="mr-2 rounded border !border-blue-600 px-2 py-1 !text-blue-900 transition duration-300 ease-in-out hover:bg-blue-900 hover:!text-white"
                                                >Editar</NavLink
                                            >

                                            <button
                                                @click="removeContent(content.id)"
                                                class="mr-2 rounded border border-red-600 px-2 py-1 !text-red-900 transition duration-300 ease-in-out hover:bg-red-900 hover:!text-white"
                                            >
                                                Apagar
                                            </button>
                                        </div>
                                    </TableCell>
                                </TableRow>
                            </TableBody>
                        </Table>

                        <Paginator v-if="contents.data.length > 10" :meta="contents" />
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
