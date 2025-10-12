<script setup>
import InputError from "@/Components/InputError.vue";
import { ref, computed } from 'vue';

const props = defineProps({
    form: Object,
    highlight: Object,
    concerts: Array,
});

const emit = defineEmits(["submit"]);

const submit = () => {
    emit("submit");
};

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

// Computed property for the initial image
const initialImageUrl = computed(() => {
    if (props.highlight.picture_url) {
        if (props.highlight.picture_url.startsWith("http")) {
            return props.highlight.picture_url;
        }
        return `/storage/${props.highlight.picture_url}`;
    }
    return null;
});

const photoPreview = ref(initialImageUrl.value);

const is_concert = ref(props.highlight.concert_id ? true : false);

</script>

<template>
    <div class="bg-white shadow-xl rounded-lg p-6 md:p-8">
        <h2 class="text-2xl font-bold mb-6 text-gray-800 border-b pb-4">Edit Highlight</h2>
        <form @submit.prevent="submit">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6">
                <!-- Highlight Title -->
                <div class="md:col-span-2">
                    <label for="title" class="block text-sm font-medium text-gray-700 mb-1">Highlight Title</label>
                    <input type="text" id="title" v-model="form.title" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                    <InputError :message="form.errors.title" class="mt-2" />
                </div>

                <!-- Description -->
                <div class="md:col-span-2">
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                    <textarea id="description" v-model="form.description" rows="4" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"></textarea>
                    <InputError :message="form.errors.description" class="mt-2" />
                </div>

                <!-- Picture Upload -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Highlight Poster</label>
                    <div class="mt-1 flex items-center">
                        <div v-if="photoPreview" class="w-32 h-32 mr-4 flex-shrink-0">
                            <img :src="photoPreview" class="w-full h-full object-cover rounded-md">
                        </div>
                        <input ref="photoInput" type="file" class="hidden" @change="updatePhotoPreview" accept="image/*">
                        <button type="button" @click.prevent="selectNewPhoto" class="px-4 py-2 bg-white border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50">
                            Change Image
                        </button>
                    </div>
                    <InputError :message="form.errors.picture_url" class="mt-2" />
                </div>

                <!-- Concert or Link Field -->
                <div class="flex md:col-span-2 items-center">
                    <div class="flex items-center">
                        <span class="text-sm font-medium text-gray-700 mr-3"
                            >Link to Concert?</span
                        >
                        <button
                            type="button"
                            @click="is_concert = !is_concert"
                            class="relative inline-flex flex-shrink-0 h-6 w-11 border-2 border-transparent rounded-full cursor-pointer transition-colors ease-in-out duration-200"
                            :class="
                                is_concert ? 'bg-green-600' : 'bg-gray-200'
                            "
                        >
                            <span
                                aria-hidden="true"
                                class="pointer-events-none inline-block h-5 w-5 rounded-full bg-white shadow transform ring-0 transition ease-in-out duration-200"
                                :class="{
                                    'translate-x-5': is_concert,
                                    'translate-x-0': !is_concert,
                                }"
                            ></span>
                        </button>
                    </div>
                </div>
                <div class="md:col-span-2">
                    <div v-if="is_concert">
                        <label
                            for="concert_id"
                            class="block text-sm font-medium text-gray-700 mb-1"
                            >Concert</label
                        >
                        <select
                            v-model="form.concert_id"
                            id="concert_id"
                            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2 focus:ring-blue-500 focus:border-blue-500"
                        >
                            <option :value="null" disabled hidden>
                                Select a concert
                            </option>
                            <option
                                v-for="concert in concerts"
                                :key="concert.id"
                                :value="concert.id"
                            >
                                {{ concert.name }}
                            </option>
                        </select>
                        <InputError
                            :message="form.errors.concert_id"
                            class="mt-2"
                        />
                    </div>
                    <div v-else>
                        <label
                            for="link"
                            class="block text-sm font-medium text-gray-700 mb-1"
                            >Link</label
                        >
                        <input
                            v-model="form.link"
                            type="url"
                            id="link"
                            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2 focus:ring-blue-500 focus:border-blue-500"
                            placeholder="https://example.com"
                        />
                        <InputError :message="form.errors.link" class="mt-2" />
                    </div>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="mt-8 flex justify-end">
                <button type="submit" :disabled="form.processing" class="inline-flex justify-center py-2 px-6 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 disabled:opacity-50">
                    Update Highlight
                </button>
            </div>
        </form>
    </div>
</template>