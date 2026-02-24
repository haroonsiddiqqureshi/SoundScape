<script setup>
import PromoterLayout from "@/Layouts/PromoterLayout.vue";
import ConcertCard from "@/Components/Concerts/ConcertCard.vue";
import { Head, Link, router } from "@inertiajs/vue3";
import { PlusIcon } from "@heroicons/vue/24/solid";
import { ref, watch, computed } from "vue";
import { debounce } from "lodash-es";
import {
    FolderPlusIcon,
    MagnifyingGlassIcon,
} from "@heroicons/vue/24/outline";

const props = defineProps({
    concerts: Object, // This should now be a paginator object
    provinces: Object,
    filters: Object, 
});

// Search logic
const search = ref(props.filters?.search || "");

// Computed for empty state
const hasConcerts = computed(() => {
    // Data is paginated, so we must check concerts.data
    return props.concerts.data && props.concerts.data.length > 0;
});

// Watcher for search
watch(
    search,
    debounce((value) => {
        router.get(
            route("promoter.concert.index"),
            { search: value },
            {
                preserveState: true,
                preserveScroll: true,
                replace: true,
            }
        );
    }, 300)
);
</script>

<template>
    <PromoterLayout title="Concert">
        <div class="w-full mx-auto rounded-md space-y-6">
            <div class="flex justify-between items-center space-x-4">
                <div class="w-full flex items-center space-x-4">
                    <div class="relative w-full">
                        <div
                            class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none"
                        >
                            <MagnifyingGlassIcon class="h-5 w-5 text-text" />
                        </div>
                        <input
                            type="text"
                            name="search"
                            id="search"
                            class="block w-full pl-10 pr-3 p-1 ring-4 ring-card border-none rounded-md leading-5 bg-card focus:outline-none focus:ring-4 focus:ring-card sm:text-sm"
                            placeholder="Search by name..."
                            v-model="search"
                        />
                    </div>
                </div>
                <Link
                    :href="route('promoter.concert.create')"
                    class="flex items-center justify-center py-1 ring-4 ring-primary rounded-md text-primary bg-primary w-32"
                >
                    <PlusIcon
                        class="w-5 h-5 text-white stroke-current stroke-[2px]"
                    />
                </Link>
            </div>

            <div
                v-if="hasConcerts"
                class="grid grid-cols-3 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 2xl:grid-cols-5 gap-6"
            >
                <Link
                    v-for="concert in concerts.data"
                    :key="concert.id"
                    :href="
                        route('promoter.concert.detail', {
                            concert: concert.id,
                        })
                    "
                    class="block"
                >
                    <ConcertCard :concert="concert" :provinces="provinces" />
                </Link>
            </div>

            <div
                v-else
                class="w-full flex flex-col space-y-2 font-semibold items-center text-text-medium justify-center py-10 rounded-md bg-card ring-4 ring-card"
            >
                <FolderPlusIcon class="w-12 h-12 stroke-current" />
                <p>No concerts found</p>
            </div>
        </div>

        <div class="mt-8" v-if="concerts.links">
            <ul class="flex justify-center gap-2">
                <li v-for="link in concerts.links">
                    <Link
                        class="px-4 py-2 border border-card hover:border-primary rounded-md hover:bg-primary hover:text-white font-semibold ring-0 bg-card transition-colors duration-150"
                        :class="{
                            'bg-primary text-white': link.active,
                        }"
                        :href="link.url ?? route('promoter.concert.index')"
                    >
                        <span v-html="link.label"></span>
                    </Link>
                </li>
            </ul>
        </div>
    </PromoterLayout>
</template>