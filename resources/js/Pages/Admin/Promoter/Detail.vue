<script setup>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import PromoterDetail from "@/Components/Promoters/PromoterDetail.vue";
import PromoterModal from "@/Components/Promoters/PromoterModal.vue";
import ConcertCard from "@/Components/Concerts/ConcertCard.vue";
import { ref } from "vue";
import { router, Link } from "@inertiajs/vue3";
import { FolderPlusIcon } from "@heroicons/vue/24/outline";

const props = defineProps({
    promoter: Object,
    concerts: Object,
    provinces: Object,
});

const showingFullBio = ref(false);
const showingFullAddress = ref(false);

const updateVerificationStatus = (promoter) => {
    router.put(
        route("admin.promoter.updateVerificationStatus", promoter.id),
        {
            is_verified: !promoter.is_verified,
        },
        {
            preserveState: true,
            preserveScroll: true,
        },
    );
};
</script>

<template>
    <AdminLayout title="Promoter Details">
        <PromoterDetail
            :promoter="promoter"
            @show-full-bio="showingFullBio = true"
            @show-full-address="showingFullAddress = true"
            @update-verification-status="updateVerificationStatus"
        />

        <div
            v-if="concerts.data && concerts.data.length > 0"
            class="bg-card rounded-md p-6 lg:px-20 mt-2"
        >
            <div
                class="p-6 bg-background rounded-md grid grid-cols-3 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 2xl:grid-cols-5 gap-6"
            >
                <Link
                    v-for="concert in concerts.data"
                    :key="concert.id"
                    :href="
                        route('admin.concert.detail', { concert: concert.id })
                    "
                    class="block"
                >
                    <ConcertCard :concert="concert" :provinces="provinces" />
                </Link>
            </div>
        </div>

        <div
            v-else
            class="bg-card rounded-md p-6 lg:px-20 mt-2"
        >
            <div class="w-full flex flex-col space-y-2 font-semibold items-center text-text-medium justify-center p-6 rounded-md bg-background">
                <FolderPlusIcon class="w-12 h-12 stroke-current" />
                <p>No concerts found for this promoter</p>
            </div>
        </div>

        <div class="mt-2" v-if="concerts.links && concerts.data.length > 0">
            <ul class="flex justify-center gap-2">
                <li v-for="(link, index) in concerts.links" :key="index">
                    <Link
                        class="px-4 py-2 border border-card hover:border-primary rounded-md hover:bg-primary hover:text-white font-semibold ring-0 bg-card transition-colors duration-150"
                        :class="{
                            'bg-primary text-white': link.active,
                        }"
                        :href="
                            link.url ??
                            route('admin.promoter.detail', {
                                promoter: promoter.id,
                            })
                        "
                    >
                        <span v-html="link.label"></span>
                    </Link>
                </li>
            </ul>
        </div>

        <PromoterModal
            :promoter="promoter"
            v-model:showingFullBio="showingFullBio"
            v-model:showingFullAddress="showingFullAddress"
        />
    </AdminLayout>
</template>
