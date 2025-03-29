<script setup lang="ts">
import InputError from '@/components/InputError.vue';
import Input from '@/components/ui/input/Input.vue';
import InputLabel from '@/components/ui/label/Label.vue';
import VideoUploaded from '@/components/videosflix/VideoUploaded.vue';

import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, router, usePage } from '@inertiajs/vue3';
import { createUpload } from '@mux/upchunk';
import axios from 'axios';
import { onMounted, ref } from 'vue';

onMounted(() => {
    Echo.channel('videos')
        .listen('.App\\Events\\VideoThumbCreated', (e) => {
            const video = getVideo(e.videoId);

            if (!video) return;

            video.thumb = e.thumb;
        })
        .listen('.App\\Events\\VideoEncodingStart', (e) => {
            const video = getVideo(e.videoId);

            if (!video) return;

            video.encoding = true;
        })
        .listen('.App\\Events\\VideoEncodingFinished', (e) => {
            const video = getVideo(e.videoId);

            if (!video) return;

            video.encoding = false;
        })
        .listen('.App\\Events\\VideoEncodingProgress', (e) => {
            const video = getVideo(e.videoId);

            if (!video) return;

            video.encodingProgress = e.progress;
        });
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
        title: 'Criar Conteúdo',
        href: '/media/contents/create',
    },
];

const getVideo = (videoId) => {
    return uploads.value.find((video) => video.id == videoId);
};

const content = usePage().props.content;

const isDragged = ref(false);
const uploads = ref([]);

const handleDropVideos = (e) => {
    mainHandleVideos(e.dataTransfer.files);
};

const handleTargetVideos = (e) => {
    mainHandleVideos(e.target.files);
};

const mainHandleVideos = (videos) => {
    Array.from(videos).forEach((video) => {
        axios
            .post(route('media.contents.videos.store', content), {
                name: video.name,
            })
            .then((response) => {
                uploads.value.unshift({
                    id: response.data.id,
                    name: response.data.name,
                    file: initChunckUpload(video, {
                        content,
                        video: response.data.id,
                    }),
                    uploading: true,
                    uploadProgress: 0,
                    encoding: false,
                    encodingProgress: 0,
                    thumb: null,
                });
            });
    });
};

const initChunckUpload = (video, params) => {
    const upload = createUpload({
        endpoint: route('media.contents.videos.upload.chuncks', params),
        headers: {
            'X-CSRF-TOKEN': usePage().props.csrf_token,
        },
        method: 'POST',
        file: video,
        chunkSize: 20 * 1024,
    });

    //progress
    upload.on('progress', (e) => (getVideo(params.video).uploadProgress = e.detail));

    //success
    upload.on('success', (e) => (getVideo(params.video).uploading = false));

    return upload;
};

const resumeUpload = (videoId) => getVideo(videoId).file.resume();
const pauseUpload = (videoId) => getVideo(videoId).file.pause();

const cancelUpload = (videoId) => {
    getVideo(videoId).file.abort();

    router.delete(route('media.contents.videos.destroy', { content, video: videoId }), {
        preserveScroll: true,
        preserveState: true,
        onSuccess: () => {
            uploads.value = uploads.value.filter((video) => video.id !== videoId);
        },
    });
};
</script>

<template>
    <Head title="Upload de Vídeos" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl">
            <div class="mx-auto mb-2 mt-10 w-[80%]">
                <InputLabel
                    @dragover.prevent="isDragged = true"
                    @dragleave.prevent="isDragged = false"
                    @drop.prevent="handleDropVideos"
                    for="photos"
                    class="bg-black-700 flex h-28 w-full items-center justify-center rounded border-2 border-dashed border-white"
                    :class="{
                        'bg-gray-900': isDragged,
                    }"
                    >Clique ou arraste a foto da capa (1280x720)</InputLabel
                >

                <Input id="photos" type="file" class="sr-only" />

                <InputError class="mt-2" :message="`Teste`" @change="handleTargetVideos" />
            </div>
            <div>
                <VideoUploaded
                    v-for="upload of uploads"
                    :key="upload.id"
                    :upload="upload"
                    :content="content.id"
                    @cancel="cancelUpload"
                    @resume="resumeUpload"
                    @pause="pauseUpload"
                />
            </div>
        </div>
    </AppLayout>
</template>
