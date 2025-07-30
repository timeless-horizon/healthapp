<template>
    <AuthenticatedLayout>
        <div class="flex flex-col p-4">
            <h1 class="text-3xl font-semibold text-teal-900 mb-4">All Users</h1>
            <div class="relative overflow-x-auto">
                <table v-if="paginatedUsers.length" class="w-full text-sm text-left text-gray-500 border">
                    <thead class="text-sm text-gray-700 uppercase bg-gray-50 font-semibold">
                        <tr>
                            <th scope="col" class="px-6 py-3">S/N</th>
                            <th scope="col" class="px-6 py-3">Surname</th>
                            <th scope="col" class="px-6 py-3">Other Names</th>
                            <th scope="col" class="px-6 py-3">Tel</th>
                            <th scope="col" class="px-6 py-3">Email</th>
                            <th scope="col" class="px-6 py-3">Username</th>
                            <th scope="col" class="px-6 py-3">Country, State</th>
                            <th scope="col" class="px-6 py-3">Created At</th>
                            <th scope="col" class="px-6 py-3">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(user, index) in paginatedUsers" :key="index" class="odd:bg-white even:bg-gray-50">
                            <td class="px-4 py-4 font-bold"> {{ (currentPage - 1) * itemsPerPage + index + 1 }}</td>
                            <td scope="row" class="px-6 py-4 font-normal text-sm text-gray-900 max-w-[350px]">{{
                                user.surname }}</td>
                            <td scope="row" class="px-6 py-4 font-normal text-sm text-gray-900 max-w-[350px]">{{
                                user.otherNames }}</td>
                            <td scope="row" class="px-6 py-4 font-normal text-sm text-gray-900 max-w-[350px]">{{
                                user.tel }}</td>
                            <td scope="row" class="px-6 py-4 font-normal text-sm text-gray-900 max-w-[350px]">{{
                                user.email }}</td>
                            <td scope="row" class="px-6 py-4 font-normal text-sm text-gray-900 max-w-[350px]">{{
                                user.username }}</td>
                            <td scope="row" class="px-6 py-4 font-normal text-sm text-gray-900 max-w-[350px]">{{
                                user.country }}, {{ user.state }}</td>
                            <td scope="row" class="px-6 py-4 font-normal text-sm text-gray-900 max-w-[350px]">{{ new
                                Date(user.created_at).toLocaleDateString() }}</td>
                            <td scope="row" class="px-6 py-4 font-normal text-sm text-gray-900 max-w-[350px]">
                                <Link :href="`view-user/${user.id}`">View details</Link>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <div v-else class="text-center py-4 bg-gray-50 border border-gray-300 rounded">
                    No users found
                </div>
                <!-- Pagination Controls -->
                <div v-if="users.length" class="mt-4 flex justify-between items-center">
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
import { defineProps, ref, computed } from "vue";
import { Link } from "@inertiajs/vue3";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";

const props = defineProps({
    users: Array,
});

const currentPage = ref(1);
const itemsPerPage = 10;

const totalPages = computed(() => {
    return Math.ceil(props.users.length / itemsPerPage);
});

const paginatedUsers = computed(() => {
    const start = (currentPage.value - 1) * itemsPerPage;
    const end = start + itemsPerPage;
    return props.users.slice(start, end);
});
</script>