<script setup>
import { computed } from "vue";
import { router } from "@inertiajs/vue3";
import { HeartIcon as OutlineHeartIcon } from "@heroicons/vue/24/outline";
import {
    HeartIcon as SolidHeartIcon,
    MapPinIcon,
    TicketIcon,
} from "@heroicons/vue/24/solid";

const props = defineProps({
    concert: Object,
    provinces: Object,
    role: String,
});

// No changes needed, logic is sound.
const pictureUrl = computed(() => {
    if (props.concert?.picture_url) {
        if (props.concert.picture_url.startsWith("http")) {
            return props.concert.picture_url;
        }
        return `/storage/${props.concert.picture_url}`;
    }
    return "https://placehold.co/600x400?text=SoundScape";
});

// No changes needed, logic is sound.
const provinceName = computed(() => {
    if (props.provinces && props.concert && props.concert.province_id) {
        const province = props.provinces[props.concert.province_id];
        return province ? province.name_th : "Unknown Province";
    }
    return "Loading...";
});

const formattedDate = computed(() => {
    if (props.concert && props.concert.start_show_date) {
        const date = new Date(props.concert.start_show_date);
        return date.toLocaleDateString("th-TH", {
            day: "2-digit",
            month: "long",
            year: "numeric",
        });
    }
    return "ยังไม่ระบุวันที่";
});

const formattedPrice = computed(() => {
    if (props.concert && props.concert.price_min != null) {
        // IMPROVEMENT: Handle free concerts explicitly
        if (props.concert.price_min === 0) {
            return "Free";
        }
        return new Intl.NumberFormat("th-TH", {
            minimumFractionDigits: 0,
            maximumFractionDigits: 0,
        }).format(props.concert.price_min);
    }
    return null;
});

const followConcert = (follow) => {
    router.post(
        route("concert.follow", props.concert.id),
        {
            is_following: !follow,
        },
        { preserveState: true }
    );
};
</script>

<template>
    <div class="bg-card rounded-md p-2 space-y-2 shadow-md overflow-hidden">
        <img
            :src="pictureUrl"
            :alt="props.concert.name || 'Concert Picture'"
            class="w-full aspect-[2/3] object-fill rounded-md"
        />
        <div class="flex flex-col space-y-1">
            <div class="flex justify-between items-center">
                <span class="text-sm text-text-medium truncate">{{
                    formattedDate
                }}</span>
                <button
                    @click.prevent.stop="
                        followConcert(props.concert.is_followed)
                    "
                    :class="{ hidden: role !== 'user' }"
                >
                    <component
                        :is="
                            props.concert.is_followed
                                ? SolidHeartIcon
                                : OutlineHeartIcon
                        "
                        class="w-5 h-5"
                        :class="
                            props.concert.is_followed
                                ? 'text-primary'
                                : 'text-text-medium hover:text-primary transition-colors duration-200'
                        "
                    />
                </button>
            </div>
            <span class="font-semibold line-clamp-2 min-h-12">{{ props.concert.name }}</span>
            <div class="flex justify-between text-sm text-text-medium">
                <div class="flex items-center gap-1 min-w-0 text-accent">
                    <TicketIcon class="w-4 h-4 flex-shrink-0" />
                    <span class="truncate"> {{ formattedPrice }} </span>
                </div>
                <div class="flex items-center gap-1 min-w-0 text-secondary">
                    <MapPinIcon class="w-4 h-4 flex-shrink-0" />
                    <span class="truncate"> {{ provinceName }} </span>
                </div>
            </div>
        </div>
    </div>
</template>
