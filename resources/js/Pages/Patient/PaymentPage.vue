<template>
    <AuthenticatedLayout>
    <div class="p-6 max-w-3xl mx-auto bg-white shadow-md rounded-lg">
        <h1 class="text-2xl font-bold text-gray-800">{{ plan.name }}</h1>
        <p class="text-gray-600">{{ plan.description }}</p>
        <div class="mt-4">
            <h2 class="text-lg font-semibold text-gray-700">Billing Cycle</h2>
            <ul class="list-disc pl-5">
                <li v-for="cycle in plan.billing_cycle" :key="cycle.id" class="text-gray-600">
                    Duration: {{ cycle.duration }} month(s) - Price: ₦{{ cycle.price }}
                </li>
            </ul>
        </div>

        <div class="mt-4">
            <h2 class="text-lg font-semibold text-gray-700">Features</h2>
            <ul class="list-disc pl-5">
                <li v-for="feature in plan.features" :key="feature.id" class="text-gray-600">
                    {{ feature.feature_name }}
                </li>
            </ul>

            <button

                @click="choosePlan(plan)"
                class="bg-teal-500 text-white font-semibold rounded-lg py-2 px-4 mt-6 hover:bg-teal-600 focus:outline-none focus:ring-4 focus:ring-teal-200 transition duration-300">
                Pay Now
            </button>

        </div>
    </div>


    </AuthenticatedLayout>
</template>

<script setup>
import { ref } from 'vue';
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
let props = defineProps({
    plan: Object,
})

</script>

