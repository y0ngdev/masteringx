import { ref, computed } from 'vue'
import type { Course, Lesson, CourseProgress } from '@/types/course'

export const useCourse = () => {
    const currentCourse = ref<Course | null>(null)
    const currentLesson = ref<Lesson | null>(null)
    const isPlaying = ref(false)
    const currentTime = ref(0)
    const duration = ref(0)
    const volume = ref(1)
    const playbackRate = ref(1)

    // Mock course data
    const mockCourse: Course = {
        id: '1',
        title: 'Complete Vue.js 3 Masterclass',
        instructor: 'Sarah Johnson',
        description: 'Master Vue.js 3 with TypeScript, Composition API, and modern development practices.',
        thumbnail: 'https://images.pexels.com/photos/4348404/pexels-photo-4348404.jpeg?auto=compress&cs=tinysrgb&w=800',
        totalDuration: '12h 45m',
        totalLessons: 48,
        completedLessons: 12,
        modules: [
            {
                id: 'module-1',
                title: 'Getting Started with Vue.js 3',
                totalDuration: '2h 15m',
                completedLessons: 4,
                lessons: [
                    {
                        id: 'lesson-1',
                        title: 'Introduction to Vue.js 3',
                        duration: '15:30',
                        completed: true,
                        type: 'video',
                        videoUrl: 'https://commondatastorage.googleapis.com/gtv-videos-bucket/sample/BigBuckBunny.mp4'
                    },
                    {
                        id: 'lesson-2',
                        title: 'Setting up Development Environment',
                        duration: '22:45',
                        completed: true,
                        type: 'video',
                        videoUrl: 'https://commondatastorage.googleapis.com/gtv-videos-bucket/sample/ElephantsDream.mp4'
                    },
                    {
                        id: 'lesson-3',
                        title: 'Your First Vue Application',
                        duration: '18:20',
                        completed: true,
                        type: 'video',
                        videoUrl: 'https://commondatastorage.googleapis.com/gtv-videos-bucket/sample/ForBiggerBlazes.mp4'
                    },
                    {
                        id: 'lesson-4',
                        title: 'Understanding the Vue Instance',
                        duration: '25:10',
                        completed: true,
                        type: 'video',
                        videoUrl: 'https://commondatastorage.googleapis.com/gtv-videos-bucket/sample/ForBiggerEscapes.mp4'
                    },
                    {
                        id: 'lesson-5',
                        title: 'Template Syntax and Directives',
                        duration: '28:45',
                        completed: false,
                        type: 'video',
                        isActive:true,
                        videoUrl: 'https://commondatastorage.googleapis.com/gtv-videos-bucket/sample/ForBiggerFun.mp4'
                    }
                ]
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
                        videoUrl: 'https://commondatastorage.googleapis.com/gtv-videos-bucket/sample/ForBiggerJoyrides.mp4'
                    },
                    {
                        id: 'lesson-7',
                        title: 'Reactive References and Computed Properties',
                        duration: '32:20',
                        completed: true,
                        type: 'video',
                        videoUrl: 'https://commondatastorage.googleapis.com/gtv-videos-bucket/sample/ForBiggerMeltdowns.mp4'
                    },
                    {
                        id: 'lesson-8',
                        title: 'Watchers and Lifecycle Hooks',
                        duration: '28:30',
                        completed: false,
                        type: 'video',
                        videoUrl: 'https://commondatastorage.googleapis.com/gtv-videos-bucket/sample/Sintel.mp4'
                    }
                ]
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
                        videoUrl: 'https://commondatastorage.googleapis.com/gtv-videos-bucket/sample/TearsOfSteel.mp4'
                    },
                    {
                        id: 'lesson-10',
                        title: 'Props and Events',
                        duration: '42:15',
                        completed: false,
                        type: 'video',
                        videoUrl: 'https://commondatastorage.googleapis.com/gtv-videos-bucket/sample/VolkswagenGTIReview.mp4'
                    }
                ]
            }
        ]
    }

    const progress = computed(() => {
        if (!currentCourse.value) return 0
        return Math.round((currentCourse.value.completedLessons / currentCourse.value.totalLessons) * 100)
    })

    const initializeCourse = () => {
        currentCourse.value = mockCourse
        currentLesson.value = mockCourse.modules[0].lessons[1] // Start with first incomplete lesson
    }


    const selectLesson = (lesson: Lesson) => {
        currentLesson.value = lesson
        isPlaying.value = false
        currentTime.value = 0
    }

    const markLessonComplete = (lessonId: string) => {
        if (!currentCourse.value) return

        currentCourse.value.modules.forEach(module => {
            const lesson = module.lessons.find(l => l.id === lessonId)
            if (lesson && !lesson.completed) {
                lesson.completed = true
                module.completedLessons++
                currentCourse.value!.completedLessons++
            }
        })
    }

    const getNextLesson = (): Lesson | null => {
        if (!currentCourse.value || !currentLesson.value) return null

        for (const module of currentCourse.value.modules) {
            const currentIndex = module.lessons.findIndex(l => l.id === currentLesson.value!.id)
            if (currentIndex !== -1) {
                // Check if there's a next lesson in current module
                if (currentIndex < module.lessons.length - 1) {
                    return module.lessons[currentIndex + 1]
                }
                // Check next module
                const moduleIndex = currentCourse.value.modules.findIndex(s => s.id === module.id)
                if (moduleIndex < currentCourse.value.modules.length - 1) {
                    const nextmodule = currentCourse.value.modules[moduleIndex + 1]
                    return nextmodule.lessons[0]
                }
            }
        }
        return null
    }

    const getPreviousLesson = (): Lesson | null => {
        if (!currentCourse.value || !currentLesson.value) return null

        for (const module of currentCourse.value.modules) {
            const currentIndex = module.lessons.findIndex(l => l.id === currentLesson.value!.id)
            if (currentIndex !== -1) {
                // Check if there's a previous lesson in current module
                if (currentIndex > 0) {
                    return module.lessons[currentIndex - 1]
                }
                // Check previous module
                const moduleIndex = currentCourse.value.modules.findIndex(s => s.id === module.id)
                if (moduleIndex > 0) {
                    const prevmodule = currentCourse.value.modules[moduleIndex - 1]
                    return prevmodule.lessons[prevmodule.lessons.length - 1]
                }
            }
        }
        return null
    }

    return {
        currentCourse,
        currentLesson,
        isPlaying,
        currentTime,
        duration,
        volume,
        playbackRate,
        progress,
        initializeCourse,
        selectLesson,
        markLessonComplete,
        getNextLesson,
        getPreviousLesson
    }
}
