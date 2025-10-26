<script setup>
import PromoterLayout from "@/Layouts/PromoterLayout.vue";
import { useForm } from "@inertiajs/vue3";
import ConcertCreateForm from "@/Components/Concerts/ConcertCreateForm.vue";

defineProps({
    artists: Object,
});

const form = useForm({
    // Core Infomation
    name: "",
    description: "",
    event_type: "",
    genre: "",
    picture_url: "",

    // Location
    venue_name: "",
    province_id: null,
    latitude: null,
    longitude: null,

    // Price
    price_min: null,
    price_max: null,

    // Date & Time
    start_sale_date: null,
    end_sale_date: null,
    start_show_date: null,
    start_show_time: null,
    end_show_date: null,
    end_show_time: null,

    // Additional Information
    ticket_link: "",
    artist_ids: [],
});

const submit = () => {
    form.post(route("promoter.concert.store"));
};
</script>

<template>
    <PromoterLayout title="Create Concert">
        <ConcertCreateForm :form="form" :artists="artists" @submit="submit" />
    </PromoterLayout>
</template>
