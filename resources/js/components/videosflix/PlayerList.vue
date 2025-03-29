<script setup>
import { Head } from '@inertiajs/vue3';
import Hls from 'hls.js';
import { onMounted, reactive, ref } from 'vue';

const props = defineProps({ videos: {} });

let video = reactive(props.videos[0]);
let player = '';
const playerEl = ref(null);
const titleTab = ref(video.name);

onMounted(() => {
    player = new Hls();
    player.loadSource(route('stream.player', { code: video.code, video: video.video }));
    player.attachMedia(playerEl.value);
});

const playVideo = (v) => {
    video = v;
    titleTab.value = v.name;

    player.loadSource(route('stream.player', { code: v.code, video: v.video }));
};
</script>
<template>
    <Head :title="`Assistindo: ${titleTab}`" />
    <div class="mx-auto w-full py-10">
        <div class="mb-4 w-full">
            <h2 class="text-2xl font-bold text-white">Assistindo: {{ video.name }}</h2>
        </div>
        <div class="flex w-full justify-between">
            <video ref="playerEl" id="player" class="ml-10 h-[680px] w-[75%]" controls></video>

            <div class="right-0 top-0 h-[480px] w-80 overflow-y-auto border-l shadow-xl">
                <ul>
                    <li class="block text-white" v-for="(v, index) of videos" :key="v.id">
                        <a
                            href="#"
                            @click.prevent="playVideo(v)"
                            class="linkVideo block border-b border-gray-900 px-4 py-4 transition duration-300 ease-in-out hover:bg-black hover:text-white"
                            :class="{
                                'bg-white text-black': video.id == v.id,
                                'rounded-t-xl': index == 0,
                            }"
                        >
                            {{ v.name }}
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</template>
