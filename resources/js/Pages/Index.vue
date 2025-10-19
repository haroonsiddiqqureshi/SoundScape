<script setup>
import { usePage, Link } from "@inertiajs/vue3";
import AppLayout from "@/Layouts/AppLayout.vue";
import HighlightBanner from "@/Components/Highlights/HighlightBanner.vue";
import ConcertCard from "@/Components/Concerts/ConcertCard.vue";

const page = usePage();

defineProps({
    highlights: Object,
    concerts: Object,
    provinces: Object,
});

</script>

<template>
    <AppLayout
        title="Home"
        :can-login="$page.props.canLogin"
        :can-register="$page.props.canRegister"
    >
        <div class="py-12 mx-4 sm:mx-0 bg-background">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <HighlightBanner :highlights="page.props.highlights" />
            </div>

            <div class="mt-10 max-w-7xl mx-auto sm:px-6 lg:px-8">
                <h2 class="text-2xl font-semibold text-text mb-4">
                    All Concerts
                </h2>
                <div
                    v-if="concerts && concerts.length > 0"
                    class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-6"
                >
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
                        <ConcertCard
                            :concert="concert"
                            :provinces="provinces"
                            :is-user="true"
                        />
                    </Link>
                </div>

                <div v-else class="text-center py-12 text-text-medium">
                    <p class="text-lg font-medium">No concerts found.</p>
                    <p class="text-sm mt-2">
                        Check back later or explore other events.
                    </p>
                </div>
            </div>
        </div>
    </AppLayout>
</template>