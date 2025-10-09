<script setup>
import ConcertDetail from "@/Components/ConcertDetail.vue";
import PromoterLayout from "@/Layouts/PromoterLayout.vue";

defineProps({
    concert: Object,
});
</script>

<template>
    <PromoterLayout title="Concert Detail">
        <ConcertDetail :concert="concert" />
    </PromoterLayout>
</template>
