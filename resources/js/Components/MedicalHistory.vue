<template>
    <div class="max-w-4xl mx-auto p-6 bg-white shadow-md rounded-lg">
        <h1 class="text-2xl font-bold mb-4">Patient's Medical History</h1>
        <div class="w-full bg-gray-300 h-px my-3"></div>

        <template v-if="props.medicalHistory">
            <div class="mb-4">
                <h2 class="text-lg font-semibold">Medical Condition</h2>
                <p class="text-gray-700">{{ props.medicalHistory.medical_condition ? 'Yes' : 'No' }}</p>
            </div>

            <div v-if="props.medicalHistory.medical_condition" class="mb-4">
                <h2 class="text-lg font-semibold">Medical Condition Details</h2>
                <p class="text-gray-700">{{ props.medicalHistory.messege || 'N/A' }}</p>
            </div>

            <div class="mb-4">
                <h2 class="text-lg font-semibold">On Medications</h2>
                <p class="text-gray-700">{{ props.medicalHistory.medications ? 'Yes' : 'No' }}</p>
            </div>

            <div v-if="props.medicalHistory.medications" class="mb-4">
                <h2 class="text-lg font-semibold">Medication Details</h2>
                <p class="text-gray-700">{{ props.medicalHistory.medications || 'N/A' }}</p>
            </div>

            <div class="mb-4">
                <h2 class="text-lg font-semibold">Allergies</h2>
                <p class="text-gray-700">{{ props.medicalHistory.is_allergic ? 'Yes' : 'No' }}</p>
            </div>

            <div v-if="props.medicalHistory.is_allergic" class="mb-4">
                <h2 class="text-lg font-semibold">Allergy Details</h2>
                <p class="text-gray-700">{{ props.medicalHistory.allergies || 'N/A' }}</p>
            </div>

            <div class="mb-4">
                <h2 class="text-lg font-semibold">Family Medical History</h2>
                <p class="text-gray-700">{{ props.medicalHistory.family_medical_history || 'N/A' }}</p>
            </div>

            <div class="mb-4">
                <h2 class="text-lg font-semibold">Previous Surgeries/Hospitalizations</h2>
                <p class="text-gray-700">{{ props.medicalHistory.previous_surgeries_or_hospitalizations || 'N/A' }}</p>
            </div>

            <div class="mb-4">
                <h2 class="text-lg font-semibold">Date & Time</h2>
                <p class="text-gray-700">{{ props.medicalHistory.clients_date_and_time || 'N/A' }}</p>
            </div>
        </template>

        <template v-else>
            <p class="text-gray-500 italic text-center">No medical history available.</p>
        </template>
    </div>

</template>

<script setup>
import { defineProps } from 'vue';

const props = defineProps({
    user: Object,
    medicalHistory: Object
});
</script>

<style scoped>
.text-gray-700 {
    color: #4a5568;
}
</style>
