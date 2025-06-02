<template>
    <AuthenticatedLayout>
        <div
            class="max-w-xl mx-auto bg-white bg-opacity-90 shadow-2xl rounded-3xl p-8 border border-blue-300 backdrop-blur-md">
            <div class="bg-gradient-to-r from-blue-600 to-blue-400 text-black rounded-2xl p-4 text-center shadow-md">
                <h2 class="text-2xl font-bold">Your Active Plan</h2>
            </div>

            <!-- Plan Details -->
            <div class="mt-6 space-y-4">
                <p class="text-gray-800 text-lg">
                    <span class="font-semibold text-blue-600">Plan Name:</span> {{ plan_details.plan.name }}
                </p>
                <!-- <p class="text-gray-700">
                    <span class="font-medium text-blue-500">Description:</span> {{ plan_details.plan.description }}
                </p> -->
            </div>

            <!-- Plan Information Grid -->
            <div class="grid grid-cols-2 gap-6 mt-6">
                <div class="p-4 bg-blue-50 rounded-xl shadow-sm">
                    <p class="text-gray-800 font-medium">Renewed On</p>
                    <p class="text-gray-600">{{ formatDate(plan_details.start_date) }}</p>
                </div>
                <div class="p-4 bg-blue-50 rounded-xl shadow-sm">
                    <p class="text-gray-800 font-medium">Expires On</p>
                    <p class="text-gray-600">{{ formatDate(plan_details.end_date) }}</p>
                </div>
                <div class="p-4 bg-blue-50 rounded-xl shadow-sm">
                    <p class="text-gray-800 font-medium">Plan Duration</p>
                    <p class="text-gray-600">{{ plan_details.duration }} months</p>
                </div>
                <div class="p-4 bg-blue-50 rounded-xl shadow-sm flex items-center justify-between">
                    <p class="text-gray-800 font-medium">Status</p>
                    <span :class="statusClass">{{ planStatus }}</span>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { computed } from "vue";

const props = defineProps({
    plan_details: Object
});

// Format date for better readability
const formatDate = (date) => new Date(date).toLocaleDateString();
const formatDateTime = (datetime) => new Date(datetime).toLocaleString();

// Compute status badge class
const planStatus = computed(() => props.plan_details.status === 1 ? "Active" : "Inactive");
const statusClass = computed(() =>
    props.plan_details.status === 1
        ? "bg-green-500 text-white px-3 py-1 rounded-full text-sm shadow-md"
        : "bg-red-500 text-white px-3 py-1 rounded-full text-sm shadow-md"
);
</script>
