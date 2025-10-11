<script setup>
import InputError from "@/Components/InputError.vue";
import { ref } from "vue";

const props = defineProps({
    form: Object,
});

const emit = defineEmits(["submit"]);

const submit = () => {
    emit("submit");
};

const photoPreview = ref(null);
const photoInput = ref(null);

const selectNewPhoto = () => {
    photoInput.value.click();
};

const updatePhotoPreview = (event) => {
    const file = event.target.files[0];
    if (!file) return;

    props.form.picture_url = file;

    const reader = new FileReader();
    reader.onload = (e) => {
        photoPreview.value = e.target.result;
    };
    reader.readAsDataURL(file);
};
</script>

<template>
    <div class="bg-white shadow-xl rounded-lg p-6 md:p-8">
        <h2 class="text-2xl font-bold mb-6 text-gray-800 border-b pb-4">
            Create New Concert
        </h2>
        <form @submit.prevent="submit">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6">
                <!-- Title Field -->
                <div>
                    <label
                        for="title"
                        class="block text-sm font-medium text-gray-700 mb-1"
                        >Title</label
                    >
                    <input
                        v-model="form.title"
                        type="text"
                        id="title"
                        class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2 focus:ring-blue-500 focus:border-blue-500"
                    />
                    <InputError :message="form.errors.title" class="mt-2" />
                </div>

                <!-- Description Field -->
                <div class="md:col-span-2">
                    <label
                        for="description"
                        class="block text-sm font-medium text-gray-700 mb-1"
                        >Description</label
                    >
                    <textarea
                        v-model="form.description"
                        id="description"
                        rows="4"
                        class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2 focus:ring-blue-500 focus:border-blue-500"
                    ></textarea>
                    <InputError
                        :message="form.errors.description"
                        class="mt-2"
                    />
                </div>
                <!-- Picture Upload Field -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1"
                        >Picture</label
                    >
                    <div class="flex items-center space-x-4">
                        <div
                            class="w-32 h-32 bg-gray-100 rounded-md overflow-hidden flex items-center justify-center"
                        >
                            <img
                                v-if="photoPreview"
                                :src="photoPreview"
                                alt="Selected Photo"
                                class="object-cover w-full h-full"
                            />
                            <span v-else class="text-gray-400">No Photo</span>
                        </div>
                        <div>
                            <input
                                ref="photoInput"
                                type="file"
                                accept="image/*"
                                class="hidden"
                                @change="updatePhotoPreview"
                            />
                            <button
                                type="button"
                                @click="selectNewPhoto"
                                class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                            >
                                Select New Photo
                            </button>
                            <InputError
                                :message="form.errors.picture_url"
                                class="mt-2"
                            />
                        </div>
                    </div>
                </div>
            </div>
            <!-- Submit Button -->
            <div class="mt-8 flex justify-end">
                <button
                    type="submit"
                    :disabled="form.processing"
                    class="inline-flex justify-center py-2 px-6 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 disabled:opacity-50"
                >
                    Create Concert
                </button>
            </div>
        </form>
    </div>
</template>
