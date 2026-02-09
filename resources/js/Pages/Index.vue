<script setup>
import { ref, watch } from "vue";
import { usePage, Link, router } from "@inertiajs/vue3";
import AppLayout from "@/Layouts/AppLayout.vue";
import HighlightBanner from "@/Components/Highlights/HighlightBanner.vue";
import ConcertCard from "@/Components/Concerts/ConcertCard.vue";
import DropdownFilter from "@/Components/DropdownFilter.vue";
import { MinusCircleIcon, HeartIcon } from "@heroicons/vue/24/outline";
import { HeartIcon as HeartIconSolid } from "@heroicons/vue/24/solid";

const page = usePage();

const props = defineProps({
    highlights: Object,
    concerts: Object,
    provinces: Object,
    filters: Object,
});

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
    { value: "newest", name: "ล่าสุด" },
    { value: "name_az", name: "ชื่อ A-Z" },
    { value: "name_za", name: "ชื่อ Z-A" },
    { value: "date_asc", name: "วันที่ใกล้ที่สุด" },
    { value: "price_asc", name: "ราคาต่ำสุด" },
    { value: "price_desc", name: "ราคาสูงสุด" },
];

const selectedEventType = ref(props.filters?.event_type || "");
const selectedGenre = ref(props.filters?.genre || "");
const selectedSort = ref(props.filters?.sort || "newest");
const showFollowedOnly = ref(props.filters?.followed === "true" || false);

watch(
    [selectedEventType, selectedGenre, selectedSort, showFollowedOnly],
    ([eventType, genre, sort, followed]) => {
        const currentSearch = props.filters?.search || undefined;

        router.get(
            route("index"),
            {
                search: currentSearch,
                event_type: eventType || undefined,
                genre: genre || undefined,
                sort: sort === "newest" ? undefined : sort,
                followed: followed ? "true" : undefined,
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
                {{
                    filters?.search
                        ? `Results for "${filters.search}"`
                        : "All Concerts"
                }}
            </h2>
            <div class="mx-8 flex flex-wrap gap-2">
                <DropdownFilter
                    id="event_type"
                    v-model="selectedEventType"
                    :options="eventTypes"
                    placeholder="ทุกประเภท"
                />

                <DropdownFilter
                    id="genre"
                    v-model="selectedGenre"
                    :options="genres"
                    placeholder="ทุกแนวเพลง"
                />

                <DropdownFilter
                    id="sort"
                    v-model="selectedSort"
                    :options="sortOptions"
                    placeholder="ล่าสุด"
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
                    class="group p-1 bg-card rounded-full text-text hover:bg-primary hover:text-white transition-colors duration-100"
                    title="Clear filters"
                >
                    <MinusCircleIcon
                        class="h-5 w-5 stroke-[2px] group-hover:stroke-[2.5px]"
                    />
                </button>

                <button
                    v-if="$page.props.auth.user"
                    @click="showFollowedOnly = !showFollowedOnly"
                    type="button"
                    :class="[
                        'group p-1 bg-card rounded-full transition-colors duration-200',
                        showFollowedOnly
                            ? 'text-primary hover:bg-primary hover:text-text'
                            : 'text-text hover:bg-primary hover:text-text',
                    ]"
                    :title="
                        showFollowedOnly
                            ? 'Show all concerts'
                            : 'Show followed concerts only'
                    "
                >
                    <HeartIconSolid
                        v-if="showFollowedOnly"
                        class="h-5 w-5 stroke-[2px] group-hover:stroke-[2.5px] fill-current"
                    />
                    <HeartIcon
                        v-else
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
                            concert: concert.id,
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
