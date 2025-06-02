<template>
    <AuthenticatedLayout>
    <div class="max-w-2xl mx-auto p-8 bg-white shadow-lg rounded-lg border border-gray-300">
        <div class="flex justify-end w-full my-3 gap-4">
            <button @click="printReceipt" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Print</button>
            <button @click="downloadReceipt" class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">Download</button>
        </div>
<div id="receipt">
        <!-- Header Section -->
        <div class="bg-blue-900 text-white text-center p-4">
            <h1 class="text-2xl font-semibold">Payment Successful</h1>
            <p class="text-lg">Your payment was successfully processed. Below are your payment details.</p>
        </div>
        <table class="w-full border-collapse border border-gray-300 mb-6">
            <thead>
            <tr class="bg-gray-100">
                <th class="border border-gray-300 px-4 py-2 text-left">Field</th>
                <th class="border border-gray-300 px-4 py-2 text-left">Details</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td class="border border-gray-300 px-4 py-2 font-semibold">Name</td>
                <td class="border border-gray-300 px-4 py-2">{{ user.surname }} {{ user.otherNames }}</td>
            </tr>
            <tr>
                <td class="border border-gray-300 px-4 py-2 font-semibold">Email</td>
                <td class="border border-gray-300 px-4 py-2">{{ user.email }}</td>
            </tr>
            <tr>
                <td class="border border-gray-300 px-4 py-2 font-semibold">Phone</td>
                <td class="border border-gray-300 px-4 py-2">{{ user.tel }}</td>
            </tr>
            <tr>
                <td class="border border-gray-300 px-4 py-2 font-semibold">Address</td>
                <td class="border border-gray-300 px-4 py-2">{{ user.address }}</td>
            </tr>
            <tr>
                <td class="border border-gray-300 px-4 py-2 font-semibold">Country</td>
                <td class="border border-gray-300 px-4 py-2">{{ user.country }}</td>
            </tr>
            <tr>
                <td class="border border-gray-300 px-4 py-2 font-semibold">State</td>
                <td class="border border-gray-300 px-4 py-2">{{ user.state }}</td>
            </tr>
            <tr class="bg-gray-100">
                <td class="border border-gray-300 px-4 py-2 font-semibold">Invoice No</td>
                <td class="border border-gray-300 px-4 py-2">{{ invoice.invoice_number }}</td>
            </tr>
            <tr>
                <td class="border border-gray-300 px-4 py-2 font-semibold">Amount</td>
                <td class="border border-gray-300 px-4 py-2">₦{{ invoice.amount }}</td>
            </tr>
            <tr>
                <td class="border border-gray-300 px-4 py-2 font-semibold">Currency</td>
                <td class="border border-gray-300 px-4 py-2">{{ invoice.currency }}</td>
            </tr>
            <tr>
                <td class="border border-gray-300 px-4 py-2 font-semibold">Status</td>
                <td class="border border-gray-300 px-4 py-2" :class="statusClass">{{ invoice.status }}</td>
            </tr>
            <tr>
                <td class="border border-gray-300 px-4 py-2 font-semibold">Payment Method</td>
                <td class="border border-gray-300 px-4 py-2">{{ invoice.payment_method }}</td>
            </tr>
            <tr>
                <td class="border border-gray-300 px-4 py-2 font-semibold">Reference</td>
                <td class="border border-gray-300 px-4 py-2">{{ invoice.reference }}</td>
            </tr>
            <tr>
                <td class="border border-gray-300 px-4 py-2 font-semibold">Paid At</td>
                <td class="border border-gray-300 px-4 py-2">{{ formatDate(invoice.paid_at) }}</td>
            </tr>
            <tr>
                <td class="border border-gray-300 px-4 py-2 font-semibold">Due Date</td>
                <td class="border border-gray-300 px-4 py-2">{{ formatDate(invoice.due_date) }}</td>
            </tr>
            </tbody>
        </table>

</div>
    </div>
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import html2canvas from "html2canvas";
import jsPDF from "jspdf";

import { computed } from 'vue';

const props = defineProps({
    user: Object,
    invoice: Object
});

const statusClass = computed(() => {
    return props.invoice.status === 'paid' ? 'text-green-600 font-bold' : 'text-red-600 font-bold';
});

const formatDate = (dateString) => {
    const options = { year: 'numeric', month: 'long', day: 'numeric', hour: '2-digit', minute: '2-digit' };
    return new Date(dateString).toLocaleDateString('en-US', options);
};

const printReceipt = () => {
    window.print();
};

    const downloadReceipt = async () => {
    const element = document.getElementById("receipt");
    if (!element) return;

    const canvas = await html2canvas(element);
    const imgData = canvas.toDataURL("image/png");

    const pdf = new jsPDF("p", "mm", "a4");
    const imgWidth = 190;
    const imgHeight = (canvas.height * imgWidth) / canvas.width;
    pdf.addImage(imgData, "PNG", 10, 10, imgWidth, imgHeight);
    pdf.save("THC-payment-receipt.pdf");
};

</script>
