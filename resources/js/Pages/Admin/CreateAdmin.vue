<template>
    <AuthenticatedLayout>
        <div class="max-w-2xl mx-auto p-6 bg-white rounded-lg shadow-sm">
            <h2 class="text-2xl font-bold text-gray-800 mb-6">Create Admin</h2>

            <form @submit.prevent="handleSubmit" class="space-y-5">
                <div>
                    <label for="user" class="block text-sm font-medium text-gray-700 mb-1">Select User</label>
                    <select v-model="selectedUser" id="user" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition bg-white">
                        <option :value="null" disabled selected>Select a user</option>
                        <option v-for="user in nonAdminUsers" :key="user.id" :value="user">
                            {{ user.username }} ({{ user.user_role }})
                        </option>
                    </select>
                </div>

                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition">
                    Make Admin
                </button>
            </form>

        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import axios from "axios";
import { toast } from 'vue3-toastify';
import 'vue3-toastify/dist/index.css';
import { ref, computed } from "vue";

const props = defineProps({
    users: Object
});
const nonAdminUsers = computed(() => {
    return props.users.filter(user => user.user_role !== 'admin');
});
const selectedUser = ref(null);

const handleSubmit = async () => {
    if (!selectedUser.value) {
        toast.error("Please select a user.");
        return;
    }
    try {
        const response = await axios.post('/make-admin', {
            user_id: selectedUser.value.id
        });
        toast.success(response.data.message || "User promoted to admin.");
        selectedUser.value = null;
    } catch (error) {
        toast.error(
            error.response?.data?.message || "Something went wrong."
        );
    }
};
</script>
