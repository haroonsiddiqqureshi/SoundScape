<script setup>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import PromoterDetail from "@/Components/Promoters/PromoterDetail.vue";
import AdminPromoterModal from "@/Components/AdminPromoterModal.vue";
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
        <div class="py-12">
            <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
                <PromoterDetail
                    :promoter="promoter"
                    @show-full-bio="showingFullBio = true"
                    @show-full-address="showingFullAddress = true"
                    @update-verification-status="updateVerificationStatus"
                />
            </div>
        </div>

        <!-- Bio & Address Modals -->
        <AdminPromoterModal
            :promoter="promoter"
            v-model:showingFullBio="showingFullBio"
            v-model:showingFullAddress="showingFullAddress"
        />
    </AdminLayout>
</template>
