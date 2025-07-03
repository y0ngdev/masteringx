
<script setup lang="ts">

import { Card, CardContent, CardDescription, CardFooter, CardHeader, CardTitle } from '@/components/ui/card';
import { Button } from '@/components/ui/button';
import { ArrowRight, Check } from 'lucide-vue-next';
import { Plan } from '@/types';
import { router } from '@inertiajs/vue3';
import { route } from 'ziggy-js';

defineProps<{
    pricing: Plan;
}>();

const buyPlan = (planId) => {
    router.post(
        route('buy'),
        { plan_id: planId },
        {
            preserveScroll: true,
            onSuccess: () => {
                // Optional: show a toast, modal, or redirect
            },
        },
    );
};
</script>

<template>
    <section id="pricing" class="border-border bg-background border-t py-20">
        <div class="mx-auto max-w-5xl px-6">
            <div class="mb-12 space-y-4 text-center">
                <h2 class="text-3xl font-bold tracking-tight sm:text-4xl">Choose your plan</h2>
                <p class="text-muted-foreground text-base">Simple, transparent pricing with no hidden fees</p>
            </div>

            <div class="mx-auto flex justify-center">
                <!-- Pricing Card -->
                <Card class="border-primary w-full max-w-md rounded-xl border shadow-sm transition hover:shadow-md">
                    <CardHeader class="pb-2">
                        <CardTitle class="text-foreground text-xl font-semibold">
                            {{ pricing.name }}
                        </CardTitle>
                        <CardDescription class="text-muted-foreground text-sm">
                            {{ pricing.short_description }}
                        </CardDescription>

                        <div class="mt-6 flex items-end gap-1">
                            <span class="text-4xl font-bold tracking-tight">{{ pricing.price }}</span>
                            <span class="text-muted-foreground text-sm">one-time</span>
                        </div>
                    </CardHeader>
                    <CardContent class="mt-6 border-t pt-6">
                        <ul class="space-y-3 text-sm">
                            <li v-for="feature in pricing.features" :key="feature" class="text-muted-foreground flex items-center gap-3">
                                <Check class="text-primary h-4 w-4 flex-shrink-0" />
                                <span>{{ feature }}</span>
                            </li>
                        </ul>
                    </CardContent>

                    <CardFooter class="mt-6">
                        <Button variant="default" class="group w-full"  @click="buyPlan(pricing.gateway_meta.stripe)">
                            Buy Now
                            <ArrowRight class="ml-2 h-4 w-4 transition-transform group-hover:translate-x-1" />
                        </Button>
                    </CardFooter>
                </Card>
            </div>
        </div>
    </section>
</template>

