<script setup>
import { onMounted, ref, watchEffect } from "vue";
import { Head, Link, router } from "@inertiajs/vue3";
import ApplicationMark from "@/Components/ApplicationMark.vue";
import Banner from "@/Components/Banner.vue";
import Dropdown from "@/Components/Dropdown.vue";
import DropdownLink from "@/Components/DropdownLink.vue";
import NavLink from "@/Components/NavLink.vue";
import ResponsiveNavLink from "@/Components/ResponsiveNavLink.vue";

defineProps({
    title: String,
    canLogin: Boolean,
    canRegister: Boolean,
});

const showingNavigationDropdown = ref(false);

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
    <div>
        <Head :title="title" />

        <Banner />

        <div class="min-h-screen bg-background dark:bg-background">
            <nav
                class="bg-card dark:bg-card border-b border-primary-low dark:border-primary-low shadow-md shadow-primary-low"
            >
                <!-- Primary Navigation Menu -->
                <div class="max-w-7xl mx-auto px-6 sm:px-8 lg:px-10">
                    <div class="flex justify-between h-16">
                        <!-- Logo -->
                        <div class="shrink-0 flex items-center">
                            <Link :href="route('index')">
                                <ApplicationMark />
                            </Link>
                        </div>

                        <!-- Navigation Links -->
                        <div
                            class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex pt-1"
                        >
                            <NavLink
                                :href="route('index')"
                                :active="route().current('index')"
                            >
                                <p class="font-bold">Home</p>
                            </NavLink>
                        </div>

                        <div class="hidden sm:flex sm:items-center sm:ms-6">
                            <!-- Settings Dropdown -->
                            <div class="ms-3 relative">
                                <Dropdown align="right" width="48">
                                    <template #trigger>
                                        <span class="inline-flex rounded-md">
                                            <button
                                                type="button"
                                                class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-primary bg-white hover:bg-primary hover:text-card dark:hover:bg-primary dark:hover:text-card focus:outline-none focus:bg-gray-50 bg-card dark:bg-card active:bg-gray-50 transition ease-in-out duration-150"
                                            >
                                                <svg
                                                    class="-ms-0.5 -me-0.5 size-4"
                                                    xmlns="http://www.w3.org/2000/svg"
                                                    fill="none"
                                                    viewBox="0 0 24 24"
                                                    stroke-width="4"
                                                    stroke="currentColor"
                                                >
                                                    <path
                                                        stroke-linecap="round"
                                                        stroke-linejoin="round"
                                                        d="M19.5 8.25l-7.5 7.5-7.5-7.5"
                                                    />
                                                </svg>
                                            </button>
                                        </span>
                                    </template>

                                    <template #content>
                                        <!-- Account Management -->
                                        <div
                                            class="block px-4 py-2 text-xs text-gray-400"
                                        >
                                            Management
                                        </div>

                                        <button
                                            @click="toggleDarkMode"
                                            class="block w-full px-4 py-2 text-start text-sm leading-5 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800 focus:outline-none focus:bg-gray-100 dark:focus:bg-gray-800 transition duration-150 ease-in-out"
                                        >
                                            <span v-if="darkMode"
                                                >Light Mode</span
                                            >
                                            <span v-else>Dark Mode</span>
                                        </button>

                                        <div class="border-t border-gray-200" />

                                        <!-- Authentication -->
                                        <DropdownLink :href="route('login')">
                                            Log in
                                        </DropdownLink>
                                    </template>
                                </Dropdown>
                            </div>
                        </div>

                        <!-- Hamburger -->
                        <div class="-me-2 flex items-center sm:hidden">
                            <button
                                class="inline-flex items-center justify-center p-2 rounded-md text-primary hover:text-text hover:bg-primary-low focus:outline-none transition duration-150 ease-in-out"
                                @click="
                                    showingNavigationDropdown =
                                        !showingNavigationDropdown
                                "
                            >
                                <svg
                                    class="size-6"
                                    stroke="currentColor"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        :class="{
                                            hidden: showingNavigationDropdown,
                                            'inline-flex':
                                                !showingNavigationDropdown,
                                        }"
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M4 6h16M4 12h16M4 18h16"
                                    />
                                    <path
                                        :class="{
                                            hidden: !showingNavigationDropdown,
                                            'inline-flex':
                                                showingNavigationDropdown,
                                        }"
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12"
                                    />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Responsive Navigation Menu -->
                <div
                    :class="{
                        block: showingNavigationDropdown,
                        hidden: !showingNavigationDropdown,
                    }"
                    class="sm:hidden"
                >
                    <div class="pt-2 pb-3 space-y-1">
                        <ResponsiveNavLink
                            :href="route('index')"
                            :active="route().current('index')"
                        >
                            Home
                        </ResponsiveNavLink>
                    </div>

                    <!-- Responsive Settings Options -->
                    <div class="pt-4 pb-1 border-t border-gray-200">
                        <div class="mt-3 space-y-1">
                            <ResponsiveNavLink
                                as="button"
                                @click="toggleDarkMode"
                            >
                                <span v-if="darkMode">Light Mode</span>
                                <span v-else>Dark Mode</span>
                            </ResponsiveNavLink>
                            <ResponsiveNavLink
                                :href="route('login')"
                                :active="route().current('login')"
                            >
                                Login
                            </ResponsiveNavLink>
                        </div>
                    </div>
                </div>
            </nav>

            <!-- Page Content -->
            <main>
                <slot />
            </main>
        </div>
    </div>
</template>
