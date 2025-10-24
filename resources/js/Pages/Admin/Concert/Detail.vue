<script setup>
import ConcertDetail from "@/Components/Concerts/ConcertDetail.vue";
import AdminLayout from "@/Layouts/AdminLayout.vue";

defineProps({
    concert: Object,
    provinces: Object,
});
</script>

<template>
    <AdminLayout title="Concert Detail">
        <ConcertDetail :concert="concert" :provinces="provinces" role="admin" />
    </AdminLayout>
</template>
