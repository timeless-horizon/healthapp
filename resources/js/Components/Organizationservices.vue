<template>
  <section class="max-w-[1300px] mx-auto w-full px-4 py-20 bg-white">
    <h2 class="text-3xl md:text-4xl font-bold text-center text-gray-900 mb-8">Services We Offer</h2>

    <!-- Tabs Navigation (Scrollable on Mobile) -->
    <div
      class="flex overflow-x-auto gap-3 md:justify-center w-full px-2 md:px-0 pb-2 scrollbar-thin scrollbar-thumb-gray-300">
      <button v-for="(tab, index) in tabs" :key="index" @click="activeTab = index"
        class="px-6 py-2 whitespace-nowrap font-semibold rounded-lg transition duration-300 flex-shrink-0 shadow-sm"
        :class="activeTab === index ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200'">
        {{ tab.name }}
      </button>
    </div>

    <!-- Tab Content with Animation -->
    <div class="mt-12 flex flex-col md:flex-row items-center md:gap-12 lg:gap-16">
      <div class="w-full md:w-1/2 flex justify-center">
        <transition name="fade" mode="out-in">
          <img :key="activeTab" :src="tabs[activeTab].image" :alt="`${tabs[activeTab].name} Service`"
            class="w-full rounded-lg shadow-lg object-cover h-72 md:h-80" />
        </transition>
      </div>
      <!-- Tab Description -->
      <div class="w-full md:w-1/2 text-center md:text-left px-4 md:px-0 mt-8 md:mt-0">
        <transition name="fade" mode="out-in">
          <div :key="activeTab" class="max-w-xl">
            <h3 class="text-xl md:text-2xl font-semibold text-gray-900">{{ tabs[activeTab].title }}</h3>
            <p class="mt-4 text-gray-600 leading-relaxed max-w-xl">{{ tabs[activeTab].description }}</p>
            <button
              class="mt-6 px-6 py-2 bg-blue-600 text-white font-semibold rounded-lg transition duration-300 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50">
              Learn More
            </button>
          </div>
        </transition>
      </div>
    </div>
  </section>
</template>

<script>
export default {
  data() {
    return {
      activeTab: 0,
      tabs: [
        {
          name: "Individuals",
          title: "Personalized Healthcare Services",
          description: "Get direct access to medical consultations, wellness programs, and health monitoring tailored to your unique health profile and personal needs.",
          image: "https://i.ibb.co/9mfrZG9f/Individual.jpg"
        },
        {
          name: "Partners",
          title: "Healthcare Partnership Solutions",
          description: "Collaborate with us to create integrated healthcare solutions that benefit your customers while expanding your service offerings.",
          image: "https://i.ibb.co/vCPW5VvV/Employers.jpg"
        },
        {
          name: "Family",
          title: "Comprehensive Family Care",
          description: "All-inclusive healthcare plans designed for families, with specialized services for every family member from children to seniors.",
          image: "https://i.ibb.co/sYm31hW/Familycare.jpg"
        },
        {
          name: "Employers",
          title: "Corporate Healthcare Solutions",
          description: "Comprehensive healthcare services for employees that enhance workplace wellness, reduce absenteeism, and improve overall productivity.",
          image: "https://i.ibb.co/g8KcnG0/employers1.jpg"
        },
        {
          name: "HMO",
          title: "Managed Care Optimization",
          description: "Our platform consolidates insurance efforts, helping HMOs optimize client retention, streamline claims processing, and control medical loss ratios.",
          image: "https://i.ibb.co/GQ065bBD/HMO.png"
        },
        {
          name: "Governments",
          title: "Public Health Initiatives",
          description: "Partnering with government agencies to provide accessible, scalable healthcare solutions that reach citizens across diverse communities.",
          image: "https://i.ibb.co/YFYTzmTJ/organisation-care.jpg"
        },
        {
          name: "NGOs",
          title: "Healthcare Support for NGOs",
          description: "Tailored healthcare programs that help NGOs deliver medical assistance to underserved communities and maximize their humanitarian impact.",
          image: "https://i.ibb.co/Lz2C4Q5J/Ngos.jpg"
        }
      ]
    };
  }
};
</script>

<style scoped>
.scrollbar-thin {
  scrollbar-width: thin;
}

.scrollbar-thumb-gray-300::-webkit-scrollbar-thumb {
  background-color: #d1d5db;
  border-radius: 10px;
}

.scrollbar-thumb-gray-300::-webkit-scrollbar {
  height: 6px;
}

/* Transitions */
.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.3s ease, transform 0.3s ease;
}

.fade-enter-from,
.fade-leave-to {
  opacity: 0;
  transform: translateY(10px);
}
</style>