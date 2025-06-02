<template>
    <div v-if="showModal" class="fixed inset-0 flex items-center justify-center z-50">
        <div class="fixed inset-0 bg-black bg-opacity-50" @click="closeModal"></div>
        <div class="bg-white rounded-lg shadow-lg p-6 w-full max-w-2xl relative z-10">
            <div class="flex justify-between items-center mb-10">
                <h2 class="text-2xl font-semibold text-teal-900">Schedule Appointment</h2>
                <button @click="closeModal" class="text-gray-500 hover:text-gray-700">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                        xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                        </path>
                    </svg>
                </button>
            </div>
            <div v-if="appointment" class="space-y-4">
                <div class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="space-y-2">
                            <label for="newTimeAndDate" class="block text-sm font-medium text-gray-700">Date &
                                Time</label>
                            <input type="datetime-local" id="newTimeAndDate"
                                v-model="editedAppointment.clients_date_and_time"
                                class="block w-full border border-gray-300 rounded-md shadow-sm px-4 py-2"
                                :class="{ 'border-red-500': errors.clients_date_and_time }">
                            <p v-if="errors.clients_date_and_time" class="text-red-500 text-sm mt-1">Please select a
                                date and time</p>
                        </div>
                        <div class="space-y-2">
                            <label for="meetingLink" class="block text-sm font-medium text-gray-700">Meeting
                                Link</label>
                            <div class="flex flex-col gap-1">
                                <input type="text" id="meetingLink" v-model="editedAppointment.meeting_link"
                                    class="block w-full border border-gray-300 rounded-md shadow-sm px-4 py-2"
                                    :class="{ 'border-red-500': errors.meeting_link }">
                                <a href="https://meet.google.com" target="_blank"
                                    class="text-teal-700 text-sm font-medium max-w-max">
                                    Generate Link
                                </a>
                            </div>
                            <p v-if="errors.meeting_link" class="text-red-500 text-sm mt-1">{{ errors.meeting_link }}
                            </p>
                        </div>
                    </div>

                    <div class="flex justify-end space-x-3 mt-4">
                        <button @click="cancelEditing"
                            class="px-4 py-2 bg-red-50 text-red-700 rounded-md hover:bg-red-100">
                            Cancel
                        </button>
                        <button @click="saveChanges"
                            class="px-4 py-2 bg-teal-600 text-white rounded-md hover:bg-teal-700 flex items-center disabled:cursor-not-allowed"
                            :disabled="hasErrors || isLoading">
                            <span v-if="isLoading"
                                class="inline-block animate-spin rounded-full h-4 w-4 border-t-2 border-b-2 border-white mr-2"></span>
                            {{ isLoading ? 'Saving...' : 'Save Changes' }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, watch, computed } from 'vue';
import axios from 'axios';
import { toast } from 'vue3-toastify';
import 'vue3-toastify/dist/index.css';

const props = defineProps({
    showModal: Boolean,
    appointment: Object
});

const emit = defineEmits(['close', 'updated', 'refetch-appointments']);
const isEditing = ref(false);
const editedAppointment = ref({});
const errors = ref({
    clients_date_and_time: false,
    meeting_link: false
});
const isLoading = ref(false);

watch(() => props.appointment, (newVal) => {
    if (newVal) {
        editedAppointment.value = { ...newVal };
        isEditing.value = false;
        errors.value = {
            clients_date_and_time: false,
            meeting_link: false
        };
    }
}, { deep: true });

const isValidUrl = (url) => {
    try {
        new URL(url);
        return true;
    } catch {
        return false;
    }
};

const validateForm = () => {
    errors.value.clients_date_and_time = !editedAppointment.value.clients_date_and_time;

    if (!editedAppointment.value.meeting_link) {
        errors.value.meeting_link = 'Meeting link is required';
    } else if (!isValidUrl(editedAppointment.value.meeting_link)) {
        errors.value.meeting_link = 'Please enter a valid URL';
    } else {
        errors.value.meeting_link = false;
    }

    return !errors.value.clients_date_and_time && !errors.value.meeting_link;
};

const hasErrors = computed(() => {
    return errors.value.clients_date_and_time || errors.value.meeting_link;
});

const closeModal = () => {
    emit('close');
};

const cancelEditing = () => {
    closeModal();
};

const saveChanges = async () => {
    if (!validateForm()) {
        return;
    }
    try {
        isLoading.value = true;
        const doctorDateAndTime = editedAppointment.value.clients_date_and_time;
        const res = await axios.post(`/doctor-reschedule-appointment/${props.appointment.id}`, {
            doctorDateAndTime: doctorDateAndTime,
            meeting_link: editedAppointment.value.meeting_link,
            user_id: props.appointment.user_id,
            status: props.appointment.status
        }, { timeout: 10000 });
        if (res.data) {
            toast.success('Appointment scheduled successfully!');
            emit('updated', editedAppointment.value);
            window.location.reload()
            closeModal();
            isEditing.value = false;
        }
    } catch (err) {
        toast.error(err);
    } finally {
        isLoading.value = false;
    }
};
</script>