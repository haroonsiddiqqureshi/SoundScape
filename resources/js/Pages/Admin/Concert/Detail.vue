<script setup>
import ConcertDetail from "@/Components/ConcertDetail.vue";
import AdminLayout from "@/Layouts/AdminLayout.vue";

defineProps({
    concert: Object,
});
</script>

<template>
    <AdminLayout title="Concert Detail">
        <ConcertDetail :concert="concert" role="admin" />
    </AdminLayout>
</template>
