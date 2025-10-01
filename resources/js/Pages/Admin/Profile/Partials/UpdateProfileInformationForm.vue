<script setup>
import { useForm } from '@inertiajs/vue3';
import ActionMessage from '@/Components/ActionMessage.vue';
import FormSection from '@/Components/FormSection.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';

const props = defineProps({
    admin: Object,
});

const form = useForm({
    _method: 'PUT',
    name: props.admin.name,
    phone: props.admin.phone,
    context_info: props.admin.context_info,
});

const updateProfileInformation = () => {
    form.post(route('admin.profile-information.update'), {
        errorBag: 'updateProfileInformation',
        preserveScroll: true,
    });
};

</script>

<template>
    <FormSection @submitted="updateProfileInformation">
        <template #title>
            Profile Information
        </template>

        <template #description>
            Update your account's profile information.
        </template>

        <template #form>
            <!-- Name -->
            <div class="col-span-6 sm:col-span-4">
                <InputLabel for="name" value="Name" />
                <TextInput
                    id="name"
                    v-model="form.name"
                    type="text"
                    class="mt-1 block w-full"
                    required
                    autocomplete="name"
                />
                <InputError :message="form.errors.name" class="mt-2" />
            </div>

            <!-- Phone -->
            <div class="col-span-6 sm:col-span-4">
                <InputLabel for="phone" value="Phone" />
                <TextInput
                    id="phone"
                    v-model="form.phone"
                    type="text"
                    class="mt-1 block w-full"
                />
                <InputError :message="form.errors.phone" class="mt-2" />
            </div>

            <!-- Context Info -->
            <div class="col-span-6 sm:col-span-4">
                <InputLabel for="context_info" value="Context Info" />
                <TextInput
                    id="context_info"
                    v-model="form.context_info"
                    type="text"
                    class="mt-1 block w-full"
                />
                <InputError :message="form.errors.context_info" class="mt-2" />
            </div>
        </template>

        <template #actions>
            <ActionMessage :on="form.recentlySuccessful" class="me-3">
                Saved.
            </ActionMessage>

            <PrimaryButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                Save
            </PrimaryButton>
        </template>
    </FormSection>
</template>
