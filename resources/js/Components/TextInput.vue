<script setup>
import { onMounted, ref, computed } from 'vue';
import { EyeIcon, EyeSlashIcon } from '@heroicons/vue/24/outline';

defineOptions({
  inheritAttrs: false
});

const props = defineProps({
    modelValue: String,
    type: {
        type: String,
        default: 'text',
    },
});

defineEmits(['update:modelValue']);

const isPasswordVisible = ref(false);

const input = ref(null);

onMounted(() => {
    if (input.value.hasAttribute('autofocus')) {
        input.value.focus();
    }
});

defineExpose({ focus: () => input.value.focus() });

function togglePasswordVisibility() {
    isPasswordVisible.value = !isPasswordVisible.value;
}

const computedType = computed(() => {
    if (props.type === 'password') {
        return isPasswordVisible.value ? 'text' : 'password';
    }
    return props.type;
});
</script>

<template>
    <div class="relative">
        <input
            ref="input"
            v-bind="$attrs"
            class="bg-background-medium border-card-medium focus:border-card-medium focus:ring-0 rounded-md shadow-inner w-full"
            :class="{ 'pr-10': type === 'password' }"
            :type="computedType"
            :value="modelValue"
            @input="$emit('update:modelValue', $event.target.value)"
        >
        <button
            v-if="type === 'password'"
            type="button"
            class="absolute inset-y-0 right-0 flex items-center px-3 text-text-medium rounded-r-md"
            aria-label="Toggle password visibility"
            @click="togglePasswordVisibility"
        >
            <EyeIcon v-if="!isPasswordVisible" class="h-5 w-5" aria-hidden="true" />
            <EyeSlashIcon v-else class="h-5 w-5" aria-hidden="true" />
        </button>
    </div>
</template>