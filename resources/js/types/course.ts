export type Lesson = {
    id: string
    title: string
    duration: string
    completed: boolean
    type: 'video' | 'quiz' | 'reading'
    videoUrl?: string
}

export type Module = {
    id: string
    title: string
    lessons: Lesson[]
    totalDuration: string
    completedLessons: number
}

export type Course = {
    id: string
    title: string
    instructor: string
    description: string
    thumbnail: string
    totalDuration: string
    totalLessons: number
    completedLessons: number
    modules: Module[]
    currentLesson?: Lesson
}

export type CourseProgress = {
    courseId: string
    completedLessons: string[]
    currentLessonId: string
    lastWatched: Date
    totalProgress: number
}
