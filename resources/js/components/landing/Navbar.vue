<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Menu, X, ShoppingCart } from 'lucide-vue-next';
import { ref, onMounted, onBeforeUnmount, watch } from 'vue';
import { useWindowScroll } from '@vueuse/core';
import { Link } from '@inertiajs/vue3';

const isOpen = ref(false);
const isScrolled = ref(false);
const { y } = useWindowScroll();

// Watch for scroll position to add shadow
watch(y, (newY) => {
    isScrolled.value = newY > 0;
});

// Close mobile menu when clicking outside
const handleClickOutside = (event: MouseEvent) => {
    const target = event.target as HTMLElement;
    if (isOpen.value && !target.closest('nav')) {
        isOpen.value = false;
    }
};

// Close mobile menu on resize if screen becomes larger
const handleResize = () => {
    if (window.innerWidth >= 768 && isOpen.value) {
        isOpen.value = false;
    }
};

onMounted(() => {
    document.addEventListener('click', handleClickOutside);
    window.addEventListener('resize', handleResize);
});

onBeforeUnmount(() => {
    document.removeEventListener('click', handleClickOutside);
    window.removeEventListener('resize', handleResize);
});

const navigation = [
    { name: 'About', href: '#about' },
    { name: 'Pricing', href: '#pricing' },
    { name: 'Articles', href: route('articles') },
    // { name: 'Watch', href: route('watch') },
];
</script>

<template>
    <header
        class="fixed z-50 flex h-16 w-full items-center bg-background/80 backdrop-blur-sm transition-all duration-300"
        :class="{ 'border-b': isScrolled }"
    >
        <div class="container mx-auto px-4 md:px-6 lg:px-14">
            <nav class="flex items-center justify-between">
                <div class="flex items-center">
                    <div class="mr-8 flex items-center">
                        <!-- Logo -->
                        <a href="/" class="flex items-center gap-2 transition hover:opacity-80">
                            <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-primary">
                                <span class="font-medium text-primary-foreground">M</span>
                            </div>
                            <span class="font-bold text-foreground">Mastering X</span>
                        </a>
                    </div>

                    <!-- Desktop Navigation -->
                    <div class="hidden items-center space-x-6 md:flex text-sm">
                        <Link
                            v-for="item in navigation"
                            :key="item.name"
                            :href="item.href"
                            class="relative font-medium text-muted-foreground transition-colors hover:text-foreground after:absolute after:bottom-0 after:left-0 after:h-0.5 after:w-0 after:bg-primary after:transition-all after:duration-300 hover:after:w-full"
                        >
                            {{ item.name }}
                        </Link>
                    </div>
                </div>

                <!-- Desktop Navigation -->
                <div class="hidden items-center space-x-6 md:flex text-sm">
                    <a
                        href="/signin"
                        class="font-medium text-muted-foreground transition-colors hover:text-foreground"
                    >
                        Sign in
                    </a>
                    <Button class="transition duration-300 hover:scale-105">
                        <ShoppingCart class="mr-2 h-4 w-4" />
                        Buy Now
                    </Button>
                </div>

                <!-- Mobile Navigation Button -->
                <div class="md:hidden">
                    <Button
                        variant="ghost"
                        @click="isOpen = !isOpen"
                        class="transition-transform duration-200"
                        :class="{ 'rotate-180': isOpen }"
                    >
                        <Menu v-if="!isOpen" class="h-6 w-6" />
                        <X v-else class="h-6 w-6" />
                    </Button>
                </div>

                <!-- Mobile Navigation Menu -->
                <Transition
                    enter-active-class="transition duration-200 ease-out"
                    enter-from-class="transform -translate-y-4 opacity-0"
                    enter-to-class="transform translate-y-0 opacity-100"
                    leave-active-class="transition duration-150 ease-in"
                    leave-from-class="transform translate-y-0 opacity-100"
                    leave-to-class="transform -translate-y-4 opacity-0"
                >
                    <div
                        v-if="isOpen"
                        class="absolute top-full left-0 w-full border-b bg-background/80 py-4 backdrop-blur-sm md:hidden"
                    >
                        <div class="container mx-auto px-4">
                            <div class="flex flex-col space-y-4">
                                <a
                                    v-for="item in navigation"
                                    :key="item.name"
                                    :href="item.href"
                                    class="transform text-muted-foreground transition-all duration-200 hover:translate-x-2 hover:text-foreground"
                                    @click="isOpen = false"
                                >
                                    {{ item.name }}
                                </a>
                                <a
                                    href="/signin"
                                    class="transform text-muted-foreground transition-all duration-200 hover:translate-x-2 hover:text-foreground"
                                    @click="isOpen = false"
                                >
                                    Sign in
                                </a>
                                <Button
                                    class="w-full transform transition-all duration-200 hover:scale-[1.02]"
                                    @click="isOpen = false"
                                >
                                    <ShoppingCart class="mr-2 h-4 w-4" />
                                    Buy Now
                                </Button>
                            </div>
                        </div>
                    </div>
                </Transition>
            </nav>
        </div>
    </header>
</template>
