<script setup>
import ConcertDetail from "@/Components/Concerts/ConcertDetail.vue";
import AppLayout from "@/Layouts/AppLayout.vue";

defineProps({
    concert: Object,
    provinces: Object,
});
</script>

<template>
    <AppLayout title="Concert Detail">
        <ConcertDetail :concert="concert" :provinces="provinces" />
    </AppLayout>
</template>
