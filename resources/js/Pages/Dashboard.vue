<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, usePage } from '@inertiajs/vue3';
import { onMounted } from 'vue';
const { props } = usePage();

onMounted(() => {
    if (props.auth.user.user_role === 'admin') {
        window.location.href = '/admin-dashboard';
    } else if (props.auth.user.user_role === 'doctor') {
        window.location.href = '/doctor-dashboard-overview';
    } else if (props.auth.user.user_role === 'patient') {
        window.location.href = '/patient-dashboard-overview';
    }
});


</script>

<template>

    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Dashboard
            </h2>
        </template>
        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        You're logged in!
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>