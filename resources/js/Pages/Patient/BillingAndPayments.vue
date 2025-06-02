<template>
    <AuthenticatedLayout>
        <div class="flex flex-col p-4">
            <h1 class="text-3xl font-bold text-teal-700 mb-6">
                My invoices
            </h1>
            <div class="relative overflow-x-auto">
                <table class="w-full text-sm text-left text-gray-500 border overflow-x-scroll">
                    <thead class="bg-gray-200">
                        <tr>
                            <th scope="col" class="px-6 py-3">
                                Invoice #
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Invoice date
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Due date
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Amount
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Status
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="odd:bg-white even:bg-gray-50" v-for="transaction in transactions"
                            :key="transaction.id">
                            <td scope="row" class="px-4 py-4 font-normal text-sm text-gray-900">
                                {{ transaction.invoice_number }}
                            </td>
                            <td scope="row" class="px-4 py-4 font-normal text-sm text-gray-900">
                                {{ transaction.generated_at }}
                            </td>
                            <td scope="row" class="px-4 py-4 font-normal text-sm text-gray-900">
                                {{ transaction.due_date }}
                            </td>
                            <td scope="row" class="px-4 py-4 font-normal text-sm text-gray-900">
                                {{ transaction.amount }}
                            </td>
                            <td scope="row" class="px-4 py-4 font-normal text-sm text-gray-900">
                                <Link href="#"> {{ transaction.status }} </Link>

                            </td>
                        </tr>

                    </tbody>
                </table>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Link } from "../../../../.vite/deps/@inertiajs_vue3.js";

let props = defineProps({
    transactions: Object
})

</script>

<style scoped></style>
