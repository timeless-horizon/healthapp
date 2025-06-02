<template>
    <AuthenticatedLayout>
        <div class="flex flex-col p-4">
            <h1 class="text-3xl font-semibold text-teal-900 mb-4">Your Medical Records</h1>
            <div class="relative overflow-x-auto">
                <table v-if="paginatedRecords.length" class="w-full text-sm text-gray-500 border">
                    <thead class="text-sm text-gray-700 uppercase bg-gray-50 font-semibold">
                        <tr>
                            <th scope="col" class="px-6 py-3">S/N</th>
                            <th scope="col" class="px-6 py-3">Diagnosis</th>
                            <th scope="col" class="px-6 py-3">Medications</th>
                            <th scope="col" class="px-6 py-3">Test Result</th>
                            <th scope="col" class="px-6 py-3">Extra Notes</th>
                            <th scope="col" class="px-6 py-3">Conducted On</th>
                            <th scope="col" class="px-6 py-3">Conducted by Dr.</th>
                            <th scope="col" class="px-6 py-3">Month</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(record, index) in paginatedRecords" :key="record.id"
                            class="odd:bg-white even:bg-gray-50">
                            <td scope="row" class="px-4 py-4 font-normal text-sm text-gray-900">
                                {{ (currentPage - 1) * itemsPerPage + index + 1 }}
                            </td>
                            <td scope="row" class="px-4 py-4 font-normal text-sm text-gray-900">{{ record.diagnosis ||
                                'N/A' }}</td>
                            <td scope="row" class="px-4 py-4 font-normal text-sm text-gray-900">{{ record.medications ||
                                'N/A' }}</td>
                            <td scope="row" class="px-4 py-4 font-normal text-sm text-gray-900">{{ record.test_result ||
                                'N/A' }}</td>
                            <td scope="row" class="px-4 py-4 font-normal text-sm text-gray-900">{{ record.extra_notes ||
                                'N/A' }}</td>
                            <td scope="row" class="px-4 py-4 font-normal text-sm text-gray-900">{{ record.conducted_on
                                }}</td>
                            <td scope="row" class="px-4 py-4 font-normal text-sm text-gray-900">{{ record.doctor_name }}
                            </td>
                            <td scope="row" class="px-4 py-4 font-normal text-sm text-gray-900">{{ record.month }}</td>
                        </tr>
                    </tbody>
                </table>
                <div v-else class="text-center py-4 bg-gray-50 border border-gray-300 rounded">
                    No records available
                </div>
                <div v-if="sortedRecords.length" class="mt-4 flex justify-between items-center">
                    <button @click="currentPage = Math.max(1, currentPage - 1)" :disabled="currentPage === 1"
                        class="px-4 py-2 bg-teal-700 text-white rounded disabled:bg-gray-300">
                        Previous
                    </button>
                    <div class="flex space-x-2">
                        <button v-for="page in totalPages" :key="page" @click="currentPage = page"
                            :class="['px-3 py-1 rounded-full text-sm', currentPage === page ? 'bg-teal-700 text-white' : 'bg-gray-200 text-teal-700']">
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
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { ref, computed } from 'vue';

const props = defineProps({
    records: Array,
});

const currentPage = ref(1);
const itemsPerPage = 10;

const sortedRecords = computed(() => {
    return [...props.records].sort((a, b) => {
        return new Date(b.conducted_on) - new Date(a.conducted_on);
    });
});
const totalPages = computed(() => {
    return Math.ceil(sortedRecords.value.length / itemsPerPage);
});

const paginatedRecords = computed(() => {
    const start = (currentPage.value - 1) * itemsPerPage;
    const end = start + itemsPerPage;
    return sortedRecords.value.slice(start, end);
});
</script>

<style scoped>
th,
td {
    text-align: left;
}
</style>