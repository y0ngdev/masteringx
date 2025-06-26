<script setup lang="ts">
import { Github, Twitter, Linkedin } from 'lucide-vue-next';

const company = {
    name: 'Untitled UI',
    logo: 'U',
};

const socialLinks = [
    { name: 'Twitter', href: '#', icon: Twitter },
    { name: 'GitHub', href: '#', icon: Github },
    { name: 'LinkedIn', href: '#', icon: Linkedin },
];

const legalLinks = [
    { name: 'Terms', href: '#' },
    { name: 'Privacy', href: '#' },
    { name: 'Cookies', href: '#' },
];
</script>

<template>
    <footer class="border-t bg-background">
        <div class="mx-auto max-w-7xl px-6 py-8">
            <div class="flex flex-col items-center justify-between gap-4 sm:flex-row">
                <!-- Company Logo -->
                <div class="flex items-center gap-2">
                    <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-primary">
                        <span class="text-sm font-semibold text-primary-foreground">{{ company.logo }}</span>
                    </div>
                    <span class="text-sm font-semibold text-foreground">{{ company.name }}</span>
                </div>

                <!-- Social Links -->
                <div class="flex space-x-4">
                    <a v-for="item in socialLinks"
                       :key="item.name"
                       :href="item.href"
                       class="text-muted-foreground hover:text-foreground transition-colors">
                        <span class="sr-only">{{ item.name }}</span>
                        <component :is="item.icon" class="h-4 w-4" />
                    </a>
                </div>

                <!-- Legal Links -->
                <div class="flex gap-4">
                    <a v-for="link in legalLinks"
                       :key="link.name"
                       :href="link.href"
                       class="text-xs text-muted-foreground hover:text-foreground">
                        {{ link.name }}
                    </a>
                </div>
            </div>
        </div>
    </footer>
</template>
