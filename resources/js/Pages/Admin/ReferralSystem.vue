<template>
    <AuthenticatedLayout>
        <div class="flex flex-col p-4">
            <div class="flex items-center justify-between">
                <h1 class="text-3xl font-semibold text-teal-900 mb-4">Referral System</h1>

            <!-- Generate Code Button -->
            <button @click="showGenerateModal = true"
                class="mb-6 px-4 py-2 bg-teal-600 text-white rounded-md hover:bg-teal-700 focus:outline-none focus:ring-2 focus:ring-teal-500">
                Generate New Referral Code
            </button>
            </div>

            <!-- Filters -->
            <div class="w-full flex items-start justify-between mb-4">
                <div class="flex flex-wrap gap-4">
                    <div class="flex flex-col">
                        <label class="text-sm text-gray-600 mb-1">Code</label>
                        <input v-model="filters.code" type="text" placeholder="Search by code..."
                            class="border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-teal-600">
                    </div>
                    <div class="flex flex-col">
                        <label class="text-sm text-gray-600 mb-1">Email</label>
                        <input v-model="filters.email" type="text" placeholder="Search by email..."
                            class="border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-teal-600">
                    </div>
                    <div class="flex flex-col">
                        <label class="text-sm text-gray-600 mb-1">Name</label>
                        <input v-model="filters.name" type="text" placeholder="Search by name..."
                            class="border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-teal-600">
                    </div>
                    <div class="flex flex-col">
                        <label class="text-sm text-gray-600 mb-1">Date Range</label>
                        <div class="flex gap-2">
                            <input v-model="filters.startDate" type="date"
                                class="border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-teal-600">
                            <input v-model="filters.endDate" type="date"
                                class="border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-teal-600">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Generate Code Modal -->
            <div v-if="showGenerateModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full flex items-center justify-center z-50">
                <div class="relative p-5 border w-96 shadow-lg rounded-md bg-white">
                    <div class="mt-3">
                        <h3 class="text-lg font-medium leading-6 text-gray-900 mb-4">Generate Referral Code</h3>
                        <form @submit.prevent="generateCode" class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Email</label>
                                <input type="email" v-model="form.email" required
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Name (Optional)</label>
                                <input type="text" v-model="form.name"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500">
                            </div>
                            <div class="flex justify-end space-x-3 mt-4">
                                <button type="button" @click="showGenerateModal = false"
                                    class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-500">
                                    Cancel
                                </button>
                                <button type="submit"
                                    class="px-4 py-2 bg-teal-600 text-white rounded-md hover:bg-teal-700 focus:outline-none focus:ring-2 focus:ring-teal-500">
                                    Generate
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Referral Codes Table -->
            <div class="relative overflow-x-auto">
                <table v-if="paginatedReferralCodes.length" class="w-full text-sm text-left text-gray-500 border">
                    <thead class="text-sm text-gray-700 uppercase bg-gray-50 font-semibold">
                        <tr>
                            <th scope="col" class="px-6 py-3">S/N</th>
                            <th scope="col" class="px-6 py-3">Code</th>
                            <th scope="col" class="px-6 py-3">Email</th>
                            <th scope="col" class="px-6 py-3">Name</th>
                            <th scope="col" class="px-6 py-3">Registrations</th>
                            <th scope="col" class="px-6 py-3">Clicks</th>
                            <th scope="col" class="px-6 py-3">Referral Link</th>
                            <th scope="col" class="px-6 py-3">Created</th>
                            <th scope="col" class="px-6 py-3">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(code, index) in paginatedReferralCodes" :key="code.id" class="odd:bg-white even:bg-gray-50">
                            <td class="px-4 py-4 font-bold">{{ (currentPage - 1) * itemsPerPage + index + 1 }}</td>
                            <td class="px-4 py-4 font-normal text-sm text-gray-900">{{ code.code }}</td>
                            <td class="px-4 py-4 font-normal text-sm text-gray-900">{{ code.email }}</td>
                            <td class="px-4 py-4 font-normal text-sm text-gray-900">{{ code.name || '-' }}</td>
                            <td class="px-4 py-4 font-normal text-sm text-gray-900">{{ code.registrations_count }}</td>
                            <td class="px-4 py-4 font-normal text-sm text-gray-900">{{ code.clicks_count }}</td>
                            <td class="px-4 py-4 font-normal text-sm text-gray-900">
                                <div class="flex items-center space-x-2">
                                    <input type="text" readonly :value="getReferralLink(code.code)"
                                        class="border rounded px-2 py-1 text-sm w-64">
                                    <button @click="copyToClipboard(getReferralLink(code.code))"
                                        class="text-teal-600 hover:text-teal-800">
                                        Copy
                                    </button>
                                </div>
                            </td>
                            <td class="px-4 py-4 font-normal text-sm text-gray-900">{{ formatDate(code.created_at) }}</td>
                            <td class="px-4 py-4 font-normal text-sm">
                                <button @click="deleteReferralCode(code.id)" 
                                    class="text-red-500 hover:text-red-700">
                                    Delete
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <div v-else class="text-center py-4 bg-gray-50 border border-gray-300 rounded">
                    No referral codes found
                </div>

                <!-- Pagination Controls -->
                <div v-if="referralCodes.length" class="mt-4 flex justify-between items-center">
                    <button @click="currentPage = Math.max(1, currentPage - 1)" :disabled="currentPage === 1"
                        class="px-4 py-2 bg-teal-700 text-white rounded disabled:bg-gray-300">
                        Previous
                    </button>
                    <div class="flex space-x-2">
                        <button v-for="page in totalPages" :key="page" @click="currentPage = page"
                            :class="['px-3 py-1 rounded-full text-sm', currentPage === page ? 'bg-teal-700 text-white' : 'bg-gray-200 text-teal-700']">
                            {{ page }}
                        </button>
                    </div>
                    <button @click="currentPage = Math.min(totalPages, currentPage + 1)"
                        :disabled="currentPage === totalPages"
                        class="px-4 py-2 bg-teal-700 text-white rounded disabled:bg-gray-300">
                        Next
                    </button>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import { ref, computed } from 'vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { router } from '@inertiajs/vue3';
import { toast } from 'vue3-toastify';
import 'vue3-toastify/dist/index.css';
import axios from 'axios';

const props = defineProps({
    referralCodes: Array
});

const showGenerateModal = ref(false);
const form = ref({
    email: '',
    name: ''
});

// Filter state
const filters = ref({
    code: '',
    email: '',
    name: '',
    startDate: '',
    endDate: ''
});

// Pagination state
const currentPage = ref(1);
const itemsPerPage = 10;

// Filtered referral codes
const filteredReferralCodes = computed(() => {
    return props.referralCodes.filter(code => {
        const matchesCode = !filters.value.code || code.code.toLowerCase().includes(filters.value.code.toLowerCase());
        const matchesEmail = !filters.value.email || code.email.toLowerCase().includes(filters.value.email.toLowerCase());
        const matchesName = !filters.value.name || (code.name && code.name.toLowerCase().includes(filters.value.name.toLowerCase()));
        
        let matchesDate = true;
        if (filters.value.startDate || filters.value.endDate) {
            const codeDate = new Date(code.created_at);
            if (filters.value.startDate) {
                matchesDate = matchesDate && codeDate >= new Date(filters.value.startDate);
            }
            if (filters.value.endDate) {
                matchesDate = matchesDate && codeDate <= new Date(filters.value.endDate);
            }
        }
        
        return matchesCode && matchesEmail && matchesName && matchesDate;
    });
});

// Pagination computed properties
const totalPages = computed(() => Math.ceil(filteredReferralCodes.value.length / itemsPerPage));
const paginatedReferralCodes = computed(() => {
    const start = (currentPage.value - 1) * itemsPerPage;
    const end = start + itemsPerPage;
    return filteredReferralCodes.value.slice(start, end);
});

const generateCode = async () => {
    try {
        const response = await axios.post('/admin/referral/generate', form.value);
        if (response.data.success) {
            toast.success('Referral code generated successfully', {
                autoClose: 3000,
                position: "top-right",
            });
            showGenerateModal.value = false;
            form.value = { email: '', name: '' };
            router.reload({ only: ['referralCodes'] });
        }
    } catch (error) {
        toast.error('Failed to generate referral code', {
            autoClose: 3000,
            position: "top-right",
        });
    }
};

const getReferralLink = (code) => {
    return `${window.location.origin}/register?ref=${code}`;
};

const copyToClipboard = (text) => {
    navigator.clipboard.writeText(text).then(() => {
        toast.success('Link copied to clipboard', {
            autoClose: 3000,
            position: "top-right",
        });
    }).catch(() => {
        toast.error('Failed to copy link', {
            autoClose: 3000,
            position: "top-right",
        });
    });
};

const formatDate = (date) => {
    return new Date(date).toLocaleDateString();
};

const deleteReferralCode = async (id) => {
    if (!confirm('Are you sure you want to delete this referral code?')) {
        return;
    }

    try {
        await axios.delete(`/admin/referral/${id}`);
        toast.success('Referral code deleted successfully', {
            autoClose: 3000,
            position: "top-right",
        });
        router.reload({ only: ['referralCodes'] });
    } catch (error) {
        console.error('Error deleting referral code:', error);
        toast.error('Failed to delete referral code', {
            autoClose: 3000,
            position: "top-right",
        });
    }
};
</script>

<style>
/* Add toast container styles */
.toast-container {
    position: fixed;
    top: 1rem;
    right: 1rem;
    z-index: 9999;
}
</style> 