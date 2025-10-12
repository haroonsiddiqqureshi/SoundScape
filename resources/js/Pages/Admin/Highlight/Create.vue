<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import HighlightCreateForm from '@/Components/Highlights/HighlightCreateForm.vue';
import { useForm } from '@inertiajs/vue3';

const props = defineProps({
    concerts: Array,
});

const form = useForm({
    title: '',
    description: '',
    picture_url: '',
    link: '',
    concert_id: null,
    is_active: false,
});

const submit = () => {
    form.post(route("admin.highlight.store"));
};
</script>

<template>
    <AdminLayout title="Create Highlight">
        <HighlightCreateForm :form="form" :concerts="concerts" @submit="submit" />
    </AdminLayout>
</template>