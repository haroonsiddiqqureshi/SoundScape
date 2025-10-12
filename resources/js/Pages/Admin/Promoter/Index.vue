<script setup>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import PromoterTable from "@/Components/Promoters/PromoterTable.vue";
import { router } from "@inertiajs/vue3";

defineProps({
    promoters: Object,
    is_verified: Boolean,
});

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
    <AdminLayout title="Promoter">
        <div class="container mx-auto px-4 py-6">
            <div class="flex justify-between mb-4">
                <h1 class="text-2xl font-bold">Promoters List</h1>
            </div>
                <PromoterTable
                    :promoters="promoters"
                    @update-verification-status="updateVerificationStatus"
                />
            </div>
    </AdminLayout>
</template>
