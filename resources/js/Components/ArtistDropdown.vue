<script setup>
import { ref, computed, onMounted, onBeforeUnmount } from "vue";
import { CheckBadgeIcon, MagnifyingGlassIcon } from "@heroicons/vue/24/solid";

// -------- PROPS & EMITS --------
const props = defineProps({
    modelValue: [String, Number],
    options: {
        type: Array,
        required: true,
    },
    placeholder: {
        type: String,
        default: "Select Artist",
    },
    isDarkMode: {
        type: Boolean,
        default: false,
    },
});

const emit = defineEmits(["update:modelValue"]);

// -------- DROPDOWN STATE & LOGIC --------
const isOpen = ref(false);
const dropdown = ref(null);
const searchQuery = ref("");

/**
 * Closes the dropdown if a click is detected outside of the component.
 */
const handleClickOutside = (event) => {
    if (dropdown.value && !dropdown.value.contains(event.target)) {
        isOpen.value = false;
    }
};

onMounted(() => {
    document.addEventListener("click", handleClickOutside);
});

onBeforeUnmount(() => {
    document.removeEventListener("click", handleClickOutside);
});

/**
 * Selects an option, emits the new value, and closes the dropdown.
 * @param {object} option - The selected artist option.
 */
function selectOption(option) {
    if (option) {
        emit("update:modelValue", option.value);
    }
    isOpen.value = false;
    searchQuery.value = ""; // Reset search query on selection
}

/**
 * Filters artist options based on the search query.
 */
const filteredOptions = computed(() => {
    if (!searchQuery.value) {
        return props.options;
    }
    const lowerCaseQuery = searchQuery.value.toLowerCase();
    return props.options.filter((option) =>
        option.name.toLowerCase().includes(lowerCaseQuery)
    );
});

/**
 * Finds the full object for the currently selected artist.
 */
const selectedOption = computed(() => {
    return props.options.find((option) => option.value === props.modelValue);
});
</script>

<template>
    <div ref="dropdown" class="relative">
        <div @click="isOpen = !isOpen" class="cursor-pointer">
            <slot name="button-face" :selectedOption="selectedOption">
                <div
                    class="flex items-center w-full min-h-[44px] rounded py-2 px-3 text-left text-sm"
                >
                    <div
                        v-if="selectedOption"
                        class="flex items-center space-x-2"
                    >
                        <img
                            :src="selectedOption?.picture_url || picturePlaceholder(selectedOption)"
                            :alt="selectedOption?.name"
                            class="h-6 w-6 rounded-full object-cover"
                        />
                        <span class="font-medium">{{
                            selectedOption?.name
                        }}</span>
                    </div>
                    <span v-else class="font-normal text-text-medium">
                        {{ placeholder }}
                    </span>
                </div>
            </slot>
        </div>

        <!-- Dropdown Panel -->
        <transition
            enter-active-class="transition ease-out duration-200"
            enter-from-class="transform opacity-0 scale-95"
            enter-to-class="transform opacity-100 scale-100"
            leave-active-class="transition ease-in duration-150"
            leave-from-class="transform opacity-100 scale-100"
            leave-to-class="transform opacity-0 scale-95"
        >
            <div
                v-if="isOpen"
                class="custom-scrollbar absolute z-10 mt-1 max-h-96 w-full min-w-[320px] overflow-auto rounded-lg bg-card text-sm shadow-2xl ring-1 ring-background focus:outline-none"
            >
                <!-- Search Input -->
                <div class="sticky top-0 z-10 bg-card/80 backdrop-blur-sm p-2">
                    <div class="relative">
                        <span
                            class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3"
                        >
                            <MagnifyingGlassIcon
                                class="h-5 w-5 text-text-medium"
                                aria-hidden="true"
                            />
                        </span>
                        <input
                            type="text"
                            v-model="searchQuery"
                            @click.stop
                            placeholder="Search..."
                            class="block w-full rounded-md border-0 bg-background py-2 pl-10 pr-3 ring-0 ring-inset placeholder: focus:ring-0"
                        />
                    </div>
                </div>

                <!-- Artists List -->
                <div
                    v-if="filteredOptions.length > 0"
                    class="flex flex-col p-1"
                >
                    <div
                        v-for="option in filteredOptions"
                        :key="option.value"
                        @click="selectOption(option)"
                        class="flex items-center w-full p-2 rounded-lg hover:bg-background cursor-pointer transition-colors"
                    >
                        <img
                            :src="option.picture_url || picturePlaceholder(option)"
                            :alt="option.name"
                            class="h-10 w-10 rounded-full object-cover"
                        />
                        <span class="ml-2 text-sm font-medium uppercase">
                            {{ option.name }}
                        </span>
                        <CheckBadgeIcon
                            v-if="modelValue === option.value"
                            class="h-6 w-6 text-primary ml-auto"
                        />
                    </div>
                </div>

                <!-- No Results Message -->
                <div v-else class="px-4 py-8 text-text-medium text-center">
                    <p class="font-normal">No results found</p>
                    <p v-if="searchQuery" class="text-xs">
                        Try a different search term.
                    </p>
                </div>
            </div>
        </transition>
    </div>
</template>

<style scoped>
/* --- Base Scrollbar Styles --- */
.custom-scrollbar {
    scrollbar-width: thin;
}

.custom-scrollbar::-webkit-scrollbar {
    width: 6px;
    height: 6px;
}

.custom-scrollbar::-webkit-scrollbar-track {
    background: transparent;
}

.custom-scrollbar::-webkit-scrollbar-thumb {
    border-radius: 10px;
}

/* --- Light Mode Scrollbar --- */
.custom-scrollbar {
    scrollbar-color: #a0aec0 #f1f5f9; /* thumb track */
}
.custom-scrollbar::-webkit-scrollbar-thumb {
    background-color: #a0aec0;
}

/* --- Dark Mode Scrollbar --- */
/* Applied when a parent element has the .dark class */
.dark .custom-scrollbar {
    scrollbar-color: #4a5568 #1f2937; /* thumb track */
}
.dark .custom-scrollbar::-webkit-scrollbar-thumb {
    background-color: #4a5568;
}
</style>
