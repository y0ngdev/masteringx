import type { Course, Lesson } from '@/types/course';
import { ref } from 'vue';

export const useCourse = () => {
    const currentCourse = ref<Course | null>(null);
    const currentLesson = ref<Lesson | null>(null);

    // Mock course data
    const mockCourse: Course = {
        modules: [
            {
                id: 'module-1',
                title: 'Getting Started with Vue.js 3',
                totalDuration: '2h 15m',
                lessons: [
                    {
                        id: 'lesson-1',
                        title: 'Introduction to Vue.js 3',
                        duration: '15:30',
                        completed: true,
                        type: 'video',
                        videoUrl: 'https://commondatastorage.googleapis.com/gtv-videos-bucket/sample/BigBuckBunny.mp4',
                    },
                    {
                        id: 'lesson-2',
                        title: 'Setting up Development Environment',
                        duration: '22:45',
                        completed: true,
                        type: 'video',
                        videoUrl: 'https://commondatastorage.googleapis.com/gtv-videos-bucket/sample/ElephantsDream.mp4',
                    },
                    {
                        id: 'lesson-3',
                        title: 'Your First Vue Application',
                        duration: '18:20',
                        completed: true,
                        type: 'video',
                        videoUrl: 'https://commondatastorage.googleapis.com/gtv-videos-bucket/sample/ForBiggerBlazes.mp4',
                    },
                    {
                        id: 'lesson-4',
                        title: 'Understanding the Vue Instance',
                        duration: '25:10',
                        completed: true,
                        type: 'video',
                        videoUrl: 'https://commondatastorage.googleapis.com/gtv-videos-bucket/sample/ForBiggerEscapes.mp4',
                    },
                    {
                        id: 'lesson-5',
                        title: 'Template Syntax and Directives',
                        duration: '28:45',
                        completed: false,
                        type: 'video',
                        isActive: true,
                        videoUrl: 'https://commondatastorage.googleapis.com/gtv-videos-bucket/sample/ForBiggerFun.mp4',
                    },
                ],
            },
            {
                id: 'module-2',
                title: 'Composition API Deep Dive',
                totalDuration: '3h 30m',
                completedLessons: 2,
                lessons: [
                    {
                        id: 'lesson-6',
                        title: 'Introduction to Composition API',
                        duration: '20:15',
                        completed: true,
                        type: 'video',
                        videoUrl: 'https://commondatastorage.googleapis.com/gtv-videos-bucket/sample/ForBiggerJoyrides.mp4',
                    },
                    {
                        id: 'lesson-7',
                        title: 'Reactive References and Computed Properties',
                        duration: '32:20',
                        completed: true,
                        type: 'video',
                        videoUrl: 'https://commondatastorage.googleapis.com/gtv-videos-bucket/sample/ForBiggerMeltdowns.mp4',
                    },
                    {
                        id: 'lesson-8',
                        title: 'Watchers and Lifecycle Hooks',
                        duration: '28:30',
                        completed: false,
                        type: 'video',
                        videoUrl: 'https://commondatastorage.googleapis.com/gtv-videos-bucket/sample/Sintel.mp4',
                    },
                ],
            },
            {
                id: 'module-3',
                title: 'Component Architecture',
                totalDuration: '4h 20m',
                completedLessons: 0,
                lessons: [
                    {
                        id: 'lesson-9',
                        title: 'Creating Reusable Components',
                        duration: '35:45',
                        completed: false,
                        type: 'video',
                        videoUrl: 'https://commondatastorage.googleapis.com/gtv-videos-bucket/sample/TearsOfSteel.mp4',
                    },
                    {
                        id: 'lesson-10',
                        title: 'Props and Events',
                        duration: '42:15',
                        completed: false,
                        type: 'video',
                        videoUrl: 'https://commondatastorage.googleapis.com/gtv-videos-bucket/sample/VolkswagenGTIReview.mp4',
                    },
                ],
            },
        ],
    };

    const initializeCourse = () => {
        currentCourse.value = mockCourse;
        currentLesson.value = mockCourse.modules[0].lessons[1]; // Start with first incomplete lesson
    };

    const selectLesson = (lesson: Lesson) => {
        currentLesson.value = lesson;
    };

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
