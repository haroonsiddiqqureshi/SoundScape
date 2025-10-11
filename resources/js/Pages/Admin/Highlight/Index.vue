<script setup>
import { Head, Link, router } from "@inertiajs/vue3";
import AdminLayout from "@/Layouts/AdminLayout.vue";
import HighlightCard from "@/Components/HighlightCard.vue";
import { ref } from "vue";

defineProps({
    highlights: Object,
});
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
            <div
                v-if="highlights && highlights.data && highlights.data.length > 0"
                class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6"
            >
                <Link
                    v-for="highlight in highlights.data"
                    :key="highlight.id"
                    :href="route('admin.highlight.edit', { highlight: highlight.id })"
                    class="block"
                >
                    <HighlightCard :highlight="highlight" />
                </Link>
            </div>
            <div
                v-else-if="highlights && highlights.length > 0"
                class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6"
            >
                <Link
                    v-for="highlight in highlights"
                    :key="highlight.id"
                    :href="
                        route('admin.highlight.detail', { highlight: highlight.id })
                    "
                    class="block"
                >
                    <HighlightCard :highlight="highlight" />
                </Link>
            </div>
            <div v-else class="text-center py-12 bg-gray-50 rounded-lg">
                <h3 class="text-xl font-medium text-gray-700">
                    No Highlights Found
                </h3>
                <p class="mt-1 text-sm text-gray-500">
                    Get started by creating a new highlight.
                </p>
            </div>
        </div>
    </AdminLayout>
</template>
