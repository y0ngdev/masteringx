
<script setup lang="ts">
import { ref, computed } from 'vue';
import {
    ArrowLeft,
    Code,
    Clock,
    Calendar,
    User,
    Share2,
    Bookmark,
} from 'lucide-vue-next';

interface Article {
    id: number;
    title: string;
    content: string;
    author: string;
    date: string;
    readTime: string;
    tags: string[];
    category: 'engineering' | 'product' | 'culture' | 'growth';
}

const article = ref<Article>({
    id: 1,
    title: 'Building Scalable Microservices with Kubernetes',
    author: 'Sarah Chen',
    date: '2024-01-15',
    readTime: '8 min',
    tags: ['Kubernetes', 'Microservices', 'DevOps'],
    category: 'engineering',
    content: `# Introduction

When we started building our platform...
(you can paste the rest of your markdown content here)`
});

const isBookmarked = ref(false);

function toggleBookmark() {
    isBookmarked.value = !isBookmarked.value;
}

function handleShare() {
    if (navigator.share) {
        navigator.share({
            title: article.value.title,
            url: window.location.href
        });
    } else {
        navigator.clipboard.writeText(window.location.href);
    }
}

const formattedDate = computed(() =>
    new Date(article.value.date).toLocaleDateString('en-US', {
        month: 'long',
        day: 'numeric',
        year: 'numeric'
    })
);

const formattedContent = computed(() => {
    return article.value.content.split('\n').map((line) => {
        if (line.startsWith('# ')) {
            return { type: 'h1', content: line.slice(2), props: { class: 'text-2xl font-bold text-gray-900 mt-12 mb-6 first:mt-0' } };
        }
        if (line.startsWith('## ')) {
            return { type: 'h2', content: line.slice(3), props: { class: 'text-xl font-semibold text-gray-900 mt-10 mb-4' } };
        }
        if (line.startsWith('### ')) {
            return { type: 'h3', content: line.slice(4), props: { class: 'text-lg font-medium text-gray-900 mt-8 mb-3' } };
        }
        if (line.startsWith('- ')) {
            return { type: 'li', content: line.slice(2), props: { class: 'text-gray-700 leading-relaxed ml-4' } };
        }
        if (line.startsWith('---')) {
            return { type: 'hr', content: '', props: { class: 'border-gray-200 my-12' } };
        }
        if (line.startsWith('*') && line.endsWith('*')) {
            return { type: 'p', content: line.slice(1, -1), props: { class: 'text-gray-600 italic leading-relaxed mb-6' } };
        }
        if (line.trim() === '') {
            return { type: 'div', content: '', props: { class: 'h-4' } };
        }
        return { type: 'p', content: line, props: { class: 'text-gray-700 leading-relaxed mb-6' } };
    });
});

const relatedArticles = [
    {
        title: 'From Startup to Scale: Managing Technical Debt',
        excerpt: 'Our journey from MVP to enterprise-grade platform.',
        readTime: '7 min'
    },
    {
        title: 'AI-Powered Code Review: Our Open Source Approach',
        excerpt: 'We\'re open-sourcing our AI code review tool.',
        readTime: '10 min'
    }
];
</script>


<template>
    <div class="min-h-screen bg-white">
        <!-- Header -->
        <header class="border-b border-gray-100 sticky top-0 bg-white/80 backdrop-blur-sm">
            <div class="max-w-4xl mx-auto px-6">
                <div class="flex justify-between items-center h-16">
                    <div class="flex items-center gap-4">
                        <button class="flex items-center gap-2 text-gray-600 hover:text-gray-900 transition-colors">
                            <ArrowLeft class="w-4 h-4" />
                            <span class="text-sm">Back</span>
                        </button>
                        <div class="w-px h-4 bg-gray-200" />
                        <div class="flex items-center gap-2">
                            <div class="w-6 h-6 bg-black rounded flex items-center justify-center">
                                <Code class="w-3.5 h-3.5 text-white" />
                            </div>
                            <span class="font-medium text-gray-900">DevFlow</span>
                        </div>
                    </div>
                    <div class="flex items-center gap-3">
                        <button
                            @click="toggleBookmark"
                            :class="['p-2 rounded-lg transition-colors', isBookmarked ? 'text-gray-900 bg-gray-100' : 'text-gray-500 hover:text-gray-900']"
                        >
                            <Bookmark class="w-4 h-4" />
                        </button>
                        <button
                            @click="handleShare"
                            class="p-2 rounded-lg text-gray-500 hover:text-gray-900 transition-colors"
                        >
                            <Share2 class="w-4 h-4" />
                        </button>
                    </div>
                </div>
            </div>
        </header>

        <!-- Article -->
        <article class="max-w-3xl mx-auto px-6 py-16">
            <header class="mb-12">
                <div class="mb-4">
          <span class="text-xs text-gray-500 uppercase tracking-wide">
            {{ article.category }}
          </span>
                </div>
                <h1 class="text-3xl md:text-4xl font-bold text-gray-900 mb-6 leading-tight">
                    {{ article.title }}
                </h1>
                <div class="flex flex-wrap items-center gap-6 text-sm text-gray-600 mb-6">
                    <div class="flex items-center gap-2">
                        <User class="w-4 h-4" />
                        <span>{{ article.author }}</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <Calendar class="w-4 h-4" />
                        <span>{{ formattedDate }}</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <Clock class="w-4 h-4" />
                        <span>{{ article.readTime }} read</span>
                    </div>
                </div>
                <div class="flex gap-2">
          <span
              v-for="tag in article.tags"
              :key="tag"
              class="text-xs text-gray-600 bg-gray-50 px-3 py-1 rounded-full"
          >
            {{ tag }}
          </span>
                </div>
            </header>

            <!-- Markdown-like content -->
            <div class="prose prose-gray max-w-none">
                <component
                    v-for="(block, index) in formattedContent"
                    :key="index"
                    :is="block.type"
                    v-bind="block.props"
                >
                    {{ block.content }}
                </component>
            </div>

            <!-- Footer -->
            <footer class="mt-16 pt-8 border-t border-gray-100">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-gray-200 rounded-full flex items-center justify-center">
                            <User class="w-5 h-5 text-gray-600" />
                        </div>
                        <div>
                            <p class="font-medium text-gray-900">{{ article.author }}</p>
                            <p class="text-sm text-gray-600">Senior Engineering Manager</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-3">
                        <button
                            @click="toggleBookmark"
                            :class="[
                'px-4 py-2 rounded-lg text-sm transition-colors',
                isBookmarked ? 'bg-gray-900 text-white' : 'border border-gray-200 text-gray-700 hover:border-gray-300'
              ]"
                        >
                            {{ isBookmarked ? 'Bookmarked' : 'Bookmark' }}
                        </button>
                        <button
                            @click="handleShare"
                            class="px-4 py-2 border border-gray-200 text-gray-700 hover:border-gray-300 rounded-lg text-sm transition-colors"
                        >
                            Share
                        </button>
                    </div>
                </div>
            </footer>
        </article>

        <!-- Related Articles -->
        <section class="border-t border-gray-100 bg-gray-50">
            <div class="max-w-3xl mx-auto px-6 py-16">
                <h2 class="text-xl font-semibold text-gray-900 mb-8">Related Articles</h2>
                <div class="space-y-6">
                    <article
                        v-for="(related, index) in relatedArticles"
                        :key="index"
                        class="group cursor-pointer"
                    >
                        <h3 class="font-medium text-gray-900 mb-1 group-hover:text-gray-700 transition-colors">
                            {{ related.title }}
                        </h3>
                        <p class="text-gray-600 text-sm mb-2">{{ related.excerpt }}</p>
                        <span class="text-xs text-gray-500">{{ related.readTime }} read</span>
                    </article>
                </div>
            </div>
        </section>
    </div>
</template>
