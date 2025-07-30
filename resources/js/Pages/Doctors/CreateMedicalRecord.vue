<template>
    <AuthenticatedLayout>
        <div class="max-w-4xl mx-auto p-6 bg-white shadow-md rounded-lg">
            <h1 class="text-2xl font-bold mb-4">Medical Records Management</h1>
            <form @submit.prevent="submitForm">
                <div class="mb-4">
                    <label for="patient-select" class="block text-sm font-medium text-gray-700">Select Patient:</label>
                    <select v-model="selectedPatientId" id="patient-select"
                        class="w-full border-gray-300 border rounded px-4 py-2 my-2">
                        <option value="" disabled>Choose a patient</option>
                        <option v-for="user in patients" :key="user.id" :value="user.id">
                            {{ user.surname }} {{ user.otherNames }}
                        </option>
                    </select>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Test conducted on:</label>
                    <input type="date" v-model="form.conducted_on"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-teal-500 focus:border-teal-500">
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Diagnosis</label>
                    <textarea v-model="form.diagnosis"
                        class="w-full h-24 px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-teal-500 focus:border-teal-500"
                        placeholder="Enter diagnosis"></textarea>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Medications</label>
                    <textarea v-model="form.medications"
                        class="w-full h-24 px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-teal-500 focus:border-teal-500"
                        placeholder="Enter prescribed medications"></textarea>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Test Results</label>
                    <textarea v-model="form.test_result"
                        class="w-full h-24 px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-teal-500 focus:border-teal-500"
                        placeholder="Enter test results"></textarea>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Additional Notes</label>
                    <textarea v-model="form.extra_notes"
                        class="w-full h-24 px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-teal-500 focus:border-teal-500"
                        placeholder="Enter any additional notes"></textarea>
                </div>
                <div class="flex justify-end">
                    <button type="submit"
                        class="px-4 py-2 bg-teal-500 text-white font-semibold rounded-lg shadow hover:bg-teal-600 transition"
                        :disabled="isSubmitting">
                        <span v-if="isSubmitting">Saving...</span>
                        <span v-else>Save Record</span>
                    </button>
                </div>
            </form>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import { computed, ref, watch } from "vue";
import axios from "axios";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
// import ResponseMessage from "@/Components/ResponseMessage.vue";
import { toast } from 'vue3-toastify';
import 'vue3-toastify/dist/index.css';

const form = ref({
    diagnosis: "",
    medications: "",
    test_result: "",
    extra_notes: "",
    conducted_on: ""
});

const isSubmitting = ref(false);

const resetForm = () => {
    form.value = {
        diagnosis: "",
        medications: "",
        test_result: "",
        extra_notes: "",
        conducted_on: "",
    };
};

let props = defineProps({
    users: Array,
});
const patients = computed(() => {
    return props.users.filter(user => user.user_role === 'patient');
});

let selectedPatientId = ref('');
let response = ref('');
let bg = ref('');

watch(selectedPatientId, (newVal) => {
    if (newVal) {
        const selectedPatient = patients.value.find(user => user.id === newVal);
        if (selectedPatient) {
            patient.value = selectedPatient;
        }
    }
});
const patient = ref(null);
const submitForm = () => {
    if (!patient.value) {
        toast.error('Please select a Patient')
        return;
    }
    isSubmitting.value = true;
    let data = {
        user_id: patient.value.id,
        ...form.value,
    }
    axios.post("/doctor-update-medical-records", data)
        .then(res => {
            if (res.status === 201) {
                toast.success(res.data.message)
                resetForm();
                selectedPatientId.value = '';
            }
        })
        .catch(error => {
            toast.error(error)
        })
        .finally(() => {
            isSubmitting.value = false;
        });
};
</script>