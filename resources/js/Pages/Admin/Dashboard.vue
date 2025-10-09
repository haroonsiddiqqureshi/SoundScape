<script setup>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import Welcome from "@/Pages/Admin/Welcome.vue";
</script>

<template>
    <AdminLayout title="Profile">
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
            <Welcome />
        </div>
    </AdminLayout>
</template>
