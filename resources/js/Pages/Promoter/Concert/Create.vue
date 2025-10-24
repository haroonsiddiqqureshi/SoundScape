<script setup>
import PromoterLayout from "@/Layouts/PromoterLayout.vue";
import { useForm } from "@inertiajs/vue3";
import CreateConcertForm from "@/Components/Concerts/ConcertCreateForm.vue";
import ConcertCreateForm from "@/Components/Concerts/ConcertCreateForm.vue";

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
    start_show: "",
    end_show: "",
    start_sale: "To be announced",
    end_sale: "",

    // Additional Information
    ticket_link: "",
});

const submit = () => {
    form.post(route("promoter.concert.store"));
};
</script>

<template>
    <PromoterLayout title="Create Concert">
        <ConcertCreateForm :form="form" @submit="submit" />
    </PromoterLayout>
</template>
