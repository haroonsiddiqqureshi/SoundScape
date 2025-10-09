<script setup>
import { computed } from "vue";
import PromoterLayout from "@/Layouts/PromoterLayout.vue";
import Pending from "./Components/Pending.vue";
import Dashboard from "./Components/Dashboard.vue";

const props = defineProps({
    promoter: Object,
});

const isVerified = computed(() => {
    const v = props.promoter?.is_verified;
    return Number(v) === 1 || v === true;
});

</script>

<template>
    <PromoterLayout title="Promoter Dashboard">
        <div v-if="isVerified">
            <Dashboard />
        </div>
        <div v-else-if="!isVerified">
            <Pending />
        </div>
    </PromoterLayout>
</template>