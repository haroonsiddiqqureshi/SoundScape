<script setup>
import { ref, onMounted, onUnmounted } from "vue";
import { Head, Link, router } from "@inertiajs/vue3";
import ApplicationMark from "@/Components/ApplicationMark.vue";
import Banner from "@/Components/Banner.vue";
import Dropdown from "@/Components/Dropdown.vue";
import DropdownLink from "@/Components/DropdownLink.vue";
import NavLink from "@/Components/NavLink.vue";
import ResponsiveNavLink from "@/Components/ResponsiveNavLink.vue";

defineProps({
    title: String,
});

const showingNavigationDropdown = ref(false);

const sidebarOpen = ref(false);

const toggleSidebar = () => {
    sidebarOpen.value = !sidebarOpen.value;
};

const handleResize = () => {
    if (window.innerWidth >= 640) {
        sidebarOpen.value = true;
    } else {
        sidebarOpen.value = false;
    }
};

onMounted(() => {
    handleResize();
    window.addEventListener("resize", handleResize);
});

onUnmounted(() => {
    window.removeEventListener("resize", handleResize);
});

const logout = () => {
    router.post(route("logout"));
};
</script>

<template>
    <div>
        <Head :title="title" />

        <Banner />

        <div class="flex h-screen bg-gray-100 dark:bg-gray-900">
            <!-- Sidebar -->
            <aside
                :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
                class="fixed inset-y-0 left-0 z-30 w-64 bg-white dark:bg-gray-800 text-gray-800 dark:text-white transform transition-transform duration-300 ease-in-out sm:relative sm:translate-x-0 flex flex-col"
            >
                <div>
                    <div
                        class="p-4 border-b border-gray-200 dark:border-gray-700"
                    >
                        <Link
                            :href="route('dashboard')"
                            class="flex items-center"
                        >
                            <ApplicationMark class="block h-9 w-auto" />
                            <span
                                class="ml-2 text-xl font-bold text-gray-800 dark:text-white"
                                >SoundScape</span
                            >
                        </Link>
                    </div>
                    <nav class="mt-4 flex-1 flex flex-col space-y-2 px-2">
                        <NavLink
                            :href="route('dashboard')"
                            :active="route().current('dashboard')"
                            class="w-full flex items-center py-2 px-4 text-sm rounded-md text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700"
                        >
                            Dashboard
                        </NavLink>
                        <NavLink
                            :href="route('admin.concert.index')"
                            class="w-full flex items-center py-2 px-4 text-sm rounded-md text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700"
                        >
                            Concerts
                        </NavLink>
                        <NavLink
                            :href="route('admin.promoter.index')"
                            class="w-full flex items-center py-2 px-4 text-sm rounded-md text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700"
                        >
                            Promoters
                        </NavLink>
                        <NavLink
                            :href="route('admin.user.index')"
                            class="w-full flex items-center py-2 px-4 text-sm rounded-md text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700"
                        >
                            Users
                        </NavLink>
                    </nav>
                </div>
                <div class="p-2 mt-auto">
                    <form @submit.prevent="logout">
                        <button
                            class="w-full flex items-center py-2 px-4 text-sm rounded-md text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700"
                        >
                            Logout
                        </button>
                    </form>
                </div>
            </aside>

            <!-- Main content -->
            <div class="flex-1 flex flex-col overflow-hidden">
                <!-- Top navigation -->
                <header class="bg-white dark:bg-gray-800 shadow">
                    <div
                        class="py-4 px-4 sm:px-6 lg:px-8 flex justify-between items-center"
                    >
                        <div class="flex items-center">
                            <button
                                @click.stop="toggleSidebar"
                                class="text-gray-500 dark:text-gray-400 focus:outline-none focus:text-gray-700 dark:focus:text-gray-300 sm:hidden"
                            >
                                <svg
                                    class="size-6"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                    xmlns="http://www.w3.org/2000/svg"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M4 6h16M4 12h16M4 18h16"
                                    ></path>
                                </svg>
                            </button>
                        </div>

                        <div class="flex items-center sm:ms-6">
                            <div class="ms-3 relative">
                                <span class="inline-flex rounded-md">
                                    <button
                                        type="button"
                                        class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-800 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none focus:bg-gray-50 dark:focus:bg-gray-700 active:bg-gray-50 dark:active:bg-gray-700 transition ease-in-out duration-150"
                                    >
                                        <img
                                            v-if="
                                                $page.props.jetstream
                                                    .managesProfilePhotos
                                            "
                                            class="size-8 rounded-full object-cover"
                                            :src="
                                                $page.props.auth.user
                                                    .profile_photo_url
                                            "
                                            :alt="$page.props.auth.user.name"
                                        />
                                        <span class="ml-2 sm:inline">{{
                                            $page.props.auth.user.name
                                        }}</span>
                                    </button>
                                </span>
                            </div>
                        </div>
                    </div>
                </header>

                <!-- Page Content -->
                <main
                    class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-200 dark:bg-gray-900"
                    @click="sidebarOpen = false"
                >
                    <div class="mx-auto sm:px-6 lg:px-8 py-6 lg:py-8">
                        <slot />
                    </div>
                </main>
            </div>
            <!-- Sidebar overlay -->
            <div
                v-if="sidebarOpen"
                @click="sidebarOpen = false"
                class="fixed inset-0 bg-black opacity-50 z-20 sm:hidden"
            ></div>
        </div>
    </div>
</template>
