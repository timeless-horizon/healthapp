<template>
    <AuthenticatedLayout>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 cursor-pointer">
            <div v-for="(link, index) in links" :key="index"
                class=" bg-white p-6 rounded-2xl shadow-md hover:shadow-xl transition-shadow flex flex-col items-center text-center">
                <div v-html="link.icon" class="w-10 h-10"></div>
                <h3 class="text-xl font-semibold text-gray-800 mb-2">{{ capitalizeFirstLetter(link.name) }}</h3>
                <Link :href="link.name"
                    class="mt-4 px-6 py-2 bg-teal-900 text-white rounded-lg hover:bg-teal-800 transition">Visit</Link>
            </div>
        </div>
    </AuthenticatedLayout>
</template>


<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Link } from "@inertiajs/vue3";
import { onMounted, ref } from "vue";

let widthHight = ref('w-18 h-18')
let strokeColor = ref('teal')
const links = ref([
    {
        name: "doctor-dashboard-overview",
        icon: `<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="${strokeColor.value}" class="${widthHight.value}"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 3v6.75h6.75V3H3.75zm0 10.5V21h6.75v-6.75H3.75zm10.5-10.5V21h6.75V3h-6.75zM14.25 14.25v6.75h6.75v-6.75h-6.75z" /></svg>`
    },
    {
        name: "doctor-appointments",
        icon: `<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="${strokeColor.value}" class="${widthHight.value}"><path stroke-linecap="round" stroke-linejoin="round" d="M6 3.75V6M18 3.75V6M3 10.5h18M6.75 10.5v9.75m10.5-9.75v9.75m-5.25-9.75v9.75" /></svg>`
    },
    // {
    //     name: "doctor-prescription-management",
    //     icon: `<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="${strokeColor.value}" class="${widthHight.value}"><path stroke-linecap="round" stroke-linejoin="round" d="M7.5 3.75h9M7.5 3.75v16.5m9-16.5v16.5M3 9h18M3 15h18" /></svg>`
    // },
    {
        name: "doctor-messages",
        icon: `<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="${strokeColor.value}" class="${widthHight.value}"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 5.25h16.5m-16.5 6h16.5m-16.5 6h16.5" /></svg>`
    },
    {
        name: "doctor-shared-files",
        icon: `<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="${strokeColor.value}" class="${widthHight.value}"><path stroke-linecap="round" stroke-linejoin="round" d="M7.217 10.907a2.25 2.25 0 100 2.186m0-2.186c.18.324.283.696.283 1.093s-.103.77-.283 1.093m0-2.186l9.566-5.314m-9.566 7.5l9.566 5.314m0 0a2.25 2.25 0 103.935 2.186 2.25 2.25 0 00-3.935-2.186zm0-12.814a2.25 2.25 0 103.933-2.185 2.25 2.25 0 00-3.933 2.185z" /></svg>`
    },
    // {
    //     name: "doctor-profile-and-availability",
    //     icon: `<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="${strokeColor.value}" class="${widthHight.value}"><path stroke-linecap="round" stroke-linejoin="round" d="M12 3.75A5.25 5.25 0 0117.25 9v6a5.25 5.25 0 01-10.5 0V9A5.25 5.25 0 0112 3.75z" /></svg>`
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

<style scoped></style>
