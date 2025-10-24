<script setup>
import { onMounted, provide, ref, watchEffect, watch } from "vue";
import { Head, Link, router, usePage } from "@inertiajs/vue3";
import ApplicationLogo from "@/Components/ApplicationLogo.vue";
import Banner from "@/Components/Banner.vue";
import Dropdown from "@/Components/Dropdown.vue";
import DropdownLink from "@/Components/DropdownLink.vue";
import NavLink from "@/Components/NavLink.vue";
import ResponsiveNavLink from "@/Components/ResponsiveNavLink.vue";
import SearchResults from "@/Components/SearchResults.vue";
import axios from "axios";
import Footer from "@/Components/Footer.vue";
import {
    Bars3Icon,
    XMarkIcon,
    MagnifyingGlassIcon,
    SunIcon,
    MoonIcon,
} from "@heroicons/vue/24/solid";
import {
    MoonIcon as OutlineMoonIcon,
    SunIcon as OutlineSunIcon,
} from "@heroicons/vue/24/outline";

defineProps({
    title: String,
    canLogin: Boolean,
    canRegister: Boolean,
});

const page = usePage();
const showingNavigationDropdown = ref(false);
const darkMode = ref(false);

function debounce(fn, delay) {
    let timeoutId;
    return (...args) => {
        clearTimeout(timeoutId);
        timeoutId = setTimeout(() => fn(...args), delay);
    };
}

const search = ref(page.props.filters?.search || "");
const searchResults = ref([]);
const isSearchFocused = ref(false);
const isLoadingSearch = ref(false);

const submitSearch = () => {
    let queryParams = { ...page.props.filters, search: search.value };

    if (!search.value) {
        delete queryParams.search;
    }

    router.get(route("index"), queryParams, {
        preserveState: true,
        replace: true,
    });

    isSearchFocused.value = false;
};

const fetchSearchResults = async () => {
    if (search.value.length < 2) {
        searchResults.value = [];
        isLoadingSearch.value = false;
        return;
    }

    isLoadingSearch.value = true;

    try {
        const response = await axios.get(route("api.search"), {
            params: { q: search.value },
        });
        searchResults.value = response.data.data;
    } catch (error) {
        console.error("Error fetching search results:", error);
        searchResults.value = [];
    } finally {
        isLoadingSearch.value = false;
    }
};

watch(
    () => page.props.filters?.search,
    (newSearch) => {
        search.value = newSearch || "";
    }
);

watch(search, debounce(fetchSearchResults, 300));

const onSearchFocus = () => {
    isSearchFocused.value = true;
};

const onSearchBlur = () => {
    setTimeout(() => {
        isSearchFocused.value = false;
    }, 200);
};

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

        <div class="bg-background text-text z-0">
            <nav
                class="sticky top-0 bg-card border-b border-primary-low shadow-md shadow-primary-low z-20"
            >
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex justify-between h-16">
                        <div class="flex">
                            <div class="shrink-0 flex items-center">
                                <Link :href="route('index')">
                                    <ApplicationLogo
                                        class="block h-12 p-2 rounded-md w-auto bg-primary text-white"
                                    />
                                </Link>
                            </div>

                            <div
                                class="hidden space-x-8 sm:-my-[2px] sm:ms-10 sm:flex font-semibold uppercase"
                            >
                                <NavLink
                                    :href="route('index')"
                                    :active="route().current('index')"
                                >
                                    Home
                                </NavLink>
                                <NavLink
                                    :href="route('map.index')"
                                    :active="route().current('map.index')"
                                >
                                    Map
                                </NavLink>
                            </div>
                        </div>

                        <div
                            class="flex-1 flex items-center justify-end px-2 ms-2 group"
                        >
                            <div
                                class="relative max-w-xs w-full lg:max-w-xs group-focus-within:max-w-full transition-all duration-300 ease-in-out"
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
                                        class="block w-full pl-10 pr-3 py-2 border border-transparent rounded-md leading-5 bg-background text-text placeholder-text-medium focus:pl-12 focus:border-primary focus:ring-primary sm:text-xs"
                                        placeholder="Search"
                                        type="search"
                                        autocomplete="off"
                                        v-model="search"
                                        @focus="onSearchFocus"
                                        @blur="onSearchBlur"
                                        @keyup.enter="submitSearch"
                                    />
                                </div>

                                <SearchResults
                                    v-if="
                                        isSearchFocused && search.length > 1
                                    "
                                    :is-loading="isLoadingSearch"
                                    :results="searchResults"
                                />
                            </div>
                        </div>

                        <div class="hidden sm:flex sm:items-center space-x-4">
                            <button
                                @click="toggleDarkMode"
                                :class="{ hidden: page.props.auth.user }"
                                class="ms-2 inline-flex items-center justify-center rounded-md text-primary hover:text-text focus:outline-none transition duration-150 ease-in-out"
                            >
                                <SunIcon v-if="darkMode" class="h-6 w-6" />
                                <MoonIcon v-else class="h-6 w-6" />
                            </button>

                            <div
                                v-if="page.props.auth.user"
                                class="flex items-center sm:ms-6 bg-primary relative"
                            >
                                <div
                                    class="absolute -left-3 bg-card p-2 rounded-full"
                                />
                                <div
                                    class="absolute -right-3 bg-card p-2 rounded-full"
                                />
                                <button
                                    @click="toggleDarkMode"
                                    class="group p-2 text-white outline-dashed outline-2 outline-card bg-secondary-high"
                                >
                                    <div
                                        v-if="darkMode"
                                        class="relative h-[20px] w-[20px]"
                                    >
                                        <OutlineSunIcon
                                            class="absolute inset-0 h-full w-full stroke-current stroke-[2px] opacity-100 transition-opacity duration-200 group-hover:opacity-0"
                                        />
                                        <SunIcon
                                            class="absolute inset-0 h-full w-full stroke-current opacity-0 transition-opacity duration-200 group-hover:opacity-100"
                                        />
                                    </div>

                                    <div
                                        v-else
                                        class="relative h-[20px] w-[20px]"
                                    >
                                        <OutlineMoonIcon
                                            class="absolute inset-0 h-full w-full stroke-current stroke-[2.5px] opacity-100 transition-opacity duration-200 group-hover:opacity-0"
                                        />
                                        <MoonIcon
                                            class="absolute inset-0 h-full w-full stroke-current opacity-0 transition-opacity duration-200 group-hover:opacity-100"
                                        />
                                    </div>
                                </button>
                                <Dropdown align="right" width="48">
                                    <template #trigger>
                                        <button
                                            v-if="
                                                $page.props.jetstream
                                                    .managesProfilePhotos
                                            "
                                            class="flex text-sm border-2 border-transparent rounded-full focus:outline-none focus:border-text transition"
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
                                                class="inline-flex items-center px-3 py-2 border border-transparent leading-4 font-bold text-white bg-transparent focus:outline-none uppercase"
                                            >
                                                {{ $page.props.auth.user.name }}
                                            </button>
                                        </span>
                                    </template>

                                    <template #content>
                                        <div
                                            class="block px-4 py-2 text-xs text-text-medium"
                                        >
                                            Manage Account
                                        </div>
                                        <DropdownLink
                                            :href="route('profile.show')"
                                        >
                                            Profile
                                        </DropdownLink>
                                        <DropdownLink
                                            :href="route('promoter.index')"
                                        >
                                            Promoter Account
                                        </DropdownLink>
                                        <div
                                            class="border-t border-2 border-background"
                                        />
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
                                    class="font-semibold uppercase"
                                    >Log in</Link
                                >
                                <Link
                                    v-if="canRegister"
                                    :href="route('register')"
                                    class="font-semibold uppercase"
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
                            :href="route('map.index')"
                            :active="route().current('map.index')"
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
                                :href="route('profile.show')"
                                :active="route().current('profile.show')"
                            >
                                Profile
                            </ResponsiveNavLink>
                            <ResponsiveNavLink
                                as="button"
                                @click="toggleDarkMode"
                            >
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

                    <div v-else class="pt-4 pb-1 border-t border-gray-200">
                        <div class="space-y-1">
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

            <main class="py-12 mx-4 sm:mx-0 bg-background z-10">
                <div
                    class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-12"
                >
                    <slot />
                </div>
            </main>
        </div>

        <Footer />
    </div>
</template>
