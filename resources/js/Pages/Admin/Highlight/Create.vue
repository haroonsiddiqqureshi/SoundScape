<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import HighlightCreateForm from '@/Components/HighlightCreateForm.vue';
import { useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

const form = useForm({
    title: '',
    description: '',
    picture_url: '',
});

const submit = () => {
    form.post(route("admin.highlight.store"));
};
</script>

<template>
    <AdminLayout title="Create Highlight">
        <HighlightCreateForm :form="form" @submit="submit" />
    </AdminLayout>
</template>