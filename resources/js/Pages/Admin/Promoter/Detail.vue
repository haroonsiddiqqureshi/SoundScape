<script setup>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import AdminPromoterModal from "@/Components/AdminPromoterModal.vue";
import { Head, Link, router } from "@inertiajs/vue3";
import { computed, ref } from "vue";

const props = defineProps({
    promoter: Object,
});

const availableSocialLinks = computed(() => {
    if (!props.promoter.social_links) {
        return {};
    }
    return Object.fromEntries(
        Object.entries(props.promoter.social_links).filter(([_, link]) => link)
    );
});

const updateVerificationStatus = (promoter) => {
    router.put(
        route("admin.promoter.updateVerificationStatus", promoter.id),
        {
            is_verified: !promoter.is_verified,
        },
        {
            preserveState: true,
            preserveScroll: true,
        }
    );
};

const showingFullBio = ref(false);
const showingFullAddress = ref(false);
</script>

<template>
    <AdminLayout title="Promoter Details">
        <div class="py-12">
            <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white shadow-xl sm:rounded-lg">
                    <div class="p-6 sm:px-20 bg-white border-b border-gray-200">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-6">
                                <img
                                    class="h-18 w-18 rounded-full flex items-center justify-center object-cover"
                                    :src="promoter.user.profile_photo_url"
                                    :alt="promoter.user.name"
                                />
                                <div>
                                    <h2
                                        class="text-2xl font-semibold text-gray-800"
                                    >
                                        {{ promoter.fullname }}
                                    </h2>
                                    <p class="text-sm text-gray-600">
                                        {{ promoter.user.email }}
                                    </p>
                                </div>
                            </div>
                            <div class="flex flex-col items-end space-y-2">
                                <div class="flex items-center space-x-4">
                                    <span
                                        class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full"
                                        :class="
                                            promoter.is_verified
                                                ? 'bg-green-100 text-green-800'
                                                : 'bg-red-100 text-red-800'
                                        "
                                    >
                                        {{
                                            promoter.is_verified
                                                ? "Verified"
                                                : "Not Verified"
                                        }}
                                    </span>
                                    <button
                                        @click="
                                            updateVerificationStatus(promoter)
                                        "
                                        class="relative inline-flex flex-shrink-0 h-6 w-11 border-2 border-transparent rounded-full cursor-pointer transition-colors ease-in-out duration-200"
                                        :class="
                                            promoter.is_verified
                                                ? 'bg-green-600'
                                                : 'bg-gray-200'
                                        "
                                    >
                                        <span
                                            aria-hidden="true"
                                            class="pointer-events-none inline-block h-5 w-5 rounded-full bg-white shadow transform transition ease-in-out duration-200"
                                            :class="{
                                                'translate-x-5':
                                                    promoter.is_verified,
                                                'translate-x-0':
                                                    !promoter.is_verified,
                                            }"
                                        ></span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div
                        class="p-6 sm:px-20 bg-white grid grid-cols-1 md:grid-cols-2 gap-6"
                    >
                        <!-- Business Details -->
                        <div class="p-6 bg-gray-50 rounded-lg">
                            <h3
                                class="text-lg font-semibold text-gray-800 mb-4"
                            >
                                Business Details
                            </h3>
                            <dl class="space-y-4">
                                <div>
                                    <dt
                                        class="text-sm font-medium text-gray-500"
                                    >
                                        Business Name
                                    </dt>
                                    <dd class="mt-1 text-sm text-gray-900">
                                        {{ promoter.business_name }}
                                    </dd>
                                </div>
                                <div>
                                    <dt
                                        class="text-sm font-medium text-gray-500"
                                    >
                                        Business Type
                                    </dt>
                                    <dd class="mt-1 text-sm text-gray-900">
                                        {{ promoter.business_category }}
                                    </dd>
                                </div>
                                <div>
                                    <dt
                                        class="text-sm font-medium text-gray-500"
                                    >
                                        Business Address
                                    </dt>
                                    <dd
                                        class="mt-1 text-sm text-gray-900 flex justify-between"
                                    >
                                        <span class="max-w-xs truncate">{{
                                            promoter.business_address
                                        }}</span>
                                        <button
                                            @click="showingFullAddress = true"
                                            class="text-indigo-600 hover:text-indigo-900 text-sm ml-2"
                                        >
                                            View
                                        </button>
                                    </dd>
                                </div>
                            </dl>
                        </div>

                        <!-- Contact and Bio -->
                        <div class="p-6 bg-gray-50 rounded-lg">
                            <h3
                                class="text-lg font-semibold text-gray-800 mb-4"
                            >
                                Contact & Bio
                            </h3>
                            <dl class="space-y-4">
                                <div>
                                    <dt
                                        class="text-sm font-medium text-gray-500"
                                    >
                                        Phone Number
                                    </dt>
                                    <dd class="mt-1 text-sm text-gray-900">
                                        {{ promoter.user.phone }}
                                    </dd>
                                </div>
                                <div>
                                    <dt
                                        class="text-sm font-medium text-gray-500"
                                    >
                                        Line
                                    </dt>
                                    <dd class="mt-1 text-sm text-gray-900">
                                        {{
                                            promoter.user?.line ??
                                            "Not provided"
                                        }}
                                    </dd>
                                </div>
                                <div>
                                    <dt
                                        class="text-sm font-medium text-gray-500"
                                    >
                                        Bio
                                    </dt>
                                    <dd
                                        class="mt-1 text-sm text-gray-900 flex justify-between"
                                    >
                                        <span class="max-w-xs truncate">{{
                                            promoter.bio
                                        }}</span>
                                        <button
                                            @click="showingFullBio = true"
                                            class="text-indigo-600 hover:text-indigo-900 text-sm ml-2"
                                        >
                                            View
                                        </button>
                                    </dd>
                                </div>
                            </dl>
                        </div>

                        <!-- Social Links -->
                        <div class="md:col-span-2 p-6 bg-gray-50 rounded-lg">
                            <h3
                                class="text-lg font-semibold text-gray-800 mb-4"
                            >
                                Social Links
                            </h3>
                            <div class="flex flex-wrap gap-4">
                                <a
                                    v-for="(
                                        link, platform
                                    ) in availableSocialLinks"
                                    :key="platform"
                                    :href="link"
                                    target="_blank"
                                    class="text-indigo-600 hover:text-indigo-900 flex items-center space-x-2"
                                >
                                    <!-- Add icons here -->
                                    <span class="capitalize">{{
                                        platform
                                    }}</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bio & Address Modals -->
        <AdminPromoterModal
            :promoter="promoter"
            v-model:showingFullBio="showingFullBio"
            v-model:showingFullAddress="showingFullAddress"
        />
    </AdminLayout>
</template>
