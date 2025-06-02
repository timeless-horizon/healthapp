<template>
    <div class="min-h-screen mt-20  py-12">
        <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white shadow overflow-hidden rounded-lg">
                <div class="px-4 py-5 sm:px-6 bg-red-500">
                    <div class="flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-white" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                    </div>
                    <h3 class="mt-2 text-xl leading-6 font-medium text-white text-center">Payment Failed</h3>
                </div>
                <div class="px-4 py-5 sm:p-6">
                    <div class="space-y-6">
                        <div class="text-center">
                            <p class="text-gray-700">
                                We're sorry, but there was an issue processing your payment.
                            </p>
                            <p v-if="error" class="mt-2 text-red-600">
                                Error: {{ error }}
                            </p>
                        </div>

                        <div v-if="invoice" class="border-t border-gray-200 pt-4">
                            <h4 class="text-lg font-medium text-gray-900">Payment Details</h4>
                            <dl class="mt-2 divide-y divide-gray-200">
                                <div class="py-3 flex justify-between">
                                    <dt class="text-sm font-medium text-gray-500">Invoice Number</dt>
                                    <dd class="text-sm font-medium text-gray-900">{{ invoice.invoice_number }}</dd>
                                </div>
                                <div class="py-3 flex justify-between">
                                    <dt class="text-sm font-medium text-gray-500">Amount</dt>
                                    <dd class="text-sm font-medium text-gray-900">{{ invoice.currency }} {{
                                        invoice.amount }}</dd>
                                </div>
                            </dl>
                        </div>
                    </div>

                    <div class="mt-8 pb-6 flex justify-center items-center space-x-4">
                        <!-- <button @click="retryPayment"
                            class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Try Again
                        </button> -->
                        <Link href="/dashboard" class=" text-gray-600 hover:text-blue-700 dark:hover:text-blue-500">
                        Return to
                        Dashboard</Link>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script setup>
import { Link } from '@inertiajs/vue3';
</script>
<script>
export default {
    props: {
        user: Object,
        invoice: Object,
        error: String
    },
    methods: {
        retryPayment() {
            if (this.invoice) {
                this.$inertia.visit(route('payment.index'), {
                    data: {
                        invoice: {
                            invoice_number: this.invoice.invoice_number
                        }
                    }
                });
            } else {
                this.$inertia.visit(route('payment.index'));
            }
        }
    }
};
</script>