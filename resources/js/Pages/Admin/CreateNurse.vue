<template>
    <AuthenticatedLayout>
        <div class="min-h-screen bg-gray-100 py-8 px-4">
            <div class="max-w-3xl mx-auto">
                <h1 class="text-3xl font-bold text-gray-800 mb-6">Create Nurse Account</h1>

                <!-- Form -->
                <div class="bg-white shadow-md rounded-lg p-6">
                    <form @submit.prevent="submitForm">
                        <!-- Select User -->
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Select User
                            </label>
                            <select v-model="form.user" required
                                class="w-full p-2 border border-gray-300 rounded-md">
                                <option value="">Choose a user...</option>
                                <option v-for="user in users" :key="user.id" :value="user.id">
                                    {{ user.surname }} {{ user.otherNames }} ({{ user.email }})
                                </option>
                            </select>
                        </div>

                        <!-- Department -->
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Department
                            </label>
                            <select v-model="form.department" required
                                class="w-full p-2 border border-gray-300 rounded-md">
                                <option value="">Select department...</option>
                                <option v-for="dept in departments" :key="dept.id" :value="dept.id">
                                    {{ dept.name }}
                                </option>
                            </select>
                        </div>

                        <!-- Qualification -->
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Qualification
                            </label>
                            <input type="text" v-model="form.qualification" required
                                class="w-full p-2 border border-gray-300 rounded-md"
                                placeholder="e.g., Registered Nurse, BSN">
                        </div>

                        <!-- Years of Experience -->
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Years of Experience
                            </label>
                            <input type="number" v-model="form.experience" required min="0"
                                class="w-full p-2 border border-gray-300 rounded-md">
                        </div>

                        <!-- Submit Button -->
                        <div class="flex justify-end">
                            <button type="submit"
                                class="bg-teal-900 text-white px-6 py-2 rounded-md hover:bg-teal-800 focus:outline-none focus:ring-2 focus:ring-teal-500">
                                Create Nurse Account
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { ref } from 'vue';
import { router } from '@inertiajs/vue3';

const props = defineProps({
    users: Array,
    departments: Array
});

const form = ref({
    user: '',
    department: '',
    qualification: '',
    experience: ''
});

const submitForm = () => {
    router.post('/admin/create-nurse', {
        sentData: {
            form: {
                qualification: form.value.qualification,
                experience: parseInt(form.value.experience)
            },
            department: parseInt(form.value.department),
            user: parseInt(form.value.user)
        }
    });
};
</script> 