<script setup>
import { Link } from "@inertiajs/vue3";
import { ClockIcon } from "@heroicons/vue/24/outline";

defineProps({
    results: {
        type: Array,
        default: () => [],
    },
    isLoading: {
        type: Boolean,
        default: false,
    },
});
</script>

<template>
    <div
        class="absolute top-full left-0 right-0 z-50 mt-1 max-w-xs lg:max-w-xs w-full overflow-hidden rounded-md bg-card shadow-lg border border-primary-low"
    >
        <div v-if="isLoading" class="p-4 text-center text-text-medium">
            <ClockIcon class="h-5 w-5 animate-spin-slow mx-auto" />
            <span class="text-sm">Searching...</span>
        </div>

        <div
            v-else-if="!isLoading && results.length === 0"
            class="p-4 text-center text-text-medium text-sm"
        >
            No results found.
        </div>

        <ul v-else class="divide-y divide-primary-low max-h-96 overflow-y-auto">
            <li v-for="concert in results" :key="concert.id">
                <Link
                    :href="route('concert.detail', concert)"
                    class="block p-3 hover:bg-primary-low transition duration-150"
                >
                    <div class="font-semibold text-text truncate">
                        {{ concert.name }}
                    </div>
                    <div class="text-sm text-text-medium truncate">
                        {{ concert.venue_name }}
                    </div>
                </Link>
            </li>
        </ul>
    </div>
</template>
