<script setup lang="ts">
import 'vidstack/bundle';

defineProps<{
    title?: string;
    src?: string;
}>();
</script>

<template>

        <media-player :title="title" :src="src" autoplay>
            <media-provider></media-provider>

            <media-video-layout ></media-video-layout>
        </media-player>

</template>
