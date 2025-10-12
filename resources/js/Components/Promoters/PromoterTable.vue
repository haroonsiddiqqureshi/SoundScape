<script setup>
const props = defineProps({
    promoters: Object,
});

const emit = defineEmits(["update-verification-status"]);
</script>

<template>
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th
                    scope="col"
                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                >
                    Name
                </th>
                <th
                    scope="col"
                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                >
                    Email
                </th>
                <th
                    scope="col"
                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                >
                    Verification
                </th>
                <th
                    scope="col"
                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                >
                    Toggle Verification
                </th>
                <th scope="col" class="relative px-6 py-3">
                    <span class="sr-only">Edit</span>
                </th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            <tr v-for="promoter in promoters" :key="promoter.id">
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="text-sm text-gray-900">
                        {{ promoter.fullname }}
                    </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="text-sm text-gray-900">
                        {{ promoter.user.email }}
                    </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <span
                        class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full"
                        :class="
                            promoter.is_verified
                                ? 'bg-green-100 text-green-800'
                                : 'bg-red-100 text-red-800'
                        "
                    >
                        {{ promoter.is_verified ? "Verified" : "Not Verified" }}
                    </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <button
                        @click="emit('update-verification-status', promoter)"
                        class="relative inline-flex flex-shrink-0 h-6 w-11 border-2 border-transparent rounded-full cursor-pointer transition-colors ease-in-out duration-200"
                        :class="
                            promoter.is_verified
                                ? 'bg-green-600'
                                : 'bg-gray-200'
                        "
                    >
                        <span class="sr-only">Use setting</span>
                        <span
                            aria-hidden="true"
                            class="pointer-events-none inline-block h-5 w-5 rounded-full bg-white shadow transform ring-0 transition ease-in-out duration-200"
                            :class="{
                                'translate-x-5': promoter.is_verified,
                                'translate-x-0': !promoter.is_verified,
                            }"
                        ></span>
                    </button>
                </td>
                <td
                    class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium"
                >
                    <a
                        :href="route('admin.promoter.detail', promoter.id)"
                        class="text-indigo-600 hover:text-indigo-900"
                        >View</a
                    >
                </td>
            </tr>
        </tbody>
    </table>
</template>
