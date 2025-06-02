<template>

  <Head title="FAQ" />
  <GuestLayout>
    <div class="bg-blue-50 min-h-screen pt-36 pb-12 px-6 lg:px-20">
      <div class="max-w-4xl mx-auto bg-white shadow-lg rounded-lg p-6 md:p-10">
        <!-- Header Section -->
        <h2 class="text-3xl font-bold text-center text-blue-600">Timeless Healthcare FAQ</h2>
        <p class="text-gray-600 text-center mt-2">Find answers to the most frequently asked questions about our
          services.
        </p>

        <!-- FAQ Section -->
        <div class="mt-8 space-y-4">
          <div v-for="(faq, index) in faqs" :key="index" class="border-b border-gray-200 pb-4">
            <button @click="toggleFAQ(index)"
              class="w-full flex justify-between items-center text-left text-lg font-semibold text-blue-600 py-3 focus:outline-none">
              {{ faq.question }}
              <span :class="{ 'rotate-180': faq.open }" class="transition-transform duration-300">
                ▼
              </span>
            </button>
            <p v-if="faq.open" class="text-gray-700 mt-2 text-sm">{{ faq.answer }}</p>
          </div>
        </div>
      </div>
    </div>
  </GuestLayout>
</template>
<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import { Head } from '@inertiajs/vue3';
</script>
<script>
export default {
  data() {
    return {
      faqs: [
        { question: "What services does Timeless Healthcare offer?", answer: "Timeless Healthcare offers telemedicine consultations, prescription refills, laboratory services, medication delivery, and secure medical records management.", open: false },
        { question: "How do I register for the Timeless web app?", answer: "You can register for the Timeless app by visiting our website at https://timelesshealthcare247.com/register and following the simple registration process.", open: false },
        { question: "Can I speak with a doctor anytime?", answer: "Yes, you can connect with certified doctors anytime and anywhere through our telemedicine web app.", open: false },
        { question: "How do I find a doctor on the Timeless web app?", answer: "After logging in, enter your symptoms in the search criteria, and we will connect you with the right specialist.", open: false },
        { question: "Is there a cost associated with using Timeless Healthcare?", answer: "Timeless Healthcare aims to provide affordable healthcare options. Specific costs may vary based on the services and duration you choose.", open: false },
        { question: "How can I get my prescriptions refilled?", answer: "You can request prescription refills through the Timeless web app, allowing you to manage your medications from home.", open: false },
        { question: "What should I do if I have a medical emergency?", answer: "In case of a medical emergency, please call your local emergency services or go to the nearest hospital.", open: false },
        { question: "How does Timeless ensure the privacy of my medical records?", answer: "We take data protection seriously. Your medical data is stored securely, and we use end-to-end encryption to protect your information.", open: false },
        { question: "Can I access my medical records anytime?", answer: "Yes, you can access your medical records anytime through the Timeless app.", open: false },
        { question: "How do I contact customer support?", answer: "You can contact our customer support team by visiting https://timelesshealthcare247.com/contact and filling out the contact form OR connect with us on our social media channel, and we will be right there to assist.", open: false },
      ],
    };
  },
  methods: {
    toggleFAQ(index) {
      this.faqs[index].open = !this.faqs[index].open;
    },
  },
};
</script>

<style scoped>
/* Smooth transition for opening/closing FAQs */
button {
  transition: all 0.3s ease;
}
</style>