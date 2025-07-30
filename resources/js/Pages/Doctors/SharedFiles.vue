<template>
    <AuthenticatedLayout>
        <div class="flex flex-col p-6">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-3xl font-semibold text-teal-900">Patient Shared Files</h1>
                <div class="text-sm text-gray-600">
                    {{ sharedFiles.length }} file(s) shared with you
                </div>
            </div>

            <!-- Search and Filter Controls -->
            <div class="bg-white rounded-lg shadow p-4 mb-6">
                <div class="flex flex-wrap gap-4 items-end">
                    <div class="flex-1 min-w-64">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Search Files</label>
                        <input type="text" v-model="searchQuery" 
                            placeholder="Search by file name, patient name, or purpose..."
                            class="w-full p-2 border border-gray-300 rounded-md">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">File Type</label>
                        <select v-model="filterFileType" class="p-2 border border-gray-300 rounded-md">
                            <option value="">All Types</option>
                            <option value="application/pdf">PDF</option>
                            <option value="image/jpeg">JPEG</option>
                            <option value="image/png">PNG</option>
                            <option value="application/msword">DOC</option>
                            <option value="application/vnd.openxmlformats-officedocument.wordprocessingml.document">DOCX</option>
                            <option value="text/plain">TXT</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Date Range</label>
                        <select v-model="filterDateRange" class="p-2 border border-gray-300 rounded-md">
                            <option value="">All Time</option>
                            <option value="today">Today</option>
                            <option value="week">This Week</option>
                            <option value="month">This Month</option>
                            <option value="quarter">Last 3 Months</option>
                        </select>
                    </div>
                    <button @click="clearFilters" 
                        class="px-4 py-2 text-gray-600 bg-gray-100 rounded-md hover:bg-gray-200">
                        Clear Filters
                    </button>
                </div>
            </div>

            <!-- Files Table -->
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <div v-if="filteredFiles.length > 0" class="overflow-x-auto">
                    <table class="w-full text-sm text-left text-gray-500">
                        <thead class="text-sm text-gray-700 uppercase bg-gray-50 font-semibold">
                            <tr>
                                <th scope="col" class="px-6 py-3">Patient</th>
                                <th scope="col" class="px-6 py-3">File Details</th>
                                <th scope="col" class="px-6 py-3">Purpose</th>
                                <th scope="col" class="px-6 py-3">Shared Date</th>
                                <th scope="col" class="px-6 py-3">Access Status</th>
                                <th scope="col" class="px-6 py-3">Notes</th>
                                <th scope="col" class="px-6 py-3">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="share in paginatedFiles" :key="share.id" 
                                class="bg-white border-b hover:bg-gray-50"
                                :class="{ 'opacity-60': share.is_expired }">
                                <td class="px-6 py-4">
                                    <div class="font-medium text-gray-900">{{ share.patient_name }}</div>
                                    <div class="text-sm text-gray-500">{{ share.patient_email }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="font-medium text-gray-900">{{ share.file_name }}</div>
                                    <div class="text-sm text-gray-500">
                                        {{ share.file_type }} • {{ share.file_size }}
                                    </div>
                                    <div v-if="share.description" class="text-sm text-gray-400 mt-1">
                                        {{ share.description }}
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        {{ share.purpose }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <div>{{ share.shared_at }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    <div v-if="share.is_expired" class="flex items-center text-red-600">
                                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                                        </svg>
                                        Expired
                                    </div>
                                    <div v-else class="flex items-center text-green-600">
                                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                        </svg>
                                        Active
                                    </div>
                                    <div v-if="share.expires_at" class="text-xs text-gray-500 mt-1">
                                        Expires: {{ share.expires_at }}
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div v-if="share.notes" class="text-sm text-gray-700 max-w-xs truncate">
                                        {{ share.notes }}
                                    </div>
                                    <span v-else class="text-gray-400 text-sm">No notes</span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex flex-col space-y-2">
                                        <button v-if="!share.is_expired"
                                            @click="previewFile(share)"
                                            class="text-blue-600 hover:text-blue-800 text-sm text-left">
                                            Preview
                                        </button>
                                        <a v-if="!share.is_expired" 
                                            :href="`/patient/files/${share.file_id}/download`"
                                            class="text-blue-600 hover:text-blue-800 text-sm">
                                            Download
                                        </a>
                                        <button v-if="!share.is_expired"
                                            @click="viewFileDetails(share)"
                                            class="text-teal-600 hover:text-teal-800 text-sm text-left">
                                            View Details
                                        </button>
                                        <span v-if="share.is_expired" class="text-gray-400 text-sm">
                                            Access Expired
                                        </span>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div v-else class="text-center py-12">
                    <div class="text-gray-500 text-lg mb-2">No shared files found</div>
                    <div class="text-gray-400 text-sm">
                        {{ searchQuery || filterFileType || filterDateRange ? 'Try adjusting your filters' : 'Patients haven\'t shared any files with you yet' }}
                    </div>
                </div>
            </div>

            <!-- Pagination -->
            <div v-if="filteredFiles.length > itemsPerPage" class="flex justify-center items-center space-x-4 mt-6">
                <button @click="currentPage = Math.max(1, currentPage - 1)" 
                    :disabled="currentPage === 1"
                    class="px-4 py-2 bg-teal-700 text-white rounded disabled:bg-gray-300">
                    Previous
                </button>
                <div class="flex space-x-2">
                    <button v-for="page in totalPages" :key="page" 
                        @click="currentPage = page"
                        :class="['px-3 py-1 rounded-full text-sm', 
                                currentPage === page ? 'bg-teal-700 text-white' : 'bg-gray-200 text-teal-700']">
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

        <!-- File Details Modal -->
        <div v-if="showDetailsModal" class="fixed inset-0 z-50 overflow-y-auto">
            <div class="flex items-center justify-center min-h-screen px-4">
                <div class="fixed inset-0 bg-black opacity-50" @click="closeDetailsModal"></div>
                <div class="relative bg-white rounded-lg shadow-lg max-w-lg w-full p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">File Details</h3>
                    
                    <div v-if="selectedShare" class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">File Name</label>
                            <p class="text-sm text-gray-900">{{ selectedShare.file_name }}</p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Patient</label>
                            <p class="text-sm text-gray-900">{{ selectedShare.patient_name }}</p>
                            <p class="text-xs text-gray-500">{{ selectedShare.patient_email }}</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Purpose</label>
                            <p class="text-sm text-gray-900">{{ selectedShare.purpose }}</p>
                        </div>

                        <div v-if="selectedShare.description">
                            <label class="block text-sm font-medium text-gray-700">Description</label>
                            <p class="text-sm text-gray-900">{{ selectedShare.description }}</p>
                        </div>

                        <div v-if="selectedShare.notes">
                            <label class="block text-sm font-medium text-gray-700">Patient Notes</label>
                            <p class="text-sm text-gray-900 bg-gray-50 p-3 rounded">{{ selectedShare.notes }}</p>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">File Type</label>
                                <p class="text-sm text-gray-900">{{ selectedShare.file_type }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">File Size</label>
                                <p class="text-sm text-gray-900">{{ selectedShare.file_size }}</p>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Shared Date</label>
                                <p class="text-sm text-gray-900">{{ selectedShare.shared_at }}</p>
                            </div>
                            <div v-if="selectedShare.expires_at">
                                <label class="block text-sm font-medium text-gray-700">Expires</label>
                                <p class="text-sm text-gray-900">{{ selectedShare.expires_at }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-end space-x-3 mt-6">
                        <button @click="closeDetailsModal"
                            class="px-4 py-2 text-gray-700 bg-gray-200 rounded-md hover:bg-gray-300">
                            Close
                        </button>
                        <a v-if="selectedShare && !selectedShare.is_expired"
                            :href="`/patient/files/${selectedShare.file_id}/download`"
                            class="px-4 py-2 bg-teal-900 text-white rounded-md hover:bg-teal-800">
                            Download File
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- File Preview Modal -->
        <FilePreviewModal
            :show-modal="showPreviewModal"
            :file="selectedFile"
            @close="closePreviewModal"
        />
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { ref, computed, watch } from "vue";
import FilePreviewModal from "@/Components/FilePreviewModal.vue";

const props = defineProps({
    sharedFiles: Array
});

const searchQuery = ref('');
const filterFileType = ref('');
const filterDateRange = ref('');
const currentPage = ref(1);
const itemsPerPage = 10;
const showDetailsModal = ref(false);
const showPreviewModal = ref(false);
const selectedShare = ref(null);
const selectedFile = ref(null);

const filteredFiles = computed(() => {
    let filtered = [...props.sharedFiles];

    // Search filter
    if (searchQuery.value) {
        const query = searchQuery.value.toLowerCase();
        filtered = filtered.filter(file => 
            file.file_name.toLowerCase().includes(query) ||
            file.patient_name.toLowerCase().includes(query) ||
            file.purpose.toLowerCase().includes(query) ||
            (file.description && file.description.toLowerCase().includes(query))
        );
    }

    // File type filter
    if (filterFileType.value) {
        filtered = filtered.filter(file => file.file_type === filterFileType.value);
    }

    // Date range filter
    if (filterDateRange.value) {
        const now = new Date();
        const filterDate = new Date();
        
        switch (filterDateRange.value) {
            case 'today':
                filterDate.setHours(0, 0, 0, 0);
                break;
            case 'week':
                filterDate.setDate(now.getDate() - 7);
                break;
            case 'month':
                filterDate.setMonth(now.getMonth() - 1);
                break;
            case 'quarter':
                filterDate.setMonth(now.getMonth() - 3);
                break;
        }
        
        filtered = filtered.filter(file => new Date(file.shared_at) >= filterDate);
    }

    return filtered;
});

const totalPages = computed(() => Math.ceil(filteredFiles.value.length / itemsPerPage));

const paginatedFiles = computed(() => {
    const start = (currentPage.value - 1) * itemsPerPage;
    const end = start + itemsPerPage;
    return filteredFiles.value.slice(start, end);
});

const viewFileDetails = (share) => {
    selectedShare.value = share;
    showDetailsModal.value = true;
};

const closeDetailsModal = () => {
    showDetailsModal.value = false;
    selectedShare.value = null;
};

const previewFile = (share) => {
    if (!share) return;
    
    selectedFile.value = {
        id: share.file_id,
        original_file_name: share.file_name,
        file_type: share.file_type,
        file_size: share.file_size,
        purpose: share.purpose,
        description: share.description
    };
    showPreviewModal.value = true;
};

const closePreviewModal = () => {
    showPreviewModal.value = false;
    selectedFile.value = null;
};

const clearFilters = () => {
    searchQuery.value = '';
    filterFileType.value = '';
    filterDateRange.value = '';
    currentPage.value = 1;
};

// Reset page when filters change
const resetPage = () => {
    currentPage.value = 1;
};

// Watch for filter changes
watch([searchQuery, filterFileType, filterDateRange], () => {
    resetPage();
});
</script>

<style scoped>
/* Additional custom styles if needed */
</style> 