<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { PlayCircle, ArrowRight } from 'lucide-vue-next';

defineProps<{
    landingImage: string;
}>();
</script>

<template>
    <div
        class="relative flex min-h-[calc(100vh-4rem)] flex-col items-center justify-center overflow-hidden bg-background">
        <!-- Content Container -->
        <div class="pt-32 pb-16">
            <div class="mx-auto max-w-[1280px] px-6">
                <div class="mx-auto mb-16 max-w-[800px] text-center">
                    <div
                        class="inline-flex mb-2 text-xs font-semibold tracking-wider uppercase rounded md:text-sm bg-primary/10 text-primary px-3 py-1">
                        Built for Instructors
                    </div>
                    <h1 class="mb-6 text-4xl sm:text-5xl lg:text-[56px] leading-tight lg:leading-[64px] font-bold tracking-[-0.02em] text-foreground">
                        Teach. Inspire. We'll Handle the Tech.
                    </h1>
                    <p class="mx-auto mb-8 max-w-[600px] text-lg sm:text-xl text-muted-foreground">
                        Upload your lessons and reach your students—no hosting headaches, no distractions. Focus on what you do best: teaching.  </p>
                    <div class="flex flex-col items-center justify-center gap-3 sm:flex-row">
                        <Button size="lg" class="gap-2 min-w-[160px]">
                            <ArrowRight class="h-5 w-5" />
                            Buy Now
                        </Button>
                        <Button variant="outline" size="lg" class="gap-2 min-w-[160px]">
                            <PlayCircle class="h-5 w-5" />
                            Watch demo
                        </Button>
                    </div>
                </div>

                <!-- Dashboard Preview -->
                <div class="relative mx-auto max-w-[1000px]">
                    <div
                        class="aspect-[16/10] overflow-hidden rounded-2xl border bg-card text-card-foreground shadow-2xl ring-1 ring-gray-900/10"
                    >
                        <div class="absolute inset-0 bg-gradient-to-t from-gray-900/10 to-gray-900/5"></div>
                        <img
                            :src="landingImage"
                            alt="Course Platform Preview"
                            class="h-full w-full object-contain transform transition-transform duration-500 hover:scale-[1.02]"
                            loading="eager"
                        />
                    </div>
                    <!-- Decorative elements -->
                    <div class="absolute inset-0 -z-10 scale-[1.15] opacity-30 dark:opacity-20 blur-lg">
                        <div
                            class="absolute -top-10 left-0 h-72 w-72 rounded-full bg-primary/40 opacity-70 mix-blend-multiply blur-2xl filter">
                        </div>
                        <div
                            class="absolute -top-10 right-0 h-72 w-72 rounded-full bg-primary/40 opacity-70 mix-blend-multiply blur-2xl filter">
                        </div>
                    </div>
                    <!-- Browser-like top bar for more realism -->
                    <div class="absolute top-0 left-0 right-0 h-8 bg-card/80 backdrop-blur-sm border-b flex items-center px-4 rounded-t-2xl">
                        <div class="flex space-x-2">
                            <div class="w-3 h-3 rounded-full bg-red-400"></div>
                            <div class="w-3 h-3 rounded-full bg-yellow-400"></div>
                            <div class="w-3 h-3 rounded-full bg-green-400"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</template>
