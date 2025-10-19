<script setup>
import { router, Link } from "@inertiajs/vue3";

const props = defineProps({
    highlights: Object,
});

const emit = defineEmits(["update-active-status"]);

const getPictureUrl = (highlight) => {
    if (highlight?.picture_url) {
        if (highlight.picture_url.startsWith("http")) {
            return highlight.picture_url;
        }
        return `/storage/${highlight.picture_url}`;
    }
    return "https://placehold.co/600x400?text=SoundScape";
};
</script>

<template>
    <div
        v-if="highlights && highlights.length > 0"
        class="bg-white shadow-md rounded-lg"
    >
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th
                        scope="col"
                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                    >
                        Picture
                    </th>
                    <th
                        scope="col"
                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                    >
                        Title
                    </th>
                    <th
                        scope="col"
                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                    >
                        Description
                    </th>
                    <th
                        scope="col"
                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                    >
                        Toggle Activation
                    </th>
                    <th scope="col" class="relative px-6 py-3">
                        <span class="sr-only">Edit</span>
                    </th>
                    <th scope="col" class="relative px-6 py-3">
                        <span class="sr-only">Delete</span>
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                <tr v-for="highlight in highlights" :key="highlight.id">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <img
                            :src="getPictureUrl(highlight)"
                            alt="Highlight image"
                            class="h-10 w-10 rounded-full object-cover cursor-pointer"
                            @click="
                                $emit(
                                    'open-image-modal',
                                    getPictureUrl(highlight)
                                )
                            "
                        />
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-medium text-gray-900">
                            {{ highlight.title }}
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-500">
                            {{ highlight.description }}
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <button
                            @click="emit('update-active-status', highlight)"
                            class="relative inline-flex flex-shrink-0 h-6 w-11 border-2 border-transparent rounded-full cursor-pointer transition-colors ease-in-out duration-200"
                            :class="
                                highlight.is_active
                                    ? 'bg-green-600'
                                    : 'bg-gray-200'
                            "
                        >
                            <span
                                aria-hidden="true"
                                class="pointer-events-none inline-block h-5 w-5 rounded-full bg-white shadow transform ring-0 transition ease-in-out duration-200"
                                :class="{
                                    'translate-x-5': highlight.is_active,
                                    'translate-x-0': !highlight.is_active,
                                }"
                            ></span>
                        </button>
                    </td>
                    <td
                        class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium"
                    >
                        <Link
                            :href="
                                route('admin.highlight.edit', {
                                    highlight: highlight.id,
                                })
                            "
                            class="text-indigo-600 hover:text-indigo-900"
                            >Edit</Link
                        >
                    </td>
                    <td
                        class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium"
                    >
                        <Link
                            :href="
                                route('admin.highlight.delete', {
                                    highlight: highlight.id,
                                })
                            "
                            method="delete"
                            class="text-indigo-600 hover:text-indigo-900"
                            >Delete</Link
                        >
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    <div v-else class="text-center py-12 bg-gray-50 rounded-lg">
        <h3 class="text-xl font-medium text-gray-700">No Highlights Found</h3>
        <p class="mt-1 text-sm text-gray-500">
            Get started by creating a new highlight.
        </p>
    </div>
</template>
