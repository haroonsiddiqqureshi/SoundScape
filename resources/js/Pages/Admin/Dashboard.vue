<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import Welcome from '@/Pages/Admin/Welcome.vue';
</script>

<template>
    <AdminLayout title="Dashboard">

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <Welcome />
                </div>
            </div>
        </div>
    </AdminLayout>
</template>
