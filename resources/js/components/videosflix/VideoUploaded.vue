<script setup>
const props = defineProps({
    upload: Object,
    content: Number,
});

const emit = defineEmits();
</script>
<template>
    <div class="p-6 text-gray-900">
        <div class="flex space-x-6">
            <div class="w-full max-w-[350px] space-y-3">
                <div class="h-[180px] w-full" v-if="upload.thumb">
                    <img :src="`/storage/${upload.thumb}`" alt="" class="h-[180px] w-full rounded bg-white p-1 shadow" />
                </div>
                <div class="space-y-1" v-if="upload.encoding">
                    <div class="h-3 overflow-hidden rounded bg-gray-100 shadow-inner">
                        <div
                            class="h-full bg-green-500"
                            :style="{
                                width: `${upload.encodingProgress}%`,
                            }"
                        ></div>
                    </div>
                    <div class="text-sm font-bold text-white">Convertendo vídeo</div>
                </div>

                <div class="space-y-1" v-if="upload.uploading">
                    <div class="h-3 overflow-hidden rounded bg-gray-100 shadow-inner">
                        <div
                            class="h-full bg-blue-500"
                            :style="{
                                width: `${upload.uploadProgress}%`,
                            }"
                        ></div>
                    </div>
                    <div class="text-sm font-bold text-white">Enviando Vídeo</div>
                </div>

                <div class="flex items-center space-x-3" v-if="upload.uploading">
                    <!-- Disparar os eventos -->
                    <button class="text-sm font-medium text-blue-500" @click="emit('pause', upload.id)" v-if="!upload.file.pause">Pausar</button>
                    <button class="text-sm font-medium text-blue-500" @click="emit('resume', upload.id)" v-if="upload.file.pause">Continuar</button>
                    <button class="text-sm font-medium text-blue-500" @click="emit('cancel', upload.id)">Cancelar</button>
                </div>
            </div>
        </div>
    </div>
</template>
