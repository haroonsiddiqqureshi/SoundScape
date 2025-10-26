<script setup>
import { computed } from "vue";
import { FolderPlusIcon, MagnifyingGlassIcon } from "@heroicons/vue/24/outline";

const props = defineProps({
    items: {
        type: [Array, Object],
        required: true,
    },
    search: {
        type: String,
        default: "",
    },
});

const emit = defineEmits(["update:search"]);

const hasItems = computed(() => {
    if (Array.isArray(props.items)) {
        return props.items.length > 0;
    }
    if (typeof props.items === "object" && props.items !== null) {
        return Object.keys(props.items).length > 0;
    }
    return false;
});
</script>

<template>
    <div class="w-full mx-auto rounded-md space-y-6">
        <div class="flex justify-between items-center space-x-4">
            <div class="w-full flex items-center space-x-4">
                <div class="relative w-full">
                    <div
                        class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none"
                    >
                        <MagnifyingGlassIcon class="h-5 w-5 text-text" />
                    </div>
                    <input
                        type="text"
                        name="search"
                        id="search"
                        class="block w-full pl-10 pr-3 p-1 ring-4 ring-card border-none rounded-md leading-5 bg-card focus:outline-none focus:ring-4 focus:ring-card sm:text-sm"
                        placeholder="Search..."
                        :value="props.search"
                        @input="$emit('update:search', $event.target.value)"
                    />
                </div>
            </div>
            <slot name="adder" />
        </div>

        <div
            v-if="hasItems"
            class="w-full overflow-x-auto rounded-md outline outline-4 outline-card"
        >
            <table class="min-w-full">
                <thead
                    class="bg-card text-xs font-semibold uppercase tracking-wider"
                >
                    <slot name="header" />
                </thead>

                <tbody class="divide-y-2 divide-card bg-card-hover dark:bg-background-hover shadow-lg">
                    <slot name="body" />
                </tbody>
            </table>
        </div>
        <div
            v-else
            class="w-full flex flex-col space-y-2 font-semibold items-center text-text-medium justify-center py-10 rounded-md bg-card ring-4 ring-card"
        >
            <FolderPlusIcon class="w-12 h-12 stroke-current" />
            <p>No items found</p>
            <slot name="empty" />
        </div>
    </div>
</template>