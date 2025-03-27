<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, router, useForm, usePage } from '@inertiajs/vue3';
import { ref } from 'vue';

import InputError from '@/components/InputError.vue';
import PrimaryButton from '@/components/ui/button/Button.vue';
import Input from '@/components/ui/input/Input.vue';
import InputLabel from '@/components/ui/label/Label.vue';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import TextArea from '@/components/ui/textarea/TextArea.vue';

defineProps({
    content: Object,
});

const form = useForm(usePage().props.content);

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

const opts = [
    { label: 'Filme', value: 1 },
    { label: 'Série', value: 2 },
];

const isDragged = ref(false);
const filesFront = ref([]);

const handleDragEve = (e) => {
    resetFilesProp();
    mainHandleImages(e.dataTransfer.files);
    isDragged.value = false;
};
const imagesHandle = (e) => {
    resetFilesProp();
    mainHandleImages(e.target.files);
};

const mainHandleImages = (target) => {
    let images = target;

    form.photo = images.length ? images[0] : [];

    mountFilesFront(images);
};

const mountFilesFront = (images) => {
    Array.from(images).forEach((image) => {
        const reader = new FileReader(); //mdn.io/FileReader
        reader.readAsDataURL(image);

        reader.onload = (e) => {
            console.log(e.target);
            filesFront.value.push(e.target?.result);
        };
    });
};

const resetFilesProp = () => {
    form.photo = [];
    filesFront.value = [];
};

const submit = () => {
    const data = {
        _token: usePage().props.csrf_token,
        _method: 'PUT',
        title: form.title,
        description: form.description,
        body: form.body,
        type: form.type,
        photo: form.photo,
    };

    router.post(route('media.contents.update', form.id), data);
};
</script>

<template>
    <Head title="Media Conteúdos" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <form @submit.prevent="submit" novalidate>
                <div>
                    <InputLabel for="title">Título</InputLabel>

                    <Input id="title" type="text" v-model="form.title" required autofocus />

                    <InputError class="mt-2" :message="form.errors.title" />
                </div>

                <div class="mt-4">
                    <InputLabel for="description">Descrição</InputLabel>

                    <Input
                        id="description"
                        type="text"
                        class="mt-1 block w-full p-2"
                        v-model="form.description"
                        required
                        autofocus
                        autocomplete="username"
                    />

                    <InputError class="mt-2" :message="form.errors.description" />
                </div>

                <div class="mt-4">
                    <InputLabel for="body">Conteúdo</InputLabel>

                    <TextArea id="body" class="mt-1 block w-full p-2" v-model="form.body" />

                    <InputError class="mt-2" :message="form.errors.body" />
                </div>

                <div class="mt-4">
                    <InputLabel for="type">Tipo Conteúdo</InputLabel>

                    <Select id="type" :payloadOptions="opts" v-model="form.type">
                        <SelectTrigger>
                            <SelectValue placeholder="Seleciona um tipo do conteúdo" />
                        </SelectTrigger>
                        <SelectContent>
                            <SelectItem v-for="o of opts" :key="o.value" :value="o.value">
                                {{ o.label }}
                            </SelectItem>
                        </SelectContent>
                    </Select>

                    <InputError class="mt-2" :message="form.errors.type" />
                </div>

                <!-- Input File & Exibicao Foto-->
                <div class="mt-4">
                    <div class="w-full py-10 text-left">
                        <h2 class="font-bold">Foto de Capa</h2>
                    </div>
                    <div class="flex justify-around">
                        <div
                            class="flex items-center justify-center"
                            :class="{
                                'w-[48%]': filesFront.length,
                                'w-full': !filesFront.length,
                            }"
                        >
                            <InputLabel
                                @dragover.prevent="isDragged = true"
                                @dragleave.prevent="isDragged = false"
                                @drop.prevent="handleDragEve"
                                for="photos"
                                class="bg-black-700 flex h-28 w-full items-center justify-center rounded border-2 border-dashed border-white"
                                :class="{
                                    'bg-gray-900': isDragged,
                                }"
                                >Clique ou arraste a foto da capa (1280x720)</InputLabel
                            >

                            <Input id="photos" type="file" class="sr-only" @change="imagesHandle" />

                            <InputError class="mt-2" :message="form.errors.photo" />
                        </div>
                        <div class="mt-10 flex w-[48%] items-center justify-center border-l border-gray-900 px-10 pt-10" v-if="filesFront.length">
                            <div v-for="(img, key) of filesFront" :key="key">
                                <img :src="img" class="rounded bg-white p-1 shadow" />
                            </div>
                        </div>
                        <div class="mt-10 flex w-[48%] items-center justify-center border-l border-gray-900 px-10 pt-10" v-else>
                            <img :src="`/storage/${form.cover}`" class="rounded bg-white p-1 shadow" />
                        </div>
                    </div>
                </div>
                <!-- Input File & Exibicao Foto-->

                <div class="mt-8">
                    <PrimaryButton
                        :class="{
                            'opacity-25': form.processing,
                        }"
                        :disabled="form.processing"
                    >
                        Atualizar
                    </PrimaryButton>
                </div>
            </form>
        </div>
    </AppLayout>
</template>
