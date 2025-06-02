<template>

    <Head title="Login" />
    <GuestLayout>
        <div
            class="min-h-[80dvh] flex items-center justify-center px-4 sm:px-6 lg:px-8 bg-[url('https://images.unsplash.com/photo-1504813184591-01572f98c85f?q=80&w=2071&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D')] bg-gray-700 bg-blend-multiply">
            <div
                class="w-full max-w-md mt-24 mb-5 bg-white p-6 sm:p-8 rounded shadow transition duration-500 ease-in-out animate-fade-in delay-300">
                <h2 class="text-2xl font-bold mb-6">Login</h2>
                <form @submit.prevent="submit">
                    <div class="mb-4">
                        <label for="email" class="block text-sm font-medium text-gray-700">Email/Phone</label>
                        <input v-model="form.id" type="text" id="email" placeholder="Enter your email Or Phone"
                            class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" />
                    </div>
                    <div class="mb-4">
                        <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                        <div class="relative">
                            <input v-model="form.password" :type="showPassword ? 'text' : 'password'" id="password"
                                placeholder="Enter your password"
                                class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" />
                            <button type="button" @click="togglePasswordVisibility"
                                class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-500 hover:text-gray-700 focus:outline-none">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" v-if="showPassword">
                                    <path
                                        d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24">
                                    </path>
                                    <line x1="1" y1="1" x2="23" y2="23"></line>
                                </svg>
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" v-else>
                                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                    <circle cx="12" cy="12" r="3"></circle>
                                </svg>
                            </button>
                        </div>
                    </div>
                    <p class="text-sm text-gray-600 text-end mb-4">
                        <Link href="/forgot-password"
                            class="underline text-gray-600 hover:text-blue-700 dark:hover:text-blue-500">Forgot your
                        password?</Link>
                    </p>
                    <div class="mb-4">
                        <button type="submit" :disabled="loading"
                            class="w-full bg-blue-600 text-white py-2 px-4 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 disabled:bg-blue-400 disabled:cursor-not-allowed flex items-center justify-center">
                            <span v-if="loading" class="animate-spin mr-2">⟳</span>
                            <span>{{ loading ? 'Logging in...' : 'Login' }}</span>
                        </button>
                    </div>
                    <p class="text-sm text-gray-600 text-center mb-4">Don't have an account?
                        <Link href="/register"
                            class="underline text-gray-600 hover:text-blue-700 dark:hover:text-blue-500">
                        Register</Link>
                    </p>
                </form>
            </div>
        </div>
    </GuestLayout>
</template>
<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import { useForm } from "@inertiajs/vue3";
import axios from "axios";
import { ref } from 'vue';
import { toast } from 'vue3-toastify';
import 'vue3-toastify/dist/index.css';
import { Link } from '@inertiajs/vue3';
import { Head } from '@inertiajs/vue3';

const form = useForm({
    id: '',
    password: '',
});

const loading = ref(false);
const showPassword = ref(false);

const togglePasswordVisibility = () => {
    showPassword.value = !showPassword.value;
};

const submit = async () => {
    if (!form.id || !form.password) {
        toast.error('Enter valid login credentials');
        return;
    }
    loading.value = true;
    try {
        const response = await axios.post('/login', form);
        if (response.status === 200) {
            toast.success('Login Successful!');
            setTimeout(() => {
                window.location.href = '/dashboard';
            }, 1500);
        }
    } catch (error) {
        if (error.response && error.response.status === 422) {
            const errorData = error.response.data.errors;
            if (errorData) {
                Object.values(errorData).forEach(errorArray => {
                    errorArray.forEach(error => {
                        toast.error(error);
                    });
                });
            } else {
                toast.error('Invalid credentials');
            }
        } else {
            toast.error('An unexpected error occurred');
        }
    } finally {
        loading.value = false;
    }
};
</script>

<style scoped>
@keyframes fade-in {
    from {
        opacity: 0;
        transform: translateY(20px);
    }

    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.animate-fade-in {
    animation: fade-in 1s ease-out;
}
</style>