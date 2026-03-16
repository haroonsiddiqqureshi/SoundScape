<script setup>
import PromoterLayout from "@/Layouts/PromoterLayout.vue";
import { useForm } from "@inertiajs/vue3";
import ConcertForm from "@/Components/Concerts/ConcertForm.vue";

defineProps({
    artists: Array,
});

const form = useForm({
    name: "",
    description: "",
    event_type: "",
    genre: "",
    picture_url: "",
    venue_name: "",
    province_id: null,
    latitude: null,
    longitude: null,
    price_min: null,
    price_max: null,
    start_sale_date: null,
    end_sale_date: null,
    start_show_date: null,
    start_show_time: null,
    end_show_date: null,
    end_show_time: null,
    ticket_link: "",
    artist_ids: [],
});

const submit = () => {
    form.post(route("promoter.concert.store"));
};
</script>

<template>
    <PromoterLayout title="Create Concert">
        <ConcertForm :form="form" :artists="artists" @submit="submit" />
    </PromoterLayout>
</template>
