<script setup>
import PromoterLayout from "@/Layouts/PromoterLayout.vue";
import ConcertForm from "@/Components/Concerts/ConcertForm.vue";
import { useForm } from "@inertiajs/vue3";
import { defineProps } from "vue";

const props = defineProps({
    concert: Object,
    artists: Array,
});

const form = useForm({
    name: props.concert.name,
    description: props.concert.description,
    event_type: props.concert.event_type,
    genre: props.concert.genre,
    picture_url: null,
    venue_name: props.concert.venue_name,
    province_id: props.concert.province_id,
    latitude: props.concert.latitude,
    longitude: props.concert.longitude,
    price_min: props.concert.price_min,
    price_max: props.concert.price_max,
    start_show_date: props.concert.start_show_date,
    start_show_time: props.concert.start_show_time,
    end_show_date: props.concert.end_show_date,
    end_show_time: props.concert.end_show_time,
    start_sale_date: props.concert.start_sale_date,
    end_sale_date: props.concert.end_sale_date,
    ticket_link: props.concert.ticket_link,
    artist_ids: props.concert.artists
        ? props.concert.artists.map((a) => a.id)
        : [],

    _method: "POST",
});

function submit() {
    form.post(route("promoter.concert.update", { concert: props.concert.id }));
}
</script>

<template>
    <PromoterLayout title="Edit Concert">
        <ConcertForm :form="form" :artists="artists" :concert="concert" @submit="submit" />
    </PromoterLayout>
</template>
