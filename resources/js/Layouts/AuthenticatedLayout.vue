<template>
    <div class="flex relative">


        <!-- Sidebar Section -->
        <div @click="toggleSidebar" :class="[' absolute h-screen w-screen bg-gray-600 z-40  lg:hidden opacity-20',
            isSidebarOpen ? 'translate-x-0' : '-translate-x-full'
        ]">

        </div>
        <div :class="[
            'overflow-y-auto  fixed p-6 z-50 bg-gray-700 flex h-screen flex-col items-start justify-between transition-transform duration-300 lg:translate-x-0',
            isSidebarOpen ? 'translate-x-0' : '-translate-x-full',
            'w-72'
        ]">
            <!-- Render Sidebars Based on Role -->
            <AdminSideBar :adminLinks="adminLinks" v-if="page.props.auth.user.user_role === 'admin'" />

            <DoctorsSideBar :doctorLinks="doctorLinks" v-else-if="page.props.auth.user.user_role === 'doctor'" />

            <NurseSideBar :nurseLinks="nurseLinks" v-else-if="page.props.auth.user.user_role === 'nurse'" />

            <PatientSideBar :patientLinks="patientLinks" :planHasExpired="planHasExpired" v-else />
            <Logout />
        </div>

        <!-- Sidebar Toggle Button (Visible on Mobile/Tablet Only) -->
        <button
            class="lg:hidden fixed top-4 left-4 bg-gray-700 text-white p-2 rounded-full z-50 shadow-md focus:outline-none"
            @click="toggleSidebar">
            <!-- Icon: Hamburger Menu or Close -->
            <span v-if="!isSidebarOpen">☰</span>
            <span v-else>✕</span>
        </button>


        <!-- Main Content -->
        <div class="relative px-3 py-12 m-0  min-h-screen w-full transition-all duration-300"
            :class="{ 'ml-0': !isSidebarOpen && !isLargeScreen, 'lg:ml-72': true }">
            <PlanHasExpiredNotification v-show="planHasExpired == 0" />
            <slot :plan-has-expired="planHasExpired"></slot>
        </div>
    </div>
</template>

<script setup>
import Logout from "@/Components/Logout.vue";
import DoctorsSideBar from "@/Components/DoctorsSideBar.vue";
import PatientSideBar from "@/Components/PatientSideBar.vue";
import AdminSideBar from "@/Components/AdminSideBar.vue";
import NurseSideBar from "@/Components/NurseSideBar.vue";
import { ref, onMounted } from "vue";
import axios from "axios";
import Plans from "@/Components/Plans.vue";
import PlanHasExpiredNotification from "@/Components/PlanHasExpiredNotification.vue";
import { usePage } from '@inertiajs/vue3';
const page = usePage();
let widthHight = ref('w-3 h-3')
let strokeColor = ref('white')
const doctorLinks = ref([
    {
        name: "doctor-dashboard-overview",
        active: false,
        icon: `<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 3v6.75h6.75V3H3.75zm0 10.5V21h6.75v-6.75H3.75zm10.5-10.5V21h6.75V3h-6.75zM14.25 14.25v6.75h6.75v-6.75h-6.75z" /></svg>`
    },
    {
        name: "doctor-view-all-users",
        active: false,
        icon: `<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 3v6.75h6.75V3H3.75zm0 10.5V21h6.75v-6.75H3.75zm10.5-10.5V21h6.75V3h-6.75zM14.25 14.25v6.75h6.75v-6.75h-6.75z" /></svg>`
    },
    {
        name: "doctor-appointments",
        active: false,
        icon: `<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6"><path stroke-linecap="round" stroke-linejoin="round" d="M6 3.75V6M18 3.75V6M3 10.5h18M6.75 10.5v9.75m10.5-9.75v9.75m-5.25-9.75v9.75" /></svg>`
    },
    // {
    //     name: "doctor-prescription-management",
    //     active: false,
    //     icon: `<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6"><path stroke-linecap="round" stroke-linejoin="round" d="M7.5 3.75h9M7.5 3.75v16.5m9-16.5v16.5M3 9h18M3 15h18" /></svg>`
    // },
    {
        name: "doctor-messages",
        active: false,
        icon: `<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 5.25h16.5m-16.5 6h16.5m-16.5 6h16.5" /></svg>`
    },
    {
        name: "doctor-medical-records",
        active: false,
        icon: `<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="${strokeColor.value}" :class="${widthHight.value}">
        <path stroke-linecap="round" stroke-linejoin="round" d="M9 3H7a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V5a2 2 0 0 0-2-2h-2M9 3a3 3 0 1 1 6 0M9 3h6M9 8h6M9 12h3" />
    </svg>`
    },
    {
        name: "doctor-shared-files",
        active: false,
        icon: `<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="${strokeColor.value}" :class="${widthHight.value}">
        <path stroke-linecap="round" stroke-linejoin="round" d="M7.217 10.907a2.25 2.25 0 100 2.186m0-2.186c.18.324.283.696.283 1.093s-.103.77-.283 1.093m0-2.186l9.566-5.314m-9.566 7.5l9.566 5.314m0 0a2.25 2.25 0 103.935 2.186 2.25 2.25 0 00-3.935-2.186zm0-12.814a2.25 2.25 0 103.933-2.185 2.25 2.25 0 00-3.933 2.185z" />
    </svg>`
    },
    // {
    //     name: "doctor-profile-and-availability",
    //     active: false,
    //     icon: `<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6"><path stroke-linecap="round" stroke-linejoin="round" d="M12 3.75A5.25 5.25 0 0117.25 9v6a5.25 5.25 0 01-10.5 0V9A5.25 5.25 0 0112 3.75z" /></svg>`
    // }
]);
const patientLinks = ref([
    {
        name: "patient-dashboard-overview",
        active: false,
        icon: `<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="${strokeColor.value}" :class="${widthHight.value}"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 3v6.75h6.75V3H3.75zm0 10.5V21h6.75v-6.75H3.75zm10.5-10.5V21h6.75V3h-6.75zM14.25 14.25v6.75h6.75v-6.75h-6.75z" /></svg>`
    },
    {
        name: "patient-book-appointment",
        active: false,
        icon: `<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="${strokeColor.value}" :class="${widthHight.value}"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 12L3 16.5M21 16.5L15.75 12M3 7.5l5.25 4.5m5.25 4.5L21 7.5" /></svg>`
    },
    {
        name: "patient-my-appointments",
        active: false,
        icon: `<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="${strokeColor.value}" :class="${widthHight.value}"><path stroke-linecap="round" stroke-linejoin="round" d="M6 3.75V6M18 3.75V6M3 10.5h18M6.75 10.5v9.75m10.5-9.75v9.75m-5.25-9.75v9.75" /></svg>`
    },
    {
        name: "patient-medical-history",
        active: false,
        icon: `<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="${strokeColor.value}" :class="${widthHight.value}"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8.25v7.5m3.75-3.75h-7.5M12 21V3m7.5 9H3" /></svg>`
    },
    {
        name: "patient-medical-records",
        active: false,
        icon: `<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="${strokeColor.value}" :class="${widthHight.value}"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8.25v7.5m3.75-3.75h-7.5M12 21V3m7.5 9H3" /></svg>`
    },
    {
        name: "patient-follow-ups",
        active: false,
        icon: `<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="${strokeColor.value}" :class="${widthHight.value}"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25zM6.75 12h.008v.008H6.75V12zm0 3h.008v.008H6.75V15zm0 3h.008v.008H6.75V18z" /></svg>`
    },
    {
        name: "patient-prescriptions",
        active: false,
        icon: `<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="${strokeColor.value}" :class="${widthHight.value}"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9l-3-3-3 3M15 21v-8.25m-6 0V21m-3-12h12" /></svg>`
    },
    {
        name: "patient-billing-and-payments",
        active: false,
        icon: `<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="${strokeColor.value}" :class="${widthHight.value}"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 4.5h15m0 0v15m0-15l-15 15" /></svg>`
    },
    {
        name: "patient-messages",
        active: false,
        icon: `<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="${strokeColor.value}" :class="${widthHight.value}"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 21L9 16.5M21 3.75L15.75 9M3 12h18M3 7.5h18M3 16.5h18" /></svg>`
    },
    {
        name: "patient-available-plans",
        active: false,
        icon: `<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="${strokeColor.value}" :class="${widthHight.value}"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 21L9 16.5M21 3.75L15.75 9M3 12h18M3 7.5h18M3 16.5h18" /></svg>`
    },
    {
        name: "patient-profile",
        active: false,
        icon: `<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="${strokeColor.value}" :class="${widthHight.value}"><path stroke-linecap="round" stroke-linejoin="round" d="M12 3.75A5.25 5.25 0 0117.25 9v6a5.25 5.25 0 01-10.5 0V9A5.25 5.25 0 0112 3.75z" /></svg>`
    },
    {
        name: "patient-files",
        active: false,
        icon: `<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="${strokeColor.value}" :class="${widthHight.value}"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" /></svg>`
    }
]);
const adminLinks = ref([
    {
        name: "admin-fetch-appointments",
        active: false,
        icon: `<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6"><path stroke-linecap="round" stroke-linejoin="round" d="M6 3.75V6M18 3.75V6M3 10.5h18M6.75 10.5v9.75m10.5-9.75v9.75m-5.25-9.75v9.75" /></svg>`
    },
    {
        name: "admin-users",
        active: false,
        icon: `<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6"><path stroke-linecap="round" stroke-linejoin="round" d="M12 3.75A5.25 5.25 0 0117.25 9v6a5.25 5.25 0 01-10.5 0V9A5.25 5.25 0 0112 3.75z" /></svg>`
    },
    {
        name: "admin-add-user",
        active: false,
        icon: `<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>`
    },
    {
        name: "admin-plans",
        active: false,
        icon: `<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6"><path stroke-linecap="round" stroke-linejoin="round" d="M12 3.75A5.25 5.25 0 0117.25 9v6a5.25 5.25 0 01-10.5 0V9A5.25 5.25 0 0112 3.75z" /></svg>`
    },
    {
        name: "admin-add-plan",
        active: false,
        icon: `<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6"><path stroke-linecap="round" stroke-linejoin="round" d="M3 12h18M3 16.5h18M3 7.5h18" /></svg>`
    },
    {
        name: "admin-add-department",
        active: false,
        icon: `<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>`
    },
    {
        name: "admin-referral",
        active: false,
        icon: `<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6"><path stroke-linecap="round" stroke-linejoin="round" d="M13.19 8.688a4.5 4.5 0 011.242 7.244l-4.5 4.5a4.5 4.5 0 01-6.364-6.364l1.757-1.757m13.35-.622l1.757-1.757a4.5 4.5 0 00-6.364-6.364l-4.5 4.5a4.5 4.5 0 001.242 7.244" /></svg>`
    }
]);
const nurseLinks = ref([
    {
        name: "nurse-dashboard",
        icon: `<svg xmlns=\"http://www.w3.org/2000/svg\" fill=\"none\" viewBox=\"0 0 24 24\" stroke-width=\"1.5\" stroke=\"currentColor\" class=\"w-6 h-6\"><path stroke-linecap=\"round\" stroke-linejoin=\"round\" d=\"M3.75 3v6.75h6.75V3H3.75zm0 10.5V21h6.75v-6.75H3.75zm10.5-10.5V21h6.75V3h-6.75zM14.25 14.25v6.75h6.75v-6.75h-6.75z\" /></svg>`
    },
    {
        name: "nurse-follow-ups",
        icon: `<svg xmlns=\"http://www.w3.org/2000/svg\" fill=\"none\" viewBox=\"0 0 24 24\" stroke-width=\"1.5\" stroke=\"currentColor\" class=\"w-6 h-6\"><path stroke-linecap=\"round\" stroke-linejoin=\"round\" d=\"M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25zM6.75 12h.008v.008H6.75V12zm0 3h.008v.008H6.75V15zm0 3h.008v.008H6.75V18z\" /></svg>`
    }
]);
const isSidebarOpen = ref(false);
const isLargeScreen = ref(false);

let planHasExpired = ref('')
const checkIfPlanExpires = () => {
    if (page.props.auth?.user && page.props.auth.user.user_role === 'patient') {
        axios.get('/check-if-patient-plan-expires'
        )
            .then(response => {
                if (response.data.plan_details) {
                    planHasExpired.value = response.data.plan_details.status == 0 ? 0 : 1;
                } else {
                    planHasExpired.value = 0;
                }
            })
            .catch(error => {
                console.error('Plan check error:', error.response?.data, error.response?.status);
                planHasExpired.value = 0;
            });
    } else {
        // console.log('No authenticated patient user found.');
    }
};
checkIfPlanExpires();
const toggleSidebar = () => {
    isSidebarOpen.value = !isSidebarOpen.value;
};

const checkScreenSize = () => {
    isLargeScreen.value = window.innerWidth >= 1024;
    if (isLargeScreen.value) {
        isSidebarOpen.value = true;
    } else {
        isSidebarOpen.value = false;
    }
};
onMounted(() => {
    checkScreenSize();
    window.addEventListener("resize", checkScreenSize);
});
</script>
