<script setup>
defineProps({
    type: {
        type: String,
        default: 'button',
    },
});
</script>

<template>
    <button :type="type" class="inline-flex items-center px-4 py-2 bg-text border border-transparent rounded-md font-bold text-sm text-text-reverse uppercase tracking-widest shadow-sm hover:bg-text-hover focus:outline-none focus:ring-0 disabled:opacity-25 transition ease-in-out duration-150">
        <slot />
    </button>
</template>
