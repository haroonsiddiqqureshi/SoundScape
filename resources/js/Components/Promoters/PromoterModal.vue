<script setup>
import { defineProps, defineEmits } from "vue";
import { XMarkIcon } from "@heroicons/vue/24/solid";

const props = defineProps({
    promoter: Object,
    showingFullBio: Boolean,
    showingFullAddress: Boolean,
});

const emit = defineEmits([
    "update:showingFullBio",
    "update:showingFullAddress",
]);

const closeBio = () => emit("update:showingFullBio", false);
const closeAddress = () => emit("update:showingFullAddress", false);
</script>

<template>
    <!-- Bio Modal -->
    <div
        v-if="props.showingFullBio"
        class="fixed inset-0 bg-background-high z-50 flex justify-center items-center"
        @click="closeBio"
    >
        <div class="bg-card p-8 rounded-md shadow-xl min-w-[300px] max-w-2xl relative" @click.stop>
            <h3 class="text-lg font-semibold mb-4">Bio</h3>
            <p class="text-sm text-text-medium break-words">
                {{ props.promoter.bio }}
            </p>
            <div class="text-right">
                <button
                    @click="closeBio"
                    class="absolute -top-3 -right-3 z-10 bg-primary rounded-full p-1"
                >
                    <XMarkIcon class="h-4 w-4" />
                </button>
            </div>
        </div>
    </div>

    <!-- Address Modal -->
    <div
        v-if="props.showingFullAddress"
        class="fixed inset-0 bg-background-high z-50 flex justify-center items-center"
        @click="closeAddress"
    >
        <div class="bg-card p-8 rounded-md shadow-xl min-w-[300px] max-w-2xl relative" @click.stop>
            <h3 class="text-lg font-semibold mb-4">Address</h3>
            <p class="text-sm text-text-medium break-words">
                {{ props.promoter.business_address }}
            </p>
            <div class="text-right">
                <button
                    @click="closeAddress"
                    class="absolute -top-3 -right-3 z-10 bg-primary rounded-full p-1"
                >
                    <XMarkIcon class="h-4 w-4" />
                </button>
            </div>
        </div>
    </div>
</template>
