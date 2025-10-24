<script setup>
import { computed } from "vue";
import { Link } from "@inertiajs/vue3";

const props = defineProps({
    href: String,
    active: Boolean,
});

const classes = computed(() => {
    return props.active ? "hidden md:block" : "hidden";
});
</script>

<template>
    <Link :href="href" :class="classes">
        <slot />
        <div class="relative">
            <div
                class="absolute h-12 -top-6 right-4 outline-dashed outline-2 outline-card rounded-full"
            />
            <div
                class="absolute p-2 -top-2 -left-[234px] bg-card ring-[10px] ring-primary rounded-r-full"
            />
            <div
                class="absolute p-2 -top-2 -right-[26px] bg-background ring-[10px] ring-primary rounded-l-full"
            />
        </div>
    </Link>
</template>
