<template>
    <AuthenticatedLayout>
        <div class="flex">
            <div class="p-6 m-6 bg-white shadow-lg rounded-lg w-3/4">

                <div>
                    <p>Your replying to a compalin from <strong>{{ props.complained_user.username }}</strong> that says:
                    </p>
                    <h1> >>{{ props.complain.message }}</h1>
                </div>

                <h2 class="text-xl font-bold mb-4">Patient Complaints & Medications</h2>
                <table class="w-full border-collapse border border-gray-300">
                    <thead class="bg-gray-200">
                    <tr>
                        <th class="border p-2">S/N</th>
                        <th class="border p-2">Complains</th>
                        <th class="border p-2">Medication/Recommendation</th>
                        <th class="border p-2">Dosage</th>
                        <th class="border p-2">Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="(row, index) in rows" :key="index"
                        :class="row.color"
                        @dblclick="row.color = ''"
                    >
                        <td class="border p-2 text-center">{{ index + 1 }}</td>

                        <!-- Complaint Column -->
                        <td class="border p-2">
                            <input
                                title="Double tap to edit this field"
                                v-model="row.complain"
                                :readonly="!row.editable"
                                @blur="row.editable = false"
                                @dblclick="row.editable = true"

                                class="w-full p-1 transition-all"
                                :class="{
                'border-none bg-transparent focus:outline-none': !row.editable,
                'border rounded p-2': row.editable
              }"
                                type="text"
                                placeholder="Enter complaint"
                            />
                        </td>

                        <!-- Medication Column -->
                        <td class="border p-2">
                            <input
                                title="Double tap to edit this field"
                                v-model="row.medication"
                                :readonly="!row.editable"
                                @blur="row.editable = false"
                                @dblclick="row.editable = true"

                                class="w-full p-1 transition-all"
                                :class="{
                'border-none bg-transparent focus:outline-none': !row.editable,
                'border rounded p-2': row.editable
              }"
                                type="text"
                                placeholder="Enter medication"
                            />
                        </td>

                        <!-- Dosage Column -->
                        <td class="border p-2 ">
                            <input
                                title="Double tap to edit this field"
                                v-model="row.dosage"
                                :readonly="!row.editable"
                                @blur="row.editable = false"
                                @dblclick="row.editable = true"
                                class="w-full p-1 transition-all"
                                :class="{
                'border-none bg-transparent focus:outline-none': !row.editable,
                'border rounded p-2': row.editable
              }"
                                type="text"
                                placeholder="e.g. 2x3"
                            />
                        </td>

                        <!-- Action Buttons -->
                        <td class="border p-2 text-center flex">
                            <button
                                title="Edit row"
                                @click="row.editable = true; row.color = ''"
                                class="px-1 py-1 text-white rounded mr-2"
                            >
                                <svg
                                    width="30"
                                    height="30"
                                    viewBox="0 0 24 24"
                                    fill="none"
                                    xmlns="http://www.w3.org/2000/svg"
                                    stroke="blue"
                                    stroke-width="2"
                                    stroke-linecap="round"
                                    stroke-linejoin="round">
                                    <path d="M12 20h9"/>
                                    <path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4 12.5-12.5z"/>
                                </svg>

                            </button>
                            <button
                                title="Remove row"
                                @click="removeRow(index)"
                                class="px-1 py-1 text-white rounded"
                            >
                                <svg
                                    width="30"
                                    height="30"
                                    viewBox="0 0 24 24"
                                    fill="none"
                                    xmlns="http://www.w3.org/2000/svg"
                                    stroke="red"
                                    stroke-width="2"
                                    stroke-linecap="round"
                                    stroke-linejoin="round">
                                    <path d="M3 6h18"/>
                                    <path d="M8 6v-2a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/>
                                    <path d="M10 11v6"/>
                                    <path d="M14 11v6"/>
                                    <path d="M5 6l1 14a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2l1-14"/>
                                </svg>

                            </button>
                        </td>
                    </tr>
                    </tbody>

                </table>

                <div class="w-full flex items-center justify-between">

                    <!-- Floating Button to Add Row -->
                    <button
                        @click="addRow"
                        class=" bottom-6 right-6 bg-green-500 text-white px-4 py-2 rounded-full shadow-lg"
                    >
                        +
                    </button>

                    <button
                        @click="saveComplaints"
                        class="mt-4 bg-green-500 text-white px-4 py-2 rounded shadow-lg"
                    >
                        Save Complaints
                    </button>
                </div>
                <div class="my-12">
                    <div class="flex w-full flex-col bg-white shadow-lg rounded-lg border border-gray-200">
                        <h1 class="text-2xl font-semibold text-gray-800 mb-4 text-center">Patient's Medical Records</h1>
                        <MedicalRecords
                            :medicalRecords="props.medicalRecords"
                            :user="props.complained_user"
                            class=" bg-gray-50 rounded-lg shadow-md"
                        />
                    </div>
                </div>
            </div>
            <div class="flex flex-col">
                <MedicalHistory
                    :medicalHistory="props.medicalHistory"
                    :user="props.complained_user"
                />

            </div>

        </div>

    </AuthenticatedLayout>
</template>

<script setup>
import {ref} from "vue";
import axios from "axios";
import MedicalHistory from "@/Components/MedicalHistory.vue";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import MedicalRecords from "@/Components/MedicalRecords.vue";

let props = defineProps({
    complain: Object,
    complained_user: Object,
    medicalHistory: Object,
    medicalRecords: Array,
});

const rows = ref([
    {complain: "", medication: "", dosage: "", editable: true, color: ''},
]);

// Add a new row
const addRow = () => {
    rows.value.forEach(row => (row.editable = false)); // Lock previous rows
    rows.value.push({complain: "", medication: "", dosage: "", editable: true});
};

// Remove a row
const removeRow = (index) => {
    rows.value.splice(index, 1);
};


// Save complaints to the database
const saveComplaints = async () => {
    try {
        for (const row of rows.value) {
            if (row.complain === '' || row.medication === '' || row.dosage === '') {
                alert('Please check for the highlighted row(s) and fill appropriately')
                row.color = 'bg-red-300'
                return;
            }
        }
        const response = await axios.post("/doctor-reply/save-complaints", {
            complaints: rows.value,
            complaint_id: props.complain.id,
            user_id: props.complained_user.id
        });

        alert("Complaints saved successfully!");

        for (const row of rows.value) {
            row.complain = '';
            row.medication = '';
            row.dosage = '';
            removeRow(row.index)
        }

    } catch (error) {
        console.error("Error saving complaints:", error);
        alert("Failed to save complaints.");
    }
};
</script>

