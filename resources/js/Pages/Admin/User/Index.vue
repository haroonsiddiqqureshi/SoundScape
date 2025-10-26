<script setup>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import StyledTable from "@/Components/StyledTable.vue";
import { Head, router } from "@inertiajs/vue3";
import { ref, watch } from "vue";

const props = defineProps({
    users: Object,
    filters: Object,
});

const search = ref(props.filters?.search || "");

watch(
    search,
    (value) => {
        router.get(
            route("admin.user.index"),
            { search: value },
            {
                preserveState: true,
                preserveScroll: true,
                replace: true,
            }
        );
    }
);
</script>

<template>
    <AdminLayout title="User">
        <Head title="User Management" />

        <StyledTable :items="users" v-model:search="search">
            <template #header>
                <tr>
                    <th scope="col" class="px-4 py-3 pl-6 text-left">ID</th>
                    <th scope="col" class="px-4 py-3 text-left">Name</th>
                    <th scope="col" class="px-4 py-3 text-left">Email</th>
                    <th scope="col" class="px-4 py-3 text-left">Phone</th>
                    <th scope="col" class="px-4 py-3 pr-6 text-left">Line ID</th>
                </tr>
            </template>

            <template #body>
                <tr
                    v-for="user in props.users"
                    :key="user.id"
                >
                    <td class="px-4 py-3 pl-6 whitespace-nowrap text-sm text-text">
                        {{ user.id }}
                    </td>
                    <td class="px-4 py-3 whitespace-nowrap text-sm text-text">
                        {{ user.name }}
                    </td>
                    <td class="px-4 py-3 whitespace-nowrap text-sm text-text">
                        {{ user.email }}
                    </td>
                    <td class="px-4 py-3 whitespace-nowrap text-sm text-text">
                        {{ user.phone }}
                    </td>
                    <td class="px-4 py-3 whitespace-nowrap text-sm text-text">
                        {{ user.line_id ? user.line_id : "Not Provided" }}
                    </td>
                </tr>
            </template>
        </StyledTable>
    </AdminLayout>
</template>
