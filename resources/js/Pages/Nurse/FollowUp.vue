<template>
  <AuthenticatedLayout>
    <div class="max-w-2xl mx-auto mt-10 bg-white p-8 rounded shadow">
      <h2 class="text-2xl font-bold mb-4">Follow Up Details</h2>
      <div v-if="$page.props.patient">
        <p><strong>Name:</strong> {{ $page.props.patient.name }}</p>
        <p><strong>Status:</strong> {{ $page.props.patient.status }}</p>
        <p class="mt-4">More patient details and follow-up actions here...</p>
      </div>
      <div v-else>
        <p>No patient selected. Please select a patient to follow up.</p>
      </div>
    </div>
  </AuthenticatedLayout>
</template>

<script>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
export default {
  name: 'NurseFollowUp',
  components: { AuthenticatedLayout },
};
</script> 