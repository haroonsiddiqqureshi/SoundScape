<script setup>
import PromoterLayout from "@/Layouts/PromoterLayout.vue";
import ConcertEditForm from "@/Components/Concerts/ConcertEditForm.vue";
import { useForm } from "@inertiajs/vue3";
import { defineProps } from "vue";

const props = defineProps({
    concert: Object,
});

const form = useForm({
    name: props.concert.name,
    description: props.concert.description,
    status: props.concert.status,
    venue_name: props.concert.venue_name,
    city: props.concert.city,
    latitude: props.concert.latitude,
    longitude: props.concert.longitude,
    start_datetime: props.concert.start_datetime
        ? new Date(props.concert.start_datetime).toISOString().slice(0, 16)
        : "",
    price: props.concert.price,
    picture_url: null, // Will be a file if changed
    ticket_link: props.concert.ticket_link,
    _method: 'POST', // Inertia uses POST for updates with file uploads
});

function submit() {
    form.post(route("promoter.concert.update", { concert: props.concert.id }));
}
</script>

<template>
    <PromoterLayout title="Edit Concert">
        <div class="container mx-auto p-4 md:p-8">
            <ConcertEditForm :form="form" :concert="concert" @submit="submit" />
        </div>
    </PromoterLayout>
</template>
