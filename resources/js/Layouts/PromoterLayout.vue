<script setup>
import { ref, onMounted, onUnmounted, watchEffect, provide } from "vue";
import { Head, Link, router } from "@inertiajs/vue3";
import ApplicationLogo from "@/Components/ApplicationLogo.vue";
import Banner from "@/Components/Banner.vue";
import NavLink from "@/Components/DashboardNavLink.vue";
import DashboardCurrent from "@/Components/DashboardCurrent.vue";
import {
    SunIcon as SolidSunIcon,
    MoonIcon as SolidMoonIcon,
    Bars3Icon,
} from "@heroicons/vue/24/solid";
import {
    SunIcon as OutlineSunIcon,
    MoonIcon as OutlineMoonIcon,
} from "@heroicons/vue/24/outline";

defineProps({
    title: String,
});

const darkMode = ref(false);

const sidebarOpen = ref(false);

const toggleSidebar = () => {
    sidebarOpen.value = !sidebarOpen.value;
};

const handleResize = () => {
    if (window.innerWidth >= 768) {
        sidebarOpen.value = true;
    } else {
        sidebarOpen.value = false;
    }
};

const logout = () => {
    router.post(route("logout"));
};

provide("isDarkMode", darkMode);

const toggleDarkMode = () => {
    darkMode.value = !darkMode.value;
    if (darkMode.value) {
        localStorage.theme = "dark";
    } else {
        localStorage.theme = "light";
    }
};

onMounted(() => {
    handleResize();
    window.addEventListener("resize", handleResize);

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

onUnmounted(() => {
    window.removeEventListener("resize", handleResize);
});

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

        <div class="flex h-screen bg-card">
            <aside
                :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
                class="fixed inset-y-0 left-0 z-30 bg-card transform transition-transform duration-300 ease-in-out md:relative md:translate-x-0 flex flex-col"
            >
                <div>
                    <div class="p-4">
                        <Link
                            :href="route('promoter.index')"
                            class="flex items-center"
                        >
                            <div class="flex items-center space-x-2">
                                <ApplicationLogo
                                    class="block h-12 p-2 rounded-md w-auto bg-primary text-white"
                                />
                                <span
                                    class="flex justify-center text-pri font-bold tracking-wide text-2xl uppercase"
                                    >SoundScape</span
                                >
                            </div>
                        </Link>
                    </div>
                    <nav
                        class="py-2 flex-1 flex flex-col space-y-2 px-2 overflow-hidden uppercase"
                    >
                        <NavLink
                            :href="route('promoter.index')"
                            :active="route().current('promoter.index')"
                        >
                            <span class="w-full">Dashboard</span>
                            <DashboardCurrent
                                :active="route().current('promoter.index')"
                            />
                        </NavLink>
                        <NavLink
                            :href="route('promoter.concert.index')"
                            :active="route().current('promoter.concert.*')"
                        >
                            <span class="w-full">Concerts</span>
                            <DashboardCurrent
                                :active="route().current('promoter.concert.*')"
                            />
                        </NavLink>
                    </nav>
                </div>
                <div class="p-2 mt-auto">
                    <Link
                        :href="route('index')"
                        class="uppercase w-full flex items-center py-2 px-4 text-sm rounded-md text-text hover:text-primary hover:text-xl hover:font-bold hover:bg-card transition-all duration-200"
                    >
                        <span>Switch to User</span>
                    </Link>
                    <form @submit.prevent="logout">
                        <button
                            class="uppercase w-full flex items-center py-2 px-4 text-sm rounded-md text-text hover:text-primary hover:text-xl hover:font-bold hover:bg-card transition-all duration-200"
                        >
                            <span>Logout</span>
                        </button>
                    </form>
                </div>
            </aside>

            <div class="flex-1 flex flex-col overflow-hidden">
                <header class="bg-card z-10 shadow-md">
                    <div
                        class="py-4 px-6 lg:px-8 flex justify-between items-center"
                    >
                        <div class="flex items-center">
                            <button
                                @click.stop="toggleSidebar"
                                class="text-primary md:hidden"
                            >
                                <Bars3Icon class="h-6 w-6 stroke-current" />
                            </button>
                        </div>

                        <div
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
                                    <SolidSunIcon
                                        class="absolute inset-0 h-full w-full stroke-current opacity-0 transition-opacity duration-200 group-hover:opacity-100"
                                    />
                                </div>

                                <div v-else class="relative h-[20px] w-[20px]">
                                    <OutlineMoonIcon
                                        class="absolute inset-0 h-full w-full stroke-current stroke-[2px] opacity-100 transition-opacity duration-200 group-hover:opacity-0"
                                    />
                                    <SolidMoonIcon
                                        class="absolute inset-0 h-full w-full stroke-current opacity-0 transition-opacity duration-200 group-hover:opacity-100"
                                    />
                                </div>
                            </button>
                            <div class="px-4 font-bold text-white uppercase">
                                {{ $page.props.auth.user.promoter.fullname }}
                            </div>
                        </div>
                    </div>
                </header>

                <main
                    class="custom-scrollbar flex-1 overflow-x-hidden overflow-y-auto bg-background"
                    @click="sidebarOpen = false"
                >
                    <div
                        class="px-8 py-8 transition-all duration-300"
                    >
                        <slot />
                    </div>
                </main>
            </div>
            <div
                v-if="sidebarOpen"
                @click="sidebarOpen = false"
                class="fixed inset-0 bg-text-reverse opacity-50 z-20 md:hidden"
            ></div>
        </div>
    </div>
</template>