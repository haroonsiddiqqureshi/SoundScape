<script setup>
import { inject, ref } from "vue";
import VueDatePicker from "@vuepic/vue-datepicker";

const props = defineProps({
    modelValue: [Date, String],
    placeholder: {
        type: String,
    },
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

const thaiDayNames = ref(["จ", "อ", "พ", "พฤ", "ศ", "ส", "อา"]);

// Calculate year range
const currentYear = new Date().getFullYear() - 1;
const yearRange = [currentYear, currentYear + 11];

const format = (date) => {
    if (!date) {
        return props.placeholder;
    }
    return new Intl.DateTimeFormat("th-TH", { dateStyle: "medium" }).format(
        new Date(date)
    );
};
</script>

<template>
    <div
        :style="{ width: !modelValue ? '160px' : '130px' }"
        class="transition-all duration-300"
    >
        <VueDatePicker
            :model-value="modelValue"
            @update:model-value="emit('update:modelValue', $event)"
            locale="th"
            month-name-format="long"
            :day-names="thaiDayNames"
            :enable-time-picker="false"
            :dark="isDarkMode"
            :min-date="minDate"
            :max-date="maxDate"
            :start-date="minDate"
            :year-range="yearRange"
            auto-apply
            input-class-name="!hidden"
            no-today
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
