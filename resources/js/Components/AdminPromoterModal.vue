<script setup>
import { defineProps, defineEmits } from 'vue'

const props = defineProps({
    promoter: Object,
    showingFullBio: Boolean,
    showingFullAddress: Boolean,
})

const emit = defineEmits(['update:showingFullBio', 'update:showingFullAddress'])

const closeBio = () => emit('update:showingFullBio', false)
const closeAddress = () => emit('update:showingFullAddress', false)
</script>

<template>
    <!-- Bio Modal -->
    <div
        v-if="props.showingFullBio"
        class="fixed inset-0 bg-text-reverse bg-opacity-50 z-50 flex justify-center items-center"
        @click="closeBio"
    >
        <div class="bg-white p-8 rounded-lg shadow-xl max-w-2xl" @click.stop>
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Bio</h3>
            <p class="text-sm text-gray-900 break-words">{{ props.promoter.bio }}</p>
            <div class="mt-6 text-right">
                <button
                    @click="closeBio"
                    class="px-4 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300"
                >
                    Close
                </button>
            </div>
        </div>
    </div>

    <!-- Address Modal -->
    <div
        v-if="props.showingFullAddress"
        class="fixed inset-0 bg-text-reverse bg-opacity-50 z-50 flex justify-center items-center"
        @click="closeAddress"
    >
        <div class="bg-white p-8 rounded-lg shadow-xl max-w-2xl" @click.stop>
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Address</h3>
            <p class="text-sm text-gray-900 break-words">{{ props.promoter.business_address }}</p>
            <div class="mt-6 text-right">
                <button
                    @click="closeAddress"
                    class="px-4 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300"
                >
                    Close
                </button>
            </div>
        </div>
    </div>
</template>
