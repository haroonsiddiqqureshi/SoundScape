<script setup>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import { useForm } from "@inertiajs/vue3";
import CreateConcertForm from "@/Components/CreateConcertForm.vue";

const form = useForm({
    name: "",
    description: "",
    status: "upcoming",
    venue_name: "",
    city: "",
    latitude: null,
    longitude: null,
    concert_datetime: "",
    price: null,
    picture_url: "",
    ticket_link: "",
});

const submit = () => {
    form.post(route("admin.concert.store"));
};
</script>

<template>
    <AdminLayout title="Create Concert">
        <CreateConcertForm :form="form" @submit="submit" />
    </AdminLayout>
</template>
