<script setup>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import ConcertCard from "@/Components/Concerts/ConcertCard.vue";
import { Head, Link, router } from "@inertiajs/vue3";

const props = defineProps({
    concerts: Object,
    provinces: Object,
});
</script>

<template>
    <AdminLayout title="Concert">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold">Concerts</h1>
            <Link
                :href="route('admin.concert.create')"
                class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-md shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
            >
                Create New Concert
            </Link>
        </div>
        <div
            v-if="concerts && concerts.length > 0"
            class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-6"
        >
            <Link
                v-for="concert in concerts"
                :key="concert.id"
                :href="route('admin.concert.detail', { concert: concert.id })"
                class="block"
            >
                <ConcertCard :concert="concert" :provinces="provinces" />
            </Link>
        </div>
        <div v-else class="text-center py-12 bg-gray-50 rounded-lg">
            <h3 class="text-xl font-medium text-gray-700">No Concerts Found</h3>
            <p class="mt-1 text-sm text-gray-500">
                Get started by creating a new concert.
            </p>
        </div>
    </AdminLayout>
</template>
