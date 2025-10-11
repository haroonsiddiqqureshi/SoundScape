<script setup>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import { useForm } from "@inertiajs/vue3";
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
    form.post(route("admin.concert.store"));
};
</script>

<template>
    <AdminLayout title="Create Concert">
        <ConcertCreateForm :form="form" @submit="submit" />
    </AdminLayout>
</template>
