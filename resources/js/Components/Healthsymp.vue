<template>
  <div class="bg-blue-600 text-white py-12 px-6 md:px-8 lg:px-12">
    <div class="max-w-[1300px] mx-auto">
      <!-- Health Conditions Card -->
      <div class="bg-white rounded-xl p-6 md:p-8 shadow-xl">
        <h2 class="text-2xl md:text-3xl font-bold text-gray-800 mb-6">Conditions We Manage</h2>

        <div class="mb-8">
          <p class="text-gray-600">Our experienced healthcare professionals provide comprehensive care for a wide range
            of health conditions.</p>
        </div>

        <!-- Conditions Grid -->
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-4 md:gap-6">
          <div v-for="(condition, index) in healthConditions" :key="index"
            class="flex flex-col items-center p-4 rounded-lg hover:bg-blue-50 transition-all duration-300">
            <div
              class="w-24 h-24 rounded-full overflow-hidden shadow-md bg-blue-100 flex items-center justify-center mb-3">
              <img :src="condition.image" :alt="condition.name" class="w-20 h-20 object-contain" />
            </div>
            <p class="text-base font-medium text-gray-700 text-center">
              {{ condition.name }}
            </p>
          </div>
        </div>

        <!-- Footer -->
        <div class="mt-8 pt-6 border-t border-gray-100 flex flex-col sm:flex-row justify-between items-center">
          <div class="flex items-center mb-4 sm:mb-0">
            <span class="bg-green-100 text-green-800 text-xs font-medium px-3 py-1 rounded-full">Available 24/7</span>
            <span class="ml-4 text-gray-500 text-sm">Professional Medical Advice</span>
          </div>
          <a href="/login"
            class="px-5 py-2 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition-colors duration-300">
            Schedule Consultation
          </a>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      healthConditions: [
        { name: "Respiratory Health", image: "https://i.ibb.co/LmS20Sv/Respiratory-health.png" },
        { name: "Digestive Wellness", image: "https://i.ibb.co/d4PKp5Gp/Digestive-welness.png" },
        { name: "Skin Care", image: "https://i.ibb.co/ksjjbnYw/Skin-rash-7690910.png" },
        { name: "Infectious Diseases", image: "https://i.ibb.co/TxmmXk47/infectous-deseas-3734017.png" },
        { name: "Women's Health", image: "https://i.ibb.co/sJw3jYkg/Women-health-check-2463946.png" },
        { name: "Lifestyle & Wellness", image: "https://i.ibb.co/6R2PRwm9/healthy-7587309.png" },
        { name: "Health, Diet & Exercise", image: "https://i.ibb.co/9mwYkG0G/wellness-program-17385049.png" },
        { name: "Musculoskeletal Health", image: "https://i.ibb.co/hFM5cJMz/oblique-6583977.png" },
        { name: "Chronic Conditions", image: "https://i.ibb.co/HDSjWzBj/illness-13067918.png" },
      ],
    };
  },
};
</script>