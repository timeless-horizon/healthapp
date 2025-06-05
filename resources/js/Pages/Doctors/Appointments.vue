<template>
    <AuthenticatedLayout>
        <div class="flex flex-col p-4">
            <h1 class="text-3xl font-semibold text-teal-900 mb-4">Upcoming Appointments</h1>
            <!-- Filter Controls -->
            <div class="flex flex-wrap gap-4 mb-4 items-end">
                <!-- Date Filter -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Date Filter</label>
                    <select v-model="selectedDateFilter" class="border rounded px-2 py-1">
                        <option value="all">All</option>
                        <option value="today">Today</option>
                        <option value="yesterday">Yesterday</option>
                        <option value="thisMonth">This Month</option>
                        <option value="lastMonth">Last Month</option>
                        <option value="custom">Custom Range</option>
                    </select>
                </div>
                <!-- Custom Date Range -->
                <div v-if="selectedDateFilter === 'custom'" class="flex gap-2 items-end">
                    <div>
                        <label class="block text-xs text-gray-600">From</label>
                        <input type="date" v-model="customStartDate" class="border rounded px-2 py-1" />
                    </div>
                    <div>
                        <label class="block text-xs text-gray-600">To</label>
                        <input type="date" v-model="customEndDate" class="border rounded px-2 py-1" />
                    </div>
                </div>
                <!-- Status Filter -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                    <select v-model="selectedStatusFilter" class="border rounded px-2 py-1">
                        <option value="all">All</option>
                        <option value="pending">Pending</option>
                        <option value="completed">Completed</option>
                        <option value="missed">Missed</option>
                    </select>
                </div>
            </div>
            <div class="relative overflow-x-auto">
                <table v-if="paginatedAppointments.length" class="w-full text-sm text-left text-gray-500 border">
                    <thead class="text-sm text-gray-700 uppercase bg-gray-50 font-semibold">
                        <tr>
                            <th scope="col" class="px-6 py-3">Appointment Date/Time</th>
                            <th scope="col" class="px-6 py-3">Name</th>
                            <th scope="col" class="px-6 py-3">Reason</th>
                            <th scope="col" class="px-6 py-3">Countdown</th>
                            <th scope="col" class="px-6 py-3">Action</th>
                            <th scope="col" class="px-6 py-3">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(appointment, index) in paginatedAppointments" :key="index"
                            class="odd:bg-white even:bg-gray-50">
                            <td scope="row" class="px-4 py-4 font-normal text-sm text-gray-900">
                                <span v-if="appointment.clients_date_and_time !== null">
                                    {{ formatDateTime(appointment.clients_date_and_time) }}
                                </span>
                                <span v-else>Awaiting your Schedule</span>
                            </td>
                            <td scope="row" class="px-6 py-4 font-normal text-sm text-gray-900 max-w-[350px]">
                                {{ appointment.patient.surname }} {{ appointment.patient.otherNames }}
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
                                            Start Meeting
                                        </button>
                                    </a>
                                    <p v-if="appointment.status === 'completed'"
                                        class="font-normal text-sm text-gray-900">
                                        Appointment Concluded</p>
                                    <p v-if="appointment.status === 'missed'" class="font-normal text-sm text-gray-900">
                                        Appointment Missed</p>
                                </span>
                                <span v-else>Awaiting your schedule</span>
                            </td>
                            <td scope="row" class="px-4 py-4 font-normal text-sm text-gray-900">
                                <div class="flex flex-col space-y-2">
                                    <button v-if="appointment.status === 'pending' || appointment.status === 'missed'"
                                        @click="openAppointmentModal(appointment)"
                                        class="px-3 py-1 bg-teal-50 text-teal-700 rounded hover:bg-teal-100">
                                        {{ appointment.clients_date_and_time ? 'Reschedule' : 'Schedule Now' }}
                                    </button>
                                    <button v-else class="px-3 py-1 bg-green-900 text-white rounded cursor-not-allowed">
                                        Appointment Completed
                                    </button>
                                </div>
                            </td>
                            <td scope="row" class="px-2 py-4 font-normal text-sm text-gray-900 whitespace-nowrap">
                                <StatusDropdown v-model="appointment.status" :appointment-id="appointment.id"
                                    :meeting="appointment.meeting_link" :docTime="appointment.clients_date_and_time"
                                    :appointment-data="appointment"
                                    @status-changed="handleStatusChange(appointment, $event)" />
                            </td>
                        </tr>
                    </tbody>
                </table>
                <div v-else class="text-center py-4 bg-gray-50 border border-gray-300 rounded">
                    No appointments found
                </div>
                <!-- Pagination Controls -->
                <div v-if="localAppointments.length" class="mt-4 flex justify-between items-center">
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
        <appointmentModal :showModal="showModal" :appointment="selectedAppointment" @close="closeModal"
            @updated="handleAppointmentUpdate" />
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import appointmentModal from "@/Components/appointmentModal.vue";
import StatusDropdown from "@/Components/StatusDropdown.vue";
import { ref, onMounted, watch, onUnmounted, computed } from "vue";

const props = defineProps({
    appointments: Object
});

const currentPage = ref(1);
const itemsPerPage = 10;

const localAppointments = ref([]);
watch(
    () => props.appointments,
    (newAppointments) => {
        if (newAppointments) {
            localAppointments.value = [...newAppointments].sort((a, b) => {
                if (a.clients_date_and_time === null && b.clients_date_and_time === null) return 0;
                if (a.clients_date_and_time === null) return -1;
                if (b.clients_date_and_time === null) return 1;
                return new Date(b.clients_date_and_time) - new Date(a.clients_date_and_time);
            });
        }
    },
    { immediate: true, deep: true }
);

// Filter variables
const selectedDateFilter = ref('all');
const customStartDate = ref('');
const customEndDate = ref('');
const selectedStatusFilter = ref('all');

// Helper to check if a date is today, yesterday, this month, last month
function isToday(date) {
    const now = new Date();
    return date.getDate() === now.getDate() &&
        date.getMonth() === now.getMonth() &&
        date.getFullYear() === now.getFullYear();
}
function isYesterday(date) {
    const now = new Date();
    const yesterday = new Date(now);
    yesterday.setDate(now.getDate() - 1);
    return date.getDate() === yesterday.getDate() &&
        date.getMonth() === yesterday.getMonth() &&
        date.getFullYear() === yesterday.getFullYear();
}
function isThisMonth(date) {
    const now = new Date();
    return date.getMonth() === now.getMonth() &&
        date.getFullYear() === now.getFullYear();
}
function isLastMonth(date) {
    const now = new Date();
    const lastMonth = new Date(now);
    lastMonth.setMonth(now.getMonth() - 1);
    return date.getMonth() === lastMonth.getMonth() &&
        date.getFullYear() === lastMonth.getFullYear();
}

const filteredAppointments = computed(() => {
    return localAppointments.value.filter(appointment => {
        // Date filter
        let dateMatch = true;
        if (selectedDateFilter.value !== 'all') {
            if (!appointment.clients_date_and_time) {
                dateMatch = false;
            } else {
                const aptDate = new Date(appointment.clients_date_and_time);
                if (selectedDateFilter.value === 'today') {
                    dateMatch = isToday(aptDate);
                } else if (selectedDateFilter.value === 'yesterday') {
                    dateMatch = isYesterday(aptDate);
                } else if (selectedDateFilter.value === 'thisMonth') {
                    dateMatch = isThisMonth(aptDate);
                } else if (selectedDateFilter.value === 'lastMonth') {
                    dateMatch = isLastMonth(aptDate);
                } else if (selectedDateFilter.value === 'custom') {
                    if (customStartDate.value && customEndDate.value) {
                        const start = new Date(customStartDate.value);
                        const end = new Date(customEndDate.value);
                        // Set end to end of day
                        end.setHours(23,59,59,999);
                        dateMatch = aptDate >= start && aptDate <= end;
                    } else {
                        dateMatch = true;
                    }
                }
            }
        }
        // Status filter
        let statusMatch = true;
        if (selectedStatusFilter.value !== 'all') {
            statusMatch = appointment.status === selectedStatusFilter.value;
        }
        return dateMatch && statusMatch;
    });
});

const totalPages = computed(() => {
    return Math.ceil(filteredAppointments.value.length / itemsPerPage);
});

const paginatedAppointments = computed(() => {
    const start = (currentPage.value - 1) * itemsPerPage;
    const end = start + itemsPerPage;
    return filteredAppointments.value.slice(start, end);
});

const showModal = ref(false);
const selectedAppointment = ref(null);

const formatDateTime = (dateTime) => {
    const date = new Date(dateTime);
    return date.toLocaleString();
};

const getCountdown = (dateTime) => {
    const now = new Date();
    const appointmentDate = new Date(dateTime);
    const diff = appointmentDate - now;
    if (diff <= 0) return 'Appointment has passed';

    const days = Math.floor(diff / (1000 * 60 * 60 * 24));
    const hours = Math.floor((diff % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
    const minutes = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
    const seconds = Math.floor((diff % (1000 * 60)) / 1000);
    return `${days}d ${hours}h ${minutes}m ${seconds}s left`;
};

const openAppointmentModal = (appointment) => {
    selectedAppointment.value = { ...appointment };
    showModal.value = true;
};

const closeModal = () => {
    showModal.value = false;
    selectedAppointment.value = null;
};

const handleAppointmentUpdate = (updatedAppointment) => {
    const index = localAppointments.value.findIndex(apt => apt.id === updatedAppointment.id);
    if (index !== -1) {
        localAppointments.value[index] = updatedAppointment;
        localAppointments.value = [...localAppointments.value].sort((a, b) => {
            if (a.clients_date_and_time === null && b.clients_date_and_time === null) return 0;
            if (a.clients_date_and_time === null) return -1;
            if (b.clients_date_and_time === null) return 1;
            return new Date(b.clients_date_and_time) - new Date(a.clients_date_and_time);
        });
    }
    closeModal();
};

const handleStatusChange = (appointment, newStatus) => {
    const index = localAppointments.value.findIndex(apt => apt.id === appointment.id);
    if (index !== -1) {
        localAppointments.value[index].status = newStatus;
    }
};

let countdownInterval = null;
onMounted(() => {
    countdownInterval = setInterval(() => {
        if (Array.isArray(localAppointments.value)) {
            localAppointments.value.forEach(appointment => {
                if (appointment.clients_date_and_time) {
                    appointment._tick = Date.now();
                }
            });
        }
    }, 1000);
});

onUnmounted(() => {
    if (countdownInterval) {
        clearInterval(countdownInterval);
    }
});
</script>

<style scoped>
.table-container {
    position: relative;
    max-height: calc(100vh - 200px);
    overflow-y: auto;
}

table {
    width: 100%;
    border-collapse: collapse;
}

td {
    position: relative;
}

.status-dropdown {
    display: inline-block;
    width: 100%;
}
</style>