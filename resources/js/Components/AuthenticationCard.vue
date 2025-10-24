<script setup>
import { onMounted, ref, watchEffect } from "vue";
import { Link } from "@inertiajs/vue3";
import { MoonIcon, SunIcon } from "@heroicons/vue/24/solid";

const darkMode = ref(false);

onMounted(() => {
    if (
        localStorage.theme === "dark" ||
        (!("theme" in localStorage) &&
            window.matchMedia("(prefers-color-scheme: dark)").matches)
    ) {
        darkMode.value = true;
    } else {
        darkMode.value = false;
    }
});

const toggleDarkMode = () => {
    darkMode.value = !darkMode.value;
    if (darkMode.value) {
        localStorage.theme = "dark";
    } else {
        localStorage.theme = "light";
    }
};

watchEffect(() => {
    if (darkMode.value) {
        document.documentElement.classList.add("dark");
    } else {
        document.documentElement.classList.remove("dark");
    }
});
</script>

<template>
    <div
        class="min-h-screen flex justify-center items-center pt-0 bg-card-high"
    >
        <slot name="background" />

        <button
            @click="toggleDarkMode"
            class="absolute top-4 right-4 bg-card p-2 rounded-md hover:text-primary focus:outline-none transition duration-200 ease-in-out"
        >
            <SunIcon v-if="darkMode" class="h-6 w-6" />
            <MoonIcon v-else class="h-6 w-6" />
        </button>
        
        <div
            class="w-full max-w-md mt-6 px-8 py-6 bg-card shadow-2xl overflow-hidden rounded-lg ring-background"
        >
            <div class="flex justify-center">
                <Link :href="'/'" class="flex flex-col items-center">
                    <slot name="logo" />
                </Link>
            </div>
            <slot />
        </div>
    </div>
</template>
