<script setup>
defineProps({
    message: String,
});
</script>

<template>
    <div v-show="message">
        <div class="bg-card p-4 rounded-md outline-dashed -outline-offset-4 text-primary">
            <p class="text-sm text-primary font-semibold">
                {{ message }}
            </p>
        </div>
    </div>
</template>
