<script setup lang="ts">
import { Card } from '@/components/ui/card';
import { SidebarInset, SidebarProvider } from '@/components/ui/sidebar';
import CourseNav from '@/components/watch/CourseNav.vue';
import Sidebar from '@/components/watch/CourseSidebar.vue';
import LockedPlayer from '@/components/watch/LockedPlayer.vue';
import VideoPlayer from '@/components/watch/VideoPlayer.vue';
import { useCourse } from '@/composables/useCourse';
import { Lesson, Module } from '@/types/course';
import { VueMarkdownIt } from '@f3ve/vue-markdown-it';
import { abbr } from '@mdit/plugin-abbr';
import { mark } from '@mdit/plugin-mark';
import { sub } from '@mdit/plugin-sub';
import { sup } from '@mdit/plugin-sup';
import { tasklist } from '@mdit/plugin-tasklist';
import { computed, onMounted, ref } from 'vue';

import { Button } from '@/components/ui/button';

const { currentCourse, currentLesson, initializeCourse, selectLesson, markLessonComplete, getNextLesson, getPreviousLesson } = useCourse();

defineProps<{
    lesson: Lesson;
    modules: Module[];
}>();

const videoProgress = ref(0);

const handleMarkComplete = () => {
    if (currentLesson.value) {
        markLessonComplete(currentLesson.value.id);
    }
};

const handleNextLesson = () => {
    const nextLesson = getNextLesson();
    if (nextLesson) {
        selectLesson(nextLesson);
    }
};

const handlePreviousLesson = () => {
    const previousLesson = getPreviousLesson();
    if (previousLesson) {
        selectLesson(previousLesson);
    }
};

const handleVideoEnded = () => {
    // Auto-mark lesson as complete when video ends
    if (currentLesson.value && !currentLesson.value.completed) {
        markLessonComplete(currentLesson.value.id);
    }

    // Auto-advance to next lesson
    const nextLesson = getNextLesson();
    if (nextLesson) {
        setTimeout(() => {
            selectLesson(nextLesson);
        }, 2000); // 2 second delay before auto-advance
    }
};

const canMarkComplete = computed(() => {
    return videoProgress.value > 80; // Can mark complete if watched 80% of video
});

onMounted(() => {
    initializeCourse();
});

const opt = ref([tasklist, mark, abbr, sub, sup]);
</script>

<template>
    <!--    class="mt-32"-->

    <SidebarProvider>
        <CourseNav />
        <Sidebar class="mt-16" v-if="currentCourse" :lesson="lesson" :modules="modules" />
        <SidebarInset class="mt-16">
            <!-- Video player area -->
            <div class="flex flex-1 flex-col">
                <!-- Video container -->
                <div class="bg-muted/2 flex flex-1 items-center justify-center">
                    <div class="w-full">
                        <VideoPlayer v-if="lesson.canWatch" :title="lesson.title" :src="lesson.streamURL" />
                        <LockedPlayer v-else />
                    </div>
                </div>
                <div class="mx-auto w-full border-t px-4 py-2 tracking-tight md:px-6 md:py-3">
                    <div class="items-center gap-2">
                        <div class="text-xl font-semibold md:pb-1 md:text-3xl">{{ lesson.title }}</div>
                        <!--                        <p class="text-sm mt-2">Duration: {{ lesson.duration }}</p>-->
                    </div>
                </div>

                <!-- Lesson info -->
                <div class="mx-auto flex w-full flex-1 flex-col gap-6 border-t px-4 py-2.5 pb-48 md:px-6 md:pb-96 md:pt-4 lg:flex-row">
                    <!-- Sidebar Card (Buy Section) -->
                    <div class="w-full lg:order-2 lg:w-1/3">
                        <Card class="space-y-6 p-6">
                            <div>
                                <h3 class="mb-2 text-lg font-semibold">Full Course</h3>
                                <div class="flex items-baseline gap-2">
                                    <span class="text-3xl font-bold">$349</span>
                                    <span v-if="0 === '0'" class="text-muted-foreground text-sm line-through">$399</span>
                                </div>
                                <p class="text-muted-foreground text-sm">USD, one-time fee</p>
                            </div>

                            <Button class="transition duration-300 hover:scale-105" size="sm" as="a" :href="route('buy')"> Buy Now </Button>

                            <div class="space-y-4" v-if="0 === '0'">
                                <div class="flex items-center gap-1">
                                    <!-- Star ratings -->
                                </div>

                                <blockquote class="text-muted-foreground text-sm italic leading-relaxed">
                                    "The course 'Mastering Postgres' helped me practice query commands and understand why they're important.
                                    <strong class="text-foreground"
                                        >Inspiring me to explore the 'why' and 'how' behind each one. Truly a great course!</strong
                                    >"
                                </blockquote>

                                <div class="flex items-center gap-3">
                                    <div class="flex h-10 w-10 items-center justify-center rounded-full bg-gray-600">
                                        <span class="text-sm font-medium text-white">N</span>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium">Nakolus</p>
                                    </div>
                                </div>
                            </div>
                        </Card>
                    </div>

                    <!-- Course Description -->
                    <div class="w-full text-sm text-zinc-900 md:text-base lg:w-2/3 dark:text-zinc-300">
                        <VueMarkdownIt :source="lesson.description" class="prose dark:prose-invert max-w-none" :plugins="opt" />
                    </div>
                </div>
            </div>
        </SidebarInset>
    </SidebarProvider>
</template>
