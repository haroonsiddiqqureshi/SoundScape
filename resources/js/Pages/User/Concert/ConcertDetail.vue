<script setup>
import { computed } from "vue";
import { usePage } from "@inertiajs/vue3";
import GuestLayout from "@/Layouts/GuestLayout.vue";
import UserLayout from "@/Layouts/UserLayout.vue";

const page = usePage();

const props = defineProps({
    concert: Object,
});

const layout = computed(() => {
    return page.props.auth.user ? UserLayout : GuestLayout;
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

const showDate = computed(() => {
    if (!props.concert?.start_datetime)
        return { day: "", month: "TBA", year: "" };
    const date = new Date(props.concert.start_datetime);
    return {
        day: date.toLocaleDateString("en-US", { day: "2-digit" }),
        month: date.toLocaleDateString("en-US", { month: "short" }),
        year: date.toLocaleDateString("en-US", { year: "numeric" }),
    };
});

const timeRange = computed(() => {
    if (!props.concert?.start_datetime || !props.concert?.end_datetime) {
        return null;
    }
    const startTime = new Date(props.concert.start_datetime).toLocaleTimeString([], {
        hour: '2-digit',
        minute: '2-digit'
    });
    const endTime = new Date(props.concert.end_datetime).toLocaleTimeString([], {
        hour: '2-digit',
        minute: '2-digit'
    });
    return `${startTime} - ${endTime}`;
});


const ticketPrice = computed(() => {
    if (props.concert?.price) {
        return `${new Intl.NumberFormat("en-US", {
            style: "currency",
            currency: "THB",
        }).format(props.concert.price)}`;
    }
    return "Free";
});
</script>

<template>
    <component
        :is="layout"
        title="Concert Details"
        :can-login="$page.props.canLogin"
        :can-register="$page.props.canRegister"
    >
        <div class="py-12 bg-background">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 px-4">
                <div class="bg-card shadow-sm rounded-lg overflow-hidden">
                    <img
                        :src="pictureUrl"
                        alt="Concert Picture"
                        class="w-full h-64 object-cover"
                    />
                    <div class="p-6">
                        <h2 class="text-2xl font-semibold text-text mb-2">
                            {{ concert.name }}
                        </h2>
                        <p class="text-gray-500 mb-4">
                            {{ concert.venue }} - {{ concert.location }}
                        </p>
                        <p class="text-text mb-4">
                            {{ concert.description }}
                        </p>
                        <p class="text-text mb-4">
                            <strong>Ticket Price:</strong> {{ ticketPrice }}
                        </p>
                        <div class="flex items-center mb-4">
                            <div class="text-center mr-6">
                                <div class="text-3xl font-bold text-primary">
                                    {{ showDate.day }}
                                </div>
                                <div class="text-sm text-text-medium">
                                    {{ showDate.month }}
                                </div>
                                <div class="text-sm text-text-medium">
                                    {{ showDate.year }}
                                </div>
                            </div>
                            <div class="text-text-medium">
                                <div>{{ timeRange }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </component>
</template>