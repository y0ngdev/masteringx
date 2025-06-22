<script setup lang="ts">
import { ref } from 'vue'
import { Head } from '@inertiajs/vue3'
import { Button } from '@/components/ui/button'
import { Card, CardContent } from '@/components/ui/card'
import { ScrollArea } from '@/components/ui/scroll-area'
import { Separator } from '@/components/ui/separator'
import { 
  DropdownMenu,
  DropdownMenuContent,
  DropdownMenuItem,
  DropdownMenuTrigger,
} from '@/components/ui/dropdown-menu'
import {
  Sheet,
  SheetContent,
  SheetHeader,
  SheetTitle,
  SheetDescription,
} from '@/components/ui/sheet'

interface Section {
  id: number
  title: string
  subtitle: string
  lessons: {
    id: number
    title: string
    description: string
    duration: string
    isCompleted: boolean
    type: 'video' | 'exercise' | 'reading'
  }[]
}

interface Props {
  course?: {
    id: number
    title: string
    subtitle: string
    sections: Section[]
    progress: number
  }
  currentLesson?: {
    id: number
    title: string
    description: string
    content: string
    type: 'video' | 'exercise' | 'reading'
  }
}

const props = withDefaults(defineProps<Props>(), {
  course: () => ({
    id: 1,
    title: 'The Landscape of Choice',
    subtitle: 'A practical map for navigating the illusion that you actually have any agency at all.',
    progress: 35,
    sections: [
      {
        id: 1,
        title: 'Orientation',
        subtitle: 'Understanding Where You Are',
        lessons: [
          {
            id: 1,
            title: 'The Landscape of Choice',
            description: 'Explore the fundamental concepts of choice and decision-making.',
            duration: '15:30',
            isCompleted: true,
            type: 'video'
          },
          {
            id: 2,
            title: 'The Paradox of Agency',
            description: 'Understanding the complex relationship between choice and determinism.',
            duration: '12:45',
            isCompleted: true,
            type: 'video'
          },
          {
            id: 3,
            title: 'Liberation from Regret',
            description: 'Learn to move beyond the burden of past decisions.',
            duration: '20:00',
            isCompleted: false,
            type: 'video'
          }
        ]
      },
      {
        id: 2,
        title: 'Direction',
        subtitle: 'Choosing a Path',
        lessons: [
          {
            id: 4,
            title: 'Mapping the Causal Factors',
            description: 'Identify the key elements that influence your decisions.',
            duration: '18:20',
            isCompleted: false,
            type: 'video'
          },
          {
            id: 5,
            title: 'Decision Making Exercise',
            description: 'Practice applying the concepts through guided exercises.',
            duration: '30 mins',
            isCompleted: false,
            type: 'exercise'
          }
        ]
      },
      {
        id: 3,
        title: 'Navigation',
        subtitle: 'Steering Through the Inevitable',
        lessons: [
          {
            id: 6,
            title: 'Widening Your Field of View',
            description: 'Expand your perspective on decision-making.',
            duration: '25:15',
            isCompleted: false,
            type: 'video'
          },
          {
            id: 7,
            title: 'Required Reading: Decision Theory',
            description: 'Essential reading on modern decision theory.',
            duration: '45 mins',
            isCompleted: false,
            type: 'reading'
          }
        ]
      }
    ]
  }),
  currentLesson: () => ({
    id: 1,
    title: 'The Landscape of Choice',
    description: 'A practical map for navigating the illusion that you actually have any agency at all.',
    content: `You wake up each morning under the charming delusion that you're about to make a series of choices. What to wear, what to eat, whether to call in sick or drag yourself to work. But you don't actually make choices - you merely observe the outcomes of a series of predetermined processes that have been set in motion long before you even opened your eyes.`,
    type: 'video'
  })
})

const isOpen = ref(true)
const activeSectionId = ref(props.course.sections[0]?.id)

const toggleSection = (sectionId: number) => {
  activeSectionId.value = activeSectionId.value === sectionId ? -1 : sectionId
}

const getLessonIcon = (type: 'video' | 'exercise' | 'reading') => {
  switch (type) {
    case 'video':
      return 'i-lucide-play-circle'
    case 'exercise':
      return 'i-lucide-pencil'
    case 'reading':
      return 'i-lucide-book-open'
    default:
      return 'i-lucide-circle'
  }
}
</script>

<template>
  <div class="min-h-screen bg-background">
    <Head :title="course.title" />
    
    <header class="sticky top-0 z-40 w-full border-b bg-background/95 backdrop-blur supports-[backdrop-filter]:bg-background/60">
      <div class="container flex h-14 items-center">
        <div class="mr-4 flex">
          <Button variant="ghost" size="icon" class="mr-2 md:hidden" @click="isOpen = true">
            <i class="i-lucide-menu w-5 h-5" />
          </Button>
          <a class="mr-6 flex items-center space-x-2" href="/">
            <i class="i-lucide-compass w-6 h-6" />
            <span class="font-bold">Compass</span>
          </a>
        </div>
        <div class="flex items-center space-x-4">
          <Button variant="ghost" class="flex items-center gap-2">
            <i class="i-lucide-book-open w-4 h-4" />
            Course
          </Button>
          <Button variant="ghost" class="flex items-center gap-2">
            <i class="i-lucide-users w-4 h-4" />
            Interviews
          </Button>
          <Button variant="ghost" class="flex items-center gap-2">
            <i class="i-lucide-library w-4 h-4" />
            Resources
          </Button>
        </div>
        <div class="ml-auto flex items-center space-x-4">
          <DropdownMenu>
            <DropdownMenuTrigger asChild>
              <Button variant="ghost" class="relative h-8 w-8 rounded-full">
                <i class="i-lucide-user w-5 h-5" />
              </Button>
            </DropdownMenuTrigger>
            <DropdownMenuContent class="w-56" align="end">
              <DropdownMenuItem>
                <i class="i-lucide-user mr-2 h-4 w-4" />
                <span>Profile</span>
              </DropdownMenuItem>
              <DropdownMenuItem>
                <i class="i-lucide-settings mr-2 h-4 w-4" />
                <span>Settings</span>
              </DropdownMenuItem>
              <DropdownMenuItem>
                <i class="i-lucide-log-out mr-2 h-4 w-4" />
                <span>Logout</span>
              </DropdownMenuItem>
            </DropdownMenuContent>
          </DropdownMenu>
        </div>
      </div>
    </header>

    <div class="flex h-[calc(100vh-3.5rem)]">
      <!-- Course Navigation Sidebar for Desktop -->
      <div class="hidden md:flex w-80 border-r bg-card flex-col">
        <div class="p-6 border-b">
          <h1 class="text-xl font-semibold text-foreground">{{ course.title }}</h1>
          <p class="mt-1 text-sm text-muted-foreground">{{ course.subtitle }}</p>
          <div class="mt-4">
            <div class="h-2 rounded-full bg-secondary">
              <div 
                class="h-2 rounded-full bg-primary transition-all" 
                :style="{ width: `${course.progress}%` }"
              />
            </div>
            <p class="mt-2 text-sm text-muted-foreground flex items-center gap-2">
              <i class="i-lucide-target w-4 h-4" />
              {{ course.progress }}% Complete
            </p>
          </div>
        </div>
        
        <ScrollArea class="flex-1">
          <div class="p-6">
            <div v-for="section in course.sections" :key="section.id" class="mb-6">
              <button
                class="w-full text-left group"
                @click="toggleSection(section.id)"
              >
                <div class="flex items-center justify-between">
                  <div>
                    <h3 class="text-sm font-medium text-foreground">{{ section.title }}</h3>
                    <p class="text-sm text-muted-foreground">{{ section.subtitle }}</p>
                  </div>
                  <i
                    :class="[
                      activeSectionId === section.id ? 'i-lucide-chevron-down' : 'i-lucide-chevron-right',
                      'w-5 h-5 text-muted-foreground transition-transform'
                    ]"
                  />
                </div>
              </button>
              
              <div 
                v-if="activeSectionId === section.id"
                class="mt-3 space-y-1"
              >
                <div
                  v-for="lesson in section.lessons"
                  :key="lesson.id"
                  class="flex items-start gap-3 p-3 rounded-lg hover:bg-accent cursor-pointer transition-colors"
                  :class="{ 'bg-accent': currentLesson?.id === lesson.id }"
                >
                  <i 
                    :class="[
                      getLessonIcon(lesson.type),
                      'w-5 h-5 mt-0.5',
                      lesson.isCompleted ? 'text-primary' : 'text-muted-foreground'
                    ]"
                  />
                  <div class="flex-1 min-w-0">
                    <h4 class="text-sm font-medium text-foreground">{{ lesson.title }}</h4>
                    <p class="mt-1 text-sm text-muted-foreground line-clamp-2">{{ lesson.description }}</p>
                    <div class="mt-1 flex items-center gap-2 text-xs text-muted-foreground">
                      <i class="i-lucide-clock w-3 h-3" />
                      {{ lesson.duration }}
                    </div>
                  </div>
                  <i
                    v-if="lesson.isCompleted"
                    class="i-lucide-check w-5 h-5 text-primary"
                  />
                </div>
              </div>
            </div>
          </div>
        </ScrollArea>
      </div>

      <!-- Mobile Sidebar -->
      <Sheet v-model:open="isOpen" side="left">
        <SheetContent side="left" class="w-80 p-0">
          <SheetHeader class="p-6 border-b">
            <SheetTitle>{{ course.title }}</SheetTitle>
            <SheetDescription>{{ course.subtitle }}</SheetDescription>
            <div class="mt-4">
              <div class="h-2 rounded-full bg-secondary">
                <div 
                  class="h-2 rounded-full bg-primary transition-all" 
                  :style="{ width: `${course.progress}%` }"
                />
              </div>
              <p class="mt-2 text-sm text-muted-foreground flex items-center gap-2">
                <i class="i-lucide-target w-4 h-4" />
                {{ course.progress }}% Complete
              </p>
            </div>
          </SheetHeader>
          
          <ScrollArea class="flex-1 h-[calc(100vh-10rem)]">
            <div class="p-6">
              <div v-for="section in course.sections" :key="section.id" class="mb-6">
                <button
                  class="w-full text-left group"
                  @click="toggleSection(section.id)"
                >
                  <div class="flex items-center justify-between">
                    <div>
                      <h3 class="text-sm font-medium text-foreground">{{ section.title }}</h3>
                      <p class="text-sm text-muted-foreground">{{ section.subtitle }}</p>
                    </div>
                    <i
                      :class="[
                        activeSectionId === section.id ? 'i-lucide-chevron-down' : 'i-lucide-chevron-right',
                        'w-5 h-5 text-muted-foreground transition-transform'
                      ]"
                    />
                  </div>
                </button>
                
                <div 
                  v-if="activeSectionId === section.id"
                  class="mt-3 space-y-1"
                >
                  <div
                    v-for="lesson in section.lessons"
                    :key="lesson.id"
                    class="flex items-start gap-3 p-3 rounded-lg hover:bg-accent cursor-pointer transition-colors"
                    :class="{ 'bg-accent': currentLesson?.id === lesson.id }"
                    @click="isOpen = false"
                  >
                    <i 
                      :class="[
                        getLessonIcon(lesson.type),
                        'w-5 h-5 mt-0.5',
                        lesson.isCompleted ? 'text-primary' : 'text-muted-foreground'
                      ]"
                    />
                    <div class="flex-1 min-w-0">
                      <h4 class="text-sm font-medium text-foreground">{{ lesson.title }}</h4>
                      <p class="mt-1 text-sm text-muted-foreground line-clamp-2">{{ lesson.description }}</p>
                      <div class="mt-1 flex items-center gap-2 text-xs text-muted-foreground">
                        <i class="i-lucide-clock w-3 h-3" />
                        {{ lesson.duration }}
                      </div>
                    </div>
                    <i
                      v-if="lesson.isCompleted"
                      class="i-lucide-check w-5 h-5 text-primary"
                    />
                  </div>
                </div>
              </div>
            </div>
          </ScrollArea>
        </SheetContent>
      </Sheet>
      
      <!-- Main Content Area -->
      <main class="flex-1 overflow-y-auto bg-background">
        <div class="max-w-4xl mx-auto px-6 py-8">
          <div v-if="currentLesson?.type === 'video'" class="mb-8">
            <Card>
              <CardContent class="p-0 relative aspect-video bg-muted flex items-center justify-center">
                <div class="text-center">
                  <Button variant="ghost" size="icon" class="h-16 w-16">
                    <i class="i-lucide-play w-8 h-8" />
                  </Button>
                  <p class="mt-2 text-muted-foreground">Video Player</p>
                </div>
              </CardContent>
            </Card>
          </div>
          
          <article class="prose dark:prose-invert max-w-none">
            <h1>{{ currentLesson?.title }}</h1>
            <p class="lead">{{ currentLesson?.description }}</p>
            <div v-html="currentLesson?.content" />
          </article>
          
          <Separator class="my-8" />
          
          <section>
            <div class="flex items-center gap-2 mb-4">
              <i class="i-lucide-message-circle w-5 h-5" />
              <h2 class="text-lg font-semibold">Discussion</h2>
            </div>
            <Card>
              <CardContent class="p-4">
                <textarea
                  placeholder="Share your thoughts or ask a question..."
                  class="min-h-[100px] w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50"
                  rows="3"
                ></textarea>
                <div class="mt-4 flex items-center justify-end gap-2">
                  <Button variant="outline">Cancel</Button>
                  <Button>
                    <i class="i-lucide-send w-4 h-4 mr-2" />
                    Post Comment
                  </Button>
                </div>
              </CardContent>
            </Card>
          </section>
        </div>
      </main>
    </div>
  </div>
</template>
