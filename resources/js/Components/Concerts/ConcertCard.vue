<script setup>
import { computed } from "vue";
import { router } from "@inertiajs/vue3";
import { HeartIcon as OutlineHeartIcon } from "@heroicons/vue/24/outline";
import { HeartIcon as SolidHeartIcon } from "@heroicons/vue/24/solid";

const props = defineProps({
    concert: Object,
    provinces: Object,
    isUser: {
        type: Boolean,
        default: false,
    },
});

const pictureUrl = computed(() => {
    if (props.concert?.picture_url) {
        if (props.concert.picture_url.startsWith("http")) {
            return props.concert.picture_url;
        }
        return `/storage/${props.concert.picture_url}`;
    }
    return "https://placehold.co/600x400?text=SoundScape";
});

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
        return date.toLocaleDateString("en-GB", {
            day: "2-digit",
            month: "long",
            year: "2-digit",
        });
    }
    return "N/A";
});

const followConcert = (follow) => {
    router.post(
        route("concert.follow", props.concert.id),
        {
            is_following: follow ? false : true,
        },
        { preserveState: true }
    );
};
</script>

<template>
    <div class="bg-card rounded-md">
        <img
            :src="pictureUrl"
            alt="Concert Picture"
            class="aspect-[2/3] rounded-md"
        />
        <div class="flex flex-col p-4 space-y-2">
            <div class="flex justify-between relative">
                <span class="font-semibold text-text-medium">{{
                    formattedDate
                }}</span>
                <button
                    @click="followConcert(props.concert.is_followed)"
                    :class="{ hidden: !isUser }"
                    class="absolute right-0 z-10"
                >
                    <component
                        :is="
                            props.concert.is_followed
                                ? SolidHeartIcon
                                : OutlineHeartIcon
                        "
                        class="w-6 h-6"
                        :class="
                            props.concert.is_followed
                                ? 'text-primary'
                                : 'text-text-medium hover:text-primary transition-colors duration-200'
                        "
                    />
                </button>
            </div>
            <span class="font-bold">{{ props.concert.name }}</span>
            <div class="flex justify-between text-sm text-text-medium">
                <span> {{ provinceName }} </span>
                <span> {{ props.concert.price_min }} </span>
            </div>
        </div>
    </div>
</template>
