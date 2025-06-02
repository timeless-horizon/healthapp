<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { ref, watch } from 'vue';

const props = defineProps({
    email: {
        type: String,
        required: true,
    },
    token: {
        type: String,
        required: true,
    },
});

const form = useForm({
    token: props.token,
    email: props.email,
    password: '',
    password_confirmation: '',
});

const errors = ref({
    password: '',
    password_confirmation: ''
});

// Password visibility states
const showPassword = ref(false);
const showPasswordConfirmation = ref(false);

// Toggle password visibility
const togglePasswordVisibility = () => {
    showPassword.value = !showPassword.value;
};

// Toggle confirmation password visibility
const toggleConfirmationVisibility = () => {
    showPasswordConfirmation.value = !showPasswordConfirmation.value;
};

// Password validation
const validatePassword = () => {
    errors.value.password = '';

    // Check minimum length
    if (form.password.length < 8) {
        errors.value.password = 'Password must be at least 8 characters';
        return false;
    }

    // Check for at least one uppercase letter
    if (!/[A-Z]/.test(form.password)) {
        errors.value.password = 'Password must contain at least one uppercase letter';
        return false;
    }

    // Check for at least one number
    if (!/[0-9]/.test(form.password)) {
        errors.value.password = 'Password must contain at least one number';
        return false;
    }

    return true;
};

// Confirm password validation
const validatePasswordConfirmation = () => {
    errors.value.password_confirmation = '';

    if (form.password !== form.password_confirmation) {
        errors.value.password_confirmation = 'Password confirmation does not match';
        return false;
    }

    return true;
};

// Watch for changes to validate in real-time
watch(() => form.password, () => {
    validatePassword();
    if (form.password_confirmation) {
        validatePasswordConfirmation();
    }
});

watch(() => form.password_confirmation, validatePasswordConfirmation);

const submit = () => {
    // Clear previous errors
    errors.value = {
        password: '',
        password_confirmation: ''
    };

    // Validate before submission
    const isPasswordValid = validatePassword();
    const isConfirmationValid = validatePasswordConfirmation();

    if (!isPasswordValid || !isConfirmationValid) {
        return;
    }

    form.post(route('password.store'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};
</script>

<template>
    <GuestLayout>

        <Head title="Reset Password" />
        <section
            class="pl-4 pr-4 pb-4 pt-8 bg-[url('https://images.unsplash.com/photo-1504813184591-01572f98c85f?q=80&w=2071&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D')] bg-gray-700 bg-blend-multiply">
            <div
                class="max-w-xl mx-auto p-6 bg-white shadow-md rounded-md transform transition duration-500 ease-in-out animate-fade-in delay-300 mt-24 mb-10">
                <form @submit.prevent="submit">
                    <div>
                        <InputLabel for="email" value="Email" />
                        <TextInput id="email" type="email" class="mt-1 block w-full" v-model="form.email" required
                            autofocus autocomplete="username" />
                        <InputError class="mt-2" :message="form.errors.email" />
                    </div>

                    <div class="mt-4">
                        <InputLabel for="password" value="New Password" />
                        <div class="relative">
                            <TextInput id="password" :type="showPassword ? 'text' : 'password'"
                                class="mt-1 block w-full pr-10" v-model="form.password" required
                                autocomplete="new-password" />
                            <button type="button"
                                class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-600 cursor-pointer mt-1"
                                @click="togglePasswordVisibility" tabindex="-1">
                                <!-- Eye open icon (when password is hidden) -->
                                <svg v-if="!showPassword" xmlns="http://www.w3.org/2000/svg" fill="none"
                                    viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                <!-- Eye closed icon (when password is visible) -->
                                <svg v-else xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.242 4.242L9.88 9.88" />
                                </svg>
                            </button>
                        </div>
                        <InputError class="mt-2" :message="form.errors.password || errors.password" />
                        <p class="text-xs text-gray-500 mt-1">
                            Password must be at least 8 characters, include one uppercase letter and one number
                        </p>
                    </div>

                    <div class="mt-4">
                        <InputLabel for="password_confirmation" value="Confirm New Password" />
                        <div class="relative">
                            <TextInput id="password_confirmation" :type="showPasswordConfirmation ? 'text' : 'password'"
                                class="mt-1 block w-full pr-10" v-model="form.password_confirmation" required
                                autocomplete="new-password" />
                            <button type="button"
                                class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-600 cursor-pointer mt-1"
                                @click="toggleConfirmationVisibility" tabindex="-1">
                                <!-- Eye open icon (when password is hidden) -->
                                <svg v-if="!showPasswordConfirmation" xmlns="http://www.w3.org/2000/svg" fill="none"
                                    viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                <!-- Eye closed icon (when password is visible) -->
                                <svg v-else xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.242 4.242L9.88 9.88" />
                                </svg>
                            </button>
                        </div>
                        <InputError class="mt-2"
                            :message="form.errors.password_confirmation || errors.password_confirmation" />
                    </div>

                    <div class="mt-4 flex items-center justify-end">
                        <button type="submit" :disabled="form.processing" :class="{ 'opacity-25': form.processing }"
                            class="w-full bg-blue-500 text-white py-2 rounded-md hover:bg-blue-600 disabled:bg-blue-300
                            flex items-center justify-center disabled:cursor-not-allowed">
                            Reset Password
                        </button>
                    </div>
                </form>
            </div>
        </section>
    </GuestLayout>
</template>