<script setup>
import { defineProps, computed } from "vue";
import { Link } from "@inertiajs/vue3";
import { PencilIcon } from "@heroicons/vue/24/solid";

const props = defineProps({
    concert: Object,
    role: String,
});

const editHref = computed(() => {
    if (props.role === 'admin') {
        return route('admin.concert.edit', { concert: props.concert.id });
    }
    return route('promoter.concert.edit', { concert: props.concert.id });
});

const imageUrl = computed(() => {
    if (props.concert.picture_url) {
        if (props.concert.picture_url.startsWith("http")) {
            return props.concert.picture_url;
        }
        return `/storage/${props.concert.picture_url}`;
    }
    return "https://via.placeholder.com/800x1200?text=No+Poster";
});

const showDate = computed(() => {
    if (!props.concert.start_datetime) return "TBA";
    const date = new Date(props.concert.start_datetime);
    return date.toLocaleDateString("en-US", {
        weekday: "long",
        year: "numeric",
        month: "long",
        day: "numeric",
    });
});

const showTime = computed(() => {
    if (!props.concert.start_datetime) return "";
    const date = new Date(props.concert.start_datetime);
    return date.toLocaleTimeString("en-US", {
        hour: "2-digit",
        minute: "2-digit",
        hour12: true,
    });
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

const googleMapsLink = computed(() => {
    if (props.concert.latitude && props.concert.longitude) {
        return `https://www.google.com/maps/search/?api=1&query=${props.concert.latitude},${props.concert.longitude}`;
    }
    return `https://www.google.com/maps/search/?api=1&query=${encodeURIComponent(
        props.concert.venue_name + ", " + props.concert.city
    )}`;
});

const statusClass = computed(() => {
    switch (props.concert.status) {
        case "upcoming":
            return "bg-blue-100 text-blue-800";
        case "completed":
            return "bg-green-100 text-green-800";
        case "cancelled":
            return "bg-red-100 text-red-800";
        default:
            return "bg-gray-100 text-gray-800";
    }
});
</script>

<template>
    <div class="container mx-auto p-4 md:p-8">
        <div class="bg-white rounded-lg shadow-xl overflow-hidden">
            <div class="md:flex">
                <!-- Left Column: Image -->
                <div class="md:w-1/3">
                    <img
                        :src="imageUrl"
                        :alt="`Poster for ${concert.name}`"
                        class="object-cover h-full w-full"
                    />
                </div>

                <!-- Right Column: Details -->
                <div class="md:w-2/3 p-6 md:p-8">
                    <div class="flex justify-between items-start mb-2">
                        <div>
                            <span
                                :class="statusClass"
                                class="text-sm font-semibold mr-2 px-2.5 py-0.5 rounded-full mb-2 inline-block"
                            >
                                {{
                                    concert.status.charAt(0).toUpperCase() +
                                    concert.status.slice(1)
                                }}
                            </span>
                            <h1
                                class="text-xl md:text-2xl font-bold text-gray-900 mb-4 break-all"
                            >
                                {{ concert.name }}
                            </h1>
                        </div>
                        <Link
                            v-if="concert.id"
                            :href="editHref"
                            class="inline-flex items-center px-3 py-2 text-sm font-medium text-white bg-indigo-600 border border-transparent rounded-md shadow-sm hover:bg-indigo-700"
                        >
                            <PencilIcon class="h-4 w-4 mr-2" />
                            Edit
                        </Link>
                    </div>

                    <div class="space-y-5 text-gray-700">
                        <div class="flex items-center">
                            <svg
                                class="w-6 h-6 mr-3 text-gray-500"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"
                                ></path>
                            </svg>
                            <span>{{ showDate }}</span>
                        </div>
                        <div class="flex items-center">
                            <svg
                                class="w-6 h-6 mr-3 text-gray-500"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"
                                ></path>
                            </svg>
                            <span>{{ showTime }}</span>
                        </div>
                        <div class="flex items-start">
                            <svg
                                class="w-6 h-6 mr-3 text-gray-500 flex-shrink-0"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"
                                ></path>
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"
                                ></path>
                            </svg>
                            <div class="flex justify-between w-full">
                                <p>
                                    {{ concert.venue_name }}, {{ concert.city }}
                                </p>
                                <a
                                    :href="googleMapsLink"
                                    target="_blank"
                                    class="text-blue-600 hover:underline"
                                >
                                    View on Google Maps
                                </a>
                            </div>
                        </div>
                        <div class="flex items-center">
                            <svg
                                class="w-6 h-6 mr-3 text-gray-500"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"
                                ></path>
                            </svg>
                            <span>{{ ticketPrice }}</span>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Description Section -->
            <div class="p-6 md:p-8 border-t border-gray-200">
                <h2 class="text-2xl font-bold text-gray-900 mb-4">
                    About this event
                </h2>
                <div class="prose max-w-none text-gray-600">
                    <p>{{ concert.description }}</p>
                </div>
            </div>
        </div>
    </div>
</template>
