<template>
    <section class="bg-gray-50 pt-36 pb-24 px-6">
        <div class="text-center mb-12">
            <h1 class="text-4xl font-extrabold text-gray-800">Our Pricing Plans</h1>
            <p class="text-gray-600 mt-2">Choose a plan that fits your needs</p>
        </div>

        <!-- Plan period buttons -->
        <div class="max-w-[1300px] mx-auto w-full flex items-center gap-0 justify-center ">
            <div class="w-fit">
                <button v-for="planPeriod in planPeriods" :key="planPeriod" @click="updatePlanPeriod(planPeriod)"
                    :class="['px-2 text-sm sm:text-base sm:px-4 py-2 focus:outline-none focus:ring-2 focus:ring-teal-500 mx-px 2',
                        planPeriod.active ? 'bg-white text-black ring-2' : ' bg-teal-600 hover:bg-teal-700 text-white']">
                    {{ planPeriod.name }} Months
                </button>
            </div>
        </div>
        <!-- <ResponseMessage :response="response" :class="bg" /> -->
        <div class="grid max-w-sm grid-cols-1 gap-6 mx-auto mt-8 text-left md:mt-16 md:max-w-6xl md:grid-cols-3">
            <div v-for="(plan, index) in allPlans" :key="index"
                class="overflow-hidden bg-white border border-gray-200 rounded-2xl">
                <div class="p-6 lg:px-8 lg:py-8">
                    <h2 class="text-xl md:text-2xl font-bold text-teal-600 mb-4">{{ plan.name }} Plan</h2>
                    <!-- <p class="text-gray-600 mb-4">{{ plan.description }}</p> -->
                    <div class="flex items-center gap-2">
                        <p class="text-4xl font-medium text-gray-800">
                            &#8358;{{ formatPrice(plan.billing_cycle[0].price * selectedPeriod) }}
                        </p>
                        <p class="text-gray-500 font-medium text-2xl line-through">
                            <!-- {{ plan.price }} -->
                            &#8358;{{ formatPrice(getRealPrice(plan.name) * selectedPeriod) }}
                        </p>
                    </div>
                    <p class="text-sm text-gray-500 mt-1">Billing cycle: {{ selectedPeriod }} months</p>
                    <button @click="choosePlan(plan, index)" class="       inline-flex
                            items-center
                            justify-center
                            px-8
                            py-3.5
                            w-full
                            mt-8
                            text-base
                            font-bold
                            text-gray-900
                            transition-all
                            duration-200
                            border-2 border-teal-600
                            rounded-xl
                            focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-teal-600
                            hover:bg-teal-600
                            focus:bg-teal-600
                            hover:text-white
                            focus:text-white
                            hover:border-teal-600
                            focus:border-teal-600">
                        Choose Plan
                    </button>
                    <p class="mt-8 text-base font-bold text-gray-900 font-pj">What's included:</p>
                    <ul class="mt-6 space-y-3 text-medium text-base text-gray-600">
                        <li v-for="(feature, index) in plan.features" :key="index" class="flex items-center">
                            <svg class="w-6 h-6 mr-4 text-teal-600" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 13l4 4L19 7"></path>
                            </svg>
                            {{ feature.feature_name }}
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </section>
</template>

<script setup>
import ResponseMessage from "@/Components/ResponseMessage.vue";
import { onMounted, ref } from "vue";
import axios from "axios";
import { usePage } from '@inertiajs/vue3';
import { router } from '@inertiajs/vue3';
const page = usePage();
let props = defineProps({
    plans: Array,
})
const formatPrice = (price) => {
    return price.toLocaleString('en-NG');
};
let allPlans = ref(props.plans)
let planPeriods = ref([
    { name: 1, active: false },
    { name: 3, active: false },
    { name: 6, active: false },
    { name: 12, active: false }
])
const realPrice = ref([
    { name: 'Single', price: 2000 },
    { name: 'Partner Care', price: 2500 },
    { name: 'Family', price: 3500 },
])
const getRealPrice = (planName) => {
    const plan = realPrice.value.find(p => p.name === planName);
    return plan ? plan.price : 3000;
};
let selectedPeriod = ref(1)
onMounted(() => {
    planPeriods.value[0].active = true
})
let updatePlanPeriod = (period) => {
    for (const planPeriod of planPeriods.value) {
        planPeriod.active = false
    }
    selectedPeriod.value = period.name;
    period.active = true
};

let response = ref()
let bg = ref()

let choosePlan = (plan, index) => {
    if (!page.props.auth.user) {
        router.visit('/login');
        return;
    }
    const user_id = page.props.auth.user.id;
    const updatedPlan = {
        ...plan,
        billing_cycle: [{
            ...plan.billing_cycle[0],
            price: plan.billing_cycle[0].price * selectedPeriod.value,
            duration: selectedPeriod.value
        }]
    };

    axios.post(`/patient-choose-plan/${plan.id}/${user_id}`, { updatedPlan })
        .then(res => {
            if (res.data.message) {
                response.value = res.data.message;
                window.location.href = res.data.url;
                // window.location.href = response.data.authorization_url;
                bg.value = 'bg-green-400'
                setTimeout(() => {
                    response.value = '';
                    bg.value = ''
                }, 3000);
            }
        })
        .catch(err => {
            if (err) {
                response.value = "Couldn't save Plan \n Please make sure Plan doesn't already exist";
                bg.value = 'bg-red-400'
                setTimeout(() => {
                    response.value = '';
                    bg.value = ''
                }, 3000);
            }
        });
};
</script>