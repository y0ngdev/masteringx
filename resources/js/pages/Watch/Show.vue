<script setup lang="ts">
import { SidebarInset, SidebarProvider } from '@/components/ui/sidebar';
import CourseNav from '@/components/watch/CourseNav.vue';
import Sidebar from '@/components/watch/CourseSidebar.vue';
import { useCourse } from '@/composables/useCourse';


const {
    currentCourse,
    currentLesson,
    initializeCourse,
    selectLesson,
    markLessonComplete,
    getNextLesson,
    getPreviousLesson
} = useCourse()
</script>

<template>
    <!--    class="mt-32"-->

    <SidebarProvider>
        <CourseNav />
        <Sidebar class="mt-16"  :course="currentCourse"/>
        <SidebarInset class="mt-16">
            <div class="container mt-10 pl-10">sssssssssssssss</div>
        </SidebarInset>
    </SidebarProvider>
</template>
