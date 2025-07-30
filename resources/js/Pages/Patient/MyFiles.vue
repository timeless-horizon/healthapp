<template>
    <AuthenticatedLayout>
        <div class="flex flex-col p-6">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-3xl font-semibold text-teal-900">My Files</h1>
                <button @click="showUploadModal = true"
                    class="bg-teal-900 text-white py-2 px-6 rounded-lg hover:bg-teal-800 focus:outline-none focus:ring-2 focus:ring-teal-600">
                    Upload New File
                </button>
            </div>

            <!-- Success/Error Messages -->
            <div v-if="$page.props.flash?.success" 
                class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                {{ $page.props.flash.success }}
            </div>
            
            <div v-if="$page.props.flash?.error" 
                class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                {{ $page.props.flash.error }}
            </div>

            <!-- Files Table -->
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <div v-if="files.length > 0" class="overflow-x-auto">
                    <table class="w-full text-sm text-left text-gray-500">
                        <thead class="text-sm text-gray-700 uppercase bg-gray-50 font-semibold">
                            <tr>
                                <th scope="col" class="px-6 py-3">File Name</th>
                                <th scope="col" class="px-6 py-3">Purpose</th>
                                <th scope="col" class="px-6 py-3">File Type</th>
                                <th scope="col" class="px-6 py-3">Size</th>
                                <th scope="col" class="px-6 py-3">Uploaded</th>
                                <th scope="col" class="px-6 py-3">Shared With</th>
                                <th scope="col" class="px-6 py-3">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="file in files" :key="file.id" 
                                class="bg-white border-b hover:bg-gray-50">
                                <td class="px-6 py-4 font-medium text-gray-900">
                                    <div>
                                        <div class="font-semibold">{{ file.original_file_name }}</div>
                                        <div v-if="file.description" class="text-sm text-gray-500 mt-1">
                                            {{ file.description }}
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        {{ file.purpose }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">{{ file.file_type }}</td>
                                <td class="px-6 py-4">{{ file.file_size }}</td>
                                <td class="px-6 py-4">{{ file.uploaded_at }}</td>
                                <td class="px-6 py-4">
                                    <div v-if="file.shares.length > 0" class="space-y-1">
                                        <div v-for="share in file.shares" :key="share.id"
                                            class="text-sm bg-gray-100 rounded px-2 py-1">
                                            <div class="font-medium">{{ share.doctor_name }}</div>
                                            <div class="text-xs text-gray-500">
                                                Shared {{ share.shared_at }}
                                                <span v-if="share.expires_at">(Expires: {{ share.expires_at }})</span>
                                            </div>
                                            <button @click="removeShare(share.id)"
                                                class="text-red-600 hover:text-red-800 text-xs mt-1">
                                                Remove Access
                                            </button>
                                        </div>
                                    </div>
                                    <span v-else class="text-gray-400 text-sm">Not shared</span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex flex-col space-y-2">
                                        <button @click="previewFile(file)"
                                            class="text-blue-600 hover:text-blue-800 text-sm text-left">
                                            Preview
                                        </button>
                                        <a :href="`/patient/files/${file.id}/download`"
                                            class="text-blue-600 hover:text-blue-800 text-sm">
                                            Download
                                        </a>
                                        <button @click="openShareModal(file)"
                                            class="text-teal-600 hover:text-teal-800 text-sm text-left">
                                            Share
                                        </button>
                                        <button @click="deleteFile(file.id)"
                                            class="text-red-600 hover:text-red-800 text-sm text-left">
                                            Delete
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div v-else class="text-center py-12">
                    <div class="text-gray-500 text-lg mb-4">No files uploaded yet</div>
                    <button @click="showUploadModal = true"
                        class="bg-teal-900 text-white py-2 px-6 rounded-lg hover:bg-teal-800">
                        Upload Your First File
                    </button>
                </div>
            </div>
        </div>

        <!-- Upload Modal -->
        <div v-if="showUploadModal" class="fixed inset-0 z-50 overflow-y-auto">
            <div class="flex items-center justify-center min-h-screen px-4">
                <div class="fixed inset-0 bg-black opacity-50" @click="closeUploadModal"></div>
                <div class="relative bg-white rounded-lg shadow-lg max-w-md w-full p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Upload New File</h3>
                    
                    <form @submit.prevent="uploadFile">
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Select File
                            </label>
                            <input type="file" ref="fileInput" @change="handleFileSelect"
                                accept=".pdf,.doc,.docx,.jpg,.jpeg,.png,.txt,.xls,.xlsx"
                                class="w-full p-2 border border-gray-300 rounded-md">
                            <p class="text-xs text-gray-500 mt-1">
                                Max 10MB. Supported: PDF, DOC, DOCX, JPG, PNG, TXT, XLS, XLSX
                            </p>
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Purpose <span class="text-red-500">*</span>
                            </label>
                            <input type="text" v-model="uploadForm.purpose" required
                                placeholder="e.g., Lab results, X-ray, Prescription"
                                class="w-full p-2 border border-gray-300 rounded-md">
                        </div>

                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Description (Optional)
                            </label>
                            <textarea v-model="uploadForm.description" rows="3"
                                placeholder="Additional details about this file..."
                                class="w-full p-2 border border-gray-300 rounded-md"></textarea>
                        </div>

                        <div class="flex justify-end space-x-3">
                            <button type="button" @click="closeUploadModal"
                                class="px-4 py-2 text-gray-700 bg-gray-200 rounded-md hover:bg-gray-300">
                                Cancel
                            </button>
                            <button type="submit" :disabled="!uploadForm.file || uploading"
                                class="px-4 py-2 bg-teal-900 text-white rounded-md hover:bg-teal-800 disabled:opacity-50">
                                {{ uploading ? 'Uploading...' : 'Upload' }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Share Modal -->
        <div v-if="showShareModal" class="fixed inset-0 z-50 overflow-y-auto">
            <div class="flex items-center justify-center min-h-screen px-4">
                <div class="fixed inset-0 bg-black opacity-50" @click="closeShareModal"></div>
                <div class="relative bg-white rounded-lg shadow-lg max-w-md w-full p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">
                        Share "{{ selectedFile?.original_file_name }}"
                    </h3>
                    
                    <form @submit.prevent="shareFile">
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Select Doctor <span class="text-red-500">*</span>
                            </label>
                            <select v-model="shareForm.doctor_id" required
                                class="w-full p-2 border border-gray-300 rounded-md">
                                <option value="">Choose a doctor...</option>
                                <option v-for="doctor in doctors" :key="doctor.id" :value="doctor.id">
                                    {{ doctor.name }} ({{ doctor.email }})
                                </option>
                            </select>
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Access Duration
                            </label>
                            <select v-model="shareForm.expires_in_days"
                                class="w-full p-2 border border-gray-300 rounded-md">
                                <option value="">No expiration</option>
                                <option value="7">1 week</option>
                                <option value="30">1 month</option>
                                <option value="90">3 months</option>
                                <option value="365">1 year</option>
                            </select>
                        </div>

                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Notes (Optional)
                            </label>
                            <textarea v-model="shareForm.notes" rows="3"
                                placeholder="Additional notes for the doctor..."
                                class="w-full p-2 border border-gray-300 rounded-md"></textarea>
                        </div>

                        <div class="flex justify-end space-x-3">
                            <button type="button" @click="closeShareModal"
                                class="px-4 py-2 text-gray-700 bg-gray-200 rounded-md hover:bg-gray-300">
                                Cancel
                            </button>
                            <button type="submit" :disabled="!shareForm.doctor_id || sharing"
                                class="px-4 py-2 bg-teal-900 text-white rounded-md hover:bg-teal-800 disabled:opacity-50">
                                {{ sharing ? 'Sharing...' : 'Share File' }}
                            </button>
                        </div>
                    </form>
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
import { ref, reactive, onMounted } from "vue";
import { router } from "@inertiajs/vue3";
import FilePreviewModal from "@/Components/FilePreviewModal.vue";

const props = defineProps({
    files: Array
});

const showUploadModal = ref(false);
const showShareModal = ref(false);
const showPreviewModal = ref(false);
const uploading = ref(false);
const sharing = ref(false);
const selectedFile = ref(null);
const doctors = ref([]);

const uploadForm = reactive({
    file: null,
    purpose: '',
    description: ''
});

const shareForm = reactive({
    doctor_id: '',
    notes: '',
    expires_in_days: ''
});

const handleFileSelect = (event) => {
    uploadForm.file = event.target.files[0];
};

const uploadFile = () => {
    if (!uploadForm.file) return;
    
    uploading.value = true;
    
    const formData = new FormData();
    formData.append('file', uploadForm.file);
    formData.append('purpose', uploadForm.purpose);
    formData.append('description', uploadForm.description);
    
    router.post('/patient/files', formData, {
        onSuccess: () => {
            closeUploadModal();
        },
        onFinish: () => {
            uploading.value = false;
        }
    });
};

const openShareModal = async (file) => {
    selectedFile.value = file;
    showShareModal.value = true;
    
    // Load doctors if not already loaded
    if (doctors.value.length === 0) {
        try {
            const response = await fetch('/api/patient/available-doctors');
            doctors.value = await response.json();
        } catch (error) {
            console.error('Failed to load doctors:', error);
        }
    }
};

const shareFile = () => {
    if (!shareForm.doctor_id || !selectedFile.value) return;
    
    sharing.value = true;
    
    router.post(`/patient/files/${selectedFile.value.id}/share`, shareForm, {
        onSuccess: () => {
            closeShareModal();
        },
        onFinish: () => {
            sharing.value = false;
        }
    });
};

const removeShare = (shareId) => {
    if (confirm('Are you sure you want to remove this file share?')) {
        router.delete(`/patient/file-shares/${shareId}`);
    }
};

const deleteFile = (fileId) => {
    if (confirm('Are you sure you want to delete this file? This action cannot be undone.')) {
        router.delete(`/patient/files/${fileId}`);
    }
};

const closeUploadModal = () => {
    showUploadModal.value = false;
    uploadForm.file = null;
    uploadForm.purpose = '';
    uploadForm.description = '';
    if ($refs.fileInput) {
        $refs.fileInput.value = '';
    }
};

const closeShareModal = () => {
    showShareModal.value = false;
    selectedFile.value = null;
    shareForm.doctor_id = '';
    shareForm.notes = '';
    shareForm.expires_in_days = '';
};

const previewFile = (file) => {
    selectedFile.value = file;
    showPreviewModal.value = true;
};

const closePreviewModal = () => {
    showPreviewModal.value = false;
    selectedFile.value = null;
};
</script>

<style scoped>
/* Additional custom styles if needed */
</style> 