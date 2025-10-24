<script setup>
import { Link } from "@inertiajs/vue3";
</script>

<template>
    <div class="absolute -z-10 w-full h-full">
        <img
            v-bind="$attrs"
            src="/images/TempConcertBackground.jpg"
            alt="Background"
            class="block dark:hidden w-full h-full object-cover lg:object-fill"
        />
        <img
            v-bind="$attrs"
            src="/images/TempConcertBackground.jpg"
            alt="Background"
            class="hidden dark:block w-full h-full object-cover lg:object-fill"
        />
    </div>
</template>
