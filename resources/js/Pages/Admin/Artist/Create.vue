<script setup>
import { Head } from "@inertiajs/vue3";
import AdminLayout from "@/Layouts/AdminLayout.vue";
import ArtistCreate from "@/Components/Artists/ArtistCreate.vue";
</script>

<template>
    <AdminLayout title="Create Artist">
        <Head title="Create New Artist" />

        <ArtistCreate />
    </AdminLayout>
</template>
