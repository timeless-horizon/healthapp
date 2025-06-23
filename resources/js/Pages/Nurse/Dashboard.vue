<template>
  <AuthenticatedLayout>
    <div class="min-h-screen px-2">
      <div class="max-w-7xl mx-auto">
        <h1 class="text-3xl md:text-4xl font-bold text-teal-600 mb-8 tracking-tight">Nurse Dashboard</h1>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 mb-10">
         
          <!-- Appointments Card -->
          <div class="bg-gray-50 rounded-2xl shadow-lg p-6 flex flex-col">
            <h2 class="text-xl font-semibold text-teal-600 mb-4">Upcoming Appointments</h2>
            <ul class="divide-y divide-teal-50">
              <li v-for="appt in appointments.slice(0, 4)" :key="appt.id" class="py-2 flex justify-between items-center">
                <span class="text-gray-800 font-medium">{{ getPatientName(appt.user_id) }}</span>
                <span class="text-xs bg-teal-100 text-teal-700 px-2 py-1 rounded">{{ appt.status }}</span>
              </li>
            </ul>
            <div v-if="appointments.length > 4" class="mt-2 text-right">
              <span class="text-xs text-teal-500">and {{ appointments.length - 4 }} more...</span>
            </div>
          </div>
          <!-- Follow-ups Card -->
          <div class="bg-gray-50 rounded-2xl shadow-lg p-6 flex flex-col">
            <h2 class="text-xl font-semibold text-teal-600 mb-4">Follow-ups</h2>
            <ul class="divide-y divide-teal-50">
              <li v-for="follow in followUps.slice(0, 4)" :key="follow.id" class="py-2 flex justify-between items-center">
                <span class="text-gray-800 font-medium">{{ getPatientName(follow.user_id) }}</span>
                <button class="px-3 py-1 bg-teal-600 text-white rounded-lg hover:bg-teal-700 transition" @click="openFollowUp(follow)">Follow Up</button>
              </li>
            </ul>
            <div v-if="followUps.length > 4" class="mt-2 text-right">
              <span class="text-xs text-teal-500">and {{ followUps.length - 4 }} more...</span>
            </div>
          </div>
          <!-- Alerts Card -->
          <div class="bg-gray-50 rounded-2xl shadow-lg p-6 flex flex-col">
            <h2 class="text-xl font-semibold text-teal-600 mb-4">Alerts</h2>
            <ul class="divide-y divide-teal-50">
              <li v-for="alert in alerts.slice(0, 4)" :key="alert.id" class="py-2">
                <span class="font-semibold text-teal-700">{{ alert.subject || 'Alert' }}:</span>
                <span class="text-gray-700"> {{ alert.message || alert.messege }}</span>
              </li>
            </ul>
            <div v-if="alerts.length > 4" class="mt-2 text-right">
              <span class="text-xs text-teal-500">and {{ alerts.length - 4 }} more...</span>
            </div>
          </div>
        </div>

         <!-- Patients Table Full Width -->
         <div class="bg-white rounded-2xl shadow-lg p-6 mb-10">
           <h2 class="text-2xl font-semibold text-teal-600 mb-4">All Patients</h2>
           <div class="relative overflow-x-auto">
             <table v-if="paginatedPatients.length" class="w-full text-sm text-left text-gray-500 border">
               <thead class="text-sm text-gray-700 uppercase bg-gray-50 font-semibold">
                 <tr>
                   <th class="px-6 py-3">S/N</th>
                   <th class="px-6 py-3">Surname</th>
                   <th class="px-6 py-3">Other Names</th>
                   <th class="px-6 py-3">Tel</th>
                   <th class="px-6 py-3">Email</th>
                   <th class="px-6 py-3">Username</th>
                   <th class="px-6 py-3">Country, State</th>
                   <th class="px-6 py-3">Created At</th>
                   <th class="px-6 py-3">Action</th>
                 </tr>
               </thead>
               <tbody>
                 <tr v-for="(patient, index) in paginatedPatients" :key="patient.id" class="odd:bg-white even:bg-gray-50">
                   <td class="px-4 py-4 font-bold">{{ (currentPage - 1) * itemsPerPage + index + 1 }}</td>
                   <td class="px-6 py-4 text-gray-900">{{ patient.surname }}</td>
                   <td class="px-6 py-4 text-gray-900">{{ patient.otherNames }}</td>
                   <td class="px-6 py-4 text-gray-900">{{ patient.tel }}</td>
                   <td class="px-6 py-4 text-gray-900">{{ patient.email }}</td>
                   <td class="px-6 py-4 text-gray-900">{{ patient.username }}</td>
                   <td class="px-6 py-4 text-gray-900">{{ patient.country }}, {{ patient.state }}</td>
                   <td class="px-6 py-4 text-gray-900">{{ new Date(patient.created_at).toLocaleDateString() }}</td>
                   <td class="px-6 py-4 text-gray-900">
                     <button class="px-3 py-1 bg-teal-600 text-white rounded-lg hover:bg-teal-700 transition" @click="openPatientDetails(patient)">View</button>
                   </td>
                 </tr>
               </tbody>
             </table>
             <div v-else class="text-center py-4 bg-gray-50 border border-gray-300 rounded">
               No patients found
             </div>
             <!-- Pagination Controls -->
             <div v-if="patients.length" class="mt-4 flex justify-between items-center">
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
        <!-- Modals -->
        <Modal v-if="showPatientDetails" @close="showPatientDetails = false">
          <template #header>
            <h3 class="text-xl font-bold text-teal-700">Patient Details: {{ selectedPatient?.surname }} {{ selectedPatient?.otherNames }}</h3>
          </template>
          <div class="space-y-2">
            <p><b>Email:</b> {{ selectedPatient?.email }}</p>
            <p><b>Phone:</b> {{ selectedPatient?.tel }}</p>
            <p><b>Medical Conditions:</b> {{ selectedPatient?.medicalConditions }}</p>
            <p><b>Medications:</b> {{ selectedPatient?.medications }}</p>
            <p><b>Last Result:</b> {{ selectedPatient?.last_result || 'N/A' }}</p>
            <p class="mt-4 text-gray-600">More patient details and medical records can be shown here.</p>
          </div>
          <template #footer>
            <button class="px-4 py-2 bg-teal-600 text-white rounded-lg hover:bg-teal-700 transition" @click="showPatientDetails = false">Close</button>
          </template>
        </Modal>
        <Modal v-if="showFollowUp" @close="showFollowUp = false">
          <template #header>
            <h3 class="text-xl font-bold text-teal-700">Follow Up: {{ getPatientName(selectedFollowUp?.user_id) }}</h3>
          </template>
          <div class="space-y-2">
            <p>Status: {{ selectedFollowUp?.status }}</p>
            <p>Appointment Time: {{ selectedFollowUp?.clients_date_and_time }}</p>
            <p class="mt-4 text-gray-600">Add follow-up notes or actions here.</p>
          </div>
          <template #footer>
            <button class="px-4 py-2 bg-teal-600 text-white rounded-lg hover:bg-teal-700 transition" @click="showFollowUp = false">Close</button>
          </template>
        </Modal>
      </div>
    </div>
  </AuthenticatedLayout>
</template>

<script>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Modal from '@/Components/Modal.vue';
import { usePage } from '@inertiajs/vue3';
import { ref, computed } from 'vue';
export default {
  name: 'NurseDashboard',
  components: { AuthenticatedLayout, Modal },
  setup() {
    const props = usePage().props;
    const patients = props.patients || [];
    const appointments = props.appointments || [];
    const followUps = props.followUps || [];
    const alerts = props.alerts || [];
    const showPatientDetails = ref(false);
    const selectedPatient = ref(null);
    const showFollowUp = ref(false);
    const selectedFollowUp = ref(null);
    const currentPage = ref(1);
    const itemsPerPage = 10;
    const totalPages = computed(() => Math.ceil(patients.length / itemsPerPage));
    const paginatedPatients = computed(() => {
      const start = (currentPage.value - 1) * itemsPerPage;
      const end = start + itemsPerPage;
      return patients.slice(start, end);
    });
    function openPatientDetails(patient) {
      selectedPatient.value = patient;
      showPatientDetails.value = true;
    }
    function openFollowUp(follow) {
      selectedFollowUp.value = follow;
      showFollowUp.value = true;
    }
    function getPatientName(user_id) {
      const patient = patients.find(p => p.id === user_id);
      return patient ? `${patient.surname} ${patient.otherNames}` : 'Unknown';
    }
    return {
      patients,
      appointments,
      followUps,
      alerts,
      showPatientDetails,
      selectedPatient,
      showFollowUp,
      selectedFollowUp,
      currentPage,
      itemsPerPage,
      totalPages,
      paginatedPatients,
      openPatientDetails,
      openFollowUp,
      getPatientName,
    };
  },
};
</script>

<style scoped>
body {
  background: #f7fafd;
}
</style> 