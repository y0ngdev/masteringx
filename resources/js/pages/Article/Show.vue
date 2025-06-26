<script setup lang="ts">
import Footer from '@/components/landing/Footer.vue';
import Navbar from '@/components/landing/Navbar.vue';
import { VueMarkdownIt } from '@f3ve/vue-markdown-it';
import { usePage } from '@inertiajs/vue3';
import { Calendar, User } from 'lucide-vue-next';
import { computed } from 'vue';
import { tasklist } from "@mdit/plugin-tasklist";
import { mark } from "@mdit/plugin-mark";
type Article = {
    id: string;
    title: string;
    excerpt: string;
    createdAt: string;
    body: string;
    author: [name: string];
    seo: [title: string, description: string, keywords: string];
};
defineProps<{
    article: Article;
}>();
const art = usePage().props.article;

const formattedDate = computed(() =>
    new Date(art.createdAt).toLocaleDateString('en-US', {
        month: 'long',
        day: 'numeric',
        year: 'numeric',
    }),
);

const opt=[
    tasklist,
    mark
]
const content ="```php +
    ```";
</script>

<template>
    <div class="bg-background text-foreground min-h-screen">
        <!-- Header -->
        <Navbar />
        <!-- Article -->
        <article class="mx-auto max-w-3xl px-6 py-16 pt-32">
            <!-- Article Header -->
            <header class="mb-12">
                <h1 class="mb-6 text-4xl font-bold leading-tight tracking-tight">
                    {{ article.title }}
                </h1>
                <div class="text-muted-foreground mb-6 flex flex-wrap items-center gap-4 text-sm">
                    <div class="flex items-center gap-2">
                        <User class="h-4 w-4" />
                        <span>{{ article.author.name }}</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <Calendar class="h-4 w-4" />
                        <span>{{ formattedDate }}</span>
                    </div>
                </div>
            </header>

            <!-- Markdown Content -->
            <div class="prose dark:prose-invert max-w-none">
                <component v-for="(block, index) in formattedContent" :key="index" :is="block.type" v-bind="block.props">
                    {{ block.content }}
                </component>
            </div>

            <VueMarkdownIt
                :source="content"
                class="prose dark:prose-invert max-w-none"
            :plugins="opt"
            />
        </article>

        <Footer />
    </div>
</template>
