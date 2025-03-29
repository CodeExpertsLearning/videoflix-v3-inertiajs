<script setup>
import { onMounted, ref } from 'vue';
import { Head, usePage } from '@inertiajs/vue3';

import Hls from 'hls.js';

const props = defineProps({
    video: Object,
});

const video = props.video;

const playerEL = ref(null);
let player = null;

onMounted(() => {
    player = new Hls();
    player.loadSource(
        route('stream.player', {
            code: video.code,
            video: video.video,
        }),
    );
    player.attachMedia(playerEL.value);
});
</script>
<template>
    <Head :title="`Assistindo: ${video.name}`" />

    <div class="mx-auto max-w-7xl py-2">
        <div class="mb-4 w-full">
            <h2 class="text-2xl font-bold text-white">
                {{ video.name }}
            </h2>
        </div>
        <div class="justfy-between flex max-w-full">
            <video ref="playerEL" id="player" class="ml-10 mt-8 h-[780px] max-w-full" controls></video>
        </div>
    </div>
</template>
