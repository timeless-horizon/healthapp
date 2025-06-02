<template>
  <div id="timeless-carousel" class="relative w-full h-screen overflow-hidden text-white" @mouseenter="pauseAutoSlide"
    @mouseleave="resumeAutoSlide">
    <div class="absolute inset-0 z-0">
      <TransitionGroup name="slide" tag="div" class="relative h-full">
        <div v-for="(slide, index) in slides" :key="slide.title"
          class="absolute inset-0 transition-all duration-1000 ease-in-out transform" :class="{
            'opacity-100 scale-100 z-10': index === currentIndex,
            'opacity-0 scale-95 z-0': index !== currentIndex
          }">
          <!-- Background Image with Overlay -->
          <div class="absolute inset-0 bg-cover bg-no-repeat bg-center"
            :style="{ backgroundImage: `url(${slide.image})` }">
            <div class="absolute inset-0 bg-black/60"></div>
          </div>

          <!-- Content Container -->
          <div class="relative z-10 flex items-center justify-center h-full px-6 md:px-12">
            <div class="max-w-3xl text-center space-y-6">
              <div class="animate-slide-in-up">
                <h1 class="text-4xl md:text-6xl font-extrabold tracking-tight mb-4 text-white drop-shadow-lg">
                  {{ slide.title }}
                </h1>
              </div>

              <div class="animate-slide-in-up delay-100">
                <p class="text-lg md:text-xl mb-8 text-gray-100 max-w-2xl mx-auto leading-relaxed">
                  {{ slide.description }}
                </p>
              </div>

              <div class="animate-slide-in-up delay-200" v-if="!page.props.auth?.user">
                <div class="flex justify-center space-x-4">
                  <button @click="navigateTo(slide.link1)" class="px-8 py-3 bg-teal-500 text-white font-semibold rounded-full 
                           transform transition-all duration-300 hover:bg-teal-600 
                           hover:scale-105 focus:outline-none focus:ring-2 
                           focus:ring-teal-400 focus:ring-offset-2">
                    {{ slide.button1 }}
                  </button>
                  <button @click="navigateTo(slide.link2)" class="px-8 py-3 bg-white text-gray-900 font-semibold rounded-full 
                           transform transition-all duration-300 hover:bg-gray-100 
                           hover:scale-105 focus:outline-none focus:ring-2 
                           focus:ring-gray-400 focus:ring-offset-2">
                    {{ slide.button2 }}
                  </button>
                </div>
                <div v-if="page.props.auth?.user">
                  <Link href="/dashboard" class="block py-2 px-3 text-gray-600">
                  Dashboard</Link>
                </div>
              </div>
            </div>
          </div>
        </div>
      </TransitionGroup>
    </div>
    <!-- Slide Indicators -->
    <div class="absolute bottom-8 left-1/2 transform -translate-x-1/2 
             flex space-x-3 z-20">
      <div v-for="(slide, index) in slides" :key="index" @click="setCurrentIndex(index)"
        class="w-3 h-3 rounded-full cursor-pointer transition-all duration-300" :class="{
          'bg-teal-500 scale-125': index === currentIndex,
          'bg-white/50 hover:bg-white/70': index !== currentIndex
        }"></div>
    </div>
  </div>
</template>
<script setup>
import { usePage } from '@inertiajs/vue3';
import { Link } from '@inertiajs/vue3';
const page = usePage();
</script>
<script>
export default {
  data() {
    return {
      slides: [
        {
          title: "Your Health, Simplified",
          description: "At Timeless, we bring healthcare to your fingertips. Our telemedicine app connects you with certified doctors, therapists, and healthcare professionals anytime, anywhere.",
          button1: "Get Started",
          button2: "Learn More",
          link1: "/register",
          link2: "/login",
          image: "/images/african-american-mother-having-video-call-with-pediatrician-while-holding-ill-daughter-her-lap.jpg",
        },
        {
          title: "Speak with a Doctor LIVE",
          description: "Many health concerns do not require an office doctor visit. Experience secure, convenient, and affordable healthcare without leaving home.",
          button1: "Book Now",
          button2: "Get Help",
          link1: "/register",
          link2: "/login",
          image: "/images/specialist-male-doctor-wearing-medical-cap-mask.jpg",
        },
        {
          title: "Your Health, Our Priority",
          description: "Whether you need a quick consultation, prescription refills, or expert advice, we're here to provide personalized care that fits into your busy life.",
          button1: "Start Today",
          button2: "Contact Us",
          link1: "/register",
          link2: "/login",
          image: "/images/doctor-is-checking-medicine-stock.jpg",
        }
      ],
      currentIndex: 0,
      timer: null
    }
  },
  mounted() {
    this.startAutoSlide()
  },
  methods: {
    startAutoSlide() {
      this.timer = setInterval(this.nextSlide, 8000)
    },
    pauseAutoSlide() {
      clearInterval(this.timer)
    },
    resumeAutoSlide() {
      this.startAutoSlide()
    },
    nextSlide() {
      this.currentIndex = this.currentIndex === this.slides.length - 1 ? 0 : this.currentIndex + 1
    },
    prevSlide() {
      this.currentIndex = this.currentIndex === 0 ? this.slides.length - 1 : this.currentIndex - 1
    },
    setCurrentIndex(index) {
      this.pauseAutoSlide()
      this.currentIndex = index
      this.resumeAutoSlide()
    },
    navigateTo(link) {
      window.location.href = link
    },
    resetAutoSlide() {
      this.pauseAutoSlide()
      this.startAutoSlide()
    }
  },
  beforeDestroy() {
    this.pauseAutoSlide()
  }
}
</script>

<style scoped>
.slide-enter-active,
.slide-leave-active {
  transition: all 1s cubic-bezier(0.4, 0, 0.2, 1);
}

.slide-enter-from {
  opacity: 0;
  transform: scale(1.1);
}

.slide-leave-to {
  opacity: 0;
  transform: scale(0.9);
}

@keyframes slide-in-up {
  from {
    opacity: 0;
    transform: translateY(20px);
  }

  to {
    opacity: 1;
    transform: translateY(0);
  }
}

.animate-slide-in-up {
  animation: slide-in-up 1s ease-out;
}

.delay-100 {
  animation-delay: 100ms;
}

.delay-200 {
  animation-delay: 200ms;
}
</style>