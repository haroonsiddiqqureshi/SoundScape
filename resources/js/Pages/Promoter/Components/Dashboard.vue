<script setup>
import { defineProps } from "vue";

const props = defineProps({
    promoter: Object,
});
</script>

<template>
    <div class="p-6 bg-card rounded-md">
        <h2 class="text-lg font-medium">
            Welcome, {{ props.promoter.fullname }}!
        </h2>
        <p class="mt-1 text-sm text-text-medium">
            Your account is verified. You can now manage your concerts and profile.
        </p>
    </div>
</template>