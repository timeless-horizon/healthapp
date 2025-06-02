<template>
    <table class="w-full bg-white border border-gray-300 shadow-md rounded-lg">
        <thead class="bg-gray-100">
        <tr class="text-left">
            <th class="py-2 px-1 border">S/N</th>
            <th class="py-2 px-4 border">Diagnosis</th>
            <th class="py-2 px-4 border">Medications</th>
            <th class="py-2 px-4 border">Results</th>
            <th class="py-2 px-4 border">Conducted On</th>
            <th class="py-2 px-4 border">Conducted by Dr.</th>
<!--            <th class="py-2 px-4 border">Month</th>-->
            <th class="py-2 px-4 border">View</th>
        </tr>
        </thead>
        <tbody>
        <tr v-for="(record, index) in medicalRecords" :key="record.id" class="hover:bg-gray-50">
            <td class="py-2 px-2 w-fit border">{{ index + 1 }}</td>
            <td class="py-2 px-2 border">{{ truncateText(record.diagnosis || 'N/A', 12) }}</td>
            <td class="py-2 px-2 border">{{ truncateText(record.medications || 'N/A', 12) }}</td>
            <td class="py-2 px-2 border">{{ truncateText(record.test_result, 12) }}</td>
            <td class="py-2 px-2 border">{{ record.conducted_on }}</td>
            <td class="py-2 px-2 border">{{ truncateText(record.doctor_name, 12) }}</td>
<!--            <td class="py-2 px-2 border">{{ // truncateText(record.month, 12) }}</td>-->
            <td class="py-2 px-2 truncate border text-blue-500">
                <Link :href="`/view-record/${record.user_id}/${record.id}`">View details</Link>
            </td>
        </tr>
        </tbody>
    </table>
</template>

<script setup>
import {Link} from "@inertiajs/vue3";
import {computed} from "vue";
defineProps({
    medicalRecords: Array
})


const truncateText = (text, length) => {
    if (!text) return "";
    return text.length > length ? text.substring(0, length - 3) + "..." : text;
};
</script>
