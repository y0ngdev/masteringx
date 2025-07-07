<script setup lang="ts">
import { Github, Linkedin, Mail, Twitter, Youtube } from 'lucide-vue-next';
import { computed } from 'vue';

const props = defineProps<{
    socials: object;
}>();
// TODO MAke name dynamic
const company = {
    name: 'Untitled UI',
    logo: 'U',
};



const socialLinks = computed(() => {
    const map = {
        email: {
            name: 'Email',
            getHref: (v: string) => `mailto:${v}`,
            icon: Mail,
        },
        twitter: {
            name: 'Twitter',
            getHref: (v: string) => `https://twitter.com/${v}`,
            icon: Twitter,
        },
        linkedin: {
            name: 'LinkedIn',
            getHref: (v: string) => `https://linkedin.com/in/${v}`,
            icon: Linkedin,
        },
        youtube: {
            name: 'YouTube',
            getHref: (v: string) =>
                `https://youtube.com/${v.startsWith('@') ? v : '@' + v}`,
            icon: Youtube,
        },
        github: {
            name: 'GitHub',
            getHref: (v: string) => `https://github.com/${v}`,
            icon: Github, // Make sure you've imported the correct GitHub icon
        },
    };

    return Object.entries(props.socials || {})
        .filter(([key, val]) => map[key] && val)
        .map(([key, val]) => ({
            name: map[key].name,
            href: map[key].getHref(val),
            icon: map[key].icon,
        }));

});

</script>

<template>
    <footer class="bg-background border-t">
        <div class="mx-auto max-w-7xl px-6 py-8">
            <div class="flex flex-col items-center justify-between gap-4 sm:flex-row">
                <!-- Company Logo -->
                <div class="flex items-center gap-2">
                    <div class="bg-primary flex h-8 w-8 items-center justify-center rounded-lg">
                        <span class="text-primary-foreground text-sm font-semibold">{{ company.logo }}</span>
                    </div>
                    <span class="text-foreground text-sm font-semibold">{{ company.name }}</span>
                </div>

                <!-- Social Links -->
                <div class="flex space-x-4">
                    <a
                        v-for="item in socialLinks"
                        :key="item.name"
                        :href="item.href"
                        class="text-muted-foreground hover:text-foreground transition-colors"
                    >
                        <span class="sr-only">{{ item.name }}</span>
                        <component :is="item.icon" class="h-4 w-4" />
                    </a>
                </div>
            </div>
        </div>
    </footer>
</template>
