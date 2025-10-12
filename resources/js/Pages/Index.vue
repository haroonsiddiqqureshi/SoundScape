<script setup>
import UserLayout from '@/Layouts/UserLayout.vue';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import Welcome from '@/Components/Welcome.vue';
import { computed } from 'vue';
import { usePage } from '@inertiajs/vue3';

const page = usePage();

const layout = computed(() => {
  return page.props.auth.user ? UserLayout : GuestLayout;
});
</script>

<template>
    <component :is="layout" title="Home" :can-login="$page.props.canLogin" :can-register="$page.props.canRegister">
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <Welcome />
                </div>
            </div>
        </div>
    </component>
</template>
