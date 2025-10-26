<script setup>
import { Link } from "@inertiajs/vue3";
import AdminLayout from "@/Layouts/AdminLayout.vue";
import ArtistCard from "@/Components/Artists/ArtistCard.vue";

defineProps({
    artists: Object,
    filters: Object,
});
</script>

<template>
    <AdminLayout title="Artists Management">
        <ArtistCard :artists="artists" :filters="filters" />
        <div class="mt-8">
            <ul class="flex justify-center gap-2">
                <li v-for="link in artists.links">
                    <Link
                        class="px-4 py-2 border border-card hover:border-primary rounded-md hover:bg-primary hover:text-white font-semibold ring-0 bg-card transition-colors duration-150"
                        :href="link.url ?? route('admin.artist.index')"
                    >
                        <span v-html="link.label"></span>
                    </Link>
                </li>
            </ul>
        </div>
    </AdminLayout>
</template>
