<script setup>
import { computed } from 'vue';
import { Link } from '@inertiajs/vue3';

const props = defineProps({
    href: String,
    active: Boolean,
});

const classes = computed(() => {
    return props.active
        ? 'w-full flex items-center py-2 px-4 block text-xl rounded-md font-black text-white bg-primary'
        : 'w-full flex items-center py-2 px-4 text-sm rounded-md text-text hover:text-primary hover:text-xl hover:font-black hover:bg-card transition-all duration-200';
});
</script> 

<template>
    <Link :href="href" :class="classes">
        <slot />
    </Link>
</template>
