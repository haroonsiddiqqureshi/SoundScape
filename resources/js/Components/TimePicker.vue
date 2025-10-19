<script setup>
import { inject } from "vue";
import VueDatePicker from "@vuepic/vue-datepicker";

const props = defineProps({
    modelValue: [Object, String], // Can still be object or string
    placeholder: {
        type: String,
    },
});

const emit = defineEmits(["update:modelValue"]);

const isDarkMode = inject("isDarkMode");

// The 'format' function is no longer needed
// because we will use the 'value' from the slot
</script>

<template>
    <div
        :style="{ width: !modelValue ? '125px' : '90px' }"
        class="transition-all duration-300"
    >
        <VueDatePicker
            :model-value="modelValue"
            @update:model-value="emit('update:modelValue', $event)"
            time-picker
            :is-24="true"
            :dark="isDarkMode"
            auto-apply
            input-class-name="!hidden"
            model-type="HH:mm"
        >
            <template #dp-input="{ value }">
                <div
                    class="cursor-pointer bg-background rounded-md border-none focus:ring-transparent px-3 py-2"
                >
                    <span
                        class="block truncate"
                        :class="{ 'text-text-medium font-normal': !value }"
                    >
                        {{
                            value
                                ? value.replace(":", " : ")
                                : props.placeholder
                        }}
                    </span>
                </div>
            </template>
        </VueDatePicker>
    </div>
</template>
