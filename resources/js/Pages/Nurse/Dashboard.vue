<template>
  <AuthenticatedLayout>
    <div class="min-h-screen px-4 py-6">
      <div class="max-w-7xl mx-auto">
        <h1 class="text-3xl font-bold text-gray-800 mb-8">Nurse Dashboard</h1>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
          <!-- Total Patients Card -->
          <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
              <div class="p-3 rounded-full bg-teal-100 bg-opacity-75">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-teal-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
              </div>
              <div class="ml-4">
                <h2 class="text-gray-600 text-sm">Total Patients</h2>
                <p class="text-2xl font-semibold text-gray-800">{{ patients.length }}</p>
              </div>
            </div>
          </div>

          <!-- Pending Follow-ups Card -->
          <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
              <div class="p-3 rounded-full bg-yellow-100 bg-opacity-75">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-yellow-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
              </div>
              <div class="ml-4">
                <h2 class="text-gray-600 text-sm">Pending Follow-ups</h2>
                <p class="text-2xl font-semibold text-gray-800">{{ pendingFollowUps }}</p>
              </div>
            </div>
          </div>

          <!-- Today's Appointments Card -->
          <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
              <div class="p-3 rounded-full bg-blue-100 bg-opacity-75">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
              </div>
              <div class="ml-4">
                <h2 class="text-gray-600 text-sm">Today's Appointments</h2>
                <p class="text-2xl font-semibold text-gray-800">{{ todayAppointments }}</p>
              </div>
            </div>
          </div>

          <!-- Active Alerts Card -->
          <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
              <div class="p-3 rounded-full bg-red-100 bg-opacity-75">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
              </div>
              <div class="ml-4">
                <h2 class="text-gray-600 text-sm">Active Alerts</h2>
                <p class="text-2xl font-semibold text-gray-800">{{ alerts.length }}</p>
              </div>
            </div>
          </div>
        </div>

        <!-- Recent Follow-ups and Appointments Section -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
          <!-- Recent Follow-ups -->
          <div class="bg-white rounded-lg shadow">
            <div class="p-6 border-b">
              <div class="flex justify-between items-center">
                <h2 class="text-xl font-semibold text-gray-800">Recent Follow-ups</h2>
                <Link :href="route('nurse.follow-ups')" class="text-teal-600 hover:text-teal-800">View All</Link>
              </div>
            </div>
            <div class="p-6">
              <div v-if="followUps.length" class="divide-y">
                <div v-for="followUp in followUps.slice(0, 5)" :key="followUp.id" class="py-4">
                  <div class="flex justify-between items-start">
                    <div>
                      <p class="font-medium text-gray-800">{{ getPatientName(followUp.patient_id) }}</p>
                      <p class="text-sm text-gray-600">{{ formatDate(followUp.follow_up_date) }}</p>
                    </div>
                    <span :class="getStatusBadgeClass(followUp.status)" class="px-3 py-1 rounded-full text-xs font-medium">
                      {{ followUp.status }}
                    </span>
                  </div>
                  <p class="mt-2 text-sm text-gray-600 truncate">{{ followUp.notes }}</p>
                </div>
              </div>
              <div v-else class="text-center py-4 text-gray-500">No follow-ups found</div>
            </div>
          </div>

          <!-- Recent Appointments -->
          <div class="bg-white rounded-lg shadow">
            <div class="p-6 border-b">
              <h2 class="text-xl font-semibold text-gray-800">Today's Appointments</h2>
            </div>
            <div class="p-6">
              <div v-if="todayAppointmentsList.length" class="divide-y">
                <div v-for="appointment in todayAppointmentsList" :key="appointment.id" class="py-4">
                  <div class="flex justify-between items-start">
                    <div>
                      <p class="font-medium text-gray-800">{{ getPatientName(appointment.user_id) }}</p>
                      <p class="text-sm text-gray-600">{{ formatTime(appointment.clients_date_and_time) }}</p>
                    </div>
                    <span :class="getAppointmentStatusClass(appointment.status)" class="px-3 py-1 rounded-full text-xs font-medium">
                      {{ appointment.status }}
                    </span>
                  </div>
                  <p class="mt-2 text-sm text-gray-600">{{ appointment.reason }}</p>
                </div>
              </div>
              <div v-else class="text-center py-4 text-gray-500">No appointments for today</div>
            </div>
          </div>
        </div>

        <!-- Recent Alerts -->
        <div class="bg-white rounded-lg shadow mb-8">
          <div class="p-6 border-b">
            <h2 class="text-xl font-semibold text-gray-800">Recent Alerts</h2>
          </div>
          <div class="p-6">
            <div v-if="alerts.length" class="divide-y">
              <div v-for="alert in alerts.slice(0, 5)" :key="alert.id" class="py-4">
                <div class="flex items-start">
                  <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-red-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                      <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                    </svg>
                  </div>
                  <div class="ml-3">
                    <h3 class="text-sm font-medium text-gray-800">{{ alert.subject }}</h3>
                    <p class="mt-1 text-sm text-gray-600">{{ alert.message }}</p>
                    <p class="mt-1 text-xs text-gray-500">{{ formatDate(alert.created_at) }}</p>
                  </div>
                </div>
              </div>
            </div>
            <div v-else class="text-center py-4 text-gray-500">No alerts found</div>
          </div>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Link } from '@inertiajs/vue3';
import { computed } from 'vue';

const props = defineProps({
  patients: Array,
  appointments: Array,
  followUps: Array,
  alerts: Array
});

// Computed properties for stats
const pendingFollowUps = computed(() => {
  return props.followUps.filter(f => f.status === 'pending').length;
});

const todayAppointments = computed(() => {
  return todayAppointmentsList.value.length;
});

const todayAppointmentsList = computed(() => {
  const today = new Date().toISOString().split('T')[0];
  return props.appointments.filter(appointment => {
    const appointmentDate = new Date(appointment.clients_date_and_time).toISOString().split('T')[0];
    return appointmentDate === today;
  });
});

// Helper functions
const formatDate = (date) => {
  return new Date(date).toLocaleDateString('en-US', {
    year: 'numeric',
    month: 'short',
    day: 'numeric'
  });
};

const formatTime = (datetime) => {
  return new Date(datetime).toLocaleTimeString('en-US', {
    hour: '2-digit',
    minute: '2-digit'
  });
};

const getPatientName = (userId) => {
  const patient = props.patients.find(p => p.id === userId);
  return patient ? `${patient.surname} ${patient.otherNames}` : 'Unknown Patient';
};

const getStatusBadgeClass = (status) => {
  const classes = {
    pending: 'bg-yellow-100 text-yellow-800',
    completed: 'bg-green-100 text-green-800',
    missed: 'bg-red-100 text-red-800'
  };
  return classes[status] || 'bg-gray-100 text-gray-800';
};

const getAppointmentStatusClass = (status) => {
  const classes = {
    pending: 'bg-yellow-100 text-yellow-800',
    completed: 'bg-green-100 text-green-800',
    cancelled: 'bg-red-100 text-red-800',
    attended: 'bg-blue-100 text-blue-800'
  };
  return classes[status] || 'bg-gray-100 text-gray-800';
};
</script>

<style scoped>
body {
  background: #f7fafd;
}
</style> 