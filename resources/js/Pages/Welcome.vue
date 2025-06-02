<template>

  <Head title="Home" />
  <GuestLayout>
    <Carousel />
    <!-- why choose us -->
    <section ref="sectionRef" :class="[
      'transform transition-all duration-1000 ease-in-out',
      isVisible ? 'opacity-100' : 'opacity-0 translate-y-8',
    ]" class="bg-gradient-to-b from-blue-50 to-white py-20 px-6 lg:px-20 overflow-hidden relative">
      <div class="absolute top-0 left-0 w-full h-full overflow-hidden opacity-10 pointer-events-none">
        <div class="absolute -top-24 -left-24 w-64 h-64 rounded-full bg-blue-400 blur-3xl"></div>
        <div class="absolute top-1/2 -right-24 w-80 h-80 rounded-full bg-teal-400 blur-3xl"></div>
      </div>

      <div class="max-w-[1300px] mx-auto text-center relative z-10">
        <h1 class="text-4xl md:text-5xl font-bold text-gray-800 tracking-tight">
          Welcome to <span class="text-blue-600 relative inline-block">
            Timeless
            <span class="absolute bottom-1 left-0 w-full h-2 bg-blue-200 opacity-50 rounded-full -z-10"></span>
          </span>
        </h1>
        <h2 class="text-xl md:text-2xl font-medium text-blue-600 mt-3">Your Health, Simplified</h2>
        <p class="mt-6 text-lg text-gray-600 max-w-3xl mx-auto leading-relaxed">
          At Timeless, we bring healthcare to your fingertips. Our telemedicine app connects you with certified doctors,
          therapists, and healthcare professionals anytime, anywhere.
        </p>
      </div>
      <div class="mt-16 grid gap-8 lg:gap-12 md:grid-cols-2 lg:grid-cols-3 max-w-[1300px] w-full mx-auto">
        <div v-for="(card, index) in cards" :key="card.title" :class="[
          'group bg-white rounded-2xl shadow-lg overflow-hidden transform transition-all duration-500 hover:shadow-xl hover:-translate-y-1 border border-gray-100',
          isVisible ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-10',
          { 'transition-delay-100': index === 0, 'transition-delay-200': index === 1, 'transition-delay-300': index === 2 }
        ]">
          <div class="p-8 flex flex-col h-full">
            <div
              class="mb-6 p-3 rounded-full bg-blue-100 w-16 h-16 flex items-center justify-center group-hover:bg-blue-200 transition-colors">
              <component :is="card.icon" class="w-8 h-8 text-blue-600 animate-pulse duration-200" />
            </div>
            <h2 class="text-2xl font-semibold text-gray-800 mb-3">{{ card.title }}</h2>
            <p class="text-gray-600 flex-grow">
              {{ card.description }}
            </p>
          </div>
        </div>
      </div>
      <div class="mt-20 text-center">
        <div class="inline-flex space-x-4">
          <Link href="/register"
            class="inline-flex items-center bg-blue-600 text-white text-lg font-medium py-4 px-8 rounded-full shadow-lg hover:bg-blue-700 transition-colors focus:ring-4 focus:ring-blue-300 focus:outline-none">
          Get Started Today
          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-2" fill="none" viewBox="0 0 24 24"
            stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
          </svg>
          </Link>
        </div>
      </div>
    </section>
    <!--  Service Steps (Guide) Section -->
    <section :class="[
      'transform transition-all duration-1000 ease-in-out',
      isVisible ? 'opacity-100' : 'opacity-0 translate-y-8',
    ]"
      class="py-20 px-6 lg:px-20 bg-[url('https://apps.mobihealthinternational.com/images/bg-img-02.png')] overflow-hidden relative">
      <div class="max-w-[1300px] mx-auto text-center mb-16">
        <h1 class="text-4xl md:text-5xl font-bold text-gray-800 tracking-tight">
          How It <span class="text-blue-600 relative inline-block">
            Works
            <span class="absolute bottom-1 left-0 w-full h-2 bg-blue-200 opacity-50 rounded-full -z-10"></span>
          </span>
        </h1>
        <p class="mt-6 text-lg text-gray-600 max-w-2xl mx-auto leading-relaxed">
          Follow these simple steps to get connected with healthcare professionals and receive the care you need.
        </p>
      </div>

      <div class="max-w-[1300px] mx-auto relative">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-8 relative">
          <div :class="[
            'relative z-10 transform transition-all duration-700',
            isVisible ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-8',
            'delay-100'
          ]" class="text-center">
            <div class="w-16 h-16 mx-auto mb-4 rounded-full bg-blue-100 flex items-center justify-center">
              <UserPlusIcon class="w-8 h-8 text-blue-600" />
            </div>
            <h3 class="text-xl font-bold text-gray-800 mb-2">Register and Login</h3>
            <p class="text-gray-600 text-sm">
              Create your free account in seconds. No commitments, no credit card required to get started.
            </p>
            <div
              class="absolute top-0 left-1/2 transform -translate-x-1/2 w-4 h-4 rounded-full bg-blue-600 hidden md:block">
            </div>
          </div>
          <div :class="[
            'relative z-10 transform transition-all duration-700',
            isVisible ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-8',
            'delay-200'
          ]" class="text-center">
            <div class="w-16 h-16 mx-auto mb-4 rounded-full bg-blue-100 flex items-center justify-center">
              <MagnifyingGlassIcon class="w-8 h-8 text-blue-600" />
            </div>
            <h3 class="text-xl font-bold text-gray-800 mb-2">Enter Your Symptoms</h3>
            <p class="text-gray-600 text-sm">
              Input your symptoms and health concerns, and we'll match you with the right specialist for your needs.
            </p>
            <div
              class="absolute top-0 left-1/2 transform -translate-x-1/2 w-4 h-4 rounded-full bg-blue-600 hidden md:block">
            </div>
          </div>
          <div :class="[
            'relative z-10 transform transition-all duration-700',
            isVisible ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-8',
            'delay-300'
          ]" class="text-center">
            <div class="w-16 h-16 mx-auto mb-4 rounded-full bg-blue-100 flex items-center justify-center">
              <UserGroupIcon class="w-8 h-8 text-blue-600" />
            </div>
            <h3 class="text-xl font-bold text-gray-800 mb-2">Find a Doctor</h3>
            <p class="text-gray-600 text-sm">
              Browse through healthcare professional profiles, read reviews, and book your appointment with just a few
              clicks.
            </p>
            <div
              class="absolute top-0 left-1/2 transform -translate-x-1/2 w-4 h-4 rounded-full bg-blue-600 hidden md:block">
            </div>
          </div>
          <div :class="[
            'relative z-10 transform transition-all duration-700',
            isVisible ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-8',
            'delay-400'
          ]" class="text-center">
            <div class="w-16 h-16 mx-auto mb-4 rounded-full bg-blue-100 flex items-center justify-center">
              <VideoCameraIcon class="w-8 h-8 text-blue-600" />
            </div>
            <h3 class="text-xl font-bold text-gray-800 mb-2">See Your Doctor</h3>
            <p class="text-gray-600 text-sm">
              Connect instantly with your healthcare provider through our secure video platform and receive personalized
              care.
            </p>
            <div
              class="absolute top-0 left-1/2 transform -translate-x-1/2 w-4 h-4 rounded-full bg-blue-600 hidden md:block">
            </div>
          </div>
        </div>
      </div>
    </section>
    <Organizationservices />
    <!-- ==============Healthsymp component============== -->
    <Healthsymp />

    <!-- Services step -->
    <section class="bg-white dark:bg-gray-900">
      <div class="grid max-w-screen-xl px-4 py-12 mx-auto lg:gap-12 xl:gap-16 lg:py-20 lg:grid-cols-12">
        <div class="mb-8 mr-auto place-self-center lg:col-span-7">
          <span
            class="inline-flex items-center px-3 py-1 text-sm font-medium text-blue-600 bg-blue-100 dark:bg-blue-900 dark:text-blue-200 rounded-full mb-4">
            <svg class="w-3.5 h-3.5 mr-2" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
              <path
                d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118l-2.8-2.034c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z">
              </path>
            </svg>
            Virtual Healthcare Made Simple
          </span>

          <h1
            class="max-w-2xl mb-6 text-4xl font-extrabold tracking-tight leading-none md:text-5xl xl:text-6xl dark:text-white">
            SKIP THE <span class="text-blue-600 dark:text-blue-500">WAITING</span> ROOM
          </h1>

          <p class="max-w-2xl mb-8 font-normal text-gray-600 lg:mb-10 md:text-lg lg:text-xl dark:text-gray-400">
            No more long waiting lines and endless commutes! Connect with qualified doctors instantly from the comfort
            of your home with 24/7 access to care.
          </p>

          <div class="flex flex-wrap gap-4 mt-6">
            <a href="/login"
              class="inline-flex items-center justify-center px-6 py-3.5 text-base font-medium text-center text-white rounded-lg bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:ring-blue-300 dark:focus:ring-blue-800 transition-all duration-300">
              Get started
              <svg class="w-5 h-5 ml-2" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                <path fill-rule="evenodd"
                  d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z"
                  clip-rule="evenodd"></path>
              </svg>
            </a>
            <!-- <a href="/login"
              class="inline-flex items-center justify-center px-6 py-3.5 text-base font-medium text-center text-gray-900 border-2 border-gray-300 rounded-lg hover:bg-gray-100 focus:ring-4 focus:ring-gray-100 dark:text-white dark:border-gray-600 dark:hover:bg-gray-700 dark:focus:ring-gray-700 transition-all duration-300">
              Talk with a Doctor
            </a> -->
          </div>

          <!-- <div class="flex items-center mt-8">
            <div class="flex items-center flex-shrink-0 -space-x-4">
              <img class="inline-block rounded-full w-10 h-10 ring-2 ring-white"
                src="https://d33wubrfki0l68.cloudfront.net/3bfa6da479d6b9188c58f2d9a8d33350290ee2ef/301f1/images/hero/3/avatar-male.png"
                alt="" />
              <img class="inline-block rounded-full w-10 h-10 ring-2 ring-white"
                src="https://d33wubrfki0l68.cloudfront.net/b52fa09a115db3a80ceb2d52c275fadbf84cf8fc/7fd8a/images/hero/3/avatar-female-1.png"
                alt="" />
              <img class="inline-block rounded-full w-10 h-10 ring-2 ring-white"
                src="https://d33wubrfki0l68.cloudfront.net/8a2efb13f103a5ae2909e244380d73087a9c2fc4/31ed6/images/hero/3/avatar-female-2.png"
                alt="" />
            </div>
            <div class="ml-4">
              <span class="text-sm font-medium text-gray-900 dark:text-white">Trusted by 2,000+ patients</span>
              <div class="flex items-center">
                <svg class="w-4 h-4 text-yellow-400" fill="currentColor" viewBox="0 0 20 20"
                  xmlns="http://www.w3.org/2000/svg">
                  <path
                    d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118l-2.8-2.034c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z">
                  </path>
                </svg>
                <svg class="w-4 h-4 text-yellow-400" fill="currentColor" viewBox="0 0 20 20"
                  xmlns="http://www.w3.org/2000/svg">
                  <path
                    d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118l-2.8-2.034c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z">
                  </path>
                </svg>
                <svg class="w-4 h-4 text-yellow-400" fill="currentColor" viewBox="0 0 20 20"
                  xmlns="http://www.w3.org/2000/svg">
                  <path
                    d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118l-2.8-2.034c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z">
                  </path>
                </svg>
                <svg class="w-4 h-4 text-yellow-400" fill="currentColor" viewBox="0 0 20 20"
                  xmlns="http://www.w3.org/2000/svg">
                  <path
                    d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118l-2.8-2.034c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z">
                  </path>
                </svg>
                <svg class="w-4 h-4 text-yellow-400" fill="currentColor" viewBox="0 0 20 20"
                  xmlns="http://www.w3.org/2000/svg">
                  <path
                    d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118l-2.8-2.034c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z">
                  </path>
                </svg>
                <span class="ml-1 text-sm text-gray-500 dark:text-gray-400">4.9/5</span>
              </div>
            </div>
          </div> -->
        </div>

        <div class="lg:mt-0 lg:col-span-5 lg:flex">
          <div
            class="relative mx-auto border-gray-800 dark:border-gray-800 bg-gray-800 border-[14px] rounded-[2.5rem] h-[600px] w-[300px] shadow-xl">
            <!-- Phone notch and buttons -->
            <div class="w-[148px] h-[18px] bg-gray-800 top-0 rounded-b-[1rem] left-1/2 -translate-x-1/2 absolute">
            </div>
            <div class="h-[46px] w-[3px] bg-gray-800 absolute -start-[17px] top-[124px] rounded-s-lg"></div>
            <div class="h-[46px] w-[3px] bg-gray-800 absolute -start-[17px] top-[178px] rounded-s-lg"></div>
            <div class="h-[64px] w-[3px] bg-gray-800 absolute -end-[17px] top-[142px] rounded-e-lg"></div>

            <!-- Phone screen with animation -->
            <div class="rounded-[2rem] overflow-hidden w-[272px] h-[572px] bg-white dark:bg-gray-800 relative">
              <img
                src="https://images.unsplash.com/photo-1651008376811-b90baee60c1f?q=80&w=1887&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D"
                class="dark:hidden w-[272px] h-[572px] object-cover" alt="Healthcare app interface">
              <img src="https://flowbite.s3.amazonaws.com/blocks/marketing-ui/hero/mockup-2-dark.png"
                class="hidden dark:block w-[272px] h-[572px]" alt="Healthcare app interface dark mode">

              <!-- App UI overlay elements -->
              <div class="absolute top-10 left-0 right-0 flex justify-center">
                <div class="bg-white/90 dark:bg-gray-800/90 rounded-lg shadow-lg p-3 w-[240px]">
                  <div class="flex items-center mb-2">
                    <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 mr-2">
                      <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd"
                          d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z"
                          clip-rule="evenodd"></path>
                      </svg>
                    </div>
                    <div>
                      <p class="text-xs font-medium text-gray-900 dark:text-white">Appointment Ready</p>
                      <p class="text-xs text-gray-500 dark:text-gray-400">Doctor will join shortly</p>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Status indicator -->
              <div class="absolute bottom-10 left-0 right-0 flex justify-center">
                <div
                  class="bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-300 rounded-full px-3 py-1 text-xs font-medium">
                  Connected to Dr. Sarah
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- ===============Access Care Section===================== -->
    <section
      class="flex flex-col md:flex-row items-center px-8 md:px-20 py-16 space-y-10 md:space-y-0 max-w-[1300px] mx-auto">
      <!-- Left Image -->
      <div class="w-full md:w-1/2 flex justify-center">
        <img src="https://healthconnect247.com/wp-content/uploads/2021/11/8.png" alt="Doctor Consultation"
          class="max-w-full h-auto">
      </div>

      <!-- Right Content -->
      <div class="w-full md:w-1/2 md:text-left md:px-4">
        <h2 class="text-2xl md:text-4xl font-bold text-gray-900 leading-snug">
          Access to care for <span class="text-blue-600">everybody</span>
        </h2>
        <p class="text-gray-600 mt-4 text-lg leading-relaxed max-w-md w-full">
          You can speak to a doctor, psychologist, and wellness expert via audio and video call options.
        </p>

        <!-- Feature List -->
        <div class="mt-8 space-y-8 max-w-md w-full">

          <div class="flex items-start space-x-5">
            <span class="p-4 border border-teal-500 rounded-full flex items-center justify-center text-2xl">
              🎥
            </span>
            <div>
              <h3 class="font-semibold text-gray-900 text-lg">Speak to a Doctor Immediately</h3>
              <p class="text-gray-600 text-base leading-relaxed">
                You can speak to a doctor via the in-app video option at any time.
              </p>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- ===============Access Welness Section===================== -->
    <section
      class="flex flex-col md:flex-row items-center px-6 md:px-16 py-12 space-y-8 md:space-y-0 max-w-[1300px] mx-auto">
      <!-- Left Image -->

      <div class="w-full md:w-1/2 md:text-left md:px-4">
        <h2 class="text-2xl md:text-4xl font-bold text-gray-900 leading-snug">
          Access to Wellness <span class="text-blue-600">Programs</span>
        </h2>
        <p class="text-gray-600 mt-4 text-lg leading-relaxed">
          You can consult with a clinical psychologist for all mental health issues including depression, anxiety,
          suicidal tendency, panic attack and lots more.
        </p>

        <!-- Feature List -->
        <div class="mt-8 space-y-8">
          <div class="flex items-start space-x-5">
            <span class="p-4 border border-teal-500 rounded-full flex items-center justify-center text-2xl">
              💬
            </span>
            <div>
              <h3 class="font-semibold text-gray-900 text-lg">Speak with a Psychologist</h3>
              <p class="text-gray-600 text-base leading-relaxed max-w-md w-full">
                Counselling for your mental health, wellness and psychological wellbeing
              </p>
            </div>
          </div>

          <div class="flex items-start space-x-5 max-w-md w-full">
            <span class="p-4 border border-teal-500 rounded-full flex items-center justify-center text-2xl">
              🍚
            </span>
            <div>
              <h3 class="font-semibold text-gray-900 text-lg">Customised Meal Plans</h3>
              <p class="text-gray-600 text-base leading-relaxed">
                We customize diet plans that meet your specific needs, health conditions and lifestyle
              </p>
            </div>
          </div>
        </div>
      </div>

      <div class="w-full md:w-1/2 flex justify-center">
        <img src="https://healthconnect247.com/wp-content/uploads/2021/11/8.png" alt="Doctor Consultation"
          class="max-w-full h-auto">
      </div>

      <!-- Right Content -->
    </section>

    <div>
      <TawkToChat />
      <router-view />
    </div>
    <!-- ======================= Meet the Team slides================ -->
    <!-- <Team /> -->
    <!-- =============================Footer========================== -->
  </GuestLayout>
</template>



<script>
import Customfooter from '@/Components/Customfooter.vue';
import Customnav from '@/Components/Customnav.vue';
import Healthsymp from '@/Components/Healthsymp.vue';
import Carousel from "@/Components/Carousel.vue"
// import Team from '@/Components/Team.vue';
import Organizationservices from "@/Components/Organizationservices.vue";

export default {
  components: {
    Customnav,
    Healthsymp,
    Customfooter,
    Carousel,
    // Team,
    Organizationservices,
  },
  data() {
    return {

      isVisible: false,
    };
  },
  mounted() {
    const observer = new IntersectionObserver(
      (entries) => {
        entries.forEach((entry) => {
          if (entry.isIntersecting) {
            this.isVisible = true;
            observer.disconnect(); // Stop observing once visible
          }
        });
      },
      { threshold: 0.1 }
    );

    observer.observe(this.$refs.sectionRef);

  },

  methods: {

  }
};
</script>

<script setup>
import { Link } from '@inertiajs/vue3';
import GuestLayout from "@/Layouts/GuestLayout.vue";
import TawkToChat from "@/Components/TawkToChats.vue";
import {
  VideoCameraIcon,
  DocumentTextIcon,
  ShieldCheckIcon,
  UserPlusIcon,
  MagnifyingGlassIcon,
  UserGroupIcon,
  // VideoCameraIcon,
} from '@heroicons/vue/24/outline';
import { Head } from '@inertiajs/vue3';

// Then in your component template
const cards = [
  {
    title: 'Quick Consultations',
    description: 'Get expert medical advice in minutes, anytime and anywhere with our team of certified healthcare professionals.',
    icon: VideoCameraIcon
  },
  {
    title: 'Prescription Refills',
    description: 'Enjoy hassle-free prescription renewals from the comfort of your home with secure electronic prescriptions.',
    icon: DocumentTextIcon
  },
  {
    title: 'Affordable Healthcare',
    description: 'Experience secure, convenient, and budget-friendly healthcare with transparent pricing and no hidden fees.',
    icon: ShieldCheckIcon
  }
];
</script>

<style>
.clip-slant {
  clip-path: polygon(0 0, 100% 10%, 100% 100%, 0 90%);
}
</style>