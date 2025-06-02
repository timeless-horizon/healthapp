<template>
    <AuthenticatedLayout>
        <div class="flex flex-col p-4">
            <h1 class="text-3xl font-semibold text-teal-900 mb-4">All Users Dashboard</h1>
            <div class="mb-10">
                <h2 class="text-2xl font-medium text-teal-800 mb-4">Admin Users</h2>
                <div class="relative overflow-x-auto">
                    <table v-if="paginatedAdminUsers.length" class="w-full text-sm text-left text-gray-500 border">
                        <thead class="text-sm text-gray-700 uppercase bg-gray-50 font-semibold">
                            <tr>
                                <th scope="col" class="px-6 py-3">Name</th>
                                <th scope="col" class="px-6 py-3">Phone Number</th>
                                <th scope="col" class="px-6 py-3">Email Address</th>
                                <th scope="col" class="px-6 py-3">Username</th>
                                <th scope="col" class="px-6 py-3">State</th>
                                <th scope="col" class="px-6 py-3">Country</th>
                                <th scope="col" class="px-6 py-3">Created</th>
                                <th scope="col" class="px-6 py-3">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(user, index) in paginatedAdminUsers" :key="index"
                                class="odd:bg-white even:bg-gray-50">
                                <td scope="row" class="px-4 py-4 font-normal text-sm text-gray-900">{{ user.surname }}
                                    {{ user.otherNames
                                    }}
                                </td>
                                <td scope="row" class="px-4 py-4 font-normal text-sm text-gray-900">{{ user.tel }}</td>
                                <td scope="row" class="px-4 py-4 font-normal text-sm text-gray-900">{{ user.email }}
                                </td>
                                <td scope="row" class="px-4 py-4 font-normal text-sm text-gray-900">{{ user.username }}
                                </td>
                                <td scope="row" class="px-4 py-4 font-normal text-sm text-gray-900">{{ user.state }}
                                </td>
                                <td scope="row" class="px-4 py-4 font-normal text-sm text-gray-900">{{ user.country }}
                                </td>
                                <td scope="row" class="px-4 py-4 font-normal text-sm text-gray-900">{{
                                    formatDate(user.created_at) }}</td>
                                <td scope="row" class="px-4 py-4 font-normal text-sm text-red-500 cursor-pointer"
                                    @click="deleteUser(user.id)">Delete</td>
                            </tr>
                        </tbody>
                    </table>
                    <div v-else class="text-center py-4 bg-gray-50 border border-gray-300 rounded">
                        No admin users found
                    </div>
                    <div v-if="adminUsers.length" class="mt-4 flex justify-between items-center">
                        <button @click="adminPage = Math.max(1, adminPage - 1)" :disabled="adminPage === 1"
                            class="px-4 py-2 bg-teal-700 text-white rounded disabled:bg-gray-300">
                            Previous
                        </button>
                        <div class="flex space-x-2">
                            <button v-for="page in adminTotalPages" :key="page" @click="adminPage = page"
                                :class="['px-3 py-1 rounded-full text-sm', adminPage === page ? 'bg-teal-700 text-white' : 'bg-gray-200 text-teal-700']">
                                {{ page }}
                            </button>
                        </div>
                        <button @click="adminPage = Math.min(adminTotalPages, adminPage + 1)"
                            :disabled="adminPage === adminTotalPages"
                            class="px-4 py-2 bg-teal-700 text-white rounded disabled:bg-gray-300">
                            Next
                        </button>
                    </div>
                </div>
            </div>
            <div class="mb-10">
                <h2 class="text-2xl font-medium text-teal-800 mb-4">Doctor Users</h2>
                <div class="relative overflow-x-auto">
                    <table v-if="paginatedDoctorUsers.length" class="w-full text-sm text-left text-gray-500 border">
                        <thead class="text-sm text-gray-700 uppercase bg-gray-50 font-semibold">
                            <tr>
                                <th scope="col" class="px-6 py-3">Name</th>
                                <th scope="col" class="px-6 py-3">Phone Number</th>
                                <th scope="col" class="px-6 py-3">Email Address</th>
                                <th scope="col" class="px-6 py-3">Username</th>
                                <th scope="col" class="px-6 py-3">State</th>
                                <th scope="col" class="px-6 py-3">Country</th>
                                <th scope="col" class="px-6 py-3">Created</th>
                                <th scope="col" class="px-6 py-3">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(user, index) in paginatedDoctorUsers" :key="index"
                                class="odd:bg-white even:bg-gray-50">
                                <td scope="row" class="px-4 py-4 font-normal text-sm text-gray-900">{{ user.surname }}
                                    {{ user.otherNames
                                    }}
                                </td>
                                <td scope="row" class="px-4 py-4 font-normal text-sm text-gray-900">{{ user.tel }}</td>
                                <td scope="row" class="px-4 py-4 font-normal text-sm text-gray-900">{{ user.email }}
                                </td>
                                <td scope="row" class="px-4 py-4 font-normal text-sm text-gray-900">{{ user.username }}
                                </td>
                                <td scope="row" class="px-4 py-4 font-normal text-sm text-gray-900">{{ user.state }}
                                </td>
                                <td scope="row" class="px-4 py-4 font-normal text-sm text-gray-900">{{ user.country }}
                                </td>
                                <td scope="row" class="px-4 py-4 font-normal text-sm text-gray-900">{{
                                    formatDate(user.created_at) }}</td>
                                <td scope="row" class="px-4 py-4 font-normal text-sm text-red-500 cursor-pointer"
                                    @click="deleteUser(user.id)">Delete</td>
                            </tr>
                        </tbody>
                    </table>
                    <div v-else class="text-center py-4 bg-gray-50 border border-gray-300 rounded">
                        No doctor users found
                    </div>
                    <div v-if="doctorUsers.length" class="mt-4 flex justify-between items-center">
                        <button @click="doctorPage = Math.max(1, doctorPage - 1)" :disabled="doctorPage === 1"
                            class="px-4 py-2 bg-teal-700 text-white rounded disabled:bg-gray-300">
                            Previous
                        </button>
                        <div class="flex space-x-2">
                            <button v-for="page in doctorTotalPages" :key="page" @click="doctorPage = page"
                                :class="['px-3 py-1 rounded-full text-sm', doctorPage === page ? 'bg-teal-700 text-white' : 'bg-gray-200 text-teal-700']">
                                {{ page }}
                            </button>
                        </div>
                        <button @click="doctorPage = Math.min(doctorTotalPages, doctorPage + 1)"
                            :disabled="doctorPage === doctorTotalPages"
                            class="px-4 py-2 bg-teal-700 text-white rounded disabled:bg-gray-300">
                            Next
                        </button>
                    </div>
                </div>
            </div>
            <div>
                <h2 class="text-2xl font-medium text-teal-800 mb-4">Patient Users</h2>
                <div class="relative overflow-x-auto">
                    <table v-if="paginatedPatientUsers.length" class="w-full text-sm text-left text-gray-500 border">
                        <thead class="text-sm text-gray-700 uppercase bg-gray-50 font-semibold">
                            <tr>
                                <th scope="col" class="px-6 py-3">Name</th>
                                <th scope="col" class="px-6 py-3">Phone Number</th>
                                <th scope="col" class="px-6 py-3">Email Address</th>
                                <th scope="col" class="px-6 py-3">Username</th>
                                <th scope="col" class="px-6 py-3">State</th>
                                <th scope="col" class="px-6 py-3">Country</th>
                                <th scope="col" class="px-6 py-3">Created</th>
                                <th scope="col" class="px-6 py-3">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(user, index) in paginatedPatientUsers" :key="index"
                                class="odd:bg-white even:bg-gray-50">
                                <td scope="row" class="px-4 py-4 font-normal text-sm text-gray-900">{{ user.surname }}
                                    {{ user.otherNames
                                    }}
                                </td>
                                <td scope="row" class="px-4 py-4 font-normal text-sm text-gray-900">{{ user.tel }}</td>
                                <td scope="row" class="px-4 py-4 font-normal text-sm text-gray-900">{{ user.email }}
                                </td>
                                <td scope="row" class="px-4 py-4 font-normal text-sm text-gray-900">{{ user.username }}
                                </td>
                                <td scope="row" class="px-4 py-4 font-normal text-sm text-gray-900">{{ user.state }}
                                </td>
                                <td scope="row" class="px-4 py-4 font-normal text-sm text-gray-900">{{ user.country }}
                                </td>
                                <td scope="row" class="px-4 py-4 font-normal text-sm text-gray-900">{{
                                    formatDate(user.created_at) }}</td>
                                <td scope="row" class="px-4 py-4 font-normal text-sm text-red-500 cursor-pointer"
                                    @click="deleteUser(user.id)">Delete</td>
                            </tr>
                        </tbody>
                    </table>
                    <div v-else class="text-center py-4 bg-gray-50 border border-gray-300 rounded">
                        No patient users found
                    </div>
                    <div v-if="patientUsers.length" class="mt-4 flex justify-between items-center">
                        <button @click="patientPage = Math.max(1, patientPage - 1)" :disabled="patientPage === 1"
                            class="px-4 py-2 bg-teal-700 text-white rounded disabled:bg-gray-300">
                            Previous
                        </button>
                        <div class="flex space-x-2">
                            <button v-for="page in patientTotalPages" :key="page" @click="patientPage = page"
                                :class="['px-3 py-1 rounded-full text-sm', patientPage === page ? 'bg-teal-700 text-white' : 'bg-gray-200 text-teal-700']">
                                {{ page }}
                            </button>
                        </div>
                        <button @click="patientPage = Math.min(patientTotalPages, patientPage + 1)"
                            :disabled="patientPage === patientTotalPages"
                            class="px-4 py-2 bg-teal-700 text-white rounded disabled:bg-gray-300">
                            Next
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Link, router } from "@inertiajs/vue3";
import { computed, ref } from "vue";
import { toast } from "vue3-toastify";
import "vue3-toastify/dist/index.css";

const props = defineProps({
    users: Object,
});

const adminPage = ref(1);
const doctorPage = ref(1);
const patientPage = ref(1);
const itemsPerPage = 10;

const adminUsers = computed(() => {
    return props.users.filter((user) => user.user_role === "admin");
});

const doctorUsers = computed(() => {
    return props.users.filter((user) => user.user_role === "doctor");
});

const patientUsers = computed(() => {
    return props.users.filter((user) => user.user_role === "patient");
});

const adminTotalPages = computed(() => {
    return Math.ceil(adminUsers.value.length / itemsPerPage);
});

const doctorTotalPages = computed(() => {
    return Math.ceil(doctorUsers.value.length / itemsPerPage);
});

const patientTotalPages = computed(() => {
    return Math.ceil(patientUsers.value.length / itemsPerPage);
});

const paginatedAdminUsers = computed(() => {
    const start = (adminPage.value - 1) * itemsPerPage;
    const end = start + itemsPerPage;
    return adminUsers.value.slice(start, end);
});

const paginatedDoctorUsers = computed(() => {
    const start = (doctorPage.value - 1) * itemsPerPage;
    const end = start + itemsPerPage;
    return doctorUsers.value.slice(start, end);
});

const paginatedPatientUsers = computed(() => {
    const start = (patientPage.value - 1) * itemsPerPage;
    const end = start + itemsPerPage;
    return patientUsers.value.slice(start, end);
});

const formatDate = (dateString) => {
    return new Date(dateString).toLocaleDateString();
};

const deleteUser = (userId) => {
    router.delete(`/users/${userId}`, {
        preserveState: true,
        onSuccess: () => {
            toast.success("User deleted successfully", {
                autoClose: 3000,
                position: "top-right",
            });
            router.reload({ only: ["users"] });
        },
        onError: () => {
            toast.error("Failed to delete user. Please try again.", {
                autoClose: 3000,
                position: "top-right",
            });
        },
    });
};
</script>

<style></style>