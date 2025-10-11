<script setup>
import { computed } from "vue";

const props = defineProps({
    highlight: Object,
});

const pictureUrl = computed(() => {
    if (props.highlight?.picture_url) {
        if (props.highlight.picture_url.startsWith("http")) {
            return props.highlight.picture_url;
        }
        return `/storage/${props.highlight.picture_url}`;
    }
    return "https://placehold.co/600x400?text=SoundScape";
});
</script>

<template>
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <img
                class="w-full h-36 object-cover"
                :src="pictureUrl"
                :alt="`Flyer for ${props.highlight?.name}`"
            />
        <div class="p-4">
            <h2 class="text-lg font-semibold">{{ props.highlight?.name }}</h2>
            <p class="mt-2 text-gray-600">{{ props.highlight?.description }}</p>
        </div>
    </div>
</template>