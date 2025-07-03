<script setup lang="ts">
import Footer from '@/components/landing/Footer.vue';
import Hero from '@/components/landing/Hero.vue';
import Navbar from '@/components/landing/Navbar.vue';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { Head } from '@inertiajs/vue3';
import { ArrowRight, ChevronRight, Clock, FileText, Linkedin, Lock, Play, Twitter, Video, Youtube } from 'lucide-vue-next';

import Faq from '@/components/landing/Faq.vue';
import Pricing from '@/components/landing/Pricing.vue';
import { Plan } from '@/types';
import { Carousel, CarouselContent, CarouselItem, CarouselNext, CarouselPrevious } from '@/components/ui/carousel';

defineProps<{
    plan: Plan;
    settings:object
}>();
// Add course features
const courseFeatures = [
    {
        title: 'HD Video Lessons',
        description: 'Crystal-clear 4K video tutorials with professional audio quality',
        icon: Video,
    },
    {
        title: 'Lifetime Access',
        description: 'Learn at your own pace with unlimited access to all course content',
        icon: Clock,
    },
    {
        title: 'Project Files',
        description: 'Download source files and assets to follow along with the lessons',
        icon: FileText,
    },
];


const curriculum = {
    title: 'Course Curriculum',
    description: 'Learn everything you need to create professional video courses',
    modules: [
        {
            title: 'Getting Started',
            duration: '45 mins',
            videos: [
                {
                    title: 'Welcome to the Course',
                    duration: '10:00',
                    preview: true,
                },
                {
                    title: 'Setting Up Your Studio',
                    duration: '15:00',
                    preview: true,
                },
                {
                    title: 'Equipment Overview',
                    duration: '20:00',
                    preview: false,
                },
            ],
        },
        {
            title: 'Video Production',
            duration: '1h 15m',
            videos: [
                {
                    title: 'Camera Basics',
                    duration: '25:00',
                    preview: true,
                },
                {
                    title: 'Lighting Setup',
                    duration: '25:00',
                    preview: false,
                },
                {
                    title: 'Audio Recording',
                    duration: '25:00',
                    preview: false,
                },
            ],
        },
        {
            title: 'Post-Production',
            duration: '1h 30m',
            videos: [
                {
                    title: 'Editing Essentials',
                    duration: '30:00',
                    preview: true,
                },
                {
                    title: 'Adding Graphics',
                    duration: '30:00',
                    preview: false,
                },
                {
                    title: 'Final Export',
                    duration: '30:00',
                    preview: false,
                },
            ],
        },
    ],
};

// Update instructor data
const instructor = {
    name: 'Sarah Anderson',
    role: 'Senior Course Creator',
    bio: 'Former Creative Director with 10+ years of experience in video production and online education. Helping creators build successful online courses and digital products.',
    avatar: '/img/avatars/instructor.jpg',
    social: [
        { name: 'Twitter', icon: Twitter, href: 'https://twitter.com/sarahanderson' },
        { name: 'LinkedIn', icon: Linkedin, href: 'https://linkedin.com/in/sarahanderson' },
        { name: 'YouTube', icon: Youtube, href: 'https://youtube.com/@sarahanderson' },
    ],
};
</script>
<template>
    <Head title="Welcome" />
    <div class="bg-background min-h-screen">
        <!-- Navigation -->
        <Navbar />

        <!-- Hero Section -->
        <Hero />
        <!-- Course Features -->
        <section class="border-border bg-background border-t py-20">
            <div class="mx-auto max-w-5xl px-6">
                <div class="mb-12 space-y-4 text-center">
                    <h2 class="text-3xl font-semibold tracking-tight">What you'll get</h2>
                    <p class="text-muted-foreground">Everything you need to create professional videos</p>
                </div>
                <div class="grid gap-8 md:grid-cols-3">
                    <Card v-for="feature in courseFeatures" :key="feature.title">
                        <CardHeader>
                            <div class="bg-primary/10 mb-4 flex h-12 w-12 items-center justify-center rounded-full">
                                <component :is="feature.icon" class="text-primary h-6 w-6" />
                            </div>
                            <CardTitle>{{ feature.title }}</CardTitle>
                            <CardDescription>{{ feature.description }}</CardDescription>
                        </CardHeader>
                    </Card>
                </div>
            </div>
        </section>

        <!-- Start Today -->
        <section class="border-border bg-background border-t py-20">
            <div class="mx-auto max-w-[1280px] px-6 text-center">
                <h2 class="text-foreground mb-4 text-3xl font-semibold">Start your Untitled store today</h2>
                <p class="text-muted-foreground mb-8 text-xl">Start a free trial and explore Untitled for 14 days. No card required.</p>
                <div class="flex flex-col items-center justify-center gap-3 sm:flex-row">
                    <Button class="flex items-center gap-2">
                        Buy now
                        <ChevronRight class="h-4 w-4" />
                    </Button>
                </div>
            </div>
        </section>
        <!-- Meet Your Instructor -->
        <section class="border-border bg-background border-t py-20">
            <div class="mx-auto max-w-6xl px-6">
                <div class="mb-12 text-center">
                    <h2 class="text-3xl font-semibold tracking-tight">Meet your instructor</h2>
                    <p class="text-muted-foreground mt-2">Learn from an industry expert</p>
                </div>

                <Card>
                    <CardContent class="p-0">
                        <div class="grid md:grid-cols-2">
                            <!-- Instructor Image -->
                            <div class="border-border relative h-full min-h-[300px] border-r md:min-h-[400px]">
                                <img :src="instructor.avatar" :alt="instructor.name" class="absolute inset-0 h-full w-full object-cover" />
                            </div>

                            <!-- Instructor Info -->
                            <div class="flex flex-col justify-center p-8 md:p-10">
                                <div class="max-w-xl">
                                    <div class="mb-6">
                                        <h3 class="text-2xl font-semibold">{{ instructor.name }}</h3>
                                        <p class="text-primary mt-1 text-sm font-medium">{{ instructor.role }}</p>
                                    </div>

                                    <p class="text-muted-foreground mb-8 text-base leading-relaxed">
                                        {{ instructor.bio }}
                                    </p>

                                    <div class="flex items-center gap-4">
                                        <a
                                            v-for="link in instructor.social"
                                            :key="link.name"
                                            :href="link.href"
                                            target="_blank"
                                            rel="noopener noreferrer"
                                            class="hover:bg-muted inline-flex h-10 items-center justify-center rounded-md px-4 transition-colors"
                                        >
                                            <component
                                                :is="link.icon"
                                                class="text-muted-foreground hover:text-foreground h-5 w-5 transition-colors"
                                            />
                                            <span class="ml-2 text-sm font-medium">{{ link.name }}</span>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </CardContent>
                </Card>
            </div>
        </section>

        <!-- Course Curriculum -->
        <section id="about" class="border-border bg-background border-t py-20">
            <div class="mx-auto max-w-[1280px] px-6">
                <div class="mx-auto mb-16 max-w-[600px] text-center">
                    <div class="bg-primary/10 mb-4 inline-flex h-12 w-12 items-center justify-center rounded-full">
                        <span class="text-primary text-xl">📚</span>
                    </div>
                    <h2 class="text-foreground mb-4 text-3xl font-semibold">{{ curriculum.title }}</h2>
                    <p class="text-muted-foreground text-lg">{{ curriculum.description }}</p>
                </div>

                <!-- Course Modules -->
                <div class="mx-auto grid max-w-3xl gap-8 md:grid-cols-1">
                    <Card v-for="(module, moduleIndex) in curriculum.modules" :key="moduleIndex">
                        <!-- Module Header -->
                        <CardHeader class="border-border border-b p-6">
                            <div class="flex items-center gap-4">
                                <div class="bg-primary/10 flex h-12 w-12 items-center justify-center rounded-full">
                                    <span class="text-primary text-xl">{{ moduleIndex + 1 }}</span>
                                </div>
                                <div>
                                    <h3 class="text-xl font-semibold">{{ module.title }}</h3>
                                    <div class="mt-1 flex items-center gap-2">
                                        <div class="text-muted-foreground flex items-center gap-1">
                                            <Clock class="h-4 w-4" />
                                            <span class="text-sm">{{ module.duration }}</span>
                                        </div>
                                        <div class="text-muted-foreground flex items-center gap-1">
                                            <Video class="h-4 w-4" />
                                            <span class="text-sm">{{ module.videos.length }} lessons</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </CardHeader>

                        <CardContent class="p-0">
                            <!-- Videos List -->
                            <div class="divide-border divide-y">
                                <div
                                    v-for="(video, videoIndex) in module.videos"
                                    :key="videoIndex"
                                    class="hover:bg-muted/50 group relative flex items-center gap-4 p-4 transition-colors"
                                >
                                    <div
                                        class="flex h-10 w-10 flex-shrink-0 items-center justify-center rounded-full"
                                        :class="video.preview ? 'bg-primary/10' : 'bg-muted'"
                                    >
                                        <Play class="h-5 w-5" :class="video.preview ? 'text-primary' : 'text-muted-foreground'" />
                                    </div>

                                    <div class="min-w-0 flex-1">
                                        <h4 class="truncate text-sm font-medium">{{ video.title }}</h4>
                                        <div class="mt-1 flex items-center gap-2">
                                            <span class="text-muted-foreground text-sm">{{ video.duration }}</span>
                                            <Badge v-if="video.preview" variant="secondary" class="bg-primary/10 text-primary"> Preview </Badge>
                                        </div>
                                    </div>

                                    <div class="flex-shrink-0">
                                        <Button
                                            v-if="video.preview"
                                            variant="ghost"
                                            size="sm"
                                            class="opacity-0 transition-opacity group-hover:opacity-100"
                                        >
                                            Watch
                                            <ArrowRight class="ml-2 h-4 w-4" />
                                        </Button>
                                        <div v-else class="flex items-center gap-1">
                                            <Lock class="text-muted-foreground h-5 w-5" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </CardContent>
                    </Card>
                </div>

                <!-- CTA -->
                <div class="mt-12 text-center">
                    <Button variant="default" size="lg">
                        Get Full Access
                        <ArrowRight class="ml-2 h-4 w-4" />
                    </Button>
                </div>
            </div>
        </section>

        <!-- Pricing Section -->
        <Pricing :pricing="plan" />
        <!-- Testimonial -->
        <section class="border-border bg-background border-t py-20">
            <div class="mx-auto max-w-[800px] px-6">
                <div class="flex flex-col items-center text-center">
                    <div class="text-primary mb-6 flex gap-1">
                        <svg v-for="i in 5" :key="i" width="20" height="20" viewBox="0 0 20 20" fill="currentColor">
                            <path
                                d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"
                            />
                        </svg>
                    </div>
                    <blockquote class="text-foreground mb-8 text-[32px] font-medium leading-[40px] tracking-[-0.02em]">
                        "Untitled has truly impressed me as a small business owner. Selling my digital products has never been easier and the seamless
                        integration with Xero is a huge plus. I highly recommend!"
                    </blockquote>
                    <div class="flex items-center gap-4">
                        <img src="/img/avatars/testimonial.jpg" alt="Mira Vasone" class="h-14 w-14 rounded-full object-cover" />
                        <div class="text-left">
                            <div class="font-semibold">Mira Vasone</div>
                            <div class="text-muted-foreground">Shopbox Ventures, Australia</div>
                        </div>
                    </div>
                </div>
            </div>
            <Carousel class="relative w-full max-w-xs">
                <CarouselContent>
                    <CarouselItem v-for="(_, index) in 5" :key="index">
                        <div class="p-1">
                            <Card>
                                <CardContent class="flex aspect-square items-center justify-center p-6">
                                    <span class="text-4xl font-semibold">{{ index + 1 }}</span>
                                </CardContent>
                            </Card>
                        </div>
                    </CarouselItem>
                </CarouselContent>
                <CarouselPrevious />
                <CarouselNext />
            </Carousel>

        </section>

        <!-- FAQ Section -->
        <Faq v-if="settings.landing.faq" :faqs="settings.landing.faq" :email="settings.general.socials.email"  />
        <!-- Footer -->
        <Footer :socials="settings.general.socials" />
    </div>
</template>
