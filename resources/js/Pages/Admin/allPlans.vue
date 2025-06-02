<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Link, router, usePage } from "@inertiajs/vue3";
import { watch } from "vue";
import { toast } from "vue3-toastify";
import "vue3-toastify/dist/index.css";

const props = defineProps({
    plans: Object,
});

const page = usePage();

const formatDate = (dateString) => {
    return new Date(dateString).toLocaleDateString();
};

const deletePlan = (planId) => {
    router.delete(`/plans/${planId}`, {
        onError: () => {
            toast.error("Failed to delete plan. Please try again.", {
                autoClose: 3000,
                position: "top-right",
            });
        },
        onSuccess: () => {
            toast.success('Plan deleted successfully');
        }
    });
};
</script>

<template>
    <AuthenticatedLayout>
        <!-- Debugging: Display flash props in the UI -->
        <div class="p-4 bg-gray-100 mb-4" v-if="page.props.flash">
            <pre>Flash: {{ JSON.stringify(page.props.flash, null, 2) }}</pre>
        </div>
        <div class="flex flex-col p-4">
            <div class="flex items-center justify-between mb-4">
                <h1 class="text-3xl font-semibold text-teal-900 mb-4">All Plans</h1>
                <button
                    class="px-6 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                    <Link href="/admin-add-plan">Create Plan</Link>
                </button>
            </div>
            <div class="mb-10">
                <div class="relative overflow-x-auto">
                    <table class="w-full text-sm text-left text-gray-500 border">
                        <thead class="text-sm text-gray-700 uppercase bg-gray-50 font-semibold">
                            <tr>
                                <th scope="col" class="px-6 py-3">Name</th>
                                <th scope="col" class="px-6 py-3">Description</th>
                                <th scope="col" class="px-6 py-3">Created</th>
                                <th scope="col" class="px-6 py-3">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(plan, index) in plans" :key="index" class="odd:bg-white even:bg-gray-50">
                                <td scope="row" class="px-4 py-4 font-normal text-sm text-gray-900">
                                    {{ plan.name }} Plan
                                </td>
                                <td scope="row" class="px-4 py-4 font-normal text-sm text-gray-900">
                                    {{ plan.description }}
                                </td>
                                <td scope="row" class="px-4 py-4 font-normal text-sm text-gray-900">
                                    {{ formatDate(plan.created_at) }}
                                </td>
                                <td scope="row" class="px-4 py-4 font-normal text-sm text-red-500 cursor-pointer"
                                    @click="deletePlan(plan.id)">
                                    Remove Plan
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <div v-if="!plans.length" class="text-center py-4 bg-white border border-gray-300 rounded">
                        No Plans available yet
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>