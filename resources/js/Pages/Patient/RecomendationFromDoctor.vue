<template>
    <AuthenticatedLayout>

<p>Below are the recommendations to your complain that reads:</p>
        <p><strong>{{ complain[0].message }}</strong></p>
    <table class="w-full border-collapse border border-gray-300">

        <thead class="bg-gray-200">
        <tr>
            <th class="border p-2">S/N</th>
            <th class="border p-2">Complains</th>
            <th class="border p-2">Medication/Recommendation</th>
            <th class="border p-2">Dosage</th>
        </tr>
        </thead>
        <tbody>
        <tr v-for="(recomendation, index) in recomendations" :key="index" :class="recomendation.color">
            <td class="border p-2 text-center">{{ index + 1 }}</td>

            <!-- Complaint Column -->
            <td class="border p-2">
                <p class="w-full p-1">{{ recomendation.complain || 'N/A' }}</p>
            </td>

            <!-- Medication Column -->
            <td class="border p-2">
                <p class="w-full p-1">{{ recomendation.medication || 'N/A' }}</p>
            </td>

            <!-- Dosage Column -->
            <td class="border p-2">
                <p class="w-full p-1">{{ recomendation.dosage || 'N/A' }}</p>
            </td>
        </tr>
        </tbody>
    </table>
    </AuthenticatedLayout>

</template>

<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";


let props = defineProps({
    recomendations: Object,
    complain: Object,
    }
)
</script>
