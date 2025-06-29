<script setup lang="ts">
import { SidebarInset, SidebarProvider } from '@/components/ui/sidebar';
import CourseNav from '@/components/watch/CourseNav.vue';
import Sidebar from '@/components/watch/CourseSidebar.vue';
import LessonNavigation from '@/components/watch/LessonNavigation.vue';
import VideoPlayer from '@/components/watch/VideoPlayer.vue';
import { useCourse } from '@/composables/useCourse';
import { computed, onMounted, ref } from 'vue';
import { Lesson, Module } from '@/types/course';

const { currentCourse, currentLesson, initializeCourse, selectLesson, markLessonComplete, getNextLesson, getPreviousLesson } = useCourse();



defineProps<{
    lesson: Lesson;
    modules: Module[];
}>();

const videoProgress = ref(0);

const handleLessonSelect = (lesson: any) => {
    selectLesson(lesson);
};

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

const handleVideoTimeUpdate = (currentTime: number, duration: number) => {
    if (duration > 0) {
        videoProgress.value = (currentTime / duration) * 100;
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
</script>

<template>
    <!--    class="mt-32"-->

    <SidebarProvider>
        <CourseNav />
        <Sidebar class="mt-16" v-if="currentCourse" :course="currentCourse" :modules="modules" :current-lesson="currentLesson" @select-lesson="handleLessonSelect" />
        <SidebarInset class="mt-16">
            <!-- Video player area -->
            <div class="flex flex-1 flex-col">
                <!-- Video container -->
                <div class="bg-muted/2 flex flex-1 items-center justify-center">
                    <div class=" w-full">
                        <VideoPlayer
                            v-if="currentLesson?.videoUrl"
                            :src="currentLesson.videoUrl"
                            @time-update="handleVideoTimeUpdate"
                            @ended="handleVideoEnded"
                        />
                        <div v-else class="bg-muted flex h-full w-full items-center justify-center">
                            <div class="text-center">
                                <h3 class="mb-2 text-lg font-medium">No video selected</h3>
                                <p class="text-muted-foreground">Choose a lesson from the sidebar to start learning</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Lesson info -->
                <div v-if="currentLesson" class="border-border border-t p-6">
                    <div class="mx-auto max-w-5xl">
                        <h1 class="mb-2 text-2xl font-bold">{{ currentLesson.title }}</h1>
                        <div class="text-muted-foreground mb-4 flex items-center space-x-4 text-sm">
                            <span>Duration: {{ currentLesson.duration }}</span>
                            <span v-if="currentLesson.completed" class="text-green-600 dark:text-green-400"> ✓ Completed </span>
                        </div>
                        <p class="text-muted-foreground">
                            Learn the fundamentals and advanced concepts in this comprehensive lesson. Follow along with practical examples and
                            hands-on exercises to master the material.
                        </p>
                    </div>
                </div>

                <!-- Navigation -->
                <LessonNavigation
                    :current-lesson="currentLesson"
                    :previous-lesson="getPreviousLesson()"
                    :next-lesson="getNextLesson()"
                    :can-mark-complete="canMarkComplete"
                    @previous-lesson="handlePreviousLesson"
                    @next-lesson="handleNextLesson"
                    @mark-complete="handleMarkComplete"
                />
            </div>
        </SidebarInset>
    </SidebarProvider>
</template>
