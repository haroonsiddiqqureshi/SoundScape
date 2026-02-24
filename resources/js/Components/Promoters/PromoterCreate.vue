<script setup>
import { ref, watch, onMounted } from "vue";
import { router } from "@inertiajs/vue3";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import DropdownSelector from "@/Components/DropdownSelector.vue";
import InputLabel from "@/Components/InputLabel.vue";
import InputError from "@/Components/InputError.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import {
    UserIcon,
    BuildingStorefrontIcon,
    MapPinIcon,
    GlobeAltIcon,
    PlusIcon,
    TrashIcon,
} from "@heroicons/vue/24/outline";

const props = defineProps({
    form: Object,
});

const emit = defineEmits(["submit"]);

const goToHome = () => {
    router.visit(route("index"));
};

const BusinessCategories = [
    { value: "individual", name: "โปรโมเตอร์ส่วนบุคคล" },
    { value: "company", name: "บริษัทจัดการศิลปิน" },
    { value: "nightclub", name: "ไนท์คลับ/สถานที่" },
    { value: "other", name: "อื่นๆ" },
];

const platformPatterns = [
    {
        key: "facebook",
        regex: /facebook\.com|fb\.com/i,
        label: "Facebook",
        color: "text-blue-600",
        icon: "M18 2h-3a5 5 0 00-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 011-1h3z",
    },
    {
        key: "instagram",
        regex: /instagram\.com/i,
        label: "Instagram",
        color: "text-pink-600",
        icon: "M16 11.37A4 4 0 1112.63 8 4 4 0 0116 11.37zm1.5-4.87h.01M7.8 2h8.4C19.4 2 22 4.6 22 7.8v8.4a5.8 5.8 0 01-5.8 5.8H7.8C4.6 22 2 19.4 2 16.2V7.8A5.8 5.8 0 017.8 2m0 2a3.8 3.8 0 00-3.8 3.8v8.4A3.8 3.8 0 007.8 20h8.4a3.8 3.8 0 003.8-3.8V7.8A3.8 3.8 0 0016.2 4H7.8z",
    },
    {
        key: "tiktok",
        regex: /tiktok\.com/i,
        label: "TikTok",
        color: "text-gray-900",
        icon: "M12.5 4v11.5a3.5 3.5 0 11-3.5-3.5V8a7.5 7.5 0 007.5 7.5v-4A3.5 3.5 0 0112.5 4z M19 8v4a7.5 7.5 0 01-3.5-1.5V8A3.5 3.5 0 0119 8z",
    },
    {
        key: "twitter",
        regex: /twitter\.com|x\.com/i,
        label: "Twitter",
        color: "text-sky-500",
        icon: "M23 3a10.9 10.9 0 01-3.14 1.53 4.48 4.48 0 00-7.86 3v1A10.66 10.66 0 013 4s-4 9 5 13a11.64 11.64 0 01-7 2c9 5 20 0 20-11.5a4.5 4.5 0 00-.08-.83A7.72 7.72 0 0023 3z",
    },
    {
        key: "youtube",
        regex: /youtube\.com|youtu\.be/i,
        label: "YouTube",
        color: "text-red-600",
        icon: "M21.58 5.4c-.92-.92-2.42-.92-2.42-.92S15.6 4 12 4s-7.16.48-7.16.48-1.5 0-2.42.92c-.92.92-.92 2.84-.92 5.6s0 4.68.92 5.6c.92.92 2.42.92 2.42.92s3.56.48 7.16.48 7.16-.48 7.16-.48 1.5 0 2.42-.92c.92-.92.92-2.84.92-5.6s0-4.68-.92-5.6zM10 13.5V8.5l4.5 2.5-4.5 2.5z",
    },
];

const dynamicLinks = ref([]);

onMounted(() => {
    let hasData = false;
    for (const [key, value] of Object.entries(props.form.social_links)) {
        if (value) {
            dynamicLinks.value.push({ url: value, platform: key });
            hasData = true;
        }
    }
    if (!hasData) addLink();
});

const addLink = () => dynamicLinks.value.push({ url: "", platform: "website" });

const removeLink = (index) => {
    dynamicLinks.value.splice(index, 1);
    if (dynamicLinks.value.length === 0) addLink();
};

const detectPlatform = (url) => {
    if (!url) return "website";
    const match = platformPatterns.find((p) => p.regex.test(url));
    return match ? match.key : "website";
};

const getPlatformInfo = (key) =>
    platformPatterns.find((item) => item.key === key);

watch(
    dynamicLinks,
    (newLinks) => {
        Object.keys(props.form.social_links).forEach(
            (key) => (props.form.social_links[key] = ""),
        );
        newLinks.forEach((link) => {
            const platform = detectPlatform(link.url);
            link.platform = platform;
            if (props.form.social_links.hasOwnProperty(platform)) {
                props.form.social_links[platform] = link.url;
            } else {
                props.form.social_links.website = link.url;
            }
        });
    },
    { deep: true },
);

const submit = () => emit("submit");
</script>

<template>
    <form @submit.prevent="submit" class="mx-auto">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-y-6 gap-x-12 mb-12">
            <div>
                <h3 class="text-xl font-bold">General Information</h3>
                <p class="text-sm text-text-medium mt-2 leading-relaxed">
                    Personal and business details that will be displayed on your
                    public profile.
                </p>
            </div>

            <div
                class="lg:col-span-2 space-y-6 bg-card shadow-sm rounded-md p-8"
            >
                <div class="space-y-6">
                    <div class="flex items-center gap-2 text-primary">
                        <UserIcon class="h-4 w-4" />
                        <span
                            class="text-xs font-black uppercase tracking-widest"
                            >Identity</span
                        >
                        <div class="h-px grow bg-primary-medium ml-2"></div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-1.5">
                            <InputLabel value="Full Name" />
                            <input
                                v-model="form.fullname"
                                type="text"
                                class="w-full text-sm rounded-lg focus:ring-primary bg-background border-none focus:border-primary"
                                placeholder="e.g. John Doe"
                                :class="{
                                    'outline-dashed outline-primary -outline-offset-2 rounded-md':
                                        props.form.errors.fullname,
                                }"
                            />
                        </div>
                        <div class="space-y-1.5">
                            <InputLabel value="Business Name" />
                            <input
                                v-model="form.business_name"
                                type="text"
                                class="w-full text-sm rounded-lg focus:ring-primary bg-background border-none focus:border-primary"
                                placeholder="e.g. SoundScape"
                                :class="{
                                    'outline-dashed outline-primary -outline-offset-2 rounded-md':
                                        props.form.errors.business_name,
                                }"
                            />
                        </div>
                    </div>
                </div>

                <div class="space-y-6">
                    <div class="flex items-center gap-2 text-primary">
                        <BuildingStorefrontIcon class="h-4 w-4" />
                        <span
                            class="text-xs font-black uppercase tracking-widest"
                            >Business Details</span
                        >
                        <div class="h-px grow bg-primary-medium ml-2"></div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-1.5">
                            <InputLabel value="Category" />
                            <DropdownSelector
                                v-model="form.business_category"
                                :options="BusinessCategories"
                            />
                        </div>
                        <div class="space-y-1.5">
                            <InputLabel value="Business Address" />
                            <div class="relative">
                                <input
                                    v-model="form.business_address"
                                    type="text"
                                    class="w-full text-sm pl-9 rounded-lg focus:ring-primary bg-background border-none focus:border-primary"
                                    placeholder="City, Country"
                                    :class="{
                                        'outline-dashed outline-primary -outline-offset-2 rounded-md':
                                            props.form.errors.business_address,
                                    }"
                                />
                                <MapPinIcon
                                    class="absolute left-3 top-2.5 h-4 w-4 text-text-medium"
                                />
                            </div>
                        </div>
                        <div class="md:col-span-2 space-y-1.5">
                            <InputLabel value="Short Bio" />
                            <textarea
                                v-model="form.bio"
                                rows="3"
                                class="w-full text-sm rounded-lg focus:ring-primary bg-background border-none focus:border-primary"
                                placeholder="Tell us a bit about what you do..."
                                :class="{
                                    'outline-dashed outline-primary -outline-offset-2 rounded-md':
                                        props.form.errors.bio,
                                }"
                            ></textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div
            class="grid grid-cols-1 lg:grid-cols-3 gap-y-6 gap-x-12 mb-12 border-t border-text-low pt-12"
        >
            <div>
                <h3 class="text-xl font-bold">Social Media Platform</h3>
                <p class="text-sm text-text-medium mt-2 leading-relaxed">
                    Connect your platforms. We'll automatically identify the
                    service based on the URL.
                </p>
            </div>

            <div
                class="lg:col-span-2 space-y-6 bg-card shadow-sm rounded-md p-8"
            >
                <TransitionGroup name="list" tag="div" class="space-y-3">
                    <div
                        v-for="(link, index) in dynamicLinks"
                        :key="index"
                        class="flex items-center gap-1 bg-background px-3 border-none rounded-md transition-all"
                    >
                        <div
                            class="w-6 h-6 flex items-center justify-center rounded-lg"
                        >
                            <GlobeAltIcon
                                v-if="link.platform === 'website'"
                                class="h-5 w-5 text-text-medium"
                            />
                            <svg
                                v-else
                                class="h-5 w-5 fill-current"
                                :class="getPlatformInfo(link.platform)?.color"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    :d="getPlatformInfo(link.platform)?.icon"
                                />
                            </svg>
                        </div>

                        <input
                            v-model="link.url"
                            type="url"
                            class="grow bg-transparent border-none focus:ring-0 text-sm placeholder:text-text-medium"
                            placeholder="https://..."
                            :class="{
                                'outline-dashed outline-primary -outline-offset-2 rounded-md':
                                    props.form.errors.url,
                            }"
                        />

                        <button
                            type="button"
                            @click="removeLink(index)"
                            class="p-2 text-text-medium hover:text-red-500 rounded-full transition-all"
                        >
                            <TrashIcon class="h-5 w-5" />
                        </button>
                    </div>
                </TransitionGroup>

                <button
                    type="button"
                    @click="addLink"
                    class="group flex items-center gap-2 text-sm font-semibold hover:text-primary transition-all px-4"
                >
                    <PlusIcon class="h-4 w-4 stroke-[3px]" />
                    <span>Add another link</span>
                </button>
            </div>
        </div>

        <div
            class="flex items-center justify-end gap-4 pt-8 border-t border-text-low"
        >
            <SecondaryButton @click="goToHome">Cancel</SecondaryButton>
            <PrimaryButton :disabled="form.processing" class="px-8">
                {{ form.processing ? "Saving..." : "Create Account" }}
            </PrimaryButton>
        </div>
    </form>
</template>

<style scoped>
.list-enter-active,
.list-leave-active {
    transition: all 0.3s ease;
}
.list-enter-from,
.list-leave-to {
    opacity: 0;
    transform: translateY(10px);
}
</style>
