<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import { useForm } from "@inertiajs/vue3";
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import TextInput from "@/Components/TextInput.vue";

const form = useForm({
    fullname: "",
    business_name: "",
    business_category: "individual",
    business_address: "",
    bio: "",
    social_links: {
        facebook: "",
        instagram: "",
        tiktok: "",
        twitter: "",
        discord: "",
        linkedin: "",
        youtube: "",
        website: "",
    },
});

const submit = () => {
    form.post(route("promoter.store"));
};
</script>

<template>
    <AppLayout title="Create Promoter Account">
        <div class="py-12">
            <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                    <h2 class="text-2xl font-bold mb-6">Create Promoter Profile</h2>
                    <form @submit.prevent="submit">
                        <div class="grid grid-cols-1 gap-6">
                            <div>
                                <InputLabel for="fullname" value="Full Name" />
                                <TextInput
                                    id="fullname"
                                    type="text"
                                    class="mt-1 block w-full"
                                    v-model="form.fullname"
                                    required
                                    autofocus
                                />
                                <InputError class="mt-2" :message="form.errors.fullname" />
                            </div>

                            <div>
                                <InputLabel for="business_name" value="Business Name" />
                                <TextInput
                                    id="business_name"
                                    type="text"
                                    class="mt-1 block w-full"
                                    v-model="form.business_name"
                                    required
                                />
                                <InputError class="mt-2" :message="form.errors.business_name" />
                            </div>

                            <div>
                                <InputLabel for="business_category" value="Business Category" />
                                <select id="business_category" v-model="form.business_category" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                    <option value="individual">Individual Promoter</option>
                                    <option value="company">Artist Management Company</option>
                                    <option value="nightclub">Nightclub/Venue</option>
                                </select>
                                <InputError class="mt-2" :message="form.errors.business_category" />
                            </div>

                            <div>
                                <InputLabel for="business_address" value="Business Address" />
                                <TextInput
                                    id="business_address"
                                    type="text"
                                    class="mt-1 block w-full"
                                    v-model="form.business_address"
                                    required
                                />
                                <InputError class="mt-2" :message="form.errors.business_address" />
                            </div>

                            <div>
                                <InputLabel for="bio" value="Bio" />
                                <textarea id="bio" v-model="form.bio" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" rows="4"></textarea>
                                <InputError class="mt-2" :message="form.errors.bio" />
                            </div>

                            <div class="col-span-1 mt-4">
                                <h3 class="text-lg font-medium text-gray-900">Social Links</h3>
                                <p class="text-sm text-gray-500 mb-4">Provide links to your social media profiles for verification.</p>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div v-for="(value, key) in form.social_links" :key="key">
                                        <InputLabel :for="key" :value="key.charAt(0).toUpperCase() + key.slice(1)" />
                                        <TextInput
                                            :id="key"
                                            type="url"
                                            class="mt-1 block w-full"
                                            v-model="form.social_links[key]"
                                            :placeholder="`https://${key}.com/`"
                                        />
                                        <InputError class="mt-2" :message="form.errors[`social_links.${key}`]" />
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center justify-end mt-6">
                            <PrimaryButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                                Create Promoter Account
                            </PrimaryButton>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
