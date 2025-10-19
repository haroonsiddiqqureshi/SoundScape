<script setup>
import { onMounted, provide, ref, watchEffect } from "vue";
import { Head, Link, router, usePage } from "@inertiajs/vue3";
import ApplicationLogo from "@/Components/ApplicationLogo.vue";
import Banner from "@/Components/Banner.vue";
import Dropdown from "@/Components/Dropdown.vue";
import DropdownLink from "@/Components/DropdownLink.vue";
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

const page = usePage();
const showingNavigationDropdown = ref(false);
const darkMode = ref(false);

provide("isDarkMode", darkMode);

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

const logout = () => {
    router.post(route("logout"));
};
</script>

<template>
    <div>
        <Head :title="title" />

        <Banner />

        <div class="min-h-screen bg-background text-text">
            <nav
                class="sticky top-0 bg-card border-b border-primary-low shadow-md shadow-primary-low z-20"
            >
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex justify-between h-16">
                        <div class="flex">
                            <div class="shrink-0 flex items-center">
                                <Link :href="route('index')">
                                    <ApplicationLogo />
                                </Link>
                            </div>

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

                        <div
                            class="flex-1 flex items-center justify-end px-2 ms-10 group"
                        >
                            <div
                                class="max-w-xs w-full lg:max-w-xs group-focus-within:max-w-full transition-all duration-300 ease-in-out"
                            >
                                <label for="search" class="sr-only"
                                    >Search</label
                                >
                                <div class="relative">
                                    <div
                                        class="absolute inset-y-0 left-0 pl-3 pr-3 flex items-center pointer-events-none group-focus-within:bg-primary rounded transition duration-150 ease-in-out"
                                    >
                                        <MagnifyingGlassIcon
                                            class="h-5 w-5 text-text-medium group-focus-within:text-card group-focus-within:stroke-card"
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

                        <div class="hidden sm:flex sm:items-center space-x-4">
                            <button
                                @click="toggleDarkMode"
                                class="ms-2 inline-flex items-center justify-center rounded-md text-primary hover:text-text focus:outline-none transition duration-150 ease-in-out"
                            >
                                <SunIcon v-if="darkMode" class="h-6 w-6" />
                                <MoonIcon v-else class="h-6 w-6" />
                            </button>

                            <div
                                v-if="page.props.auth.user"
                                class="relative"
                            >
                                <Dropdown align="right" width="48">
                                    <template #trigger>
                                        <button
                                            v-if="
                                                $page.props.jetstream
                                                    .managesProfilePhotos
                                            "
                                            class="flex text-sm border-2 border-transparent rounded-full focus:outline-none focus:border-gray-300 transition"
                                        >
                                            <img
                                                class="size-8 rounded-full object-cover"
                                                :src="
                                                    $page.props.auth.user
                                                        .profile_photo_url
                                                "
                                                :alt="
                                                    $page.props.auth.user.name
                                                "
                                            />
                                        </button>

                                        <span
                                            v-else
                                            class="inline-flex rounded-md"
                                        >
                                            <button
                                                type="button"
                                                class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none focus:bg-gray-50 active:bg-gray-50 transition ease-in-out duration-150"
                                            >
                                                {{ $page.props.auth.user.name }}

                                                <svg
                                                    class="ms-2 -me-0.5 size-4"
                                                    xmlns="http://www.w3.org/2000/svg"
                                                    fill="none"
                                                    viewBox="0 0 24 24"
                                                    stroke-width="1.5"
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
                                        <div
                                            class="block px-4 py-2 text-xs text-gray-400"
                                        >
                                            Manage Account
                                        </div>
                                        <DropdownLink
                                            :href="route('user.profile.show')"
                                        >
                                            Profile
                                        </DropdownLink>
                                        <DropdownLink
                                            :href="route('promoter.index')"
                                        >
                                            Promoter Account
                                        </DropdownLink>
                                        <div class="border-t border-gray-200" />
                                        <form @submit.prevent="logout">
                                            <DropdownLink as="button">
                                                Log Out
                                            </DropdownLink>
                                        </form>
                                    </template>
                                </Dropdown>
                            </div>

                            <template v-else>
                                <Link
                                    :href="route('login')"
                                    class="font-semibold"
                                    >Log in</Link
                                >
                                <Link
                                    v-if="canRegister"
                                    :href="route('register')"
                                    class="ms-4 font-semibold"
                                    >Register</Link
                                >
                            </template>
                        </div>

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
                                <XMarkIcon
                                    v-else
                                    class="w-6 h-6 stroke-current"
                                />
                            </button>
                        </div>
                    </div>
                </div>

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

                    <div
                        v-if="page.props.auth.user"
                        class="pt-4 pb-1 border-t border-gray-200"
                    >
                        <div class="flex items-center px-4">
                            <div
                                v-if="
                                    $page.props.jetstream.managesProfilePhotos
                                "
                                class="shrink-0 me-3"
                            >
                                <img
                                    class="size-10 rounded-full object-cover"
                                    :src="
                                        $page.props.auth.user.profile_photo_url
                                    "
                                    :alt="$page.props.auth.user.name"
                                />
                            </div>
                            <div>
                                <div
                                    class="font-medium text-base text-gray-800"
                                >
                                    {{ $page.props.auth.user.name }}
                                </div>
                                <div class="font-medium text-sm text-gray-500">
                                    {{ $page.props.auth.user.email }}
                                </div>
                            </div>
                        </div>
                        <div class="mt-3 space-y-1">
                            <ResponsiveNavLink
                                :href="route('user.profile.show')"
                                :active="route().current('user.profile.show')"
                            >
                                Profile
                            </ResponsiveNavLink>
                            <ResponsiveNavLink as="button" @click="toggleDarkMode">
                                <span v-if="darkMode">Light Mode</span>
                                <span v-else>Dark Mode</span>
                            </ResponsiveNavLink>
                            <form method="POST" @submit.prevent="logout">
                                <ResponsiveNavLink as="button">
                                    Log Out
                                </ResponsiveNavLink>
                            </form>
                        </div>
                    </div>
                    
                    <div
                        v-else
                        class="pt-4 pb-1 border-t border-gray-200"
                    >
                        <div class="space-y-1">
                            <ResponsiveNavLink as="button" @click="toggleDarkMode">
                                <span v-if="darkMode">Light Mode</span>
                                <span v-else>Dark Mode</span>
                            </ResponsiveNavLink>
                            <ResponsiveNavLink
                                :href="route('login')"
                                :active="route().current('login')"
                            >
                                Log in
                            </ResponsiveNavLink>
                            <ResponsiveNavLink
                                v-if="canRegister"
                                :href="route('register')"
                                :active="route().current('register')"
                            >
                                Register
                            </ResponsiveNavLink>
                        </div>
                    </div>
                </div>
            </nav>

            <main>
                <slot />
            </main>
        </div>

        <Footer />
    </div>
</template>