<template>
    <AuthenticatedLayout>
        <div class="flex flex-col p-4">
            <h1 class="text-3xl font-semibold text-teal-900 mb-4">All Users Dashboard</h1>
            
            <!-- Edit User Modal -->
            <div v-if="showEditModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full flex items-center z-50 justify-center">
                <div class="relative p-5 border w-96 shadow-lg rounded-md bg-white">
                    <div class="mt-3">
                        <h3 class="text-lg font-medium leading-6 text-gray-900 mb-4">Edit User Information</h3>
                        <form @submit.prevent="updateUser" class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Surname</label>
                                <input type="text" v-model="editingUser.surname" required
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Other Names</label>
                                <input type="text" v-model="editingUser.otherNames" required
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Email</label>
                                <input type="email" v-model="editingUser.email" required
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">New Password (leave blank to keep current)</label>
                                <input type="password" v-model="editingUser.password"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500">
                            </div>
                            <div class="flex justify-end space-x-3 mt-4">
                                <button type="button" @click="closeEditModal"
                                    class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-500">
                                    Cancel
                                </button>
                                <button type="submit"
                                    class="px-4 py-2 bg-teal-600 text-white rounded-md hover:bg-teal-700 focus:outline-none focus:ring-2 focus:ring-teal-500">
                                    Save Changes
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Assign Plan Modal -->
            <div v-if="showPlanModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full flex items-center z-50 justify-center">
                <div class="relative p-5 border w-96 shadow-lg rounded-md bg-white">
                    <div class="mt-3">
                        <h3 class="text-lg font-medium leading-6 text-gray-900 mb-4">Assign Plan to User</h3>
                        <form @submit.prevent="assignPlan" class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Select Plan</label>
                                <select v-model="selectedPlan.planId" required
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500">
                                    <option value="">Select a plan</option>
                                    <option v-for="plan in plans" :key="plan.id" :value="plan.id">
                                        {{ plan.name }} - {{ plan.description }}
                                    </option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Duration (months)</label>
                                <input type="number" v-model="selectedPlan.duration" required min="1"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500">
                            </div>
                            <div class="flex justify-end space-x-3 mt-4">
                                <button type="button" @click="closePlanModal"
                                    class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-500">
                                    Cancel
                                </button>
                                <button type="submit"
                                    class="px-4 py-2 bg-teal-600 text-white rounded-md hover:bg-teal-700 focus:outline-none focus:ring-2 focus:ring-teal-500">
                                    Assign Plan
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="mb-10">
                <h2 class="text-2xl font-medium text-teal-800 mb-4">Admin Users</h2>
                <!-- Admin Users Filter -->
                <div class="mb-4 flex flex-wrap gap-4">
                    <div class="flex flex-col">
                        <label class="text-sm text-gray-600 mb-1">Name</label>
                        <input v-model="filters.admin.name" type="text" placeholder="Search by name..."
                            class="border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-teal-600">
                    </div>
                    <div class="flex flex-col">
                        <label class="text-sm text-gray-600 mb-1">Email</label>
                        <input v-model="filters.admin.email" type="text" placeholder="Search by email..."
                            class="border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-teal-600">
                    </div>
                    <div class="flex flex-col">
                        <label class="text-sm text-gray-600 mb-1">State</label>
                        <input v-model="filters.admin.state" type="text" placeholder="Search by state..."
                            class="border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-teal-600">
                    </div>
                    <div class="flex flex-col">
                        <label class="text-sm text-gray-600 mb-1">Country</label>
                        <input v-model="filters.admin.country" type="text" placeholder="Search by country..."
                            class="border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-teal-600">
                    </div>
                </div>
                <div class="relative overflow-x-auto">
                    <table v-if="paginatedAdminUsers.length" class="w-full text-sm text-left text-gray-500 border">
                        <thead class="text-sm text-gray-700 uppercase bg-gray-50 font-semibold">
                            <tr>
                                <th scope="col" class="px-6 py-3">S/N</th>
                                <th scope="col" class="px-6 py-3">Name</th>
                                <th scope="col" class="px-6 py-3">Phone Number</th>
                                <th scope="col" class="px-6 py-3">Email Address</th>
                                <th scope="col" class="px-6 py-3">Username</th>
                                <th scope="col" class="px-6 py-3">State</th>
                                <th scope="col" class="px-6 py-3">Country</th>
                                <th scope="col" class="px-6 py-3">Current Plan</th>
                                <th scope="col" class="px-6 py-3">Created</th>
                                <th scope="col" class="px-6 py-3">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(user, index) in paginatedAdminUsers" :key="index"
                                class="odd:bg-white even:bg-gray-50">
                                <td class="px-4 py-4 font-bold">{{ (adminPage - 1) * itemsPerPage + index + 1 }}</td>
                                <td scope="row" class="px-4 py-4 font-normal text-sm text-gray-900">{{ user.surname }}
                                    {{ user.otherNames }}</td>
                                <td scope="row" class="px-4 py-4 font-normal text-sm text-gray-900">{{ user.tel }}</td>
                                <td scope="row" class="px-4 py-4 font-normal text-sm text-gray-900">{{ user.email }}</td>
                                <td scope="row" class="px-4 py-4 font-normal text-sm text-gray-900">{{ user.username }}</td>
                                <td scope="row" class="px-4 py-4 font-normal text-sm text-gray-900">{{ user.state }}</td>
                                <td scope="row" class="px-4 py-4 font-normal text-sm text-gray-900">{{ user.country }}</td>
                                <td scope="row" class="px-4 py-4 font-normal text-sm text-gray-900">
                                    <span v-if="user.active_plan" class="px-2 py-1 bg-green-100 text-green-800 rounded-full text-xs">
                                        {{ user.active_plan.plan.name }}
                                    </span>
                                    <span v-else class="px-2 py-1 bg-gray-100 text-gray-800 rounded-full text-xs">
                                        No Plan
                                    </span>
                                </td>
                                <td scope="row" class="px-4 py-4 font-normal text-sm text-gray-900">{{ formatDate(user.created_at) }}</td>
                                <td scope="row" class="px-4 py-4 font-normal text-sm">
                                    <button @click="openEditModal(user)" class="text-teal-600 hover:text-teal-800 mr-3">Edit</button>
                                    <button @click="openPlanModal(user)" class="text-blue-600 hover:text-blue-800 mr-3">Assign Plan</button>
                                    <button @click="deleteUser(user.id)" class="text-red-500 hover:text-red-700">Delete</button>
                                </td>
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
                <!-- Doctor Users Filter -->
                <div class="mb-4 flex flex-wrap gap-4">
                    <div class="flex flex-col">
                        <label class="text-sm text-gray-600 mb-1">Name</label>
                        <input v-model="filters.doctor.name" type="text" placeholder="Search by name..."
                            class="border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-teal-600">
                    </div>
                    <div class="flex flex-col">
                        <label class="text-sm text-gray-600 mb-1">Email</label>
                        <input v-model="filters.doctor.email" type="text" placeholder="Search by email..."
                            class="border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-teal-600">
                    </div>
                    <div class="flex flex-col">
                        <label class="text-sm text-gray-600 mb-1">State</label>
                        <input v-model="filters.doctor.state" type="text" placeholder="Search by state..."
                            class="border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-teal-600">
                    </div>
                    <div class="flex flex-col">
                        <label class="text-sm text-gray-600 mb-1">Country</label>
                        <input v-model="filters.doctor.country" type="text" placeholder="Search by country..."
                            class="border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-teal-600">
                    </div>
                </div>
                <div class="relative overflow-x-auto">
                    <table v-if="paginatedDoctorUsers.length" class="w-full text-sm text-left text-gray-500 border">
                        <thead class="text-sm text-gray-700 uppercase bg-gray-50 font-semibold">
                            <tr>
                                <th scope="col" class="px-6 py-3">S/N</th>
                                <th scope="col" class="px-6 py-3">Name</th>
                                <th scope="col" class="px-6 py-3">Phone Number</th>
                                <th scope="col" class="px-6 py-3">Email Address</th>
                                <th scope="col" class="px-6 py-3">Username</th>
                                <th scope="col" class="px-6 py-3">State</th>
                                <th scope="col" class="px-6 py-3">Country</th>
                                <th scope="col" class="px-6 py-3">Current Plan</th>
                                <th scope="col" class="px-6 py-3">Created</th>
                                <th scope="col" class="px-6 py-3">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(user, index) in paginatedDoctorUsers" :key="index"
                                class="odd:bg-white even:bg-gray-50">
                                <td class="px-4 py-4 font-bold">{{ (doctorPage - 1) * itemsPerPage + index + 1 }}</td>
                                <td scope="row" class="px-4 py-4 font-normal text-sm text-gray-900">{{ user.surname }}
                                    {{ user.otherNames }}</td>
                                <td scope="row" class="px-4 py-4 font-normal text-sm text-gray-900">{{ user.tel }}</td>
                                <td scope="row" class="px-4 py-4 font-normal text-sm text-gray-900">{{ user.email }}</td>
                                <td scope="row" class="px-4 py-4 font-normal text-sm text-gray-900">{{ user.username }}</td>
                                <td scope="row" class="px-4 py-4 font-normal text-sm text-gray-900">{{ user.state }}</td>
                                <td scope="row" class="px-4 py-4 font-normal text-sm text-gray-900">{{ user.country }}</td>
                                <td scope="row" class="px-4 py-4 font-normal text-sm text-gray-900">
                                    <span v-if="user.active_plan" class="px-2 py-1 bg-green-100 text-green-800 rounded-full text-xs">
                                        {{ user.active_plan.plan.name }}
                                    </span>
                                    <span v-else class="px-2 py-1 bg-gray-100 text-gray-800 rounded-full text-xs">
                                        No Plan
                                    </span>
                                </td>
                                <td scope="row" class="px-4 py-4 font-normal text-sm text-gray-900">{{ formatDate(user.created_at) }}</td>
                                <td scope="row" class="px-4 py-4 font-normal text-sm">
                                    <button @click="openEditModal(user)" class="text-teal-600 hover:text-teal-800 mr-3">Edit</button>
                                    <button @click="openPlanModal(user)" class="text-blue-600 hover:text-blue-800 mr-3">Assign Plan</button>
                                    <button @click="deleteUser(user.id)" class="text-red-500 hover:text-red-700">Delete</button>
                                </td>
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
                <!-- Patient Users Filter -->
                <div class="mb-4 flex flex-wrap gap-4">
                    <div class="flex flex-col">
                        <label class="text-sm text-gray-600 mb-1">Name</label>
                        <input v-model="filters.patient.name" type="text" placeholder="Search by name..."
                            class="border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-teal-600">
                    </div>
                    <div class="flex flex-col">
                        <label class="text-sm text-gray-600 mb-1">Email</label>
                        <input v-model="filters.patient.email" type="text" placeholder="Search by email..."
                            class="border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-teal-600">
                    </div>
                    <div class="flex flex-col">
                        <label class="text-sm text-gray-600 mb-1">State</label>
                        <input v-model="filters.patient.state" type="text" placeholder="Search by state..."
                            class="border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-teal-600">
                    </div>
                    <div class="flex flex-col">
                        <label class="text-sm text-gray-600 mb-1">Country</label>
                        <input v-model="filters.patient.country" type="text" placeholder="Search by country..."
                            class="border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-teal-600">
                    </div>
                </div>
                <div class="relative overflow-x-auto">
                    <table v-if="paginatedPatientUsers.length" class="w-full text-sm text-left text-gray-500 border">
                        <thead class="text-sm text-gray-700 uppercase bg-gray-50 font-semibold">
                            <tr>
                                <th scope="col" class="px-6 py-3">S/N</th>
                                <th scope="col" class="px-6 py-3">Name</th>
                                <th scope="col" class="px-6 py-3">Phone Number</th>
                                <th scope="col" class="px-6 py-3">Email Address</th>
                                <th scope="col" class="px-6 py-3">Username</th>
                                <th scope="col" class="px-6 py-3">State</th>
                                <th scope="col" class="px-6 py-3">Country</th>
                                <th scope="col" class="px-6 py-3">Current Plan</th>
                                <th scope="col" class="px-6 py-3">Created</th>
                                <th scope="col" class="px-6 py-3">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(user, index) in paginatedPatientUsers" :key="index"
                                class="odd:bg-white even:bg-gray-50">
                                <td class="px-4 py-4 font-bold">{{ (patientPage - 1) * itemsPerPage + index + 1 }}</td>
                                <td scope="row" class="px-4 py-4 font-normal text-sm text-gray-900">{{ user.surname }}
                                    {{ user.otherNames }}</td>
                                <td scope="row" class="px-4 py-4 font-normal text-sm text-gray-900">{{ user.tel }}</td>
                                <td scope="row" class="px-4 py-4 font-normal text-sm text-gray-900">{{ user.email }}</td>
                                <td scope="row" class="px-4 py-4 font-normal text-sm text-gray-900">{{ user.username }}</td>
                                <td scope="row" class="px-4 py-4 font-normal text-sm text-gray-900">{{ user.state }}</td>
                                <td scope="row" class="px-4 py-4 font-normal text-sm text-gray-900">{{ user.country }}</td>
                                <td scope="row" class="px-4 py-4 font-normal text-sm text-gray-900">
                                    <span v-if="user.active_plan" class="px-2 py-1 bg-green-100 text-green-800 rounded-full text-xs">
                                        {{ user.active_plan.plan.name }}
                                    </span>
                                    <span v-else class="px-2 py-1 bg-gray-100 text-gray-800 rounded-full text-xs">
                                        No Plan
                                    </span>
                                </td>
                                <td scope="row" class="px-4 py-4 font-normal text-sm text-gray-900">{{ formatDate(user.created_at) }}</td>
                                <td scope="row" class="px-4 py-4 font-normal text-sm">
                                    <button @click="openEditModal(user)" class="text-teal-600 hover:text-teal-800 mr-3">Edit</button>
                                    <button @click="openPlanModal(user)" class="text-blue-600 hover:text-blue-800 mr-3">Assign Plan</button>
                                    <button @click="deleteUser(user.id)" class="text-red-500 hover:text-red-700">Delete</button>
                                </td>
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
    plans: Array,
});

const adminPage = ref(1);
const doctorPage = ref(1);
const patientPage = ref(1);
const itemsPerPage = 10;

// Filter state
const filters = ref({
    admin: {
        name: '',
        email: '',
        state: '',
        country: ''
    },
    doctor: {
        name: '',
        email: '',
        state: '',
        country: ''
    },
    patient: {
        name: '',
        email: '',
        state: '',
        country: ''
    }
});

// Filtered users
const filteredAdminUsers = computed(() => {
    return props.users.filter(user => {
        if (user.user_role !== 'admin') return false;
        
        const fullName = `${user.surname} ${user.otherNames}`.toLowerCase();
        const searchName = filters.value.admin.name.toLowerCase();
        const searchEmail = filters.value.admin.email.toLowerCase();
        const searchState = filters.value.admin.state.toLowerCase();
        const searchCountry = filters.value.admin.country.toLowerCase();

        return (!searchName || fullName.includes(searchName)) &&
               (!searchEmail || user.email.toLowerCase().includes(searchEmail)) &&
               (!searchState || (user.state && user.state.toLowerCase().includes(searchState))) &&
               (!searchCountry || (user.country && user.country.toLowerCase().includes(searchCountry)));
    });
});

const filteredDoctorUsers = computed(() => {
    return props.users.filter(user => {
        if (user.user_role !== 'doctor') return false;
        
        const fullName = `${user.surname} ${user.otherNames}`.toLowerCase();
        const searchName = filters.value.doctor.name.toLowerCase();
        const searchEmail = filters.value.doctor.email.toLowerCase();
        const searchState = filters.value.doctor.state.toLowerCase();
        const searchCountry = filters.value.doctor.country.toLowerCase();

        return (!searchName || fullName.includes(searchName)) &&
               (!searchEmail || user.email.toLowerCase().includes(searchEmail)) &&
               (!searchState || (user.state && user.state.toLowerCase().includes(searchState))) &&
               (!searchCountry || (user.country && user.country.toLowerCase().includes(searchCountry)));
    });
});

const filteredPatientUsers = computed(() => {
    return props.users.filter(user => {
        if (user.user_role !== 'patient') return false;
        
        const fullName = `${user.surname} ${user.otherNames}`.toLowerCase();
        const searchName = filters.value.patient.name.toLowerCase();
        const searchEmail = filters.value.patient.email.toLowerCase();
        const searchState = filters.value.patient.state.toLowerCase();
        const searchCountry = filters.value.patient.country.toLowerCase();

        return (!searchName || fullName.includes(searchName)) &&
               (!searchEmail || user.email.toLowerCase().includes(searchEmail)) &&
               (!searchState || (user.state && user.state.toLowerCase().includes(searchState))) &&
               (!searchCountry || (user.country && user.country.toLowerCase().includes(searchCountry)));
    });
});

// Update the existing computed properties to use filtered results
const adminUsers = computed(() => filteredAdminUsers.value);
const doctorUsers = computed(() => filteredDoctorUsers.value);
const patientUsers = computed(() => filteredPatientUsers.value);

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

// Modal state
const showEditModal = ref(false);
const editingUser = ref({
    id: null,
    surname: '',
    otherNames: '',
    email: '',
    password: ''
});

// Open edit modal
const openEditModal = (user) => {
    editingUser.value = {
        id: user.id,
        surname: user.surname,
        otherNames: user.otherNames,
        email: user.email,
        password: ''
    };
    showEditModal.value = true;
};

// Close edit modal
const closeEditModal = () => {
    showEditModal.value = false;
    editingUser.value = {
        id: null,
        surname: '',
        otherNames: '',
        email: '',
        password: ''
    };
};

// Update user
const updateUser = () => {
    const formData = {
        id: editingUser.value.id,
        surname: editingUser.value.surname,
        otherNames: editingUser.value.otherNames,
        email: editingUser.value.email,
    };

    // Only include password if it's not empty
    if (editingUser.value.password) {
        formData.password = editingUser.value.password;
    }

    router.put(route('admin.users.update', { user: editingUser.value.id }), formData, {
        onSuccess: () => {
            toast.success('User updated successfully');
            closeEditModal();
            router.reload({ only: ['users'] });
        },
        onError: (errors) => {
            toast.error('Failed to update user');
        }
    });
};

// Plan assignment state
const showPlanModal = ref(false);
const selectedPlan = ref({
    userId: null,
    planId: '',
    duration: 1
});

// Open plan modal
const openPlanModal = (user) => {
    selectedPlan.value = {
        userId: user.id,
        planId: '',
        duration: 1
    };
    showPlanModal.value = true;
};

// Close plan modal
const closePlanModal = () => {
    showPlanModal.value = false;
    selectedPlan.value = {
        userId: null,
        planId: '',
        duration: 1
    };
};

// Assign plan to user
const assignPlan = () => {
    console.log('Assigning plan:', selectedPlan.value); // Debug log
    
    router.post(route('admin.users.assign-plan'), selectedPlan.value, {
        preserveScroll: true,
        onSuccess: () => {
            toast.success('Plan assigned successfully');
            closePlanModal();
            router.reload({ only: ['users'] });
        },
        onError: (errors) => {
            console.error('Error:', errors); // Debug log
            if (errors.error) {
                toast.error(errors.error);
            } else {
                toast.error('Failed to assign plan');
            }
        }
    });
};
</script>

<style scoped>
.modal-backdrop {
    background-color: rgba(0, 0, 0, 0.5);
}
</style>