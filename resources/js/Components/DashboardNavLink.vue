<script setup>
import { computed } from 'vue';
import { Link } from '@inertiajs/vue3';

const props = defineProps({
    href: String,
    active: Boolean,
});

const classes = computed(() => {
    return props.active
        ? 'inline-flex items-center px-1 font-bold border-primary leading-5 text-primary focus:outline-none focus:border-primary transition duration-150 ease-in-out'
        : 'inline-flex items-center px-1 leading-5 text-text hover:text-text hover:border-text-low focus:outline-none focus:text-text focus:border-text-low transition duration-150 ease-in-out';
});
</script>

<template>
    <Link :href="href" :class="classes">
        <slot />
    </Link>
</template>
