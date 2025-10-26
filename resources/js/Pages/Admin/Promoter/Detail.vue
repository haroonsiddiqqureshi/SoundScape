<script setup>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import PromoterDetail from "@/Components/Promoters/PromoterDetail.vue";
import PromoterModal from "@/Components/Promoters/PromoterModal.vue";
import { ref } from "vue";
import { router } from "@inertiajs/vue3";

const props = defineProps({
    promoter: Object,
});

const showingFullBio = ref(false);
const showingFullAddress = ref(false);

const updateVerificationStatus = (promoter) => {
    router.put(
        route("admin.promoter.updateVerificationStatus", promoter.id),
        {
            is_verified: !promoter.is_verified,
        },
        {
            preserveState: true,
            preserveScroll: true,
        }
    );
};
</script>

<template>
    <AdminLayout title="Promoter Details">
        <PromoterDetail
            :promoter="promoter"
            @show-full-bio="showingFullBio = true"
            @show-full-address="showingFullAddress = true"
            @update-verification-status="updateVerificationStatus"
        />

        <!-- Bio & Address Modals -->
        <PromoterModal
            :promoter="promoter"
            v-model:showingFullBio="showingFullBio"
            v-model:showingFullAddress="showingFullAddress"
        />
    </AdminLayout>
</template>
