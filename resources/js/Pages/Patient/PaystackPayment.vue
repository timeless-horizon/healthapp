<template>
    <AuthenticatedLayout>
        <div class="container mx-auto p-6">
            <div class="bg-white shadow-lg rounded-lg overflow-hidden">

                <!-- Header Section -->
                <div class="bg-teal-900 text-white text-center p-4">
                    <h1 class="text-2xl font-semibold">Payment Details</h1>
                    <p class="text-lg">Please review your plan and payment details below</p>
                </div>

                <!-- User Info Section -->
                <div class="p-6 bg-gray-50">
                    <h2 class="text-xl font-semibold mb-4">User Information</h2>
                    <table class="min-w-full table-auto">
                        <thead>
                            <tr>
                                <th class="px-4 py-2 text-left text-gray-700">Field</th>
                                <th class="px-4 py-2 text-left text-gray-700">Details</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="px-4 py-2 font-medium">Full Name</td>
                                <td class="px-4 py-2">{{ user.surname + ' ' + user.otherNames }}</td>
                            </tr>
                            <tr>
                                <td class="px-4 py-2 font-medium">Phone Number</td>
                                <td class="px-4 py-2">{{ user.tel }}</td>
                            </tr>
                            <tr>
                                <td class="px-4 py-2 font-medium">Email</td>
                                <td class="px-4 py-2">{{ user.email }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Plan Info Section -->
                <div class="p-6">
                    <h2 class="text-xl font-semibold mb-4">Plan Information</h2>
                    <table class="min-w-full table-auto border-separate border-spacing-2">
                        <thead>
                            <tr>
                                <th class="px-4 py-2 text-left text-gray-700">Field</th>
                                <th class="px-4 py-2 text-left text-gray-700">Details</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="px-4 py-2 font-medium">Plan Name</td>
                                <td class="px-4 py-2">{{ invoice.name }}</td>
                            </tr>
                            <tr>
                                <td class="px-4 py-2 font-medium">Duration</td>
                                <td class="px-4 py-2">{{ invoice.duration }} months</td>
                            </tr>
                            <tr>
                                <td class="px-4 py-2 font-medium">Invoice Number</td>
                                <td class="px-4 py-2">{{ invoice.invoice_number }}</td>
                            </tr>
                            <tr>
                                <td class="px-4 py-2 font-medium">Invoice Generated on</td>
                                <td class="px-4 py-2">{{ invoice.generated_at }}</td>
                            </tr>
                            <tr>
                                <td class="px-4 py-2 font-medium">Due Date</td>
                                <td class="px-4 py-2">{{ invoice.due_date }}</td>
                            </tr>
                            <tr>
                                <td class="px-4 py-2 font-medium">Amount</td>
                                <td class="px-4 py-2 text-teal-900 font-semibold">{{ invoice.amount }} {{
                                    invoice.currency }}</td>
                            </tr>
                            <tr>
                                <td class="px-4 py-2 font-medium">Status</td>
                                <td class="px-4 py-2">
                                    <span :class="{
                                        'text-green-600': invoice.status === 'Paid',
                                        'text-red-600': invoice.status === 'unpaid',
                                    }" class="font-semibold">{{ invoice.status }}</span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Footer Section with Payment Button -->
                <div class="p-6 bg-teal-900 text-white text-center">
                    <button @click="payWithPaystack" :disabled="loading"
                        class="bg-yellow-500 text-black px-6 py-2 rounded-lg font-semibold transition duration-300"
                        :class="{ 'bg-yellow-400 cursor-not-allowed': loading, 'hover:bg-yellow-400': !loading }">
                        <span v-if="loading">Redirecting to paystack...</span>
                        <span v-else>Proceed with Payment</span>
                    </button>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
<script setup>
import { ref } from 'vue';
import axios from 'axios';
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";

const email = ref(''); // Declare reactive variable for email
let props = defineProps({
    invoice: Object,
    user: Object,
});
const loading = ref(false);
const payWithPaystack = async () => {
    loading.value = true;

    const payload = {
        user: props.user,
        invoice: props.invoice
    };
    try {
        const response = await axios.post('/pay', payload);
        if (response.data.authorization_url) {
            window.location.href = response.data.authorization_url;
        }
    } catch (error) {
        alert("Payment failed: " + error.response.data.error);
    } finally {
        loading.value = false;
    }
};
</script>
<style scoped>
.container {
    max-width: 900px;
}

table {
    width: 100%;
    border-collapse: collapse;
}

th,
td {
    border: 1px solid #e0e0e0;
}

th {
    background-color: #f7f7f7;
    text-align: left;
}

td {
    padding: 10px;
    text-align: left;
}
</style>
