<template>

  <Head title="Pricing" />
  <GuestLayout>
    <Plans :plans="plans" />
  </GuestLayout>
</template>

<script setup>

import GuestLayout from "@/Layouts/GuestLayout.vue";
import { ref } from "vue";
import { Head } from '@inertiajs/vue3';


import Plans from "@/Components/Plans.vue";
const packages = ref([
  {
    title: "24/7 Access",
    description: "Talk to our medical practitioners anytime you need support.",
    image: "",
  },
  {
    title: "Unlimited Calls",
    description: "Enjoy unlimited monthly calls with doctors and care practitioners.",
    image: "",
  },
  {
    title: "Follow-ups & Support",
    description: "Get regular check-ins and health monitoring.",
    image: "",
  },
  {
    title: "Private Recommendations",
    description: "Receive private doctor recommendations securely.",
    image: "",
  },
  {
    title: "Dedicated Care",
    description: "Access professionals for personalized healthcare.",
    image: "",
  },
  {
    title: "Medication & Refills",
    description: "Prompt drug prescriptions and scheduled refills.",
    image: "",
  },
]);

let props = defineProps({
  plans: Array
})
</script>


<style scoped>
@keyframes fade-in {
  from {
    opacity: 0;
    transform: translateY(20px);
  }

  to {
    opacity: 1;
    transform: translateY(0);
  }
}

.animate-fade-in {
  animation: fade-in 1s ease-out;
}
</style>
``