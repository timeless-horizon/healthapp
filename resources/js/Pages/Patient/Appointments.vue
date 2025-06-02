<template>
    <AuthenticatedLayout>
        <div class="flex flex-col p-4">
            <h1 class="text-3xl font-semibold text-teal-900 mb-4">Upcoming Appointments</h1>
            <div class="w-full flex items-center justify-end my-3">
                <Link href="patient-book-appointment">
                <button
                    class="bg-gray-900 text-white py-2 px-6 rounded-lg hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-teal-600">
                    Create Appointment
                </button>
                </Link>
            </div>
            <div class="relative overflow-x-auto">
                <table v-if="paginatedAppointments.length" class="w-full text-sm text-left text-gray-500 border">
                    <thead class="text-sm text-gray-700 uppercase bg-gray-50 font-semibold">
                        <tr>
                            <th scope="col" class="px-6 py-3">S/N</th>
                            <th scope="col" class="px-6 py-3">Appointment Date/Time</th>
                            <th scope="col" class="px-6 py-3">Department</th>
                            <th scope="col" class="px-6 py-3">Reason</th>
                            <th scope="col" class="px-6 py-3">Countdown</th>
                            <th scope="col" class="px-6 py-3">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(appointment, index) in paginatedAppointments" :key="appointment.id"
                            class="odd:bg-white even:bg-gray-50">
                            <td scope="row" class="px-4 py-4 font-normal text-sm text-gray-900">
                                {{ (currentPage - 1) * itemsPerPage + index + 1 }}
                            </td>
                            <td scope="row" class="px-6 py-4 font-normal text-sm text-gray-900">
                                <span v-if="appointment.clients_date_and_time !== null">
                                    {{ formatDateTime(appointment.clients_date_and_time) }}
                                </span>
                                <span v-else>Awaiting doctor's response</span>
                            </td>
                            <td scope="row" class="px-6 py-4 font-normal text-sm text-gray-900 whitespace-nowrap">
                                {{ appointment.department.name }}
                            </td>
                            <td scope="row" class="px-6 py-4 font-normal text-sm text-gray-900 max-w-[350px]">
                                {{ appointment.reason }}
                            </td>
                            <td scope="row" class="px-6 py-4 font-normal text-sm text-gray-900">
                                <span v-if="appointment.clients_date_and_time !== null">
                                    <span
                                        v-if="getCountdown(appointment.clients_date_and_time) !== 'Appointment has passed'">
                                        {{ getCountdown(appointment.clients_date_and_time) }}
                                    </span>
                                    <a target="_blank"
                                        v-else-if="appointment.meeting_link && appointment.status === 'pending'"
                                        :href="appointment.meeting_link">
                                        <button
                                            class="bg-teal-600 text-white py-1 px-4 rounded-lg hover:bg-teal-700 focus:outline-none focus:ring-2 focus:ring-teal-600">
                                            Join Meeting
                                        </button>
                                    </a>
                                    <p v-if="appointment.status === 'completed'"
                                        class="font-normal text-sm text-gray-900">
                                        Appointment Concluded
                                    </p>
                                    <p v-if="appointment.status === 'missed'" class="font-normal text-sm text-gray-900">
                                        Appointment Missed
                                    </p>
                                </span>
                                <span v-else>Awaiting doctor's response</span>
                            </td>
                            <td scope="row"
                                class="px-2 py-4 font-normal text-sm text-gray-900 whitespace-nowrap capitalize">
                                <div class="px-2 py-1 rounded text-center" :class="{
                                    'bg-yellow-500 text-white': appointment.status === 'pending',
                                    'bg-red-500 text-white': appointment.status === 'missed',
                                    'bg-green-100 text-black': appointment.status === 'completed'
                                }">
                                    <span class="capitalize">{{ appointment.status }}</span>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <div v-else class="text-center py-4 bg-gray-50 border border-gray-300 rounded">
                    No appointments found
                </div>
                <!-- Pagination Controls -->
                <div v-if="sortedAppointments.length" class="mt-4 flex justify-between items-center">
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
import { ref, onMounted, onUnmounted, computed } from "vue";
import { Link } from "@inertiajs/vue3";

const props = defineProps({
    appointments: Object,
});

const currentPage = ref(1);
const itemsPerPage = 10;

const sortedAppointments = computed(() => {
    return [...props.appointments].sort((a, b) => {
        if (a.clients_date_and_time === null && b.clients_date_and_time === null) return 0;
        if (a.clients_date_and_time === null) return -1;
        if (b.clients_date_and_time === null) return 1;

        return new Date(b.clients_date_and_time) - new Date(a.clients_date_and_time);
    });
});
const totalPages = computed(() => {
    return Math.ceil(sortedAppointments.value.length / itemsPerPage);
});

const paginatedAppointments = computed(() => {
    const start = (currentPage.value - 1) * itemsPerPage;
    const end = start + itemsPerPage;
    return sortedAppointments.value.slice(start, end);
});

const formatDateTime = (dateTime) => {
    const date = new Date(dateTime);
    return date.toLocaleString();
};

const getCountdown = (dateTime) => {
    const now = new Date();
    const appointmentDate = new Date(dateTime);
    const diff = appointmentDate - now;
    if (diff <= 0) return "Appointment has passed";

    const days = Math.floor(diff / (1000 * 60 * 60 * 24));
    const hours = Math.floor((diff % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
    const minutes = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
    const seconds = Math.floor((diff % (1000 * 60)) / 1000);
    return `${days}d ${hours}h ${minutes}m ${seconds}s left`;
};

const timer = ref(0);
let interval;

onMounted(() => {
    interval = setInterval(() => {
        timer.value += 1;
    }, 1000);
});

onUnmounted(() => {
    clearInterval(interval);
});
</script>