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
import { Lesson, Module } from '@/types/course';
import { Link } from '@inertiajs/vue3';
import { ChevronDown, ChevronUp, Circle, Lock } from 'lucide-vue-next';

interface Props {
    lessonID: number;
    modules: Module;
}

const props = defineProps<Props>();

const emit = defineEmits<{
    selectLesson: [lesson: Lesson];
}>();

const isActive = (id) => {
    if (id == props.lessonID) {
        return true;
    }
};
</script>

<template>
    <Sidebar class="bg-background">
        <SidebarContent class="mt-4 text-sm font-light">
            <SidebarGroup>
                <SidebarMenu>
                    <!--                   Todo: this is here the where the collapsible default open is configured -->
                    <Collapsible v-for="(item, index) in modules" :key="item.id" :default-open="index === 0" class="group/collapsible">
                        <SidebarMenuItem>
                            <CollapsibleTrigger as-child>
                                <SidebarMenuButton size="sm" :tooltip="item.title" class="truncate text-zinc-300">
                                    {{ item.title }}
                                    <ChevronDown class="ml-auto group-data-[state=open]/collapsible:hidden" />
                                    <ChevronUp class="ml-auto group-data-[state=closed]/collapsible:hidden" />
                                </SidebarMenuButton>
                            </CollapsibleTrigger>

                            <CollapsibleContent v-if="item.lessons">
                                <SidebarMenuSub>
                                    <SidebarMenuSubItem
                                        v-for="lesson in item.lessons"
                                        :key="lesson.title"
                                        class="border-l-1 -ml-[0.690rem] mb-4 cursor-pointer"
                                        :class="{ 'border-zinc-200': isActive(lesson.id), '': lesson.completed }"
                                    >
                                        <SidebarMenuSubButton
                                            size="sm"
                                            as-child
                                            :is-active="isActive(lesson.id)"
                                            class="min truncate py-2.5 font-medium text-zinc-600"
                                        >
                                            <Link :href="lesson.url" class="flex w-full items-center justify-between py-2">
                                                <div class="flex min-w-0 flex-1 items-center gap-3">
                                                    <span class="truncate">{{ lesson.title }}</span>
                                                </div>
                                                <div class="text-muted-foreground ml-2 flex flex-shrink-0 items-center gap-1 text-xs">
                                                    <Circle class="h-2 w-2 object-fill" v-if="lesson.canWatch" />

                                                    <Lock class="h-2 w-2" v-else />
                                                </div>
                                            </Link>
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
