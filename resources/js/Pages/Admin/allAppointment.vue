<template>
    <AuthenticatedLayout>
        <div class="flex flex-col p-4">
            <h1 class="text-3xl font-semibold text-teal-900 mb-4">All Appointments</h1>
            <div class="relative overflow-x-auto">
                <table v-if="paginatedAppointments.length" class="w-full text-sm text-left text-gray-500 border">
                    <thead class="text-sm text-gray-700 uppercase bg-gray-50 font-semibold">
                        <tr>
                            <th scope="col" class="px-6 py-3">S/N</th>
                            <th scope="col" class="px-6 py-3">Appointment Date/Time</th>
                            <th scope="col" class="px-6 py-3">Reason</th>
                            <th scope="col" class="px-6 py-3">Countdown</th>
                            <!-- <th scope="col" class="px-6 py-3">Action</th> -->
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(appointment, index) in paginatedAppointments" :key="index"
                            class="odd:bg-white even:bg-gray-50">
                            <td class="px-4 py-4 font-bold"> {{ (currentPage - 1) * itemsPerPage + index + 1 }}</td>
                            <td scope="row" class="px-4 py-4 font-normal text-sm text-gray-900">
                                {{ formatDateTime(appointment.clients_date_and_time) }}
                            </td>
                            <td scope="row" class="px-4 py-4 font-normal text-sm text-gray-900">{{ appointment.reason }}
                            </td>
                            <td scope="row" class="px-4 py-4 font-normal text-sm text-gray-900">
                                {{ getCountdown(appointment.clients_date_and_time) }}
                            </td>
                            <td scope="row" class="px-4 py-4 font-normal text-sm text-gray-900">
                                <div v-if="editingIndex === index" class="flex flex-col space-y-2">
                                    <input type="datetime-local" v-model="editedAppointment.clients_date_and_time"
                                        class="border border-gray-300 px-2 py-1 rounded">
                                    <div class="flex space-x-2">
                                        <button @click="saveAppointment(index)"
                                            class="px-3 py-1 bg-teal-700 text-white rounded hover:bg-teal-800">
                                            Save
                                        </button>
                                        <button @click="editingIndex = null"
                                            class="px-3 py-1 bg-gray-300 text-gray-700 rounded hover:bg-gray-400">
                                            Cancel
                                        </button>
                                    </div>
                                </div>
                                <!-- <button v-else @click="editAppointment(index, appointment)"
                                    class="px-3 py-1 bg-teal-700 text-white rounded hover:bg-teal-800">
                                    Edit
                                </button> -->
                            </td>
                        </tr>
                    </tbody>
                </table>
                <div v-else class="text-center py-4 bg-gray-50 border border-gray-300 rounded">
                    No appointments found
                </div>
                <!-- Pagination Controls -->
                <div v-if="appointments.length" class="mt-4 flex justify-between items-center">
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
import { ref, computed } from "vue";

const props = defineProps({
    appointments: Object
});

// Pagination state
const currentPage = ref(1);
const itemsPerPage = 10;

// Computed properties for pagination
const totalPages = computed(() => {
    return Math.ceil(props.appointments.length / itemsPerPage);
});

const paginatedAppointments = computed(() => {
    const start = (currentPage.value - 1) * itemsPerPage;
    const end = start + itemsPerPage;
    return props.appointments.slice(start, end);
});

// Editing state
const editingIndex = ref(null);
const editedAppointment = ref({});

// Methods
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

const editAppointment = (index, appointment) => {
    editingIndex.value = index;
    editedAppointment.value = { ...appointment };
};

const saveAppointment = (index) => {
    console.log('Saving appointment:', editedAppointment.value);
    editingIndex.value = null;
};
</script>

<style></style>