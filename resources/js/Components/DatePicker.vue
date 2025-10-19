<script setup>
import { inject } from "vue";
import VueDatePicker from "@vuepic/vue-datepicker";

const props = defineProps({
    modelValue: [Date, String],
    placeholder: {
        type: String,
    },
    // REMOVE the 'startDate' prop
    // ADD minDate and maxDate props
    minDate: {
        type: [Date, String, null],
        default: null,
    },
    maxDate: {
        type: [Date, String, null],
        default: null,
    },
});

const emit = defineEmits(["update:modelValue"]);

const isDarkMode = inject("isDarkMode");

const format = (date) => {
    if (!date) {
        return props.placeholder;
    }
    return new Intl.DateTimeFormat("en-US", { dateStyle: "long" }).format(
        new Date(date)
    );
};
</script>

<template>
    <div
        :style="{ width: !modelValue ? '175px' : '160px' }"
        class="transition-all duration-300"
    >
        <VueDatePicker
            :model-value="modelValue"
            @update:model-value="emit('update:modelValue', $event)"
            :enable-time-picker="false"
            :dark="isDarkMode"
            
            :min-date="minDate"
            :max-date="maxDate"
            
            auto-apply
            input-class-name="!hidden"
        >
            <template #dp-input="{ value }">
                <div
                    class="cursor-pointer bg-background rounded-md border-none focus:ring-transparent px-3 py-2"
                >
                    <span
                        class="block truncate"
                        :class="{ 'text-text-medium font-normal': !value }"
                    >
                        {{ format(value) }}
                    </span>
                </div>
            </template>
        </VueDatePicker>
    </div>
</template>