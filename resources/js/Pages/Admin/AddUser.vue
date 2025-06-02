<template>
    <AuthenticatedLayout>
    <div class="min-h-screen bg-gray-100 flex flex-col items-center py-8">
        <!-- Page Title -->
        <h1 class="text-3xl font-bold text-gray-800 mb-6">Choose User Type</h1>

        <!-- Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 w-full max-w-4xl px-4">
            <!-- Admin Card -->
            <Link href="/create-admin" class="bg-white shadow-md rounded-lg p-6 text-center hover:shadow-lg transition-shadow cursor-pointer">
                <h2 class="text-xl font-semibold text-gray-700 mb-2">Admin</h2>
                <p class="text-gray-600">Manage users and oversee the system.</p>
            </Link>

            <!-- Doctor Card -->
            <Link href="/create-doctor" class="bg-white shadow-md rounded-lg p-6 text-center hover:shadow-lg transition-shadow cursor-pointer">
                <h2 class="text-xl font-semibold text-gray-700 mb-2">Doctor</h2>
                <p class="text-gray-600">Access medical records and assist patients.</p>
            </Link>

            <!-- Patient Card -->
            <Link href="/create-patient" class="bg-white shadow-md rounded-lg p-6 text-center hover:shadow-lg transition-shadow cursor-pointer">
                <h2 class="text-xl font-semibold text-gray-700 mb-2">Patient</h2>
                <p class="text-gray-600">View your medical records and book appointments.</p>
            </Link>
        </div>
    </div>
    </AuthenticatedLayout>
</template>

<script setup>
import AdminSideBar from "@/Components/AdminSideBar.vue";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import {Link} from "@inertiajs/vue3";
</script>

<style scoped>
/* Add any custom styles here if needed */
</style>
