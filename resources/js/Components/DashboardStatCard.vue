<script setup>
defineProps({
    title: {
        type: String,
        required: true,
    },
    value: {
        type: [String, Number],
        required: true,
    },
    borderColor: {
        type: String,
        default: 'border-indigo-500',
    }
});
</script>

<template>
    <div class="bg-card overflow-hidden shadow-sm rounded-md px-4 py-6 border-l-4" :class="borderColor">
        <div class="flex items-center">
            <div class="flex-shrink-0">
                <slot name="icon"></slot>
            </div>
            <div class="ml-4 w-full">
                <div class="text-sm font-medium truncate">
                    {{ title }}
                </div>
                <div class="mt-1 text-2xl font-semibold">
                    {{ value }}
                </div>
            </div>
        </div>
    </div>
</template>