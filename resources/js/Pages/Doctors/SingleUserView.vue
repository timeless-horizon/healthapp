<template>
    <AuthenticatedLayout>

    <div class="max-w-5xl mx-auto p-6 bg-white shadow-lg rounded-lg border border-gray-200">
        <!-- Header -->
        <h1 class="text-3xl font-bold text-blue-700 mb-6 text-center">Patient Profile</h1>

        <!-- User Info -->
        <div class="bg-blue-50 p-6 rounded-lg shadow-md mb-6">
            <h2 class="text-xl font-semibold text-blue-800 mb-4">Personal Information</h2>
            <div class="grid grid-cols-2 gap-4">
                <div><strong class="text-blue-900">Full Name:</strong> {{ user.surname }} {{ user.otherNames }}</div>
                <div><strong class="text-blue-900">Username:</strong> {{ user.username }}</div>
                <div><strong class="text-blue-900">Role:</strong> {{ user.user_role }}</div>
                <div><strong class="text-blue-900">Email:</strong> {{ user.email }}</div>
                <div><strong class="text-blue-900">Phone:</strong> {{ user.tel }}</div>
                <div><strong class="text-blue-900">Address:</strong> {{ user.address }}</div>
                <div><strong class="text-blue-900">Country:</strong> {{ user.country }}</div>
                <div><strong class="text-blue-900">State:</strong> {{ user.state }}</div>
                <div><strong class="text-blue-900">Preferred Language:</strong> {{ user.preferredLanguage }}</div>
                <div><strong class="text-blue-900">Terms Accepted:</strong> {{ user.termsAccepted ? 'Yes' : 'No' }}</div>
            </div>
        </div>

        <!-- Medical History -->
        <div class="bg-blue-100 p-6 rounded-lg shadow-md mb-6">
            <h2 class="text-xl font-semibold text-blue-800 mb-4">Medical History</h2>
            <div
                v-if="medicalHistory"
                class="grid grid-cols-2 gap-4">
                <div><strong class="text-blue-900">Condition:</strong> {{ medicalHistory.medical_condition }}</div>
                <div><strong class="text-blue-900">Medications:</strong> {{ medicalHistory.medications }}</div>
                <div><strong class="text-blue-900">Allergies:</strong> {{ medicalHistory.allergies }}</div>
                <div><strong class="text-blue-900">Family History:</strong> {{ medicalHistory.family_medical_history }}</div>
                <div><strong class="text-blue-900">Previous Surgeries:</strong> {{ medicalHistory.previous_surgeries_or_hospitalizations }}</div>
                <div><strong class="text-blue-900">Additional Notes:</strong> {{ medicalHistory.messege }}</div>
            </div>
        </div>

        <!-- Medical Records -->
        <div class="bg-blue-200 p-6 rounded-lg shadow-md">
            <h2 class="text-xl font-semibold text-blue-800 mb-4">Medical Records</h2>
            <div class="space-y-4">
                <div v-for="(record, index) in record" :key="index" class="p-4 bg-white rounded-lg shadow">
                    <h3 class="text-lg font-semibold text-blue-900 mb-2">Record {{ index + 1 }}</h3>
                    <div class="grid grid-cols-2 gap-4">
                        <div><strong class="text-blue-900">Diagnosed Doctor:</strong> {{ record.doctor_name }}</div>
                        <div><strong class="text-blue-900">Diagnosis:</strong> {{ record.diagnosis }}</div>
                        <div><strong class="text-blue-900">Result from Diagnose:</strong> {{ record.test_result }}</div>
                        <div><strong class="text-blue-900">Administered Medications:</strong> {{ record.medications }}</div>
                        <div><strong class="text-blue-900">Conducted On:</strong> {{ record.conducted_on }}</div>
                        <div><strong class="text-blue-900">Month:</strong> {{ record.month }}</div>
                    </div>
                    <div v-if="record.test_image" class="mt-4">
                        <strong class="text-blue-900">Test Image:</strong>
                        <img :src="record.test_image" alt="Test Image" class="mt-2 rounded-lg shadow-md w-full max-h-60 object-cover">
                    </div>
                </div>
            </div>
        </div>
    </div>
    </AuthenticatedLayout>
</template>

<script setup>
import AuthLinks from "@/Components/AuthLinks.vue";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";

defineProps({
    user: Object, // Receiving user details as props
    record: Array, // Receiving user details as props
    medicalHistory: Object, // Receiving user details as props
});
</script>
