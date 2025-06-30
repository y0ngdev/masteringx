<script setup lang="ts">
import 'vidstack/bundle';

defineProps<{
    title?: string;
    src?: string;
}>();

// Todo onscroll, pop out picture in picture
</script>

<template>
    <media-player v-if="src" :title="title" :src="src" autoplay class="border-0">
        <media-provider></media-provider>
        <media-video-layout></media-video-layout>
    </media-player>
</template>
