<template>
    <div class=" min-h-1/2 flex items-center justify-center text-white mb-10">
        <div class="text-center max-w-lg p-6 bg-blue-800 rounded-lg shadow-lg">
            <h1 class="text-4xl font-bold mb-4">You have no active plan</h1>
            <p class="text-lg mb-6">It looks like you currently have no active subscription. Please subscribe to a plan to continue enjoying our services.</p>
            <div class="space-x-4">
                <Link href="/patient-available-plans">
                    <button class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-6 rounded-lg transition duration-300">
                        Renew Now
                    </button>
                </Link>
            </div>
        </div>
    </div>
</template>

<script setup>
import { Link } from "@inertiajs/vue3";
</script>