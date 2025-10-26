<script setup>
import PromoterLayout from "@/Layouts/PromoterLayout.vue";
import ConcertEditForm from "@/Components/Concerts/ConcertEditForm.vue";
import { useForm } from "@inertiajs/vue3";
import { defineProps } from "vue";

const props = defineProps({
    concert: Object,
    artists: Object,
});

const form = useForm({
    // Core Infomation
    name: props.concert.name,
    description: props.concert.description,
    event_type: props.concert.event_type,
    genre: props.concert.genre,
    picture_url: null, // Always null on edit load, preview is handled by component

    // Location
    venue_name: props.concert.venue_name,
    province_id: props.concert.province_id,
    latitude: props.concert.latitude,
    longitude: props.concert.longitude,

    // Price
    price_min: props.concert.price_min,
    price_max: props.concert.price_max,

    // Date & Time
    start_show_date: props.concert.start_show_date,
    start_show_time: props.concert.start_show_time,
    end_show_date: props.concert.end_show_date,
    end_show_time: props.concert.end_show_time,
    start_sale_date: props.concert.start_sale_date,
    end_sale_date: props.concert.end_sale_date,

    // Additional Information
    ticket_link: props.concert.ticket_link,
    // Pre-populate the artist IDs from the loaded relationship
    artist_ids: props.concert.artists
        ? props.concert.artists.map((a) => a.id)
        : [],

    _method: "POST", // Inertia uses POST for updates with file uploads
});

function submit() {
    form.post(route("promoter.concert.update", { concert: props.concert.id }));
}
</script>

<template>
    <PromoterLayout title="Edit Concert">
        <ConcertEditForm
            :form="form"
            :concert="concert"
            :artists="artists"
            @submit="submit"
        />
    </PromoterLayout>
</template>
