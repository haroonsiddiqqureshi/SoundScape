<script setup>
import { computed } from "vue";

const props = defineProps({
    concert: Object,
});

const imageUrl = computed(() => {
    if (props.concert?.picture_url) {
        if (props.concert.picture_url.startsWith("http")) {
            return props.concert.picture_url;
        }
        return `/storage/${props.concert.picture_url}`;
    }
    return "https://placehold.co/600x400?text=SoundScape";
});

const showDate = computed(() => {
    if (!props.concert?.start_datetime) return { month: "TBA", day: "" };
    const date = new Date(props.concert.start_datetime);
    return {
        month: date.toLocaleDateString("en-US", { month: "short" }),
        day: date.toLocaleDateString("en-US", { day: "2-digit" }),
    };
});

const ticketPrice = computed(() => {
    if (props.concert.price) {
        return `${new Intl.NumberFormat("en-US", {
            style: "currency",
            currency: "THB",
        }).format(props.concert.price)}`;
    }
    return "Free";
});

const statusClass = computed(() => {
    switch (props.concert?.status) {
        case "upcoming":
            return "bg-blue-100 text-blue-800";
        case "cancelled":
            return "bg-red-100 text-red-800";
        case "past":
            return "bg-gray-100 text-gray-800";
        default:
            return "bg-gray-100 text-gray-800";
    }
});
</script>

<template>
    <div
        class="group bg-white rounded-lg shadow-md overflow-hidden transition-all duration-300 hover:shadow-lg hover:-translate-y-1"
    >
        <div class="relative">
            <img
                class="w-full h-36 object-cover"
                :src="imageUrl"
                :alt="`Flyer for ${props.concert?.name}`"
            />
            <div
                class="absolute top-1.5 right-1.5 px-1.5 py-0.5 rounded-full text-[10px] font-semibold"
                :class="statusClass"
            >
                {{ props.concert?.status }}
            </div>
            <div
                class="absolute bottom-0 left-0 bg-white/80 backdrop-blur-sm px-2 py-1 rounded-tr-lg flex flex-col items-center justify-center w-14"
            >
                <span class="text-xs font-bold text-red-600">{{
                    concertDate.month
                }}</span>
                <span class="text-xl font-extrabold text-gray-800">{{
                    concertDate.day
                }}</span>
            </div>
        </div>
        <div class="p-3">
            <h3
                class="text-base font-bold text-gray-800 truncate group-hover:text-indigo-600"
                :title="props.concert?.name"
            >
                {{ props.concert?.name }}
            </h3>
            <div class="mt-2 space-y-1.5 text-xs text-gray-600">
                <div class="flex items-center">
                    <span class="truncate"
                        >{{ props.concert?.venue_name }},
                        {{ props.concert?.city }}</span
                    >
                </div>
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <span>{{ showDate }}</span>
                    </div>
                    <div
                        class="flex items-center font-semibold text-indigo-600"
                    >
                        <span>{{ ticketPrice }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
