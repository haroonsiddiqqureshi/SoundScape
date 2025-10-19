<script setup>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import { useForm } from "@inertiajs/vue3";
import ConcertCreateForm from "@/Components/Concerts/ConcertCreateForm.vue";

defineProps({
    artists: Object,
});

const form = useForm({
    // Core Infomation
    name: "",
    description: "",
    status: "",
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
    start_show_date: null,
    start_show_time: null,
    end_show_date: null,
    end_show_time: null,
    start_sale_date: null,
    end_sale_date: null,

    // Additional Information
    ticket_link: "",
    artist_ids: [],
});

const submit = () => {
    form.post(route("admin.concert.store"));
};
</script>

<template>
    <AdminLayout title="Create Concert">
        <ConcertCreateForm :form="form" :artists="artists" @submit="submit" />
    </AdminLayout>
</template>
