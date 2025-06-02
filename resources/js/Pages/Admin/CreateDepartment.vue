<template>
    <AuthenticatedLayout>
        <div class="max-w-4xl mx-auto px-6 space-y-6">
            <!-- Create Department Section -->
            <div class="bg-white shadow-md rounded-lg p-6">
                <p class="text-xl font-semibold text-gray-800">Create Department</p>
                <form class="mt-4" @submit.prevent="submit">
                    <div class="flex items-center space-x-2">
                        <input type="text" placeholder="Search or create department" v-model="form.department"
                            class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" />
                        <button type="submit"
                            class="px-6 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            Create
                        </button>
                    </div>
                </form>
            </div>
            <!-- Available Departments Section -->
            <div class="bg-white shadow-md rounded-lg p-6">
                <h2 class="text-xl font-semibold text-gray-800">Available Departments</h2>
                <ul class="mt-4 space-y-2" v-for="(department, index) in departments" :key="index">
                    <li
                        class="bg-gray-100 hover:bg-gray-200 flex items-center justify-between px-4 py-2 rounded-md cursor-pointer">
                        <span>{{ index + 1 }}. {{ department.name }}</span>
                        <span class="font-normal text-sm text-red-500 cursor-pointer"
                            @click="deleteDepartment(department.id)">
                            Delete
                        </span>
                    </li>
                </ul>
                <div v-if="!departments.length" class="text-center py-4 bg-gray-50 border border-gray-300 rounded">
                    No departments available
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import axios from "axios";
import { Link, router, useForm } from "@inertiajs/vue3";
import { ref } from "vue";
import { toast } from "vue3-toastify";
import "vue3-toastify/dist/index.css";

const form = useForm({
    department: "",
});

const props = defineProps({
    departments: Object,
});

const departments = ref(props.departments);

const submit = () => {
    axios
        .post("/admin-add-department", { form })
        .then((res) => {
            toast.success(res.data.message, {
                autoClose: 3000,
                position: "top-right",
            });
            form.department = "";
            fetchDepartments();
        })
        .catch((err) => {
            toast.error("Couldn't save department\nPlease make sure department doesn't exist", {
                autoClose: 3000,
                position: "top-right",
            });
            form.department = "";
        });
};

const fetchDepartments = () => {
    axios.get("/fetch-departments").then((res) => {
        departments.value = res.data;
    });
};
const deleteDepartment = (departmentId) => {
    router.delete(`/departments/${departmentId}`, {
        onError: () => {
            toast.error("Failed to delete Department. Please try again.");
        },
        onSuccess: () => {
            toast.success('Department deleted successfully');
            fetchDepartments();
        }
    });
};
</script>

<style scoped></style>