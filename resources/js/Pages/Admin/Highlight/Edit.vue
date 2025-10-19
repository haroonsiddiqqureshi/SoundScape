<script setup>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import HighlightEditForm from "@/Components/Highlights/HighlightEditForm.vue";
import { useForm } from "@inertiajs/vue3";
import { defineProps } from "vue";

const props = defineProps({
    highlight: Object,
    concerts: Array,
});

const form = useForm({
    title: props.highlight.title,
    description: props.highlight.description,
    link: props.highlight.link,
    concert_id: props.highlight.concert_id,
    is_active: props.highlight.is_active,
    picture_url: null, // Will be a file if changed
    _method: 'POST', // Inertia uses POST for updates with file uploads
});

function submit() {
    form.post(route("admin.highlight.update", { highlight: props.highlight.id }));
}
</script>

<template>
    <AdminLayout title="Edit Highlight">
        <div class="container mx-auto p-4 md:p-8">
            <HighlightEditForm :form="form" :highlight="highlight" :concerts="concerts" @submit="submit" />
        </div>
    </AdminLayout>
</template>
