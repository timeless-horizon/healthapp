<template>
    <AuthenticatedLayout>
        <div class="max-w-4xl mx-auto p-10 bg-gray-50 shadow-md rounded-lg mt-20">
            <h1 class="text-2xl font-bold mb-4">Book an Appointment</h1>

            <div class="w-full flex items-center justify-end my-3">
                <Link href="patient-my-appointments">
                <button
                    class="bg-gray-900 text-white py-2 px-6 rounded-lg hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-teal-600">
                    All appointments
                </button>
                </Link>
            </div>

            <form @submit.prevent="submitForm">
                <div class="mb-4">
                    <select name="department" id="department" v-model="form.form.department"
                        class="p-2 border border-gray-300 rounded-md text-base w-full appearance-none bg-transparent cursor-pointer"
                        :class="{ 'border-red-500': form.errors['form.department'] }">
                        <option value="" disabled>Select department</option>
                        <option v-for="(department, index) in props.departments" :value="department.id" :key="index">
                            {{ department.name }}
                        </option>
                    </select>
                    <span v-if="form.errors['form.department']" class="text-red-500 text-sm">{{
                        form.errors['form.department'] }}</span>
                </div>

                <!-- <div class="mb-4">
                        <label for="clients_date_and_time" class="block text-sm font-medium text-gray-700">Pick a Date and
                            Time of your convenience</label>
                        <input type="datetime-local" v-model="form.form.clients_date_and_time" id="clients_date_and_time"
                            name="clients_date_and_time"
                            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                            :class="{ 'border-red-500': form.errors['form.clients_date_and_time'] }" />
                        <span v-if="form.errors['form.clients_date_and_time']" class="text-red-500 text-sm">{{
                            form.errors['form.clients_date_and_time'] }}</span>
                    </div> -->

                <div class="mb-4">
                    <label for="messege" class="block text-sm font-medium text-gray-700">Reason for
                        appointment</label>
                    <textarea v-model="form.form.messege" id="messege" name="messege"
                        class="h-24 mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                        :class="{ 'border-red-500': form.errors['form.messege'] }"></textarea>
                    <span class="text-red-500 text-sm animate-pulse">Ensure it's short and concise</span>
                    <span v-if="form.errors['form.messege']" class="text-red-500 text-sm">{{
                        form.errors['form.messege']
                        }}</span>
                </div>

                <div class="flex items-center justify-end">
                    <button type="submit"
                        class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                        :disabled="form.processing">
                        Submit
                    </button>
                </div>
            </form>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import { ref } from 'vue';
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Link, useForm } from "@inertiajs/vue3";
import { toast } from 'vue3-toastify';
import 'vue3-toastify/dist/index.css';

const form = useForm({
    form: {
        messege: '',
        department: '',
        // clients_date_and_time: '',
    },
});

let response = ref('');
let bg = ref('');

const props = defineProps({
    departments: Array,
});

function submitForm() {
    form.clearErrors();
    let hasErrors = false;

    if (!form.form.department) {
        form.setError('form.department', 'Please select a department');
        hasErrors = true;
    }
    // if (!form.form.clients_date_and_time) {
    //     form.setError('form.clients_date_and_time', 'Please select a date and time');
    //     hasErrors = true;
    // }
    if (!form.form.messege.trim()) {
        form.setError('form.messege', 'Please enter a reason for your appointment');
        hasErrors = true;
    }
    if (hasErrors) {
        return;
    }

    form.post('/patient-schedule-appointment', {
        preserveState: true,
        onSuccess: () => {
            toast.success('Appointment booked successfully!');
            form.reset();

            setTimeout(() => {
                window.location.href = '/patient-my-appointments';
            }, 3000);
        },
        onError: (errors) => {
            toast.error(errors);
            setTimeout(() => {
                response.value = '';
                bg.value = '';
            }, 3000);
        },
    });
}
</script>