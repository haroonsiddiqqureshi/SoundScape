<script setup>
import { ref } from "vue";
import { useForm } from "@inertiajs/vue3";
import AppLayout from "@/Layouts/AppLayout.vue";
import PromoterCreateForm from "@/Components/Promoters/PromoterCreate.vue";

// Define the form state using useForm
const form = useForm({
    fullname: "",
    business_name: "",
    business_category: "individual",
    business_address: "",
    bio: "",
    social_links: {
        facebook: "",
        instagram: "",
        tiktok: "",
        twitter: "",
        discord: "",
        linkedin: "",
        youtube: "",
        website: "",
    },
});

// Ref for the error summary block
const errorSummary = ref(null);

// Labels for the error summary
const fieldLabels = {
    fullname: "Full Name",
    business_name: "Business Name",
    business_category: "Business Category",
    business_address: "Business Address",
    bio: "Bio",
    "social_links.facebook": "Facebook Link",
    "social_links.instagram": "Instagram Link",
    "social_links.tiktok": "Tiktok Link",
    "social_links.twitter": "Twitter Link",
    "social_links.discord": "Discord Link",
    "social_links.linkedin": "LinkedIn Link",
    "social_links.youtube": "YouTube Link",
    "social_links.website": "Website Link",
};

// Submit function
const submit = () => {
    form.post(route("promoter.store"));
};
</script>

<template>
    <AppLayout title="Create Promoter Account">
        <div
            ref="errorSummary"
            v-if="Object.keys(form.errors).length"
            class="bg-card lg:shadow-xl rounded-md mb-4 p-6"
        >
            <div>
                <div class="flex flex-col w-full space-y-2">
                    <span class="text-lg font-semibold text-primary"
                        >โอ๊ะ! เกิดข้อผิดพลาด</span
                    >
                    <span class="text-sm"
                        >กรุณาตรวจสอบข้อมูลในช่องที่มีกรอบเส้นประอีกครั้ง</span
                    >
                    <ul
                        class="list-disc list-inside space-y-1 pl-5 bg-background p-4 rounded-md outline-dashed -outline-offset-4 text-primary"
                    >
                        <li
                            v-for="(errorMessages, fieldName) in form.errors"
                            :key="fieldName"
                            class="text-sm text-primary"
                        >
                            <strong class="font-bold"
                                >{{
                                    fieldLabels[fieldName] || fieldName
                                }}:</strong
                            >
                            <span class="ml-1 text-text">{{
                                errorMessages
                            }}</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="overflow-hidden">
            <PromoterCreateForm :form="form" @submit="submit" />
        </div>
    </AppLayout>
</template>