<script setup>
import { computed } from "vue";
import ApplicationLogo from "./ApplicationLogo.vue";

const currentYear = computed(() => new Date().getFullYear());
</script>

<template>
    <footer>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center my-auto space-x-3">
                    <!-- Logo -->
                    <div class="flex items-center space-x-2">
                        <ApplicationLogo
                            class="block h-8 rounded-md text-text"
                        />
                        <span
                            class="flex justify-center text-text font-bold tracking-wide text-2xl uppercase"
                            >SoundScape</span
                        >
                    </div>
                    <div class="text-sm">© {{ currentYear }}</div>
                </div>
                <div class="flex items-center space-x-4">
                    <p class="text-sm">Follow Us</p>
                    <div class="flex items-center space-x-3">
                        <!-- Facebook -->
                        <a
                            href="https://facebook.com"
                            target="_blank"
                            rel="noopener noreferrer"
                            aria-label="Follow us on Facebook"
                            class="text-text-low hover:text-text"
                        >
                            <svg
                                class="w-5 h-5"
                                viewBox="0 0 24 24"
                                fill="currentColor"
                                aria-hidden="true"
                            >
                                <path
                                    d="M22 12a10 10 0 10-11.5 9.9v-7h-2.2v-2.9h2.2V9.2c0-2.2 1.3-3.4 3.3-3.4.9 0 1.8.1 1.8.1v2h-1c-1 0-1.3.6-1.3 1.2v1.5h2.2l-.4 2.9h-1.8v7A10 10 0 0022 12z"
                                />
                            </svg>
                        </a>

                        <!-- Instagram -->
                        <a
                            href="https://instagram.com"
                            target="_blank"
                            rel="noopener noreferrer"
                            aria-label="Follow us on Instagram"
                            class="text-text-low hover:text-text"
                        >
                            <svg
                                class="w-5 h-5"
                                viewBox="0 0 24 24"
                                fill="currentColor"
                                aria-hidden="true"
                            >
                                <path
                                    d="M7 2h10a5 5 0 015 5v10a5 5 0 01-5 5H7a5 5 0 01-5-5V7a5 5 0 015-5zm5 5.5A4.5 4.5 0 107 12a4.5 4.5 0 005-4.5zM18.5 6.1a1.1 1.1 0 11-2.2 0 1.1 1.1 0 012.2 0z"
                                />
                            </svg>
                        </a>

                        <!-- X / Twitter -->
                        <a
                            href="https://x.com"
                            target="_blank"
                            rel="noopener noreferrer"
                            aria-label="Follow us on X"
                            class="text-text-low hover:text-text"
                        >
                            <svg
                                class="w-5 h-5"
                                viewBox="0 0 24 24"
                                fill="currentColor"
                                aria-hidden="true"
                            >
                                <path
                                    d="M22 4.01c-.7.31-1.46.52-2.26.61.81-.49 1.44-1.27 1.74-2.2-.76.45-1.6.78-2.49.96A3.92 3.92 0 0016.5 2c-2.17 0-3.93 1.8-3.93 4.02 0 .32.04.63.11.93-3.26-.16-6.15-1.78-8.09-4.22-.34.58-.54 1.26-.54 1.98 0 1.37.7 2.57 1.76 3.27-.64-.02-1.24-.2-1.77-.5v.05c0 1.9 1.36 3.49 3.16 3.85-.33.1-.68.16-1.04.16-.25 0-.5-.02-.74-.07.5 1.56 1.95 2.7 3.67 2.73A7.86 7.86 0 012 19.54 11.08 11.08 0 007.29 21c7.55 0 11.68-6.37 11.68-11.9 0-.18 0-.36-.01-.54.8-.59 1.45-1.32 1.98-2.16-.73.33-1.52.56-2.34.66z"
                                />
                            </svg>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </footer>
</template>
