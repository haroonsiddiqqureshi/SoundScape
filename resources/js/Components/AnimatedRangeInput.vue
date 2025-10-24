<script setup>
import { watch } from "vue";

const props = defineProps({
    triggerValue: {
        type: [String, Number, Date, null],
        required: true,
    },
    endValue: {
        type: [String, Number, Date, null],
        default: null,
    },
    separator: {
        type: String,
        default: "-",
    },
    isLong: {
        type: Boolean,
        default: false,
    },
});

const emit = defineEmits(["update:endValue"]);

watch(
    () => props.triggerValue,
    (newTriggerValue) => {
        if (!newTriggerValue) {
            emit("update:endValue", null);
        }
    }
);
</script>

<template>
    <div class="flex space-x-2 items-start text-sm font-semibold">
        <slot name="icon"></slot>

        <div class="flex flex-1 space-x-2">
            <slot name="startInput"></slot>

            <Transition
                enter-from-class="opacity-0 transform -translate-x-2"
                enter-active-class="transition-all duration-300 ease-in-out"
                leave-to-class="opacity-0 transform -translate-x-2"
                leave-active-class="transition-all duration-300 ease-in-out"
            >
                <div
                    v-if="triggerValue"
                    class="flex items-center space-x-2 grow"
                >
                    <span v-if="!isLong" class="text-text font-semibold">{{
                        separator
                    }}</span>
                    <div class="grow">
                        <slot name="endInput"></slot>
                    </div>
                </div>
            </Transition>
        </div>
    </div>
</template>
