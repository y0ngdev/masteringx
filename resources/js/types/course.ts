export type Lesson = {
    id: string
    title: string
    description: string
    duration: string
    canWatch:boolean
    url: string
}

export type Module = {
    id: string
    title: string
    lessons: Lesson[]

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

