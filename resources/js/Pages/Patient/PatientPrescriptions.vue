<template>
    <AuthenticatedLayout>
        <div class="flex flex-col p-4">
            <h1 class="text-3xl font-semibold text-teal-900 mb-4" v-if="paginatedRecomendations.length">
                Below are the recommendations to your complain(s)
            </h1>
            <p v-if="paginatedRecomendations.length"><strong>{{ recomendations[0].complain.message }}</strong></p>
            <div class="relative overflow-x-auto">
                <table v-if="paginatedRecomendations.length" class="w-full text-sm text-gray-500 border">
                    <thead class="bg-gray-200">
                        <tr>
                            <th scope="col" class="px-4 py-3">S/N</th>
                            <th scope="col" class="px-4 py-3">Patient Complain</th>
                            <th scope="col" class="px-4 py-3">Medication For</th>
                            <th scope="col" class="px-4 py-3">Medication/Recommendation</th>
                            <th scope="col" class="px-4 py-3">Dosage</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(recomendation, index) in paginatedRecomendations" :key="recomendation.id"
                            :class="recomendation.color" class="odd:bg-white even:bg-gray-50">
                            <td scope="row" class="px-6 py-4 font-normal text-sm text-gray-900">
                                {{ (currentPage - 1) * itemsPerPage + index + 1 }}
                            </td>
                            <td scope="row" class="px-6 py-4 font-normal text-sm text-gray-900">
                                {{ recomendation.patient_complain?.message || 'N/A' }}
                            </td>
                            <td scope="row" class="px-6 py-4 font-normal text-sm text-gray-900">
                                {{ recomendation.complain || 'N/A' }}
                            </td>
                            <td scope="row" class="px-6 py-4 font-normal text-sm text-gray-900">
                                {{ recomendation.medication || 'N/A' }}
                            </td>
                            <td scope="row" class="px-6 py-4 font-normal text-sm text-gray-900">
                                {{ recomendation.dosage || 'N/A' }}
                            </td>
                        </tr>
                    </tbody>
                </table>
                <div v-else class="text-center py-4 bg-gray-50 border border-gray-300 rounded">
                    No prescriptions available
                </div>
                <!-- Pagination Controls -->
                <div v-if="sortedRecomendations.length" class="mt-4 flex justify-between items-center">
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
import { defineProps, ref, computed } from "vue";

const props = defineProps({
    recomendations: Object,
    complain: Object,
});

const currentPage = ref(1);
const itemsPerPage = 10;

const sortedRecomendations = computed(() => {
    return [...props.recomendations].sort((a, b) => {
        return new Date(b.created_at) - new Date(a.created_at);
    });
});

const totalPages = computed(() => {
    return Math.ceil(sortedRecomendations.value.length / itemsPerPage);
});

const paginatedRecomendations = computed(() => {
    const start = (currentPage.value - 1) * itemsPerPage;
    const end = start + itemsPerPage;
    return sortedRecomendations.value.slice(start, end);
});
</script>