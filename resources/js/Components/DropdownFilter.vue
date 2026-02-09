<script setup>
import { ref, computed, onMounted, onBeforeUnmount } from "vue";
import { ChevronDownIcon } from "@heroicons/vue/24/solid";

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
});

const emit = defineEmits(["update:modelValue"]);

const isOpen = ref(false);
const dropdown = ref(null);

const selectedOption = computed(() => {
    return props.options.find((option) => option.value === props.modelValue);
});

function selectOption(optionValue) {
    emit("update:modelValue", optionValue);
    isOpen.value = false;
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
                class="group relative w-full cursor-pointer rounded-full bg-card py-1 pl-3 pr-10 text-left text-sm font-semibold ring-0 focus:outline-none hover:bg-primary hover:text-white focus:bg-primary focus:text-white transition-colors duration-100"
            >
                <span
                    class="block truncate"
                    :class="{
                        'text-text group-hover:text-white group-focus:text-white': !selectedOption,
                    }"
                >
                    {{ selectedOption ? selectedOption.name : placeholder }}
                </span>
                <span
                    class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-2"
                >
                    <ChevronDownIcon
                        class="h-4 w-4 stroke-current text-text group-hover:text-white group-focus:text-white transition-transform duration-200"
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
                        v-for="option in options"
                        :key="option.value"
                        @click="selectOption(option.value)"
                        class="relative cursor-pointer py-2 px-6 font-normal hover:text-primary hover:font-bold hover:text-lg transition-all duration-150"
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
                            class="absolute inset-y-0 left-0 flex items-center pl-3 text-primary"
                        >
                        </span>
                    </div>
                </div>
            </transition>
        </div>
    </div>
</template>
