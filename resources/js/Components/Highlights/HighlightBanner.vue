<script setup>
import { ref, computed, onMounted, onUnmounted , inject} from "vue";
import {
    ChevronDoubleLeftIcon,
    ChevronDoubleRightIcon,
} from "@heroicons/vue/24/solid";

const props = defineProps({
    highlights: Object,
});

const isDarkMode = inject("isDarkMode");

const getPictureUrl = (highlight) => {
    if (highlight?.picture_url) {
        if (highlight.picture_url.startsWith("http")) {
            return highlight.picture_url;
        }
        return `/storage/${highlight.picture_url}`;
    }
    return isDarkMode.value
        ? "https://placehold.co/600x400/1a1a2e/ffffff?text=SoundScape"
        : "https://placehold.co/600x400/ffffff/000000?text=SoundScape";
};

const currentIndex = ref(0);
const highlightsKeys = computed(() => {
    return props.highlights && typeof props.highlights === "object"
        ? Object.keys(props.highlights)
        : [];
});

let intervalId = null;
const INTERVAL_MS = 3000;

const startInterval = () => {
    stopInterval();
    intervalId = setInterval(() => {
        nextHighlight();
    }, INTERVAL_MS);
};

const stopInterval = () => {
    if (intervalId) {
        clearInterval(intervalId);
        intervalId = null;
    }
};

const resetTimer = () => {
    stopInterval();
    startInterval();
};

const nextHighlight = () => {
    if (highlightsKeys.value.length > 0) {
        currentIndex.value =
            (currentIndex.value + 1) % highlightsKeys.value.length;
    }
    resetTimer();
};

const previousHighlight = () => {
    if (highlightsKeys.value.length > 0) {
        currentIndex.value =
            (currentIndex.value - 1 + highlightsKeys.value.length) %
            highlightsKeys.value.length;
    }
    resetTimer();
};

const selectIndex = (index) => {
    currentIndex.value = index;
    resetTimer();
};

onMounted(() => startInterval());
onUnmounted(() => stopInterval());
</script>

<template>
    <div v-if="highlightsKeys.length > 0" class="relative flex text-primary-low">
        <button
            @click="previousHighlight"
            class="absolute top-1/2 p-2 xl:p-0 left-0 xl:-left-12 transform -translate-y-1/2 h-full bg-card-low xl:bg-transparent text-primary z-10 transition-all duration-300"
        >
            <ChevronDoubleLeftIcon
                class="w-6 h-6 sm:w-8 sm:h-8 stroke-current"
            />
        </button>
        <div class="shadow-md rounded-lg overflow-hidden flex-1">
            <div
                class="flex max-h-96 transition-transform duration-500 ease-in-out"
                :style="{ transform: `translateX(-${currentIndex * 100}%)` }"
            >
                <div
                    v-for="key in highlightsKeys"
                    :key="key"
                    class="w-full flex-shrink-0"
                >
                    <img
                        :src="getPictureUrl(highlights[key])"
                        :alt="highlights[key].name"
                        class="w-full h-full object-cover block"
                    />
                </div>
            </div>
        </div>
        <button
            @click="nextHighlight"
            class="absolute top-1/2 p-2 xl:p-0 right-0 xl:-right-12 transform -translate-y-1/2 h-full bg-card-low xl:bg-transparent text-primary z-10 transition-all duration-300"
        >
            <ChevronDoubleRightIcon
                class="w-6 h-6 sm:w-8 sm:h-8 stroke-current"
            />
        </button>
        <div
            class="absolute -bottom-6 left-1/2 transform -translate-x-1/2 flex space-x-2"
        >
            <button
                v-for="(key, index) in highlightsKeys"
                :key="key"
                @click="selectIndex(index)"
                :class="{
                    'w-3 h-3 rounded-full bg-primary-medium shadow-inner shadow-primary':
                        index === currentIndex,
                    'w-3 h-3 rounded-full bg-primary-low':
                        index !== currentIndex,
                }"
                class="cursor-pointer focus:outline-none"
                :aria-label="`Show highlight ${index + 1}`"
            ></button>
        </div>
    </div>
    <div v-else class="shadow-md rounded-lg overflow-hidden flex-1">
        <div class="flex max-h-96">
            <div class="w-full">
                <img
                    :src="getPictureUrl()"
                    alt="SoundScape"
                    class="w-full h-full object-cover block"
                />
            </div>
        </div>
    </div>
</template>
