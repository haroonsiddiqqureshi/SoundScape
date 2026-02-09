<script setup>
import { computed, ref } from "vue";
import { router } from "@inertiajs/vue3";
import { HeartIcon as OutlineHeartIcon, TagIcon } from "@heroicons/vue/24/outline";
import {
    HeartIcon as SolidHeartIcon,
    MusicalNoteIcon,
} from "@heroicons/vue/24/solid";

const props = defineProps({
    concert: Object,
    role: {
        type: String,
        default: 'user',
    },
});

const isLoading = ref(false);

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

const pictureUrl = computed(() => {
    if (props.concert?.picture_url) {
        if (props.concert.picture_url.startsWith("http")) {
            return props.concert.picture_url;
        }
        return `/storage/${props.concert.picture_url}`;
    }
    return "https://placehold.co/600x400?text=SoundScape";
});

const formattedDate = computed(() => {
    if (props.concert && props.concert.start_show_date) {
        const date = new Date(props.concert.start_show_date);
        return date.toLocaleDateString("en-GB", {
            day: "2-digit",
            month: "long",
            year: "numeric",
        });
    }
    return "ไม่ระบุวันที่";
});

const eventTypeName = computed(() => {
    if (props.concert && props.concert.event_type) {
        const event = eventTypes.find(e => e.value === props.concert.event_type);
        return event ? event.name : props.concert.event_type;
    }
    return "ไม่ระบุประเภท";
});

const genreName = computed(() => {
    if (props.concert && props.concert.genre) {
        const genre = genres.find(g => g.value === props.concert.genre);
        return genre ? genre.name : props.concert.genre;
    }
    return "ไม่ระบุแนวเพลง";
});

const detailUrl = computed(() => {
    return route('concert.detail', { concert: props.concert.id });
});

const followConcert = (follow) => {
    if (isLoading.value) return;
    isLoading.value = true;

    props.concert.is_followed = !follow;

    router.post(
        route("concert.follow", props.concert.id),
        {
            is_following: !follow,
        },
        { 
            preserveState: true,
            preserveScroll: true,
            onFinish: () => {
                isLoading.value = false;
            },
            onError: () => {
                props.concert.is_followed = follow; 
            }
        }
    );
};
</script>

<template>
    <div class="w-full bg-card overflow-hidden">
        <img
            :src="pictureUrl"
            :alt="props.concert.name || 'Concert Picture'"
            class="w-full aspect-[2/3] object-fill rounded-md"
        />
        <div class="space-y-2 mt-1">
            <div class="flex flex-col">
                <div class="flex justify-between items-center">
                    <span class="text-sm text-text-medium truncate">{{
                        formattedDate
                    }}</span>
                    <button
                        @click.prevent.stop="
                            followConcert(props.concert.is_followed)
                        "
                        :class="{ hidden: role !== 'user' }"
                        :disabled="isLoading"
                    >
                        <component
                            :is="
                                props.concert.is_followed
                                    ? SolidHeartIcon
                                    : OutlineHeartIcon
                            "
                            class="w-5 h-5"
                            :class="[
                                props.concert.is_followed
                                    ? 'text-primary'
                                    : 'text-text-medium hover:text-primary',
                                'transition-colors duration-200',
                                { 'opacity-50 cursor-not-allowed': isLoading }
                            ]"
                        />
                    </button>
                </div>
                <span class="font-semibold line-clamp-2 text-lg text-text">{{ props.concert.name }}</span>
                
                <div class="flex justify-between text-sm text-text-medium pt-1">
                    <div class="flex items-center gap-1.5 min-w-0 text-accent">
                        <TagIcon class="w-4 h-4 flex-shrink-0 stroke-[2.5px]" />
                        <span class="truncate"> {{ eventTypeName }} </span>
                    </div>
                    <div class="flex items-center gap-1.5 min-w-0 text-secondary">
                        <MusicalNoteIcon class="w-4 h-4 flex-shrink-0" />
                        <span class="truncate"> {{ genreName }} </span>
                    </div>
                </div>
                </div>

            <a
                :href="detailUrl"
                target="_blank"
                class="block w-full rounded-md px-3 py-1 text-center text-sm font-semibold text-white shadow-sm transition bg-primary hover:bg-primary-dark"
            >
                View Details
            </a>
        </div>
    </div>
</template>