<template>
    <AuthenticatedLayout>
        <div class="py-6">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-bold text-gray-800">Patient Follow-ups</h2>
                    <button @click="showAddModal = true" 
                        class="bg-teal-900 text-white px-4 py-2 rounded hover:bg-teal-800">
                        Add Follow-up
                    </button>
                </div>

                <!-- Filters -->
                <div class="bg-white rounded-lg shadow p-4 mb-6">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                            <select v-model="filters.status" class="w-full border-gray-300 rounded-md shadow-sm">
                                <option value="">All</option>
                                <option value="pending">Pending</option>
                                <option value="completed">Completed</option>
                                <option value="missed">Missed</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Date Range</label>
                            <input type="date" v-model="filters.date" class="w-full border-gray-300 rounded-md shadow-sm">
                        </div>
                        <div class="flex items-end">
                            <button @click="resetFilters" class="text-gray-600 hover:text-gray-800">
                                Reset Filters
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Follow-ups Table -->
                <div class="bg-white rounded-lg shadow overflow-hidden">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Patient</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Follow-up Date</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Notes</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <tr v-for="followUp in filteredFollowUps" :key="followUp.id">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">
                                        {{ followUp.patient.surname }} {{ followUp.patient.otherNames }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">
                                        {{ formatDate(followUp.follow_up_date) }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span :class="getStatusClass(followUp.status)"
                                        class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full">
                                        {{ followUp.status }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm text-gray-900 truncate max-w-xs">
                                        {{ followUp.notes }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    <button @click="viewDetails(followUp)" 
                                        class="text-teal-900 hover:text-teal-800 mr-3">View/Update</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Add Follow-up Modal -->
        <Modal :show="showAddModal" @close="showAddModal = false">
            <div class="p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Add New Follow-up</h3>
                <form @submit.prevent="submitFollowUp">
                    <div class="space-y-4">
                        <!-- Patient Selection -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Patient</label>
                            <select v-model="form.patient_id" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                <option value="">Select Patient</option>
                                <option v-for="appointment in completedAppointments" 
                                    :key="appointment.id" 
                                    :value="appointment.user_id">
                                    {{ appointment.patient.surname }} {{ appointment.patient.otherNames }}
                                </option>
                            </select>
                        </div>

                        <!-- Follow-up Date -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Follow-up Date</label>
                            <input type="datetime-local" v-model="form.follow_up_date" required
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                        </div>

                        <!-- Notes -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Notes</label>
                            <textarea v-model="form.notes" required rows="3"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"></textarea>
                        </div>

                        <!-- Recommendations -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Recommendations</label>
                            <textarea v-model="form.recommendations" rows="2"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"></textarea>
                        </div>
                    </div>

                    <div class="mt-6 flex justify-end space-x-3">
                        <button type="button" @click="showAddModal = false"
                            class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50">
                            Cancel
                        </button>
                        <button type="submit"
                            class="bg-teal-900 py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white hover:bg-teal-800">
                            Add Follow-up
                        </button>
                    </div>
                </form>
            </div>
        </Modal>

        <!-- View/Update Modal -->
        <Modal :show="showViewModal" @close="showViewModal = false">
            <div class="p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Follow-up Details</h3>
                <div v-if="selectedFollowUp" class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Patient</label>
                        <p class="mt-1 text-sm text-gray-900">
                            {{ selectedFollowUp.patient.surname }} {{ selectedFollowUp.patient.otherNames }}
                        </p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Status</label>
                        <select v-model="selectedFollowUp.status" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                            <option value="pending">Pending</option>
                            <option value="completed">Completed</option>
                            <option value="missed">Missed</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Action Taken</label>
                        <textarea v-model="selectedFollowUp.action_taken" rows="3"
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"></textarea>
                    </div>

                    <div class="mt-6 flex justify-end space-x-3">
                        <button @click="showViewModal = false"
                            class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50">
                            Close
                        </button>
                        <button @click="saveUpdate"
                            class="bg-teal-900 py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white hover:bg-teal-800">
                            Save Changes
                        </button>
                    </div>
                </div>
            </div>
        </Modal>
    </AuthenticatedLayout>
</template>

<script setup>
import { ref, computed } from 'vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Modal from '@/Components/Modal.vue';
import { router } from '@inertiajs/vue3';

const props = defineProps({
    followUps: Array,
    completedAppointments: Array
});

const showAddModal = ref(false);
const showViewModal = ref(false);
const selectedFollowUp = ref(null);

const filters = ref({
    status: '',
    date: ''
});

const form = ref({
    patient_id: '',
    follow_up_date: '',
    notes: '',
    recommendations: ''
});

const filteredFollowUps = computed(() => {
    let filtered = props.followUps;
    
    if (filters.value.status) {
        filtered = filtered.filter(f => f.status === filters.value.status);
    }
    
    if (filters.value.date) {
        const filterDate = new Date(filters.value.date).toDateString();
        filtered = filtered.filter(f => {
            const followUpDate = new Date(f.follow_up_date).toDateString();
            return followUpDate === filterDate;
        });
    }
    
    return filtered;
});

const formatDate = (date) => {
    return new Date(date).toLocaleString();
};

const getStatusClass = (status) => {
    const classes = {
        pending: 'bg-yellow-100 text-yellow-800',
        completed: 'bg-green-100 text-green-800',
        missed: 'bg-red-100 text-red-800'
    };
    return classes[status] || '';
};

const resetFilters = () => {
    filters.value = {
        status: '',
        date: ''
    };
};

const submitFollowUp = () => {
    router.post('/nurse-follow-ups', form.value, {
        onSuccess: () => {
            showAddModal.value = false;
            form.value = {
                patient_id: '',
                follow_up_date: '',
                notes: '',
                recommendations: ''
            };
        }
    });
};

const viewDetails = (followUp) => {
    selectedFollowUp.value = { ...followUp };
    showViewModal.value = true;
};

const saveUpdate = () => {
    router.put(`/nurse-follow-ups/${selectedFollowUp.value.id}`, selectedFollowUp.value, {
        onSuccess: () => {
            showViewModal.value = false;
            selectedFollowUp.value = null;
        }
    });
};
</script> 