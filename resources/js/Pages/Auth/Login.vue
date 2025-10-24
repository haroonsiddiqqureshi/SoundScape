<script setup>
import { Head, Link, useForm } from "@inertiajs/vue3";
import AuthenticationCard from "@/Components/AuthenticationCard.vue";
import AuthenticationCardBackground from "@/Components/AuthenticationCardBackground.vue";
import ApplicationLogo from "@/Components/ApplicationLogo.vue";
import Checkbox from "@/Components/Checkbox.vue";
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import TextInput from "@/Components/TextInput.vue";

defineProps({
    canResetPassword: Boolean,
    canRegister: Boolean,
    status: String,
});

const form = useForm({
    email: "",
    password: "",
    remember: false,
});

const submit = () => {
    form.transform((data) => ({
        ...data,
        remember: form.remember ? "on" : "",
    })).post(route("login"), {
        onFinish: () => form.reset("password"),
    });
};
</script>

<template>
    <Head title="Log in" />

    <AuthenticationCard>
        <template #background>
            <AuthenticationCardBackground />
        </template>

        <template #logo>
            <div class="flex items-center space-x-2 mb-4">
                <ApplicationLogo
                    class="block h-12 p-2 rounded-md w-auto bg-primary text-white"
                />
                <span
                    class="flex justify-center text-pri font-bold tracking-wide text-2xl uppercase"
                    >SoundScape</span
                >
            </div>
        </template>

        <div v-if="status" class="mb-4 font-medium text-sm text-secondary">
            {{ status }}
        </div>

        <form @submit.prevent="submit">
            <div>
                <InputLabel for="email" value="Email" />
                <TextInput
                    id="email"
                    v-model="form.email"
                    type="email"
                    class="mt-1 block w-full"
                    required
                    autofocus
                    autocomplete="username"
                />
                <InputError class="mt-2" :message="form.errors.email" />
            </div>

            <div class="mt-4">
                <div class="flex justify-between">
                    <InputLabel for="password" value="Password" />
                    <Link
                        v-if="canResetPassword"
                        :href="route('password.request')"
                        class="hover:underline text-sm text-text-medium hover:text-text rounded-md"
                        tabindex="1"
                    >
                        Forgot your password?
                    </Link>
                </div>
                <TextInput
                    id="password"
                    v-model="form.password"
                    type="password"
                    class="mt-1 block w-full"
                    required
                    autocomplete="current-password"
                />
                <InputError class="mt-2" :message="form.errors.password" />
            </div>

            <div class="flex justify-between mt-4">
                <label class="flex items-center">
                    <Checkbox v-model:checked="form.remember" name="remember" />
                    <span class="ms-2 text-sm text-text-medium"
                        >Remember me</span
                    >
                </label>
            </div>

            <div class="flex items-center justify-end mt-2">
                <Link
                    v-if="canRegister"
                    :href="route('register')"
                    class="hover:underline text-sm text-text-medium hover:text-text rounded-md"
                >
                    Don't have an account?
                </Link>
                <PrimaryButton
                    class="ms-4"
                    :class="{ 'opacity-25': form.processing }"
                    :disabled="form.processing"
                >
                    Log in
                </PrimaryButton>
            </div>
        </form>
    </AuthenticationCard>
</template>
