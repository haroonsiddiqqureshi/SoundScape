<script setup>
import ConcertDetail from "@/Components/Concerts/ConcertDetail.vue";
import PromoterLayout from "@/Layouts/PromoterLayout.vue";

defineProps({
    concert: Object,
    provinces: Object,
});
</script>

<template>
    <PromoterLayout title="Concert Detail">
        <ConcertDetail
            :concert="concert"
            :provinces="provinces"
            role="promoter"
        />
    </PromoterLayout>
</template>
