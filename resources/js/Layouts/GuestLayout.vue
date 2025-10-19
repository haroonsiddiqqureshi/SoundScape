<script setup>
import { onMounted, ref, watchEffect } from "vue";
import { Head, Link } from "@inertiajs/vue3";
import ApplicationLogo from "@/Components/ApplicationLogo.vue";
import Banner from "@/Components/Banner.vue";
import NavLink from "@/Components/NavLink.vue";
import ResponsiveNavLink from "@/Components/ResponsiveNavLink.vue";
import Footer from "@/Components/Footer.vue";
import {
    Bars3Icon,
    XMarkIcon,
    MagnifyingGlassIcon,
    SunIcon,
    MoonIcon,
} from "@heroicons/vue/24/solid";

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
    <Head :title="title" />

    <Banner />

    <nav class="sticky top-0 bg-card border-b border-primary-low shadow-md shadow-primary-low z-20">
        <!-- Primary Navigation Menu -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex">
                    <!-- Logo -->
                    <div class="shrink-0 flex items-center">
                        <Link :href="route('index')">
                            <ApplicationLogo />
                        </Link>
                    </div>

                    <!-- Navigation Links -->
                    <div
                        class="hidden space-x-8 sm:-my-[2px] sm:ms-10 sm:flex font-semibold"
                    >
                        <NavLink
                            :href="route('index')"
                            :active="route().current('index')"
                        >
                            Home
                        </NavLink>
                        <NavLink
                            :href="route('index')"
                            :active="route().current('map')"
                        >
                            Map
                        </NavLink>
                    </div>
                </div>

                <!-- Search Bar -->
                <div
                    class="flex-1 flex items-center justify-end px-2 ms-10 group"
                >
                    <div
                        class="max-w-xs w-full lg:max-w-xs group-focus-within:max-w-full transition-all duration-300 ease-in-out"
                    >
                        <label for="search" class="sr-only">Search</label>
                        <div class="relative">
                            <div
                                class="absolute inset-y-0 left-0 pl-3 pr-3 flex items-center pointer-events-none group-focus-within:bg-primary rounded transition duration-150 ease-in-out"
                            >
                                <MagnifyingGlassIcon
                                    class="h-5 w-5 text-text-high group-focus-within:text-card group-focus-within:stroke-card"
                                    aria-hidden="true"
                                />
                            </div>
                            <input
                                id="search"
                                name="search"
                                class="block w-full pl-10 pr-3 py-2 border border-transparent rounded-md leading-5 bg-background text-text placeholder-text-medium focus:pl-12 focus:bg-card focus:border-primary focus:ring-primary sm:text-xs"
                                placeholder="Search"
                                type="search"
                            />
                        </div>
                    </div>
                </div>

                <div class="hidden sm:flex sm:items-center">
                    <!-- Dark Mode Toggle -->
                    <button
                        @click="toggleDarkMode"
                        class="me-4 inline-flex items-center justify-center p-2 rounded-md text-primary hover:text-text hover:bg-primary-low focus:outline-none transition duration-150 ease-in-out"
                    >
                        <SunIcon v-if="darkMode" class="h-6 w-6" />
                        <MoonIcon v-else class="h-6 w-6" />
                    </button>

                    <!-- Authentication Links -->
                    <template v-if="canLogin">
                        <Link
                            v-if="$page.props.auth.user"
                            :href="route('dashboard')"
                            class="font-semibold text-text-high"
                            >Dashboard</Link
                        >

                        <template v-else>
                            <Link
                                :href="route('login')"
                                class="font-semibold text-text-high"
                                >Log in</Link
                            >

                            <Link
                                v-if="canRegister"
                                :href="route('register')"
                                class="ms-4 font-semibold text-text-high"
                                >Register</Link
                            >
                        </template>
                    </template>
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
                        <Bars3Icon
                            v-if="!showingNavigationDropdown"
                            class="w-6 h-6 stroke-current"
                        />
                        <XMarkIcon v-else class="w-6 h-6 stroke-current" />
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
                <ResponsiveNavLink
                    :href="route('index')"
                    :active="route().current('map')"
                >
                    Map
                </ResponsiveNavLink>
            </div>

            <!-- Responsive Settings Options -->
            <div
                class="pt-4 pb-1 border-t border-gray-200"
            >
                <div class="space-y-1">
                    <ResponsiveNavLink as="button" @click="toggleDarkMode">
                        <span v-if="darkMode">Light Mode</span>
                        <span v-else>Dark Mode</span>
                    </ResponsiveNavLink>

                    <template v-if="canLogin">
                        <ResponsiveNavLink
                            v-if="!$page.props.auth.user"
                            :href="route('login')"
                            :active="route().current('login')"
                        >
                            Log in
                        </ResponsiveNavLink>
                        <ResponsiveNavLink
                            v-if="canRegister && !$page.props.auth.user"
                            :href="route('register')"
                            :active="route().current('register')"
                        >
                            Register
                        </ResponsiveNavLink>
                    </template>
                </div>
            </div>
        </div>
    </nav>

    <!-- Page Content -->
    <main>
        <slot />
    </main>

    <!-- Footer -->
    <Footer />
</template>
