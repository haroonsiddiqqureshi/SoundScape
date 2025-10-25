<script setup>
import { computed } from "vue";
import { Link } from "@inertiajs/vue3";
import { ClockIcon } from "@heroicons/vue/24/outline";

const props = defineProps({
    results: {
        type: Array,
        default: () => [],
    },
    isLoading: {
        type: Boolean,
        default: false,
    },
});

const pictureUrl = computed(() => {
    if (props.concert?.picture_url) {
        if (props.concert.picture_url.startsWith("http")) {
            return props.concert.picture_url;
        }
        return `/storage/${props.concert.picture_url}`;
    }
    return "https://placehold.co/600x400?text=SoundScape";
});
</script>

<template>
    <div
        class="custom-scrollbar absolute top-full left-0 right-0 z-50 mt-1 w-full overflow-hidden rounded-md bg-card shadow-lg border border-primary-low"
    >
        <div v-if="props.isLoading" class="p-4 text-center text-text-medium">
            <ClockIcon class="h-5 w-5 animate-spin-slow mx-auto" />
            <span class="text-sm">Searching...</span>
        </div>

        <div
            v-else-if="!props.isLoading && results.length === 0"
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
                    <div>
                        <img :src="props.pictureUrl" alt="Concert Picture" class="w-full h-40 object-cover rounded-md mb-2" />
                    </div>
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
