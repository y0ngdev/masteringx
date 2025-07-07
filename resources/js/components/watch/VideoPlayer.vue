<script setup lang="ts">
import 'vidstack/bundle';
import { onMounted } from 'vue';

defineProps<{
    title?: string;
    src?: string;
}>();

const emit = defineEmits<{
    (e: 'video-ended'): void;
}>();

onMounted(() => {
    const instance = document.querySelector('media-player');

    instance.subscribe(({ ended }: { ended: boolean }) => {
        if (ended) {
            emit('video-ended');
        }
    });
});

// Todo onscroll, pop out picture in picture
</script>

<template>
    <media-player v-if="src" :title="title" :src="src" autoplay class="border-0">
        <media-provider></media-provider>
        <media-video-layout></media-video-layout>
    </media-player>
</template>
