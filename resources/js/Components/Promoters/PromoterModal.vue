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
    <!-- Address Modal -->
    <div v-if="props.showingFullAddress" class="fixed inset-0 bg-background-high z-50 flex justify-center items-center"
        @click="closeAddress">
        <div class="bg-card p-8 rounded-md shadow-xl min-w-[300px] max-w-2xl relative" @click.stop>
            <h3 class="text-sm text-text-medium">Address</h3>
            <div class="mt-1 mb-2 border-[1px] border-text-low"></div>
            <p class="break-words">
                {{ props.promoter.business_address }}
            </p>
            <div class="text-right">
                <button @click="closeAddress" class="absolute -top-3 -right-3 z-10 bg-primary rounded-full p-1">
                    <XMarkIcon class="h-4 w-4 text-white stroke-current" />
                </button>
            </div>
        </div>
    </div>

    <!-- Bio Modal -->
    <div v-if="props.showingFullBio" class="fixed inset-0 bg-background-high z-50 flex justify-center items-center"
        @click="closeBio">
        <div class="bg-card p-8 rounded-md shadow-xl min-w-[300px] max-w-2xl relative" @click.stop>
            <h3 class="text-sm text-text-medium">Bio</h3>
            <div class="mt-1 mb-2 border-[1px] border-text-low"></div>
            <p class="break-words">
                {{ props.promoter.bio }}
            </p>
            <div class="text-right">
                <button @click="closeBio" class="absolute -top-3 -right-3 z-10 bg-primary rounded-full p-1">
                    <XMarkIcon class="h-4 w-4 text-white stroke-current" />
                </button>
            </div>
        </div>
    </div>
</template>
