<script setup>
import { computed } from "vue";
import { Link } from "@inertiajs/vue3";
import {
    ClockIcon,
    FireIcon,
    UserIcon,
    MapPinIcon,
    MusicalNoteIcon
} from "@heroicons/vue/24/outline";

const props = defineProps({
    results: {
        type: Object,
        default: () => ({ top_concerts: [], artists: [], concerts: [], provinces: [] }),
    },
    isLoading: {
        type: Boolean,
        default: false,
    },
    searchQuery: {
        type: String,
        default: "",
    }
});

const getPictureUrl = (item) => {
    if (item?.picture_url) {
        if (item.picture_url.startsWith("http")) {
            return item.picture_url;
        }
        return `/storage/${item.picture_url}`;
    }
    return "https://placehold.co/600x400?text=SoundScape";
};

const isSearchEmpty = computed(() => {
    if (!props.searchQuery) {
        return !props.results?.top_concerts?.length;
    }
    return !props.results?.artists?.length &&
        !props.results?.concerts?.length &&
        !props.results?.provinces?.length;
});
</script>

<template>
    <div
        class="custom-scrollbar absolute top-full left-0 right-0 z-50 mt-1 w-full overflow-hidden rounded-md bg-card shadow-lg border-2 border-card-hover max-h-[28rem] overflow-y-auto">

        <div v-if="props.isLoading" class="p-4 text-center text-text-medium">
            <ClockIcon class="h-6 w-6 animate-spin-slow mx-auto mb-2" />
            <span class="text-sm font-semibold">Searching...</span>
        </div>

        <div v-else-if="!props.isLoading && isSearchEmpty" class="p-6 text-center text-text-medium text-sm">
            No results found for "<span class="font-bold">{{ searchQuery }}</span>".
        </div>

        <div v-else class="divide-y-2 divide-card-hover pb-2">

            <div v-if="!searchQuery && results.top_concerts?.length > 0">
                <div
                    class="px-4 py-2 mt-2 text-xs font-bold text-text-medium uppercase tracking-wider flex items-center">
                    <FireIcon class="h-4 w-4 mr-1 text-primary" /> Trending Concerts
                </div>
                <ul>
                    <li v-for="concert in results.top_concerts" :key="'top-' + concert.id">
                        <Link :href="route('concert.detail', concert)"
                            class="flex items-center px-4 py-2 hover:bg-primary-low transition duration-150">
                            <img :src="getPictureUrl(concert)" alt="Concert Picture"
                                class="w-12 h-12 object-cover rounded-md mr-4 transition" />
                            <div class="flex-1 min-w-0">
                                <div
                                    class="font-semibold truncate text-sm transition-colors">
                                    {{ concert.name }}</div>
                                <div class="text-xs text-text-medium font-normal truncate mt-0.5">
                                    {{ concert.venue_name }}
                                </div>
                            </div>
                        </Link>
                    </li>
                </ul>
            </div>

            <template v-if="searchQuery">

                <div v-if="results.artists?.length > 0">
                    <div
                        class="px-4 py-2 mt-2 text-xs font-bold text-text-medium uppercase tracking-wider flex items-center">
                        <UserIcon class="h-4 w-4 mr-1" /> Artists
                    </div>
                    <ul>
                        <li v-for="artist in results.artists" :key="'artist-' + artist.id">
                            <Link :href="route('index', { search: artist.name })"
                                class="flex items-center px-4 py-2 hover:bg-primary-low transition duration-150">

                                <img v-if="artist.picture_url" :src="getPictureUrl(artist)" :alt="artist.name"
                                    class="w-8 h-8 rounded-full object-cover mr-4 transition-transform shadow-sm" />

                                <div v-else
                                    class="w-8 h-8 rounded-full bg-primary flex items-center justify-center text-white font-bold mr-4 text-sm transition-transform shadow-sm">
                                    {{ artist.name.charAt(0).toUpperCase() }}
                                </div>

                                <div
                                    class="font-semibold text-text truncate text-sm flex-1 transition-colors">
                                    {{ artist.name }}</div>
                            </Link>
                        </li>
                    </ul>
                </div>

                <div v-if="results.provinces?.length > 0"
                    :class="{ 'border-t-2 border-card-hover': results.artists?.length > 0 }">
                    <div
                        class="px-4 py-2 mt-2 text-xs font-bold text-text-medium uppercase tracking-wider flex items-center">
                        <MapPinIcon class="h-4 w-4 mr-1" /> Provinces
                    </div>
                    <ul>
                        <li v-for="province in results.provinces" :key="'prov-' + province.id">
                            <Link :href="route('index', { search: province.name_en })"
                                class="flex items-center px-4 py-2 hover:bg-primary-low transition duration-150">
                                <div
                                    class="font-semibold text-text truncate text-sm transition-colors">
                                    {{ province.name_th }} <span class="text-text-medium font-normal ml-1">({{
                                        province.name_en }})</span>
                                </div>
                            </Link>
                        </li>
                    </ul>
                </div>

                <div v-if="results.concerts?.length > 0"
                    :class="{ 'border-t-2 border-card-hover': (results.artists?.length > 0 || results.provinces?.length > 0) }">
                    <div
                        class="px-4 py-2 mt-2 text-xs font-bold text-text-medium uppercase tracking-wider flex items-center">
                        <MusicalNoteIcon class="h-4 w-4 mr-1" /> Concerts
                    </div>
                    <ul>
                        <li v-for="concert in results.concerts" :key="'concert-' + concert.id">
                            <Link :href="route('concert.detail', concert)"
                                class="flex items-center px-4 py-2 hover:bg-primary-low transition duration-150">
                                <img :src="getPictureUrl(concert)" alt="Concert Picture"
                                    class="w-12 h-12 object-cover rounded-md mr-4 transition" />
                                <div class="flex-1 min-w-0">
                                    <div
                                        class="font-semibold text-text truncate text-sm transition-colors">
                                        {{ concert.name }}</div>
                                    <div class="text-xs text-text-medium truncate mt-0.5">{{ concert.venue_name }}</div>
                                </div>
                            </Link>
                        </li>
                    </ul>
                </div>

            </template>
        </div>
    </div>
</template>