<template>
    <AuthenticatedLayout>
        <div class="max-w-2xl mx-auto p-6 bg-white rounded-lg shadow-sm">
            <h2 class="text-2xl font-bold text-gray-800 mb-6">Create Doctor</h2>
            <form @submit.prevent="handleSubmit" class="space-y-5">
                <div>
                    <label for="user" class="block text-sm font-medium text-gray-700 mb-1">Select User</label>
                    <select v-model="selectedUser" id="user" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition bg-white">
                        <option :value="null" disabled selected>Select a user</option>
                        <option v-for="user in props.users" :key="user.id" :value="user">
                            {{ user.username }}
                        </option>
                    </select>
                </div>
                <div>
                    <label for="age" class="block text-sm font-medium text-gray-700 mb-1">Age</label>
                    <input v-model="form.age" id="age" type="number" required placeholder="Enter age"
                        class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition" />
                </div>
                <div>
                    <label for="qualification"
                        class="block text-sm font-medium text-gray-700 mb-1">Qualification</label>
                    <input v-model="form.qualification" id="qualification" type="text" required
                        placeholder="Enter qualification"
                        class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition" />
                </div>
                <div>
                    <label for="fee" class="block text-sm font-medium text-gray-700 mb-1">Fee</label>
                    <input v-model="form.fee" id="fee" type="number" required step="0.01" placeholder="Enter fee"
                        class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition" />
                </div>
                <div>
                    <label for="department" class="block text-sm font-medium text-gray-700 mb-1">Department</label>
                    <select v-model="department" id="department" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition bg-white">
                        <option value="" disabled selected>Select Department</option>
                        <option v-for="department in props.departments" :key="department.id" :value="department.id">
                            {{ department.name }}
                        </option>
                    </select>
                </div>
                <div class="flex justify-end">
                    <button type="submit"
                        class="px-6 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                        Create Doctor
                    </button>
                </div>
            </form>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import { ref } from 'vue';
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import axios from "axios";
import { useForm } from "@inertiajs/vue3";
import { toast } from 'vue3-toastify';
import 'vue3-toastify/dist/index.css';

const props = defineProps({
    departments: Object,
    users: Object
});

const form = useForm({
    age: '',
    qualification: '',
    fee: '',
});

const department = ref('');
const selectedUser = ref(null);

const handleSubmit = async () => {
    if (!selectedUser.value) {
        toast.error('Please select a user');
        return;
    }

    const sentData = {
        form: {
            age: parseInt(form.age),
            qualification: form.qualification,
            fee: parseFloat(form.fee),
        },
        department: parseInt(department.value),
        user: selectedUser.value.id
    };

    try {
        const res = await axios.post('/add-doctor', { sentData }, {
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        });
        toast.success('Doctor created successfully');
        form.reset();
        department.value = '';
        selectedUser.value = null;
        setTimeout(() => {
            window.location.href = '/admin-users';
        }, 3000);
    } catch (error) {
        toast.error('Error:', error.response?.data || error.message);
    }
};
</script>