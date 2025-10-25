<script setup>
import ConcertDetail from "@/Components/Concerts/ConcertDetail.vue";
import AppLayout from "@/Layouts/AppLayout.vue";

defineProps({
    concert: Object,
    provinces: Object,
});
</script>

<template>
    <AppLayout
        title="Concert Detail"
        :can-login="$page.props.canLogin"
        :can-register="$page.props.canRegister"
    >
        <ConcertDetail :concert="concert" :provinces="provinces" />
    </AppLayout>
</template>
