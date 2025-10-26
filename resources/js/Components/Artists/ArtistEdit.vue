<script setup>
// All imports
import { Head, Link, useForm, router } from "@inertiajs/vue3";
import { ref, computed, inject, watch, nextTick } from "vue";
import { DocumentPlusIcon } from "@heroicons/vue/24/solid";

// --- Define Props ---
const props = defineProps({
    artist: Object,
});

// --- Artist Form Definition ---
const form = useForm({
    _method: "PUT", // Spoof PUT method for file uploads
    name: props.artist.name, // Pre-fill the name
    picture_url: null, // Will hold the *new* file object if one is selected
});

// --- Injected dark mode reactive ref ---
const isDarkMode = inject("isDarkMode");

// --- Refs for error summary and photo preview ---
const errorSummary = ref(null);
const uploadedPhotoUrl = ref(null); // Holds the Data URL for a *new* preview
const photoInput = ref(null);

// --- Field labels for the error summary ---
const fieldLabels = {
    picture_url: "รูปโปรไฟล์",
    name: "ชื่อศิลปิน",
};

// --- Computed property for image preview with dark mode ---
const photoPreview = computed(() => {
    // 1. If a new photo preview has been generated, show it.
    if (uploadedPhotoUrl.value) {
        return uploadedPhotoUrl.value;
    }

    // 2. If no new preview, but the artist has an existing picture, show that.
    if (props.artist.picture_url) {
        const url = props.artist.picture_url;

        // Check if it's already a full URL
        if (url.startsWith('http://') || url.startsWith('https://')) {
            return url; // Return the full URL directly
        }
        
        // Otherwise, assume it's a relative path and prepend storage
        return `/storage/${url}`;
    }

    // 3. Fallback to placeholders
    return isDarkMode.value
        ? "https://placehold.co/800x1200/1c1423/ffffff80?text=Upload%5CnPicture"
        : "https://placehold.co/800x1200/e5e7eb/00000080?text=Upload%5CnPicture";
});

// --- Image selection methods ---
const selectNewPhoto = () => {
    photoInput.value.click();
};

const updatePhotoPreview = (event) => {
    const file = event.target.files[0];
    if (!file) return;
    form.picture_url = file;
    const reader = new FileReader();
    reader.onload = (e) => {
        uploadedPhotoUrl.value = e.target.result;
    };
    reader.readAsDataURL(file);
};

// --- Watch for errors and scroll to summary (identical to create) ---
watch(
    () => form.errors,
    (newErrors) => {
        if (newErrors && Object.keys(newErrors).length > 0) {
            nextTick(() => {
                if (errorSummary.value) {
                    errorSummary.value.scrollIntoView({
                        behavior: "smooth",
                        block: "start",
                    });
                }
            });
        }
    },
    { deep: true }
);

// --- Form submission (Update) ---
const submit = () => {
    // Use POST to tunnel PUT for file upload compatibility
    form.post(route("admin.artist.update", props.artist.id), {
        onError: () => {
            if (form.errors.picture_url) {
                // Reset form field and preview if file fails validation
                form.reset("picture_url");
                uploadedPhotoUrl.value = null;
            }
        },
    });
};

</script>

<template>
    <div>
        <div
            ref="errorSummary"
            v-if="Object.keys(form.errors).length"
            class="max-w-fit bg-card lg:shadow-xl rounded-md mb-4 p-6"
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

        <div
            class="max-w-xs p-6 bg-card rounded-md shadow-lg ring-4 ring-card"
        >
            <form @submit.prevent="submit" class="flex flex-col gap-2">
                <div>
                    <div
                        v-if="photoPreview"
                        class="relative cursor-pointer w-full h-full"
                        :class="{
                            'outline-dashed outline-primary -outline-offset-4 rounded-md':
                                form.errors.picture_url,
                        }"
                    >
                        <img
                            :src="photoPreview"
                            class="object-cover rounded-md bg-background-hover"
                        />
                        <input
                            ref="photoInput"
                            type="file"
                            class="hidden"
                            @change="updatePhotoPreview"
                            accept="image/*"
                        />
                        <button
                            type="button"
                            @click.prevent="selectNewPhoto"
                            class="absolute inset-0"
                        />
                    </div>
                </div>

                <div class="inline-flex justify-between gap-2 items-center">
                    <input
                        type="text"
                        id="name"
                        v-model="form.name"
                        class="bg-background w-full h-full rounded-md font-medium border-background focus:ring-0 focus:outline-none focus:border-transparent placeholder:font-normal placeholder:text-text-medium"
                        :class="{
                            'outline-dashed outline-primary -outline-offset-4 rounded-md':
                                form.errors.name,
                        }"
                        placeholder="ชื่อศิลปิน"
                    />
                    <button
                        type="submit"
                        :disabled="form.processing"
                        class="whitespace-nowrap px-1 py-2 bg-primary text-white font-semibold rounded-md shadow-md hover:bg-primary-hover transition-colors duration-200 disabled:cursor-not-allowed"
                    >
                        <DocumentPlusIcon class="w-5 h-5" />
                    </button>
                </div>
            </form>
        </div>
    </div>
</template>