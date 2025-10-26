<script setup>
import { Head, Link, router } from "@inertiajs/vue3";
import AdminLayout from "@/Layouts/AdminLayout.vue";
import StyledTable from "@/Components/StyledTable.vue";
import { ref, watch } from "vue";
import { debounce } from "lodash-es";
import {
    PlusIcon,
    XMarkIcon,
    PencilIcon,
    TrashIcon,
} from "@heroicons/vue/24/solid";

const props = defineProps({
    highlights: Object,
    filters: Object,
});

const search = ref(props.filters?.search || "");

watch(
    search,
    debounce((value) => {
        router.get(
            route("admin.highlight.index"),
            { search: value },
            {
                preserveState: true,
                preserveScroll: true,
                replace: true,
            }
        );
    }, 300)
);

const updateActiveStatus = (highlight) => {
    router.put(
        route("admin.highlight.updateActiveStatus", highlight.id),
        {
            is_active: !highlight.is_active,
        },
        {
            preserveState: true,
            preserveScroll: true,
        }
    );
};

const showModal = ref(false);
const modalImageUrl = ref("");

const openImageModal = (imageUrl) => {
    modalImageUrl.value = imageUrl;
    showModal.value = true;
};

const closeModal = () => {
    showModal.value = false;
    modalImageUrl.value = "";
};

const getPictureUrl = (highlight) => {
    if (highlight?.picture_url) {
        if (highlight.picture_url.startsWith("http")) {
            return highlight.picture_url;
        }
        return `/storage/${highlight.picture_url}`;
    }
    return "https://placehold.co/800x600/1c1423/ffffff80?text=Highlight";
};
</script>

<template>
    <AdminLayout title="Highlights">
        <Head title="Highlights Management" />
        <StyledTable :items="highlights" v-model:search="search">
            <template #adder>
                <Link
                    :href="route('admin.highlight.create')"
                    class="flex items-center justify-center py-1 ring-4 ring-primary rounded-md text-primary bg-primary w-32"
                >
                    <PlusIcon
                        class="w-5 h-5 text-white stroke-current stroke-[2px]"
                    />
                </Link>
            </template>

            <template #header>
                <tr>
                    <th scope="col" class="px-4 py-3 pl-6 text-left">
                        Picture
                    </th>
                    <th scope="col" class="px-4 py-3 text-left">Title</th>
                    <th scope="col" class="px-4 py-3 whitespace-nowrap">Toggle Status</th>
                    <th scope="col" class="px-4 py-3 pr-6 text-end"></th>
                </tr>
            </template>

            <template #body>
                <tr v-for="highlight in highlights" :key="highlight.id">
                    <td class="px-4 py-3 pl-6">
                        <img
                            :src="getPictureUrl(highlight)"
                            alt="Highlight image"
                            class="w-16 h-10 object-cover rounded-md bg-background cursor-pointer"
                            @click="openImageModal(getPictureUrl(highlight))"
                        />
                    </td>
                    <td
                        class="px-4 py-3 whitespace-nowrap text-sm truncate text-text"
                    >
                        {{ highlight.title }}
                    </td>
                    <td
                        class="px-4 py-3 text-center whitespace-nowrap text-sm uppercase"
                    >
                        <span
                            @click="updateActiveStatus(highlight)"
                            class="px-2.5 py-1 rounded-full text-xs text-white font-semibold cursor-pointer"
                            :class="
                                highlight.is_active
                                    ? 'bg-primary'
                                    : 'bg-background-hover dark:bg-card-hover'
                            "
                        >
                            {{ highlight.is_active ? "Active" : "Inactive" }}
                        </span>
                    </td>
                    <td
                        class="flex justify-end space-x-2 px-4 py-5 pr-6 whitespace-nowrap text-sm text-white font-semibold"
                    >
                        <Link
                            :href="
                                route('admin.highlight.edit', {
                                    highlight: highlight.id,
                                })
                            "
                            class="flex items-center px-2 h-6 w-fit space-x-1 bg-secondary hover:bg-secondary-hover rounded-full transition-colors duration-200"
                        >
                            <PencilIcon class="w-4 h-4" />
                            <span>แก้ไข</span>
                        </Link>
                        <Link
                            :href="
                                route('admin.highlight.delete', {
                                    highlight: highlight.id,
                                })
                            "
                            method="delete"
                            class="flex items-center px-2 h-6 w-fit space-x-1 bg-red-600 hover:bg-red-700 rounded-full transition-colors duration-200"
                        >
                            <TrashIcon class="w-4 h-4" />
                            <span>ลบ</span>
                        </Link>
                    </td>
                </tr>
            </template>
        </StyledTable>
        <teleport to="body">
            <div
                v-if="showModal"
                class="fixed inset-0 z-50 flex items-center justify-center bg-background-high p-4"
                @click="closeModal"
            >
                <div class="relative bg-card p-2 rounded-md" @click.stop>
                    <button
                        @click="closeModal"
                        class="absolute -top-3 -right-3 z-10 bg-primary rounded-full p-1 text-white"
                    >
                        <XMarkIcon class="h-4 w-4" />
                    </button>
                    <img
                        :src="modalImageUrl"
                        alt="Full size highlight image"
                        class="max-w-full h-auto max-h-[80vh] rounded-md"
                    />
                </div>
            </div>
        </teleport>
    </AdminLayout>
</template>