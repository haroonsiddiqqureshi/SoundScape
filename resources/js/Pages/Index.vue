<script setup>
import { ref, watch } from "vue";
import { usePage, Link, router } from "@inertiajs/vue3";
import AppLayout from "@/Layouts/AppLayout.vue";
import HighlightBanner from "@/Components/Highlights/HighlightBanner.vue";
import ConcertCard from "@/Components/Concerts/ConcertCard.vue";
import DropdownFilter from "@/Components/DropdownFilter.vue";
import { MinusCircleIcon } from "@heroicons/vue/24/outline";

const page = usePage();

const props = defineProps({
    highlights: Object,
    concerts: Object,
    provinces: Object,
    filters: Object,
});

// --- Filter & Sort Data ---

const eventTypes = [
    { value: "music_festival", name: "เทศกาลดนตรี" },
    { value: "concert", name: "คอนเสิร์ต" },
    { value: "club", name: "คลับ / ผับ" },
    { value: "fan_meeting", name: "แฟนมีตติ้ง" },
    { value: "folk", name: "เพลงพื้นบ้าน / หมอลำ" },
    { value: "other", name: "อื่นๆ" },
];

const genres = [
    { value: "pop", name: "ป๊อป" },
    { value: "rock", name: "ร็อก" },
    { value: "hiphop", name: "ฮิปฮอป" },
    { value: "jazz", name: "แจ๊ส" },
    { value: "classical", name: "คลาสสิก" },
    { value: "country", name: "คันทรี" },
    { value: "electronic", name: "อิเล็กทรอนิกส์" },
    { value: "other", name: "อื่นๆ" },
];

const sortOptions = [
    { value: "newest", name: "Newest" },
    { value: "date_asc", name: "Date: Soonest" },
    { value: "price_asc", name: "Price: Low to High" },
    { value: "price_desc", name: "Price: High to Low" },
];

// --- Reactive State ---

const selectedEventType = ref(props.filters?.event_type || "");
const selectedGenre = ref(props.filters?.genre || "");
const selectedSort = ref(props.filters?.sort || "newest");
// --- Watcher ---

watch(
    [selectedEventType, selectedGenre, selectedSort],
    ([eventType, genre, sort]) => {
        const currentSearch = props.filters?.search || undefined;

        router.get(
            route("index"),
            {
                search: currentSearch,
                event_type: eventType || undefined,
                genre: genre || undefined,
                sort: sort === "newest" ? undefined : sort,
            },
            {
                preserveState: true,
                preserveScroll: true,
                replace: true,
            }
        );
    }
);
</script>

<template>
    <AppLayout
        title="Home"
        :can-login="$page.props.canLogin"
        :can-register="$page.props.canRegister"
    >
        <HighlightBanner
            v-if="!filters?.search"
            :highlights="page.props.highlights"
        />

        <div class="space-y-4">
            <h2 class="text-2xl font-bold uppercase">
                {{ filters?.search ? `Results for "${filters.search}"` : 'All Concerts' }}
            </h2>
            <div class="mx-8 flex flex-wrap gap-2">
                <DropdownFilter
                    id="event_type"
                    v-model="selectedEventType"
                    :options="eventTypes"
                    placeholder="All Types"
                />

                <DropdownFilter
                    id="genre"
                    v-model="selectedGenre"
                    :options="genres"
                    placeholder="All Genres"
                />

                <DropdownFilter
                    id="sort"
                    v-model="selectedSort"
                    :options="sortOptions"
                    placeholder="Sort By"
                />

                <button
                    v-if="
                        selectedEventType ||
                        selectedGenre ||
                        selectedSort !== 'newest'
                    "
                    @click="
                        (selectedEventType = ''),
                            (selectedGenre = ''),
                            (selectedSort = 'newest')
                    "
                    type="button"
                    class="group p-1 bg-card rounded-full text-text hover:bg-primary hover:text-text transition-colors duration-200"
                    title="Clear filters"
                >
                    <MinusCircleIcon
                        class="h-5 w-5 stroke-[2px] group-hover:stroke-[2.5px]"
                    />
                </button>
            </div>
            <div
                v-if="concerts && concerts.length > 0"
                class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-2"
            >
                <Link
                    v-for="concert in concerts"
                    :key="concert.id"
                    :href="
                        route('concert.detail', {
                            concert: concert.name,
                        })
                    "
                    class="block"
                >
                    <ConcertCard
                        :concert="concert"
                        :provinces="provinces"
                        role="user"
                    />
                </Link>
            </div>

            <div v-else class="text-center py-12 text-text-medium">
                <p class="text-lg font-medium">No concerts found.</p>
                <p class="text-sm mt-2">
                    Try adjusting your filters or check back later.
                </p>
            </div>
        </div>
    </AppLayout>
</template>