<script setup>
import { inject } from "vue";
import VueDatePicker from "@vuepic/vue-datepicker";

const props = defineProps({
    modelValue: [Object, String],
    placeholder: {
        type: String,
    },
    error: {
        type: [String, Boolean],
        default: false,
    },
});

const emit = defineEmits(["update:modelValue"]);

const isDarkMode = inject("isDarkMode");
</script>

<template>
    <div
        :style="{ width: !modelValue ? '130px' : '90px' }"
        class="transition-all duration-300"
    >
        <VueDatePicker
            :model-value="props.modelValue"
            @update:model-value="emit('update:modelValue', $event)"
            time-picker
            :is-24="true"
            :dark="isDarkMode"
            auto-apply
            input-class-name="!hidden"
            model-type="HH:mm:ss"
            :start-time="{ hours: 0, minutes: 0 }"
        >
            <template #dp-input="{ value }">
                <div
                    class="cursor-pointer bg-background rounded-md border-none focus:ring-transparent px-3 py-2"
                    :class="{
                        'outline-dashed outline-primary -outline-offset-4 rounded-md':
                            error,
                    }"
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
