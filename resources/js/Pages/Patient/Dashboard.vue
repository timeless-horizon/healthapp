<template>
    <AuthenticatedLayout>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 cursor-pointer">
            <div v-for="(link, index) in links" :key="index"
                 class=" bg-white p-6 rounded-2xl shadow-md hover:shadow-xl transition-shadow flex flex-col items-center text-center">
                <div v-html="link.icon" class="w-10 h-10"></div>
                <h3 class="text-xl font-semibold text-gray-800 mb-2">{{ capitalizeFirstLetter(link.name) }}</h3>
                <Link :href="link.name" class="mt-4 px-6 py-2 bg-teal-900 text-white rounded-lg hover:bg-teal-800 transition">Visit</Link>
            </div>
        </div>
    </AuthenticatedLayout>
</template>


<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import {Link} from "@inertiajs/vue3";
import {onMounted, ref} from "vue";

let widthHight = ref('w-24 h-24')
let strokeColor = ref('teal')
const links = ref([
    {
        name: "patient-dashboard-overview",
        icon: `<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="${strokeColor.value}" :class="${widthHight.value}"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 3v6.75h6.75V3H3.75zm0 10.5V21h6.75v-6.75H3.75zm10.5-10.5V21h6.75V3h-6.75zM14.25 14.25v6.75h6.75v-6.75h-6.75z" /></svg>`
    },
    {
        name: "patient-book-appointment",
        icon: `<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="${strokeColor.value}" :class="${widthHight.value}"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 12L3 16.5M21 16.5L15.75 12M3 7.5l5.25 4.5m5.25 4.5L21 7.5" /></svg>`
    },
    {
        name: "patient-my-appointments",
        icon: `<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="${strokeColor.value}" :class="${widthHight.value}"><path stroke-linecap="round" stroke-linejoin="round" d="M6 3.75V6M18 3.75V6M3 10.5h18M6.75 10.5v9.75m10.5-9.75v9.75m-5.25-9.75v9.75" /></svg>`
    },
    {
        name: "patient-medical-records",
        icon: `<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="${strokeColor.value}" :class="${widthHight.value}"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8.25v7.5m3.75-3.75h-7.5M12 21V3m7.5 9H3" /></svg>`
    },
    {
        name: "patient-prescriptions",
        icon: `<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="${strokeColor.value}" :class="${widthHight.value}"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9l-3-3-3 3M15 21v-8.25m-6 0V21m-3-12h12" /></svg>`
    },
    {
        name: "patient-billing-and-payments",
        icon: `<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="${strokeColor.value}" :class="${widthHight.value}"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 4.5h15m0 0v15m0-15l-15 15" /></svg>`
    },
    {
        name: "patient-messages",
        icon: `<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="${strokeColor.value}" :class="${widthHight.value}"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 21L9 16.5M21 3.75L15.75 9M3 12h18M3 7.5h18M3 16.5h18" /></svg>`
    },
    {
        name: "patient-profile",
        icon: `<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="${strokeColor.value}" :class="${widthHight.value}"><path stroke-linecap="round" stroke-linejoin="round" d="M12 3.75A5.25 5.25 0 0117.25 9v6a5.25 5.25 0 01-10.5 0V9A5.25 5.25 0 0112 3.75z" /></svg>`
    },
    // {
    //     name: "patient-health-tracker",
    //     icon: `<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="${strokeColor.value}" :class="${widthHight.value}"><path stroke-linecap="round" stroke-linejoin="round" d="M3 12h18M3 16.5h18M3 7.5h18" /></svg>`
    // }
]);

let capitalizeFirstLetter = (str) => {
    let processLetter = str.replace(/[-_]/g, ' ')

    let words = processLetter.split(' ');
    words.shift();
    words = words.join(' ')
    words = words[0].charAt(0).toUpperCase() + words.slice(1)
    return words;
}


</script>

<style scoped>

</style>
