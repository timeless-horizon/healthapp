<template>
    <AuthenticatedLayout>
        <div class="flex flex-col p-4">
            <h1 class="text-3xl font-semibold text-teal-900 mb-4">
                Your Complains/Messages
            </h1>
            <div class="flex justify-end my-4">
                <Link href="/patient-messages-create" class="text-white">
                <button
                    class="bg-gray-900 text-white py-2 px-6 rounded-lg hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-teal-600">
                    Lay a Complain
                </button>
                </Link>
            </div>
            <div class="relative overflow-x-auto">
                <table v-if="paginatedComplains.length" class="w-full text-left text-sm text-gray-500 border">
                    <thead class="text-sm text-gray-700 uppercase bg-gray-50 font-semibold">
                        <tr>
                            <th scope="col" class="px-4 py-3">S/N</th>
                            <th scope="col" class="px-4 py-3">Subject</th>
                            <th scope="col" class="px-4 py-3">Message</th>
                            <th scope="col" class="px-4 py-3">Department</th>
                            <th scope="col" class="px-4 py-3">Status</th>
                            <th scope="col" class="px-4 py-3">Responded By</th>
                            <th scope="col" class="px-4 py-3">Date/Time</th>
                            <th scope="col" class="px-4 py-3">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(complain, index) in paginatedComplains" :key="complain.id"
                            class="odd:bg-white even:bg-gray-50">
                            <td scope="row" class="px-4 py-4 font-normal text-sm text-gray-900">
                                {{ (currentPage - 1) * itemsPerPage + index + 1 }}
                            </td>
                            <td scope="row" class="px-4 py-4 font-normal text-sm text-gray-900">{{ complain.subject }}
                            </td>
                            <td scope="row" class="px-4 py-4 font-normal text-sm text-gray-900">{{ complain.message }}
                            </td>
                            <td scope="row" class="px-4 py-4 font-normal text-sm text-gray-900">
                                {{ complain.department ? complain.department.name : 'N/A' }}
                            </td>
                            <td scope="row" class="px-4 py-4 font-normal text-sm text-gray-900">{{ complain.status }}
                            </td>
                            <td scope="row" class="px-4 py-4 font-normal text-sm text-gray-900">
                                {{ complain.responded_by ? complain.responded_by.name : 'Not Responded' }}
                            </td>
                            <td scope="row" class="px-4 py-4 font-normal text-sm text-gray-900">
                                {{ new Date(complain.created_at).toLocaleString() }}
                            </td>
                            <td scope="row" class="px-4 py-4 font-normal text-sm text-gray-900 flex space-x-2">
                                <Link :href="`/patient-view-reply/${$page.props.auth.user.id}/${complain.id}`"
                                    class="text-blue-500 hover:text-blue-700 border-b-2">
                                {{ complain.responded_by ? 'View Reply' : 'View Reply' }}
                                </Link>
                                <Link :href="{ name: 'complains.delete', params: { id: complain.id } }"
                                    class="text-red-500 hover:text-red-700 border-b-2">
                                Delete
                                </Link>
                                <Link :href="{ name: 'complains.edit', params: { id: complain.id } }"
                                    class="text-green-500 hover:text-green-700 border-b-2">
                                Edit
                                </Link>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <div v-else class="text-center py-4 bg-gray-50 border border-gray-300 rounded">
                    No complaints available
                </div>
                <div v-if="sortedComplains.length" class="mt-4 flex justify-between items-center">
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
import { defineProps, ref, computed } from 'vue';
import { Link } from "@inertiajs/vue3";

const props = defineProps({
    complains: Object
});

const currentPage = ref(1);
const itemsPerPage = 10;

const sortedComplains = computed(() => {
    return [...props.complains].sort((a, b) => {
        return new Date(b.created_at) - new Date(a.created_at);
    });
});

const totalPages = computed(() => {
    return Math.ceil(sortedComplains.value.length / itemsPerPage);
});

const paginatedComplains = computed(() => {
    const start = (currentPage.value - 1) * itemsPerPage;
    const end = start + itemsPerPage;
    return sortedComplains.value.slice(start, end);
});
</script>

<style scoped>
/* Optional styles */
</style>