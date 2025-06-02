<template>
    <AuthenticatedLayout>

    <div class="p-6 bg-white shadow-lg rounded-lg border border-gray-200 max-w-3xl mx-auto">
        <h2 class="text-2xl font-bold text-gray-800 mb-4">Medical Record</h2>

        <div class="grid grid-cols-1 gap-4">
            <div class="p-4 bg-gray-100 rounded-lg">
                <strong class="text-gray-700">Doctor:</strong>
                <p class="text-gray-900">{{ record[0].doctor_name }}</p>
            </div>

            <div class="p-4 bg-gray-100 rounded-lg">
                <strong class="text-gray-700">Diagnosis:</strong>
                <p class="text-gray-900">{{ record[0].diagnosis }}</p>
            </div>

            <div class="p-4 bg-gray-100 rounded-lg">
                <strong class="text-gray-700">Medications:</strong>
                <p class="text-gray-900">{{ record[0].medications }}</p>
            </div>

            <div class="p-4 bg-gray-100 rounded-lg">
                <strong class="text-gray-700">Test Result:</strong>
                <p class="text-gray-900">{{ record[0].test_result }}</p>
            </div>

            <div v-if="record[0].test_image" class="p-4 bg-gray-100 rounded-lg">
                <strong class="text-gray-700">Test Image:</strong>
                <img :src="record[0].test_image" alt="Test Image" class="mt-2 rounded-lg shadow-md w-full max-h-60 object-cover">
            </div>

            <div class="p-4 bg-gray-100 rounded-lg">
                <strong class="text-gray-700">Extra Notes:</strong>
                <p class="text-gray-900">{{ record[0].extra_notes }}</p>
            </div>

            <div class="p-4 bg-gray-100 rounded-lg">
                <strong class="text-gray-700">Conducted On:</strong>
                <p class="text-gray-900">{{ record[0].conducted_on }}</p>
            </div>

            <div class="p-4 bg-gray-100 rounded-lg">
                <strong class="text-gray-700">Month:</strong>
                <p class="text-gray-900">{{ record[0].month }}</p>
            </div>
        </div>
    </div>
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";

defineProps({
    record: Object, // Expecting a medical record object
});
</script>
