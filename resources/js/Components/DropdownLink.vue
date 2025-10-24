<script setup>
import { Link } from '@inertiajs/vue3';

defineProps({
    href: String,
    as: String,
});
</script>

<template>
    <div>
        <button v-if="as == 'button'" type="submit" class="block w-full px-4 py-2 text-start text-sm hover:text-lg hover:font-medium leading-5 text-text focus:outline-0 active:outline-0 transition-all duration-100 ease-in-out">
            <slot />
        </button>

        <a v-else-if="as =='a'" :href="href" class="block px-4 py-2 text-sm leading-5 text-text hover:text-lg hover:font-medium focus:outline-0 active:outline-0 transition-all duration-100 ease-in-out">
            <slot />
        </a>

        <Link v-else :href="href" class="block px-4 py-2 text-sm leading-5 text-text hover:text-lg hover:font-medium focus:outline-0 active:outline-0 transition-all duration-100 ease-in-out">
            <slot />
        </Link>
    </div>
</template>
