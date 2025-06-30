<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { ArrowRight, PlayCircle } from 'lucide-vue-next';

defineProps<{
    landingImage: string;
}>();
</script>

<template>
    <div class="bg-background relative flex min-h-[calc(100vh-4rem)] flex-col items-center justify-center overflow-hidden">
        <!-- Content Container -->
        <div class="pb-16 pt-32">
            <div class="mx-auto max-w-[1280px] px-6">
                <div class="mx-auto mb-16 max-w-[800px] text-center">
                    <div
                        class="bg-primary/10 text-primary mb-2 inline-flex rounded px-3 py-1 text-xs font-semibold uppercase tracking-wider md:text-sm"
                    >
                        Built for Instructors
                    </div>
                    <h1 class="text-foreground mb-6 text-4xl font-bold leading-tight tracking-[-0.02em] sm:text-5xl lg:text-[56px] lg:leading-[64px]">
                        Teach. Inspire. We'll Handle the Tech.
                    </h1>
                    <p class="text-muted-foreground mx-auto mb-8 max-w-[600px] text-lg sm:text-xl">
                        Upload your lessons and reach your students—no hosting headaches, no distractions. Focus on what you do best: teaching.
                    </p>
                    <div class="flex flex-col items-center justify-center gap-3 sm:flex-row">
                        <Button size="lg" class="min-w-[160px] gap-2">
                            <ArrowRight class="h-5 w-5" />
                            Buy Now
                        </Button>
                        <Button variant="outline" size="lg" class="min-w-[160px] gap-2">
                            <PlayCircle class="h-5 w-5" />
                            Watch demo
                        </Button>
                    </div>
                </div>

                <!-- Dashboard Preview -->
                <div class="relative mx-auto max-w-[1000px]">
                    <div class="bg-card text-card-foreground aspect-[16/10] overflow-hidden rounded-2xl border shadow-2xl ring-1 ring-gray-900/10">
                        <div class="absolute inset-0 bg-gradient-to-t from-gray-900/10 to-gray-900/5"></div>
                        <img
                            :src="landingImage"
                            alt="Course Platform Preview"
                            class="h-full w-full transform object-contain transition-transform duration-500 hover:scale-[1.02]"
                            loading="eager"
                        />
                    </div>
                    <!-- Decorative elements -->
                    <div class="absolute inset-0 -z-10 scale-[1.15] opacity-30 blur-lg dark:opacity-20">
                        <div class="bg-primary/40 absolute -top-10 left-0 h-72 w-72 rounded-full opacity-70 mix-blend-multiply blur-2xl filter"></div>
                        <div
                            class="bg-primary/40 absolute -top-10 right-0 h-72 w-72 rounded-full opacity-70 mix-blend-multiply blur-2xl filter"
                        ></div>
                    </div>
                    <!-- Browser-like top bar for more realism -->
                    <div class="bg-card/80 absolute left-0 right-0 top-0 flex h-8 items-center rounded-t-2xl border-b px-4 backdrop-blur-sm">
                        <div class="flex space-x-2">
                            <div class="h-3 w-3 rounded-full bg-red-400"></div>
                            <div class="h-3 w-3 rounded-full bg-yellow-400"></div>
                            <div class="h-3 w-3 rounded-full bg-green-400"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
