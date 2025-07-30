<template>
    <div v-if="showModal" class="fixed inset-0 z-50 overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen px-4">
            <!-- Modal Backdrop -->
            <div class="fixed inset-0 bg-black opacity-50" @click="closeModal"></div>

            <!-- Modal Content -->
            <div class="relative bg-white rounded-lg shadow-lg max-w-5xl w-full">
                <!-- Modal Header -->
                <div class="flex justify-between items-center p-4 border-b">
                    <h3 class="text-xl font-semibold text-gray-900">
                        {{ file?.original_file_name || 'File Preview' }}
                    </h3>
                    <button @click="closeModal" class="text-gray-500 hover:text-gray-700">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <!-- Modal Body -->
                <div class="p-6">
                    <!-- File Preview -->
                    <div class="mb-4">
                        <!-- PDF Preview -->
                        <div v-if="isPDF" class="h-[600px] w-full">
                            <iframe :src="previewUrl" class="w-full h-full border rounded"></iframe>
                        </div>

                        <!-- Image Preview -->
                        <div v-else-if="isImage" class="flex justify-center">
                            <img :src="previewUrl" :alt="file.original_file_name" class="max-h-[600px] object-contain">
                        </div>

                        <!-- Text Preview -->
                        <div v-else-if="isText" class="h-[600px] overflow-y-auto bg-gray-50 p-4 rounded font-mono">
                            <pre>{{ textContent }}</pre>
                        </div>

                        <!-- Unsupported Format -->
                        <div v-else class="text-center py-8">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            <p class="mt-2 text-gray-600">Preview not available for this file type</p>
                        </div>
                    </div>

                    <!-- File Info -->
                    <div class="mt-4 bg-gray-50 p-4 rounded">
                        <div v-if="file" class="grid grid-cols-2 gap-4">
                            <div>
                                <p class="text-sm text-gray-600">File Type</p>
                                <p class="font-medium">{{ file.file_type }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Size</p>
                                <p class="font-medium">{{ file.file_size }}</p>
                            </div>
                        </div>
                        <div v-if="file" class="mt-3">
                            <p class="text-sm text-gray-600">Purpose</p>
                            <p class="font-medium">{{ file.purpose }}</p>
                        </div>
                        <div v-if="file?.description" class="mt-3">
                            <p class="text-sm text-gray-600">Description</p>
                            <p class="font-medium">{{ file.description }}</p>
                        </div>
                    </div>
                </div>

                <!-- Modal Footer -->
                <div class="flex justify-end space-x-3 p-4 border-t">
                    <button @click="closeModal"
                        class="px-4 py-2 text-gray-700 bg-gray-200 rounded-md hover:bg-gray-300">
                        Close
                    </button>
                    <a :href="downloadUrl"
                        class="px-4 py-2 bg-teal-900 text-white rounded-md hover:bg-teal-800">
                        Download
                    </a>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';

const props = defineProps({
    showModal: {
        type: Boolean,
        required: true
    },
    file: {
        type: Object,
        required: true
    }
});

const emit = defineEmits(['close']);
const textContent = ref('');

const closeModal = () => {
    emit('close');
};

const isPDF = computed(() => {
    return props.file?.file_type === 'application/pdf';
});

const isImage = computed(() => {
    return props.file?.file_type?.startsWith('image/') || false;
});

const isText = computed(() => {
    if (!props.file) return false;
    
    return props.file.file_type === 'text/plain' || 
           props.file.file_type === 'text/csv' ||
           props.file.original_file_name?.endsWith('.txt') ||
           props.file.original_file_name?.endsWith('.csv') ||
           false;
});

const previewUrl = computed(() => {
    if (!props.file) return null;
    if (!isPDF.value && !isImage.value) return null;
    
    return `/patient/files/${props.file.id}/preview`;
});

const downloadUrl = computed(() => {
    if (!props.file) return '#';
    return `/patient/files/${props.file.id}/download`;
});

// Load text content if it's a text file
onMounted(async () => {
    if (isText.value) {
        try {
            const response = await fetch(`/patient/files/${props.file.id}/content`);
            if (response.ok) {
                textContent.value = await response.text();
            }
        } catch (error) {
            console.error('Error loading text content:', error);
            textContent.value = 'Error loading file content';
        }
    }
});
</script> 