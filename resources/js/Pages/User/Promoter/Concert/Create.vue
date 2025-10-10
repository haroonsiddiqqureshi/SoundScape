<script setup>
import PromoterLayout from "@/Layouts/PromoterLayout.vue";
import { useForm } from "@inertiajs/vue3";
import CreateConcertForm from "@/Components/ConcertCreateForm.vue";
import ConcertCreateForm from "@/Components/ConcertCreateForm.vue";

const form = useForm({
    name: "",
    description: "",
    status: "upcoming",
    venue_name: "",
    city: "",
    latitude: null,
    longitude: null,
    start_datetime: "",
    price: null,
    picture_url: "",
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
