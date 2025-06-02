<template>
    <AuthenticatedLayout>
        <div class="max-w-4xl mx-auto p-10 bg-gray-50 shadow-md rounded-lg">
            <h1 class="text-2xl font-bold mb-4">Your Medical History</h1>
            <ResponseMessage :class="bg" :response="response" />
            <form v-if="existingHistory" @submit.prevent="updateForm">
                <div class="flex items-center justify-end">

                    <button type="button" @click="toggleEditing"
                        class="py-2 px-4 bg-blue-500 text-white rounded-md hover:bg-blue-600">
                        {{ isEditing ? 'Cancel Edit' : 'Update Data' }}
                    </button>
                </div>
                <!-- Medical Conditions -->
                <div class="flex items-center mt-4">
                    <label for="isMedicalConditions" class="block text-sm font-medium text-gray-700">Do you have any
                        medical conditions?</label>
                    <!--                    <input type="checkbox" id="isMedicalConditions" v-model="isMedicalConditions" :disabled="!isEditing" class="ml-2" />-->
                </div>
                <div>
                    <textarea v-model="existingHistory.medical_condition" :readonly="!isEditing"
                        class="mt-1 block w-full p-2 border border-gray-300 rounded-md"
                        placeholder="Enter medical conditions"></textarea>
                </div>
                <!-- Medications -->
                <div class="flex items-center mt-4">
                    <label for="onMedication" class="block text-sm font-medium text-gray-700">Are you on any
                        medications?</label>
                    <!--                    <input type="checkbox" id="onMedication" v-model="onMedication" :disabled="!isEditing" class="ml-2" />-->
                </div>
                <div>
                    <textarea v-model="existingHistory.medications" :readonly="!isEditing"
                        class="mt-1 block w-full p-2 border border-gray-300 rounded-md"
                        placeholder="Enter medications"></textarea>
                </div>

                <!-- Allergies -->
                <div class="flex items-center mt-4">
                    <label for="isAllergic" class="block text-sm font-medium text-gray-700">Are you allergic to
                        anything?</label>
                    <!--                    <input type="checkbox" id="isAllergic" v-model="isAllergic" :disabled="!isEditing" class="ml-2" />-->
                </div>
                <div>
                    <textarea v-model="existingHistory.allergies" :readonly="!isEditing"
                        class="mt-1 block w-full p-2 border border-gray-300 rounded-md"
                        placeholder="Describe your allergies"></textarea>
                </div>


                <!-- Previous Surgeries -->
                <div class="flex items-center mt-4">
                    <label for="onPreviousSurgeries" class="block text-sm font-medium text-gray-700">Have you had any
                        previous surgeries?</label>
                    <!--                    <input type="checkbox" id="onPreviousSurgeries" v-model="onPreviousSurgeries" :disabled="!isEditing" class="ml-2" />-->
                </div>
                <div>
                    <textarea v-model="existingHistory.previous_surgeries_or_hospitalizations" :readonly="!isEditing"
                        class="mt-1 block w-full p-2 border border-gray-300 rounded-md"
                        placeholder="Tell us when and why"></textarea>
                </div>

                <!-- Family Medical History -->
                <div class="flex items-center mt-4">
                    <label for="familyMedicalHistory" class="block text-sm font-medium text-gray-700">Family Medical
                        History</label>
                </div>
                <textarea v-model="existingHistory.family_medical_history" :readonly="!isEditing"
                    class="mt-1 block w-full p-2 border border-gray-300 rounded-md"
                    placeholder="Enter family medical history"></textarea>

                <!-- Message -->
                <div class="flex items-center justify-between mt-4">
                    <label for="message" class="block text-sm font-medium text-gray-700">Extra message</label>
                </div>
                <textarea v-model="existingHistory.messege" :readonly="!isEditing"
                    class="mt-1 block w-full p-2 border border-gray-300 rounded-md"
                    placeholder="Enter your message"></textarea>


                <!-- Update Data Button -->
                <div class="flex justify-between mt-4">
                    <button type="submit" v-show="isEditing"
                        class="py-2 px-4 bg-green-500 text-white rounded-md hover:bg-green-600">Submit</button>
                    <button></button>
                    <button type="button" @click="toggleEditing"
                        class="py-2 px-4 bg-blue-500 text-white rounded-md hover:bg-blue-600">
                        {{ isEditing ? 'Cancel Edit' : 'Update Data' }}
                    </button>
                </div>
            </form>

            <form v-else @submit.prevent="submitForm">
                <div class="flex items-center justify-end">

                    <button type="button" @click="toggleEditing"
                        class="py-2 px-4 bg-blue-500 text-white rounded-md hover:bg-blue-600">
                        {{ isEditing ? 'Cancel Edit' : 'Update Data' }}
                    </button>
                </div>
                <!-- Medical Conditions -->
                <div class="flex items-center mt-4">
                    <label for="isMedicalConditions" class="block text-sm font-medium text-gray-700">Do you have any
                        medical conditions?</label>
                    <input type="checkbox" id="isMedicalConditions" v-model="isMedicalConditions" :disabled="!isEditing"
                        class="ml-2" />
                </div>
                <div v-if="isMedicalConditions">
                    <textarea v-model="medicalConditions" :readonly="!isEditing"
                        class="mt-1 block w-full p-2 border border-gray-300 rounded-md"
                        placeholder="Enter medical conditions"></textarea>
                </div>
                <!-- Medications -->
                <div class="flex items-center mt-4">
                    <label for="onMedication" class="block text-sm font-medium text-gray-700">Are you on any
                        medications?</label>
                    <input type="checkbox" id="onMedication" v-model="onMedication" :disabled="!isEditing"
                        class="ml-2" />
                </div>
                <div v-if="onMedication">
                    <textarea v-model="medications" :readonly="!isEditing"
                        class="mt-1 block w-full p-2 border border-gray-300 rounded-md"
                        placeholder="Enter medications"></textarea>
                </div>

                <!-- Allergies -->
                <div class="flex items-center mt-4">
                    <label for="isAllergic" class="block text-sm font-medium text-gray-700">Are you allergic to
                        anything?</label>
                    <input type="checkbox" id="isAllergic" v-model="isAllergic" :disabled="!isEditing" class="ml-2" />
                </div>
                <div v-if="isAllergic">
                    <textarea v-model="allergies" :readonly="!isEditing"
                        class="mt-1 block w-full p-2 border border-gray-300 rounded-md"
                        placeholder="Describe your allergies"></textarea>
                </div>


                <!-- Previous Surgeries -->
                <div class="flex items-center mt-4">
                    <label for="onPreviousSurgeries" class="block text-sm font-medium text-gray-700">Have you had any
                        previous surgeries?</label>
                    <input type="checkbox" id="onPreviousSurgeries" v-model="onPreviousSurgeries" :disabled="!isEditing"
                        class="ml-2" />
                </div>
                <div v-if="onPreviousSurgeries">
                    <textarea v-model="previousSurgeries" :readonly="!isEditing"
                        class="mt-1 block w-full p-2 border border-gray-300 rounded-md"
                        placeholder="Tell us when and why"></textarea>
                </div>

                <!-- Family Medical History -->
                <div class="flex items-center mt-4">
                    <label for="familyMedicalHistory" class="block text-sm font-medium text-gray-700">Family Medical
                        History</label>
                </div>
                <textarea v-model="familyMedicalHistory" :readonly="!isEditing"
                    class="mt-1 block w-full p-2 border border-gray-300 rounded-md"
                    placeholder="Enter family medical history"></textarea>

                <!-- Message -->
                <div class="flex items-center justify-between mt-4">
                    <label for="message" class="block text-sm font-medium text-gray-700">Extra message</label>
                </div>
                <textarea v-model="message" :readonly="!isEditing"
                    class="mt-1 block w-full p-2 border border-gray-300 rounded-md"
                    placeholder="Enter your message"></textarea>


                <!-- Update Data Button -->
                <div class="flex justify-between mt-4">
                    <button type="submit" v-show="isEditing"
                        class="py-2 px-4 bg-green-500 text-white rounded-md hover:bg-green-600">Submit</button>
                    <button></button>
                    <button type="button" @click="toggleEditing"
                        class="py-2 px-4 bg-blue-500 text-white rounded-md hover:bg-blue-600">
                        {{ isEditing ? 'Cancel Edit' : 'Update Data' }}
                    </button>
                </div>
            </form>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import { ref } from 'vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import ResponseMessage from '@/Components/ResponseMessage.vue';
import axios from 'axios';


let props = defineProps({
    existingHistory: Object,
})

// Reactive variables
const message = ref('');
const onMedication = ref(false);
const medications = ref('');
const isAllergic = ref(false);
const allergies = ref('');
const familyMedicalHistory = ref('');
const onPreviousSurgeries = ref(false);
const previousSurgeries = ref('');
const isMedicalConditions = ref(false);
const medicalConditions = ref('');
const isEditing = ref(false);
const response = ref('');
const bg = ref('');

// Toggle editing state
function toggleEditing() {
    isEditing.value = !isEditing.value;
}

// Submit form data
async function updateForm() {
    const formData = {
        message: message.value,
        medications: onMedication.value ? medications.value : '',
        allergies: isAllergic.value ? allergies.value : '',
        familyMedicalHistory: familyMedicalHistory.value,
        previousSurgeries: onPreviousSurgeries.value ? previousSurgeries.value : '',
        medicalConditions: isMedicalConditions.value ? medicalConditions.value : '',
    };
    try {
        const res = await axios.post('/patient-medical-records', formData);
        if (res.status === 200) {
            response.value = res.data.message;
            bg.value = 'bg-green-500';
            setTimeout(() => {
                response.value = '';
                bg.value = '';
            }, 3000);
        }
    } catch (error) {
        response.value = 'Error updating your medical history, please try again';
        bg.value = 'bg-red-500';
        setTimeout(() => {
            response.value = '';
            bg.value = '';
        }, 3000);
    }
}



async function submitForm() {
    const formData = {
        message: props.existingHistory.messege,
        medications: props.existingHistory.medications,
        allergies: props.existingHistory.allergies,
        familyMedicalHistory: props.existingHistory.family_medical_history,
        previousSurgeries: props.existingHistory.previous_surgeries_or_hospitalizations,
        medicalConditions: props.existingHistory.medical_condition,
    }

    try {
        const res = await axios.post('/patient-medical-records', formData);
        if (res.status === 200) {
            response.value = res.data.message;
            bg.value = 'bg-green-500';
            setTimeout(() => {
                response.value = '';
                bg.value = '';
            }, 3000);
        }
    } catch (error) {
        response.value = 'Error updating your medical history, please try again';
        bg.value = 'bg-red-500';
        setTimeout(() => {
            response.value = '';
            bg.value = '';
        }, 3000);
    }
}
</script>

<style scoped>
/* Additional styles if needed */
</style>
