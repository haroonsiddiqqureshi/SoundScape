<script setup>
import { ref, computed, onMounted, onBeforeUnmount } from "vue";
import {
    ChevronDownIcon,
    CheckIcon,
    MagnifyingGlassIcon,
} from "@heroicons/vue/24/solid";

const props = defineProps({
    modelValue: [String, Number],
    options: {
        type: Array,
        required: true,
    },
    placeholder: {
        type: String,
        default: "",
    },
    isSearchable: {
        type: Boolean,
        default: false,
    },
});

const emit = defineEmits(["update:modelValue", "search-change"]);

const isOpen = ref(false);
const dropdown = ref(null);
const searchInput = ref("");

const selectedOption = computed(() => {
    return props.options.find((option) => option.value === props.modelValue);
});

function selectOption(optionValue) {
    emit("update:modelValue", optionValue);
    isOpen.value = false;
    if (props.isSearchable) {
        searchInput.value = "";
        emit("search-change", "");
    }
}

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
</script>

<template>
    <div
        :class="[
            'transition-all duration-300 ease-in-out',
            isOpen ? 'grow' : 'grow-0',
        ]"
    >
        <div ref="dropdown" class="relative">
            <button
                type="button"
                @click="isOpen = !isOpen"
                class="relative w-full cursor-pointer rounded-lg bg-background py-2 pl-3 pr-10 text-left text-sm font-medium ring-1 ring-inset ring-background focus:outline-none"
            >
                <span
                    class="block truncate"
                    :class="{ 'font-normal text-text-medium': !selectedOption }"
                >
                    {{ selectedOption ? selectedOption.name : placeholder }}
                </span>
                <span
                    class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-2"
                >
                    <ChevronDownIcon
                        class="h-4 w-4 text-text-medium transition-transform duration-200"
                        :class="{ 'transform rotate-180': isOpen }"
                    />
                </span>
            </button>

            <transition
                enter-active-class="transition ease-out duration-300"
                enter-from-class="transform opacity-0 scale-95"
                enter-to-class="transform opacity-100 scale-100"
                leave-active-class="transition ease-in duration-300"
                leave-from-class="transform opacity-100 scale-100"
                leave-to-class="transform opacity-0 scale-95"
            >
                <div
                    v-if="isOpen"
                    class="custom-scrollbar absolute z-10 mt-1 max-h-80 w-full overflow-auto rounded-md bg-card text-sm shadow-2xl ring-1 ring-background focus:outline-none"
                >
                    <div
                        v-if="isSearchable"
                        class="sticky top-0 z-10 bg-card/80 backdrop-blur-sm p-2"
                    >
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
                                v-model="searchInput"
                                @input="
                                    $emit('search-change', $event.target.value)
                                "
                                @click.stop
                                placeholder="Search..."
                                class="block w-full font-normal rounded-md border-0 bg-background py-2 pl-10 pr-3 ring-0 ring-inset focus:ring-0"
                            />
                        </div>
                    </div>

                    <div
                        v-for="option in options"
                        :key="option.value"
                        @click="selectOption(option.value)"
                        class="relative cursor-pointer py-2 px-6 font-normal hover:text-secondary hover:text-lg transition-all duration-150"
                    >
                        <span
                            class="block truncate"
                            :class="{
                                'font-medium': modelValue === option.value,
                            }"
                        >
                            {{ option.name }}
                        </span>
                        <span
                            v-if="modelValue === option.value"
                            class="absolute inset-y-0 left-0 flex items-center pl-3 text-secondary"
                        >
                        </span>
                    </div>

                    <div
                        v-if="options.length === 0 && isSearchable"
                        class="px-4 py-2 text-text-medium text-center"
                    >
                        No results found
                    </div>
                </div>
            </transition>
        </div>
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
    scrollbar-color: #A0AEC0 #F1F5F9; /* thumb track */
}
.custom-scrollbar::-webkit-scrollbar-thumb {
    background-color: #A0AEC0;
}

/* --- Dark Mode Scrollbar --- */
/* Applied when a parent element has the .dark class */
.dark .custom-scrollbar {
    scrollbar-color: #4A5568 #1F2937; /* thumb track */
}
.dark .custom-scrollbar::-webkit-scrollbar-thumb {
    background-color: #4A5568;
}
</style>