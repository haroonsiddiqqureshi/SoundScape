<script setup>
import { Head } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import DashboardStatCard from '@/Components/DashboardStatCard.vue';
import {
    UserIcon,
    CheckBadgeIcon,
    MusicalNoteIcon,
    FlagIcon,
} from '@heroicons/vue/24/outline';

defineProps({
    stats: Object,
    recentConcerts: Array,
});
</script>

<template>

    <Head title="Admin Dashboard" />

    <AdminLayout>
        <div class="max-w-7xl mx-auto space-y-2">

            <div class="grid grid-cols-2 lg:grid-cols-4 gap-2">
                <DashboardStatCard title="ผู้ใช้ทั้งหมด" :value="stats.total_users" border-color="border-blue-500">
                    <template #icon>
                        <UserIcon class="w-8 h-8 text-blue-500 stroke-[2px]" />
                    </template>
                </DashboardStatCard>
                <DashboardStatCard title="โปรโมเตอร์ทั้งหมด" :value="stats.total_promoters"
                    border-color="border-green-500">
                    <template #icon>
                        <CheckBadgeIcon class="w-8 h-8 text-green-500 stroke-[2px]" />
                    </template>
                </DashboardStatCard>
                <DashboardStatCard title="คอนเสิร์ตในระบบ" :value="stats.total_concerts"
                    border-color="border-purple-500">
                    <template #icon>
                        <MusicalNoteIcon class="w-8 h-8 text-purple-500 stroke-[2px]" />
                    </template>
                </DashboardStatCard>
                <DashboardStatCard title="ไฮไลท์ที่เปิดใช้งานอยู่" :value="stats.active_highlights"
                    border-color="border-yellow-500">
                    <template #icon>
                        <FlagIcon class="w-8 h-8 text-yellow-500 stroke-[2px]" />
                    </template>
                </DashboardStatCard>
            </div>

            <div class="bg-card overflow-hidden shadow-sm rounded-md">
                <div class="p-6">
                    <h3 class="text-lg font-bold mb-2">คอนเสิร์ตล่าสุด</h3>
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-left text-text-medium">
                            <thead class="text-xs text-text-high uppercase bg-card-hover">
                                <tr>
                                    <th scope="col" class="px-6 py-3">ชื่อคอนเสิร์ต</th>
                                    <th scope="col" class="px-6 py-3">โปรโมเตอร์</th>
                                    <th scope="col" class="px-6 py-3">วันที่แสดง</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="concert in recentConcerts" :key="concert.id"
                                    class="bg-card border-b hover:bg-background-hover">
                                    <td class="px-6 py-4 font-medium whitespace-nowrap">
                                        {{ concert.title }}
                                    </td>
                                    <td class="px-6 py-4">
                                        {{ concert.promoter?.business_name || 'N/A' }}
                                    </td>
                                    <td class="px-6 py-4">
                                        {{ new Date(concert.date).toLocaleDateString('th-TH') }}
                                    </td>
                                </tr>
                                <tr v-if="recentConcerts.length === 0">
                                    <td colspan="3" class="px-6 py-4 text-center text-text-medium">ไม่มีข้อมูล</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>