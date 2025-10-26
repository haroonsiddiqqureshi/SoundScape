<script setup>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import ConcertCard from "@/Components/Concerts/ConcertCard.vue";
import { Head, Link, router } from "@inertiajs/vue3";
import { PlusIcon } from "@heroicons/vue/24/solid";

const props = defineProps({
    concerts: Object,
    provinces: Object,
});
</script>

<template>
    <AdminLayout title="Concert">
        <div
            v-if="props.concerts.length"
            class="grid grid-cols-3 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 2xl:grid-cols-5 gap-6"
        >
            <Link
                :href="route('admin.concert.create')"
                class="inline-flex py-16 items-center justify-center text-primary bg-primary-low rounded-md outline-dashed outline-4 -outline-offset-4"
            >
                <PlusIcon class="w-12 h-12 stroke-current" />
            </Link>
            <Link
                v-for="concert in concerts"
                :key="concert.id"
                :href="
                    route('admin.concert.detail', {
                        concert: concert.id,
                    })
                "
                class="block"
            >
                <ConcertCard :concert="concert" :provinces="provinces" />
            </Link>
        </div>
        <Link
            v-else
            :href="route('admin.concert.create')"
            class="w-full flex items-center justify-center py-20 outline-dashed outline-4 rounded-md text-primary bg-primary-low"
        >
            <PlusIcon class="w-12 h-12 stroke-current" />
        </Link>
    </AdminLayout>
</template>
