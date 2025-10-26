<script setup>
import { Head } from "@inertiajs/vue3";
import AdminLayout from "@/Layouts/AdminLayout.vue";
import ArtistCard from "@/Components/Artists/ArtistCard.vue";

// The page still receives props from Inertia
defineProps({
    artists: Object,
    filters: Object,
});
</script>

<template>
    <AdminLayout title="Artists">
        <Head title="Artists Management" />

        <ArtistCard :artists="artists" :filters="filters" />
    </AdminLayout>
</template>