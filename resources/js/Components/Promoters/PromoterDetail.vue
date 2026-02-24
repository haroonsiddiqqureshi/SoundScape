<script setup>
import { computed, inject } from "vue";

const props = defineProps({
    promoter: Object,
});

const emit = defineEmits([
    "show-full-bio",
    "show-full-address",
    "update-verification-status",
]);
const isDarkMode = inject("isDarkMode");

const promoterPicturePlaceholder = computed(() => {
    const name = encodeURIComponent(props.promoter?.fullname || "NA");

    return isDarkMode.value
        ? `https://ui-avatars.com/api/?name=${name}&background=1c1423&color=ffffff`
        : `https://ui-avatars.com/api/?name=${name}&background=e5e7eb&color=000000`;
});

const availableSocialLinks = computed(() => {
    if (!props.promoter.social_links) {
        return {};
    }
    return Object.fromEntries(
        Object.entries(props.promoter.social_links).filter(([_, link]) => link)
    );
});
</script>

<template>
    <div class="overflow-hidden space-y-2">
        <div class="bg-card rounded-md p-6 lg:px-20">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-6">
                    <img
                        class="h-16 w-16 rounded-full flex items-center justify-center object-cover"
                        :src="promoterPicturePlaceholder"
                        :alt="promoter.fullname"
                    />
                    <div>
                        <h2 class="lg:text-2xl font-semibold text-text">
                            {{ promoter.fullname }}
                        </h2>
                        <p class="text-sm text-text-medium">
                            {{ promoter.user.email }}
                        </p>
                    </div>
                </div>
                <div class="flex flex-col items-end space-y-2">
                    <span
                        @click="emit('update-verification-status', promoter)"
                        class="px-2.5 py-1 rounded-full text-xs font-medium cursor-pointer"
                        :class="
                            promoter.is_verified
                                ? 'bg-primary text-white'
                                : 'bg-background'
                        "
                    >
                        {{ promoter.is_verified ? "Verified" : "Not Verified" }}
                    </span>
                </div>
            </div>
        </div>

        <div
            class="bg-card rounded-md p-6 lg:px-20 grid grid-cols-1 md:grid-cols-2 gap-6"
        >
            <div class="p-6 bg-background rounded-md">
                <h3 class="text-lg font-semibold text-text mb-4">
                    Business Details
                </h3>
                <dl class="space-y-4">
                    <div>
                        <dt class="text-sm font-medium text-text/80">
                            Business Name
                        </dt>
                        <dd class="mt-1 text-sm text-text">
                            {{ promoter.business_name }}
                        </dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-text/80">
                            Business Type
                        </dt>
                        <dd class="mt-1 text-sm text-text">
                            {{ promoter.business_category }}
                        </dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-text/80">
                            Business Address
                        </dt>
                        <dd class="mt-1 text-sm text-text flex justify-between">
                            <span class="max-w-xs truncate">
                                {{ promoter.business_address }}
                            </span>
                            <button
                                @click="emit('show-full-address')"
                                class="text-primary hover:text-primary-hover text-sm ml-2"
                            >
                                View
                            </button>
                        </dd>
                    </div>
                </dl>
            </div>

            <div class="p-6 bg-background rounded-md">
                <h3 class="text-lg font-semibold text-text mb-4">
                    Contact & Bio
                </h3>
                <dl class="space-y-4">
                    <div>
                        <dt class="text-sm font-medium text-text/80">
                            Phone Number
                        </dt>
                        <dd class="mt-1 text-sm text-text">
                            {{ promoter.user.phone }}
                        </dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-text/80">Line</dt>
                        <dd class="mt-1 text-sm text-text">
                            {{ promoter.user?.line ?? "Not provided" }}
                        </dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-text/80">Bio</dt>
                        <dd class="mt-1 text-sm text-text flex justify-between">
                            <span class="max-w-xs truncate">
                                {{ promoter.bio || "Not provided" }}
                            </span>
                            <button
                                v-if="promoter.bio"
                                @click="emit('show-full-bio')"
                                class="text-primary hover:text-primary-hover text-sm ml-2"
                            >
                                View
                            </button>
                        </dd>
                    </div>
                </dl>
            </div>

            <div class="md:col-span-2 p-6 bg-background rounded-md">
                <h3 class="text-lg font-semibold text-text mb-4">
                    Social Links
                </h3>
                <div class="flex flex-wrap gap-4">
                    <a
                        v-for="(link, platform) in availableSocialLinks"
                        :key="platform"
                        :href="link"
                        target="_blank"
                        class="text-primary hover:text-primary-hover flex items-center space-x-2 transition-colors"
                    >
                        <span class="capitalize font-medium">{{
                            platform
                        }}</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</template>
