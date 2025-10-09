<script setup>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import { Head, Link, router } from "@inertiajs/vue3";

defineProps({
    concerts: Object,
});
</script>

<template>
    <AdminLayout title="Concert">
        <div class="mb-6">
            <Link
                :href="route('admin.concert.create')"
                class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-md shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
            >
                <p>Create New Concert</p>
            </Link>
        </div>
        {{ concerts }}
    </AdminLayout>
</template>
