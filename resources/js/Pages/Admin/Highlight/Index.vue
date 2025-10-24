<script setup>
import { Head, Link, router } from "@inertiajs/vue3";
import AdminLayout from "@/Layouts/AdminLayout.vue";
import HighlightTable from "@/Components/Highlights/HighlightTable.vue";
import { ref } from "vue";

defineProps({
    highlights: Object,
    is_actove: Boolean,
});

const updateActiveStatus = (highlight) => {
    router.put(
        route("admin.highlight.updateActiveStatus", highlight.id),
        {
            is_active: !highlight.is_active,
        },
        {
            preserveState: true,
            preserveScroll: true,
        }
    );
};

const showModal = ref(false);
const modalImageUrl = ref("");

const openImageModal = (imageUrl) => {
    modalImageUrl.value = imageUrl;
    showModal.value = true;
};

const closeModal = () => {
    showModal.value = false;
    modalImageUrl.value = "";
};
</script>

<template>
    <AdminLayout title="Highlights">
        <div class="container mx-auto px-4 py-6">
            <div class="flex justify-between mb-4">
                <h1 class="text-2xl font-bold">Highlights</h1>
                <Link
                    :href="route('admin.highlight.create')"
                    class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600"
                    >Add New Highlight</Link
                >
            </div>
            <HighlightTable
                :highlights="highlights"
                @update-active-status="updateActiveStatus"
                @open-image-modal="openImageModal"
            />
        </div>

        <!-- Modal -->
        <div
            v-if="showModal"
            class="fixed inset-0 z-50 flex items-center justify-center bg-text-reverse bg-opacity-50"
            @click="closeModal"
        >
            <div class="p-2 bg-white rounded-lg" @click.stop>
                <img
                    :src="modalImageUrl"
                    alt="Full size highlight image"
                    class="max-w-full h-auto max-h-[80vh] rounded-lg"
                />
            </div>
        </div>
    </AdminLayout>
</template>
