<script setup>
import InputError from "@/Components/InputError.vue";
import { ref, computed } from 'vue';

const props = defineProps({
    form: Object,
    concert: Object,
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
    if (props.concert.picture_url) {
        if (props.concert.picture_url.startsWith("http")) {
            return props.concert.picture_url;
        }
        return `/storage/${props.concert.picture_url}`;
    }
    return null;
});

const photoPreview = ref(initialImageUrl.value);

</script>

<template>
    <div class="bg-white shadow-xl rounded-lg p-6 md:p-8">
        <h2 class="text-2xl font-bold mb-6 text-gray-800 border-b pb-4">Edit Concert</h2>
        <form @submit.prevent="submit">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6">
                <!-- Concert Name -->
                <div class="md:col-span-2">
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Concert Name</label>
                    <input type="text" id="name" v-model="form.name" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                    <InputError :message="form.errors.name" class="mt-2" />
                </div>

                <!-- Description -->
                <div class="md:col-span-2">
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                    <textarea id="description" v-model="form.description" rows="4" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"></textarea>
                    <InputError :message="form.errors.description" class="mt-2" />
                </div>

                <!-- Picture Upload -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Concert Poster</label>
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

                <!-- Venue Name -->
                <div>
                    <label for="venue_name" class="block text-sm font-medium text-gray-700 mb-1">Venue Name</label>
                    <input type="text" id="venue_name" v-model="form.venue_name" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                    <InputError :message="form.errors.venue_name" class="mt-2" />
                </div>

                <!-- City -->
                <div>
                    <label for="city" class="block text-sm font-medium text-gray-700 mb-1">City</label>
                    <input type="text" id="city" v-model="form.city" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                    <InputError :message="form.errors.city" class="mt-2" />
                </div>

                <!-- Latitude -->
                <div>
                    <label for="latitude" class="block text-sm font-medium text-gray-700 mb-1">Latitude</label>
                    <input type="number" step="any" id="latitude" v-model="form.latitude" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                    <InputError :message="form.errors.latitude" class="mt-2" />
                </div>

                <!-- Longitude -->
                <div>
                    <label for="longitude" class="block text-sm font-medium text-gray-700 mb-1">Longitude</label>
                    <input type="number" step="any" id="longitude" v-model="form.longitude" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                    <InputError :message="form.errors.longitude" class="mt-2" />
                </div>

                <!-- Concert Date & Time -->
                <div>
                    <label for="start_datetime" class="block text-sm font-medium text-gray-700 mb-1">Concert Date & Time</label>
                    <input type="datetime-local" id="start_datetime" v-model="form.start_datetime" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                    <InputError :message="form.errors.start_datetime" class="mt-2" />
                </div>

                <!-- Price -->
                <div>
                    <label for="price" class="block text-sm font-medium text-gray-700 mb-1">Price (THB)</label>
                    <input type="number" step="any" id="price" v-model="form.price" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                    <InputError :message="form.errors.price" class="mt-2" />
                </div>

                <!-- Status -->
                <div class="md:col-span-2">
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                    <select id="status" v-model="form.status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                        <option value="upcoming">Upcoming</option>
                        <option value="completed">Completed</option>
                        <option value="cancelled">Cancelled</option>
                    </select>
                    <InputError :message="form.errors.status" class="mt-2" />
                </div>

                <!-- Ticket Link -->
                <div class="md:col-span-2">
                    <label for="ticket_link" class="block text-sm font-medium text-gray-700 mb-1">Ticket Link</label>
                    <input type="url" id="ticket_link" v-model="form.ticket_link" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                    <InputError :message="form.errors.ticket_link" class="mt-2" />
                </div>
            </div>

            <!-- Submit Button -->
            <div class="mt-8 flex justify-end">
                <button type="submit" :disabled="form.processing" class="inline-flex justify-center py-2 px-6 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 disabled:opacity-50">
                    Update Concert
                </button>
            </div>
        </form>
    </div>
</template>