<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import Navbar from '@/components/landing/Navbar.vue';
</script>

<template>
    <Head title="Welcome" />
    <div class="bg-background min-h-screen">
        <!-- Navigation -->
        <Navbar />
    </div>
</template>
