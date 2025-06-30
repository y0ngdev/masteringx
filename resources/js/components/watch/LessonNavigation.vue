<script setup lang="ts">
import { Button } from '@/components/ui/button';
import type { Lesson } from '@/types/course';
import { CheckCircle, ChevronLeft, ChevronRight } from 'lucide-vue-next';

interface Props {
    currentLesson?: Lesson | null;
    previousLesson?: Lesson | null;
    nextLesson?: Lesson | null;
    canMarkComplete?: boolean;
}

const props = defineProps<Props>();

const emit = defineEmits<{
    previousLesson: [];
    nextLesson: [];
    markComplete: [];
}>();
</script>

<template>
    <div class="bg-background border-border border-t px-6 py-4">
        <div class="flex items-center justify-between">
            <!-- Previous lesson -->
            <Button @click="emit('previousLesson')" :disabled="!previousLesson" variant="outline" class="flex items-center space-x-2">
                <ChevronLeft class="h-4 w-4" />
                <span class="hidden sm:inline">Previous</span>
            </Button>

            <!-- Current lesson info and actions -->
            <div class="flex items-center space-x-4">
                <Button
                    v-if="currentLesson && !currentLesson.completed && canMarkComplete"
                    @click="emit('markComplete')"
                    variant="default"
                    class="flex items-center space-x-2"
                >
                    <CheckCircle class="h-4 w-4" />
                    <span>Mark Complete</span>
                </Button>

                <div v-if="currentLesson?.completed" class="flex items-center space-x-2 text-green-600 dark:text-green-400">
                    <CheckCircle class="h-4 w-4" />
                    <span class="text-sm font-medium">Completed</span>
                </div>
            </div>

            <!-- Next lesson -->
            <Button @click="emit('nextLesson')" :disabled="!nextLesson" variant="outline" class="flex items-center space-x-2">
                <span class="hidden sm:inline">Next</span>
                <ChevronRight class="h-4 w-4" />
            </Button>
        </div>
    </div>
</template>
