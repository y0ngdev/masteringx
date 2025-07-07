import type { Course, Lesson } from '@/types/course';
import { ref } from 'vue';

export const useCourse = () => {


    const markLessonComplete = (lessonId: string) => {
        if (!currentCourse.value) return;

        currentCourse.value.modules.forEach((module) => {
            const lesson = module.lessons.find((l) => l.id === lessonId);
            if (lesson && !lesson.completed) {
                lesson.completed = true;
                module.completedLessons++;
                currentCourse.value!.completedLessons++;
            }
        });
    };

    const getNextLesson = (): Lesson | null => {
        if (!currentCourse.value || !currentLesson.value) return null;

        for (const module of currentCourse.value.modules) {
            const currentIndex = module.lessons.findIndex((l) => l.id === currentLesson.value!.id);
            if (currentIndex !== -1) {
                // Check if there's a next lesson in current module
                if (currentIndex < module.lessons.length - 1) {
                    return module.lessons[currentIndex + 1];
                }
                // Check next module
                const moduleIndex = currentCourse.value.modules.findIndex((s) => s.id === module.id);
                if (moduleIndex < currentCourse.value.modules.length - 1) {
                    const nextmodule = currentCourse.value.modules[moduleIndex + 1];
                    return nextmodule.lessons[0];
                }
            }
        }
        return null;
    };

    const getPreviousLesson = (): Lesson | null => {
        if (!currentCourse.value || !currentLesson.value) return null;

        for (const module of currentCourse.value.modules) {
            const currentIndex = module.lessons.findIndex((l) => l.id === currentLesson.value!.id);
            if (currentIndex !== -1) {
                // Check if there's a previous lesson in current module
                if (currentIndex > 0) {
                    return module.lessons[currentIndex - 1];
                }
                // Check previous module
                const moduleIndex = currentCourse.value.modules.findIndex((s) => s.id === module.id);
                if (moduleIndex > 0) {
                    const prevmodule = currentCourse.value.modules[moduleIndex - 1];
                    return prevmodule.lessons[prevmodule.lessons.length - 1];
                }
            }
        }
        return null;
    };

    return {
        currentCourse,
        currentLesson,

        initializeCourse,
        selectLesson,
        markLessonComplete,
        getNextLesson,
        getPreviousLesson,
    };
};
