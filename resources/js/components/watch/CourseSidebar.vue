<script setup lang="ts">
import { Collapsible, CollapsibleContent, CollapsibleTrigger } from '@/components/ui/collapsible';
import {
    Sidebar,
    SidebarContent,
    SidebarGroup,
    SidebarMenu,
    SidebarMenuButton,
    SidebarMenuItem,
    SidebarMenuSub,
    SidebarMenuSubButton,
    SidebarMenuSubItem,
    SidebarRail,
} from '@/components/ui/sidebar';
import { Minus, Plus } from 'lucide-vue-next';
import { Course, Lesson } from '@/types/course';
import { ref } from 'vue';

interface Props {
    course: Course
    currentLesson?: Lesson | null
}

const props = defineProps<Props>()

const emit = defineEmits<{
    selectLesson: [lesson: Lesson]
}>()

const expandedSections = ref<Set<string>>(new Set(['section-1', 'section-2', 'section-3']))

const toggleSection = (sectionId: string) => {
    if (expandedSections.value.has(sectionId)) {
        expandedSections.value.delete(sectionId)
    } else {
        expandedSections.value.add(sectionId)
    }
}

const getLessonIcon = (lesson: Lesson) => {
    if (lesson.completed) return CheckCircle2
    if (lesson.type === 'video') return Play
    if (lesson.type === 'reading') return FileText
    if (lesson.type === 'quiz') return HelpCircle
    return Clock
}

const getLessonIconColor = (lesson: Lesson) => {
    if (lesson.completed) return 'text-green-400'
    if (lesson.id === props.currentLesson?.id) return 'text-blue-400'
    return 'text-gray-400'
}

</script>

<template>
    <Sidebar class="bg-background">
        <SidebarContent class="mt-4">
            <SidebarGroup>
                <SidebarMenu>
                    <Collapsible v-for="(item, index) in course.modules" :key="item.id" :default-open="index === 1" class="group/collapsible">
                        <SidebarMenuItem>
                            <CollapsibleTrigger as-child>
                                <SidebarMenuButton>
                                    {{ item.title }}
                                    <Plus class="ml-auto group-data-[state=open]/collapsible:hidden" />
                                    <Minus class="ml-auto group-data-[state=closed]/collapsible:hidden" />
                                </SidebarMenuButton>
                            </CollapsibleTrigger>
                            <CollapsibleContent v-if="item.items.length">
                                <SidebarMenuSub>
                                    <SidebarMenuSubItem v-for="lesson in item.lessons" :key="lesson.title">
                                        <SidebarMenuSubButton as-child :is-active="lesson.isActive">
                                            <a :href="lesson.url">{{ lesson.title }}</a>
                                        </SidebarMenuSubButton>
                                    </SidebarMenuSubItem>
                                </SidebarMenuSub>
                            </CollapsibleContent>
                        </SidebarMenuItem>
                    </Collapsible>
                </SidebarMenu>
            </SidebarGroup>
        </SidebarContent>
        <SidebarRail />
    </Sidebar>
</template>
