<script setup>
import { Head } from "@inertiajs/vue3";
import AdminLayout from "@/Layouts/AdminLayout.vue";
import ArtistEdit from "@/Components/Artists/ArtistEdit.vue";

defineProps({
    artist: Object,
});
</script>

<template>
    <AdminLayout title="Create Artist">
        <Head title="Create New Artist" />

        <ArtistEdit :artist="artist" />
    </AdminLayout>
</template>
